<?php

namespace Coluna\Entity\View;

use Nucleo\Service\GenericEntity;

class VColColuna extends GenericEntity {
	
    private $usrId;
    private $ctgId;
    private $chvId;
    private $colId;
    private $colTitulo;
    private $colTexto;
    private $colDataPostagem;
    private $colDataModificacao;
    private $colResumo;
    private $colObservacao;
    private $colEndImagem;
    private $colStatus;
    private $serId;
    private $serNome;
    private $colPaiId;
    private $serOrdem;
    private $chvNome;
    private $ctgNome;
    private $ctgApelido;
    private $usrLogin;
    private $usrEmail;
    private $usrNome;
    private $usrSobrenome;
    private $usrGenero;
    private $usrDataNascimento;
    private $usrDataCadastro;
    private $usrFoto;
    private $usrStatus;

    public function setColDataPostagem($colDataPostagem){
        $this->colDataPostagem = (new \DateTime($colDataPostagem))->format("d/m/y");
    }

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