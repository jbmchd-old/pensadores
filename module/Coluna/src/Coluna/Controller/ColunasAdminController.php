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
        $entity = $srv_colunas->save($entity_coluna);
        
        return new JsonModel($entity->toArray());
    }
    
    public function excluirAction(){
        $request = $this->getRequest();
        
        if( ! $request->isPost()){ return false; }
        
        $col_id = $request->getPost()->toArray()['col_id'];
        $srv_colunas = $this->p()->getEntity('Coluna','ColColuna');
        $result = $srv_colunas->removeByColId($col_id);
        
        
        return new JsonModel([$result]);
        
    }
    
}
