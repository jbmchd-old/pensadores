<?php

namespace Coluna\Controller;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Nucleo\Controller\ControllerGenerico;

class ColunasAdminController extends ControllerGenerico {
    
    public function indexAction(){
        $sessao = $this->sessao()->getArrayCopy();
        $usuario = $sessao['storage']['usuario'];
        
        $colunista_cod = $usuario['usr_id'];
        $srv_vcolunas = $this->p()->getEntity('Coluna','VColColuna');
        $colunas_raw = $this->objetosParaArray( $srv_vcolunas->getAllByUsrId($colunista_cod) ); 
        
        $srv_categoria = $this->p()->getEntity('Coluna','ColCategoria'); 
        $categorias_raw = $this->objetosParaArray( $srv_categoria->getAll() ); 
        
        return new ViewModel([
            'colunas' => $colunas_raw,
            'categorias' => $categorias_raw,
            'usuario' => $usuario,
        ]);
    }
    
    public function saveAction(){
        $request = $this->getRequest();
        
        if( ! $request->isPost()){ return false; }
        
        $files = $request->getFiles()->toArray();
        
        $dados = $request->getPost()->toArray();
        if((int) $dados['col_id'] > 0){
            $dados['col_data_modificacao'] = date('Y-m-d H:i:s');
        } else {
            $dados['col_data_postagem'] = date('Y-m-d H:i:s');    
            $dados['col_id'] = false;
        }
        $dados['usr_id'] = $this->sessao()->getArrayCopy()['storage']['usuario']['usr_id'];
        $dados['col_status'] = 'A';
        
        $srv_colunas = $this->p()->getEntity('Coluna','ColColuna');
        $entity_coluna = $srv_colunas->create($dados);
        $entity_coluna = $srv_colunas->save($entity_coluna);
        $col_id = $entity_coluna->getColId();
        
        if((int) $col_id){
            $result['coluna'] = $entity_coluna->toArray();
            if (sizeof($files)){
                $result['imagem'] = $this->saveImage($files['col_imagem'], $col_id);
            }
        } 
        
        
        return new JsonModel($result);
        
    }
    
    public function excluirAction(){
        $request = $this->getRequest();
        
        if( ! $request->isPost()){ return false; }
        
        $col_id = $request->getPost()->toArray()['col_id'];
        $srv_colunas = $this->p()->getEntity('Coluna','ColColuna');
        $result = $srv_colunas->removeByColId($col_id);
        
        
        return new JsonModel([$result]);
        
    }
    
    private function saveImage($array_files, $col_id){
        
        if(empty($array_files['tmp_name'])){
            return 'branco';
        }
        
        $valid_file = true;
        $result = 'ok';
        $type = explode('/', $array_files['type']);
        $ext = '.'.array_pop($type);
        $novo_nome = strtolower('col_'.$col_id.$ext); //rename file
        $endereco = getcwd().'\\public\\img\\colunas\\'.$novo_nome;
        $endereco = str_replace('\\', DIRECTORY_SEPARATOR, $endereco);
        //can't be larger than 3 MB
        if($array_files['size'] > (3072000)) {
            $valid_file = false;
            $message = 'Imagem muito grande. Escolha outra.';
        }

        //if the file has passed the test
        if($valid_file) {
            //move it to where we want it to be
            $result = move_uploaded_file($array_files['tmp_name'], $endereco);
            if(!$result){
                $message = 'Ocorreu algum problema ao inserir a imagem';
            }
        }

        return $result;
    }
    
}
