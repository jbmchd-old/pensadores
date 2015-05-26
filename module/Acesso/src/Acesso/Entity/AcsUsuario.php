<?php

namespace Acesso\Entity;

use Nucleo\Service\GenericEntity;

class AcsUsuario extends GenericEntity {
	
    private $usrId;
    private $usrLogin;
    private $usrSenha;
    private $usrEmail;
    private $usrNome;
    private $usrSobrenome;
    private $usrGenero;
    private $usrDataNascimento;
    private $usrDataCadastro;
    private $usrFoto;
    private $usrStatus;

    
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