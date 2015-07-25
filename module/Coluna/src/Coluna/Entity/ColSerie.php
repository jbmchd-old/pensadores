<?php

namespace Coluna\Entity;

use Nucleo\Service\GenericEntity;

class ColSerie extends GenericEntity {
	
    private $serId;
    private $colId;
    private $colFilhoId;
    private $serOrdem;
    
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