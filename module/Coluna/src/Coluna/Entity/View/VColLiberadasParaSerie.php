<?php

namespace Coluna\Entity\View;

use Nucleo\Service\GenericEntity;

class VColLiberadasParaSerie extends GenericEntity {
	
    private $colId;
    private $colTitulo;
    private $ctgId;
    private $usrId;
    
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