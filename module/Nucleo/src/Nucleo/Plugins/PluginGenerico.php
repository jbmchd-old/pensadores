<?php

namespace Nucleo\Plugins;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class PluginGenerico extends AbstractPlugin {

    public function buscaSessao(){
        return $this->sessao = new \Zend\Session\Container("Pensadores");
    }

    public function getService($module, $service_name){
        $service = $this->getController()->getServiceLocator()->get('Nucleo\ServiceManager')->getService($module, $service_name);
        return $service;
    }


} 