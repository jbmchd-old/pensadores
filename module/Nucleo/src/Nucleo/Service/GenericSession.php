<?php

namespace Nucleo\Service;

use Zend\Session\Container;
use Zend\Authentication\Storage\Session;

class GenericSession extends Container {
    
    const NAME_SESSION_DEFAULT = 'Pensadores';
    
    private $container = null;
    private $session = null;

    private $nameSession = self::NAME_SESSION_DEFAULT;

    public function __construct() {
        parent::__construct();
        $this->initSession();
        $this->container = $this->getManager()->getStorage()->toArray()[$this->nameSession];
    }

    public function initSession(){
        $sessao = $this->getManager()->getStorage()->toArray();
        if( ! key_exists($this->nameSession, $sessao)){
            $this->session = new Session($this->nameSession);
            $this->session->write([]);
        } 
    }

    public function getSession(){
        return $this->container;
    }
    
    public function getArrayCopy($member = false){
        $container = (array) $this->container->getArrayCopy();
        return ($member && isset($container[$member])) ? $container[$member] : $container ;
    }
    
    public function isLogged(){
        $container = $this->getArrayCopy();
        return (key_exists('storage', $container) && sizeof($container['storage']));
    }
    
    public function addInSession(array $cont, $member='default'){
        try {
            $this->container[$member] = $cont;
            return $this;
        } catch (Exception $ex) {
            return $ex;
        }
        
    }
    
    public function removeOfSession($keys, $member='default'){
        try {
            $keys = (array) $keys;
            foreach ($keys as $key) {
                unset($this->container[$member]);
            }
            return $this;
        } catch (Exception $ex) {
            return $ex;
        }
       
    }

    public function clearAll(){
        try {
            echo '<pre>';
            print_r(get_class_methods($this->session));
            die();
            $this->container->getManager()->getStorage()->clear();
            return $this;
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
