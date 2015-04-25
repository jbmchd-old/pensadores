<?php

namespace Nucleo\ViewHelpers;

use Zend\View\Helper\AbstractHelper;

class HelperGenerico extends AbstractHelper {
    
    public function buscaSessao(){
        return (new \Zend\Session\Container(""))->storage;
    }
    
    

}
