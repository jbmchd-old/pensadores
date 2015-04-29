<?php

namespace Nucleo\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Zend\View\Model\JsonModel;

abstract class ControllerGenerico extends AbstractActionController {
    
    const MESCLAR_DUPLICADOS = 1;

    public function buscaDateServidorJsonAction(){
        return new JsonModel(array('date' => (new \DateTime('now'))->format('Y-m-d H:i:s')));
    }

    public function __invoke() {
        die(__NAMESPACE__.__CLASS__);
    }
    
    public function setFlashMessage($msg, $tipo){
        $flash = $this->flashMessenger();
        if($tipo === 'success'){
            $flash->addSuccessMessage($msg);
        } else if($tipo === 'danger'){
            $flash->addErrorMessage($msg);
        } else if($tipo === 'info'){
            $flash->addInfoMessage($msg);
        } else if($tipo === 'warnig'){
            $flash->addMessage($msg);
        } 
    }
    
    public function getAllFlashMessages(){
        $flash = $this->flashMessenger();
        $msg_controller=[];
        if( $flash->hasSuccessMessages() ){
            $msg_controller['success'] = $flash->getSuccessMessages();
        } 
        
        if( $flash->hasErrorMessages() ){
            $msg_controller['danger'] = $flash->getErrorMessages();
        } 
        
        if( $flash->hasInfoMessages() ){
            $msg_controller['info'] = $flash->getInfoMessages();
        } 
        
        if( $flash->hasMessages() ){
            $msg_controller['warning'] = $flash->getMessages();
        }
        
        return $msg_controller;
    }
    
    public function sendFlashMessagesLayout(){
        $msg_controller = $this->getAllFlashMessages();
        $this->layout()->setVariable('flash_messages',$msg_controller);   
    }
    
    public function objetosParaArray($objetos, $mesclar_duplicados=false){
        if( ! is_array($objetos)){
            $objetos = [];
        }
        $array = [];
        foreach ($objetos as $objeto) {
            if(is_object($objeto) && method_exists($objeto, 'toArray')){
                $array[] = $objeto->toArray($objeto);
            }
        }
        
        if($mesclar_duplicados){
            $array = $this->super_array_unique($array);
        }
        
        return $array;
        
    }
    
    protected function super_array_unique(array $arrays){
        $merge=[];
        foreach ($arrays as $array) {
            $merge = array_merge_recursive($merge, $array); 
        }
        
        $merge_unique = [];
        foreach ($merge as $key => $array) {
            $unique = array_unique($array);
            $merge_unique[$key] = (sizeof($unique)==1)?$unique[0]:$unique;
        }
        return $merge_unique;
    }
    
    protected function basePath(){
        $server = $this->getRequest()->getServer()->toArray();
        return $server['REQUEST_URI'];
    }
    
}
