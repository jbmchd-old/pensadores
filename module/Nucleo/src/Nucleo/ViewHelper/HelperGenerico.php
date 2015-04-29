<?php

namespace Nucleo\ViewHelpers;

use Zend\View\Helper\AbstractHelper;

class HelperGenerico extends AbstractHelper {
    
    private $sessao;
    
    public function __construct() {
        $this->sessao = new \Zend\Session\Container("Pensadores");
    }

    public function getMainSession(){
        return $this->sessao->getArrayCopy();
    }
    
    public function isLogged(){
        return isset($this->sessao->getArrayCopy()['storage']['usuario']);
    }
    
    

}
