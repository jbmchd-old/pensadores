<?php

namespace Nucleo\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Zend\View\Model\JsonModel;

abstract class ControllerGenerico extends AbstractActionController {

    /**
     * constante usada pelo metodo 'enviarMensagem' para definir o destino das mensagens
     */
    const MSG_PARA_ACTION = 1;
    
    /**
     * constante usada pelo metodo 'enviarMensagem' para definir o destino das mensagens
     */
    const MSG_PARA_LAYOUT = 0;
    
    const MESCLAR_DUPLICADOS = 1;
    
    private $msg_controller;

    public function buscaDateServidorJsonAction(){
        return new JsonModel(array('date' => (new \DateTime('now'))->format('Y-m-d H:i:s')));
    }

    public function __invoke() {
        die(__NAMESPACE__.__CLASS__);
    }
    
    public function setarMensagem($msg, $tipo){
        $flash = $this->flashMessenger();
        if($tipo === 'sucesso'){
            $flash->addSuccessMessage($msg);
        } else if($tipo === 'erro'){
            $flash->addErrorMessage($msg);
        } else if($tipo === 'info'){
            $flash->addInfoMessage($msg);
        } else if($tipo === 'alerta'){
            $flash->addMessage($msg);
        } 
    }
    
    public function enviarMensagens($destino=ControllerGenerico::MSG_PARA_LAYOUT){
        $flash = $this->flashMessenger();
        $msg_controller='';
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
        
        if($msg_controller){
            
            if($destino == ControllerGenerico::MSG_PARA_LAYOUT){
                $this->layout()->setVariable('msg_controller',$msg_controller);
            } else {
                $this->msg_controller = $msg_controller;
            }
        }   
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
    
}
