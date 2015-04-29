<?php

namespace Coluna\Entity;

use Nucleo\Service\GenericEntity;

class ColCategoria extends GenericEntity {
	
    private $ctgId;
    private $ctgNome;
    private $ctgApelido;
    
    public function __call($name, $arguments) {
        $attr = lcfirst(substr($name, 3));
        if(strpos($name, 'set') === 0){
            $this->$attr = $arguments[0];
        } else if(strpos($name, 'get') === 0){
            return $this->$attr;
        } else {
            return false;;
        }
    }

    
}
?>