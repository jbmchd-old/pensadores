<?php

namespace Nucleo\Services;

use ZeDb\Entity;

class GenericEntity extends Entity {
    
    private $data = [];
    
    public function exchangeArray($data){
        $this->data = $data;
        (new \Zend\Stdlib\Hydrator\ClassMethods())->hydrate($data, $this);
    }
    
    function tableFill(Array $data){
        (new \Zend\Stdlib\Hydrator\ClassMethods())->hydrate($data, $this);
        return $this;
    }
    
    function toArray() {
        $array = [];
        $get_methods = $this->attrToGetMethods();
        if(sizeof($get_methods)){
            foreach ($get_methods as $attr => $method) {
                $array[$attr] = $this->$method();
            }
        }
        return $array;
        
    }
    
    private function attrToGetMethods($data=null){
        if(sizeof($this->data)){ $data = $this->data; }
        
        $gettersName = [];
        foreach ($data as $property => $value) {
            $gettersName[$property] = 'get' . implode('', explode(' ', ucwords(str_replace('_', ' ',$property))));
        }
        return $gettersName;
    }

}
