<?php

namespace Coluna\Controller;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Nucleo\Controller\ControllerGenerico;

class ColunasAdminController extends ControllerGenerico {

    public function indexAction() {
        $srv_vcolunas = $this->p()->getEntity('Coluna', 'VColColuna');
        $srv_vcol_series = $this->p()->getEntity('Coluna', 'VColLiberadasParaSerie');
        
        $sessao = $this->sessao()->getArrayCopy();
        $usuario = $sessao['storage']['usuario'];

        $colunista_cod = $usuario['usr_id'];
        $colunas_raw = $this->objetosParaArray($srv_vcolunas->getAllByUsrId($colunista_cod));
        
        $col_series = $this->objetosParaArray($srv_vcol_series->getAllByUsrId($colunista_cod));
        
        $colunas_pais = [];

        foreach ($colunas_raw as $cada) {
            if((int) $cada['ser_id'] != 0){
                $colunas_pais[]=$cada['col_pai_id'];
            }
        }
        
        $srv_categoria = $this->p()->getEntity('Coluna', 'ColCategoria');
        $categorias_raw = $this->objetosParaArray($srv_categoria->getAll());
        
        return new ViewModel([
            'colunas' => $colunas_raw,
            'series' => $col_series,
            'colunas_pais' => $colunas_pais,
            'categorias' => $categorias_raw,
            'usuario' => $usuario,
        ]);
    }

    public function saveAction() {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return false;
        }

        $files = $request->getFiles()->toArray();

        $dados = $request->getPost()->toArray();
        
        if ((int) $dados['col_id'] > 0) {
            $dados['col_data_modificacao'] = date('Y-m-d H:i:s');
        } else {
            $dados['col_data_postagem'] = date('Y-m-d H:i:s');
            $dados['col_id'] = false;
        }
        $dados['usr_id'] = $this->sessao()->getArrayCopy()['storage']['usuario']['usr_id'];
        $dados['col_status'] = 'A';
        
        $col_pai_id = (int) $dados['col_pai_id'];
        unset($dados['col_pai_id']);
        
        $srv_colunas = $this->p()->getEntity('Coluna', 'ColColuna');
        $entity_coluna = $srv_colunas->create($dados);
        
        $entity_coluna = $srv_colunas->save($entity_coluna);
        $col_id = $entity_coluna->getColId();
        if ((int) $col_id) {
            $result['coluna'] = $entity_coluna->toArray();
            if ($files['col_imagem']['size']) {
                $nome_arq = strtolower('col_' . $col_id); 
                $caminho = $this->p()->getCaminhoUniversal(getcwd() . '/public/img/colunas/');
                $result['imagem'] = $this->saveImage($files['col_imagem'], $caminho, $nome_arq );
                if (file_exists($result['imagem']['caminho'])) {
                    $result['coluna']['col_end_imagem'] = str_replace(getcwd().DIRECTORY_SEPARATOR, '', $result['imagem']['caminho']);
                    $entity_coluna = $srv_colunas->create($result['coluna']);
                    $entity_coluna = $srv_colunas->save($entity_coluna);
                }
            }
            
            $result['serie'] = $this->gerenciaSerie($col_pai_id, $col_id);
            
            $this->backupBD();
        }
        return new JsonModel($result);
    }

    public function excluirAction() {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return false;
        }
        
        $col_id = $request->getPost()->toArray()['col_id'];
        $srv_colunas = $this->p()->getEntity('Coluna', 'ColColuna');
        $entity = $srv_colunas->getByColId($col_id);
        $file = '';
        if(get_class($entity) == 'Coluna\Entity\ColColuna'){
            
            $srv_series = $this->p()->getEntity('Coluna', 'ColSerie');
            $srv_series->removeByColFilhoId($col_id);
            
            $result = $srv_colunas->removeByColId($col_id);
            
            if( ! empty($entity->getColEndImagem())){
                $file = $this->p()->getCaminhoUniversal(getcwd().DIRECTORY_SEPARATOR.$entity->getColEndImagem());
                if($result && file_exists($file)){
                    unlink($file);
                    $srv_series = $this->p()->getEntity('Coluna', 'ColSerie');
                    $srv_series->removeByColId($col_id);
                    $srv_series->removeByColFilhoId($col_id);
                }
                
            }
           
            $this->backupBD();
            
        }
        return new JsonModel([$result]);
    }

    private function gerenciaSerie($pai_id, $filho_id){
        $srv_series = $this->p()->getEntity('Coluna', 'ColSerie');

        if((int) $pai_id > 0){
            
            $result = $srv_series->getByColumns(['col_id'=>$pai_id,'col_filho_id'=>$filho_id]);
            
            if( is_object($result)){
                $result = $result->toArray();
                $result['ignorar_serie'] = TRUE;
            } else{
                $srv_series->removeByColFilhoId($filho_id);
                $ordem_ultima = $srv_series->getByColIdOrderBySerOrdemDesc($pai_id);
                $ordem_ultima = (get_class($ordem_ultima)==='Coluna\Entity\ColSerie')?(int) $ordem_ultima->getSerOrdem():0;
                $entity_ser = $srv_series->create([
                    'ser_id'=>false,
                    'col_id'=>$pai_id,
                    'col_filho_id'=>$filho_id,
                    'ser_ordem'=>$ordem_ultima+1,
                ]);
                $result = $srv_series->save($entity_ser);
                $result = $result->toArray();
            }
            
        } else {
            $result = $srv_series->removeByColFilhoId($filho_id);
        }
        
        return $result;
    }

    private function saveImage($array_files, $caminho, $nome_arq) {

        if (empty($array_files['tmp_name'])) {
            return 'branco';
        }

        $valid_file = true;
        $result['message'] = 'tudo ok';
        $result = [
            'message' => 'tudo ok',
            'caminho' => '',
            'result' => false
        ];
        $type = explode('/', $array_files['type']);
        $ext = '.' . array_pop($type);
        $caminho_completo = $caminho.$nome_arq.$ext;
        //can't be larger than 3 MB
        if ($array_files['size'] > (3072000)) {
            $valid_file = false;
            $result['message'] = 'Imagem muito grande. Escolha outra.';
        }

        //if the file has passed the test
        if ($valid_file) {
            //move it to where we want it to be
            $result['result'] = move_uploaded_file($array_files['tmp_name'], $caminho_completo);
            if ($result) {
                $result['caminho'] = $caminho_completo;
            } else {
                $result['message'] = 'Ocorreu algum problema ao inserir a imagem';
            }
        }

        return $result;
    }

    private function backupBD() {
        
        $db_paramenters = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter')->getDriver()->getConnection()->getConnectionParameters();
        $dbname = $db_paramenters['database'];
        $dbhost = $db_paramenters['host'];
        $user = $db_paramenters['username'];
        $pass = $db_paramenters['password'];
        $tables = '*';

        $dsn = "mysql:dbname=$dbname;host=$dbhost;port=3306";
        try {
            $pdo = new \PDO($dsn, $user, $pass); // also allows an extra parameter of configuration
            // file header stuff
            $output = "-- PHP MySQL Dump\n--\n";
            $output .= "-- Host: $dbhost\n";
            $output .= "-- Generated: " . date("r", time()) . "\n";
            $output .= "-- PHP Version: " . phpversion() . "\n\n";
            $output .= "SET FOREIGN_KEY_CHECKS=0;\n";
            $output .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
            $output .= "SET AUTOCOMMIT = 0;\n";
            $output .= "START TRANSACTION;\n";
            $output .= "SET time_zone = \"-03:00\";\n\n";

            $output .= "--\n-- Database: `$dbname`\n--\n";
            // get all table names in db and stuff them into an array
            $tables = array();

            $stmt = $pdo->query("select * from information_schema.tables as t where t.table_schema=\"$dbname\";");
            while ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
                $tables[] = $row[2] . '-' . $row[3];
            }

            // process each table in the db
            foreach ($tables as $table) {
                $table = explode('-', $table);
                $table_tipo = $table[1];
                $table = $table[0];

                $fields = "";
                $sep2 = "";
                $output .= "\n-- " . str_repeat("-", 60) . "\n\n";
                $output .= "--\n-- Table structure for table `$table`\n--\n\n";
                // get table create info
                $stmt = $pdo->query("SHOW CREATE TABLE $table");
                $row = $stmt->fetch(\PDO::FETCH_NUM);
                if ($table_tipo == 'VIEW') {
                    $row[1] = str_replace('ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER', '', $row[1]);
                    $output.= $row[1] . ";\n\n";
                } else {
                    $output.= $row[1] . ";\n\n";
                    // get table data
                    $output .= "--\n-- Dumping data for table `$table`\n--\n\n";
                    $stmt = $pdo->query("SELECT * FROM $table");
                    while ($row = $stmt->fetch(\PDO::FETCH_OBJ)) {
                        // runs once per table - create the INSERT INTO clause
                        if ($fields == "") {
                            $fields = "INSERT INTO `$table` (";
                            $sep = "";
                            // grab each field name
                            foreach ($row as $col => $val) {
                                $fields .= $sep . "`$col`";
                                $sep = ", ";
                            }
                            $fields .= ") VALUES";
                            $output .= $fields . "\n";
                        }
                        // grab table data
                        $sep = "";
                        $output .= $sep2 . "(";
                        foreach ($row as $col => $val) {
                            // add slashes to field content
                            $val = addslashes($val);
                            // replace stuff that needs replacing
                            $search = array("\'", "\n", "\r");
                            $replace = array("''", "\\n", "\\r");
                            $val = str_replace($search, $replace, $val);
                            $output .= $sep . "'$val'";
                            $sep = ", ";
                        }
                        // terminate row data
                        $output .= ")";
                        $sep2 = ",\n";
                    }
                    // terminate insert data
                    $output .= ";\n";
                }
            }

            $output .= "SET FOREIGN_KEY_CHECKS=1;\n";
            $output .= "COMMIT;\n\n";

            $output = str_replace("CREATE TABLE", "CREATE TABLE IF NOT EXISTS", $output);

            //save file
            $nome_arq = 'mysqldump_' . date('Y-m-d_H-i');
            $destino = getcwd().'/data/mysqldump/';
            $destino_completo = $this->p()->getCaminhoUniversal($destino.$nome_arq.'.zip');
            $this->sqlToZip($destino, $nome_arq, $output);
            
            if(file_exists($destino_completo) and filesize($destino_completo)){
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            die('Could not connect to the database:<br/>' . $e);
        }
    }
    
    private function sqlToZip($caminho, $nome_arq, $string){
        $zip = new \ZipArchive();
        $filename = $this->p()->getCaminhoUniversal($caminho.$nome_arq.'.zip');
        if ($zip->open($filename, \ZipArchive::CREATE)!==TRUE) {
            exit("cannot open <$filename>\n");
        }
        $zip->addFromString($nome_arq.'.sql', $string);
        $num_files = $zip->numFiles;
        $zip->close();
        return $num_files;
    }
    
}
