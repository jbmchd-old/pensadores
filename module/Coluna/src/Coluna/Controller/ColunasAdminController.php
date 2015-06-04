<?php

namespace Coluna\Controller;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Nucleo\Controller\ControllerGenerico;

class ColunasAdminController extends ControllerGenerico {

    public function indexAction() {
        $sessao = $this->sessao()->getArrayCopy();
        $usuario = $sessao['storage']['usuario'];

        $colunista_cod = $usuario['usr_id'];
        $srv_vcolunas = $this->p()->getEntity('Coluna', 'VColColuna');
        $colunas_raw = $this->objetosParaArray($srv_vcolunas->getAllByUsrId($colunista_cod));

        $srv_categoria = $this->p()->getEntity('Coluna', 'ColCategoria');
        $categorias_raw = $this->objetosParaArray($srv_categoria->getAll());

        return new ViewModel([
            'colunas' => $colunas_raw,
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

        $srv_colunas = $this->p()->getEntity('Coluna', 'ColColuna');
        $entity_coluna = $srv_colunas->create($dados);
        $entity_coluna = $srv_colunas->save($entity_coluna);
        $col_id = $entity_coluna->getColId();

        if ((int) $col_id) {
            $result['coluna'] = $entity_coluna->toArray();
            if (sizeof($files)) {
                $result['imagem'] = $this->saveImage($files['col_imagem'], $col_id);
                if ($result === TRUE) {
                    $result['coluna']['col_end_imagem'] = 'col_' . $col_id;
                    $entity_coluna = $srv_colunas->create($result['coluna']);
                    $entity_coluna = $srv_colunas->save($entity_coluna);
                }
            }
            $this->backupBD();
        }

        $result['temp'] = $entity_coluna->toArray();

        return new JsonModel($result);
    }

    public function excluirAction() {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return false;
        }

        $col_id = $request->getPost()->toArray()['col_id'];
        $srv_colunas = $this->p()->getEntity('Coluna', 'ColColuna');
        $result = $srv_colunas->removeByColId($col_id);
        $this->backupBD();
        return new JsonModel([$result]);
    }

    private function saveImage($array_files, $col_id) {

        if (empty($array_files['tmp_name'])) {
            return 'branco';
        }

        $valid_file = true;
        $result = '';
        $type = explode('/', $array_files['type']);
        $ext = '.' . array_pop($type);
        $novo_nome = strtolower('col_' . $col_id . $ext); //rename file
        $endereco = getcwd() . '\\public\\img\\colunas\\' . $novo_nome;
        $endereco = str_replace('\\', DIRECTORY_SEPARATOR, $endereco);
        //can't be larger than 3 MB
        if ($array_files['size'] > (3072000)) {
            $valid_file = false;
            $message = 'Imagem muito grande. Escolha outra.';
        }

        //if the file has passed the test
        if ($valid_file) {
            //move it to where we want it to be
            $result = move_uploaded_file($array_files['tmp_name'], $endereco);
            if (!$result) {
                $message = 'Ocorreu algum problema ao inserir a imagem';
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
            $destino_completo = $destino.$nome_arq.'.zip';
            $destino_completo = str_replace('\\', DIRECTORY_SEPARATOR, str_replace('/', DIRECTORY_SEPARATOR, $destino_completo));
            $this->stringToZip($destino, $nome_arq, $output);
            
            if(file_exists($destino_completo) and filesize($destino_completo)){
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            die('Could not connect to the database:<br/>' . $e);
        }
    }
    
    private function stringToZip($caminho, $nome_arq, $string){
        $filter = new \Zend\Filter\Compress(array(
            'adapter' => 'Zip',
            'options' => array(
                'archive' => $caminho.$nome_arq.'.zip',
            ),
        ));
        return $filter->filter($string);
    }

}
