<?php
/**
 * Created by PhpStorm.
 * User: jb
 * Date: 05/02/14
 * Time: 12:03
 */

namespace Nucleo\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Nucleo\Service\GenericSession;

class Session extends AbstractPlugin {
    
    private $container = null;
    
    public function __construct() {
        $this->container = new GenericSession();   
    }
    
    public function initSession(){
        return $this->container->initSession();
    }

    public function getSession(){
        return $this->container;
    }
    
    public function getArrayCopy($member = false){
        return $this->container->getArrayCopy($member);
    }
    
    public function isLogged(){
        return $this->container->isLogged();
    }
    
    public function addInSession(array $cont, $member='default'){
        return $this->container->addInSession($cont, $member);
    }
    
    public function removeOfSession($keys, $member='default'){
        return $this->container->removeOfSession($keys, $member);
    }

    public function clearAll(){
        return $this->container->clearAll();
    }
    
} 