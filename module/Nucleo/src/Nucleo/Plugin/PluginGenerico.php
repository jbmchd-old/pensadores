<?php

namespace Nucleo\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class PluginGenerico extends AbstractPlugin {

    private $sessao = null;
    
    public function __construct() {
        $this->sessao = new \Zend\Session\Container("Pensadores");
    }

    public function getMainSession(){
        return $this->sessao->getArrayCopy();
    }
    
    public function isLogged(){
        return isset($this->sessao->getArrayCopy()['storage']['usuario']);
    }

    public function getEntity($module, $entity_name){
        $entity = $this->getController()->getServiceLocator()->get('Nucleo\ServiceManager')->getEntity($module, $entity_name);
        return $entity;
    }


} 