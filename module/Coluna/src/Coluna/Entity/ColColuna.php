<?php

namespace Coluna\Entity;

use Nucleo\Services\GenericEntity;

class ColColuna extends GenericEntity {
	
    private $colId;
    private $colTitulo;
    private $colTexto;
    private $colDataPostagem;
    private $colDataModificacao;
    private $colResumo;
    private $colObservacao;
    private $colEndImagem;
    private $colBloqueado;
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