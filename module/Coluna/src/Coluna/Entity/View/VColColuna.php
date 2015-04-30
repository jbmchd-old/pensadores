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
    private $chvNome;
    private $ctgNome;
    private $ctgApelido;
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

    function getColDataPostagem() {
        return $this->colDataPostagem;
    }

    function getColDataModificacao() {
        return $this->colDataModificacao;
    }

    function getUsrDataNascimento() {
        return $this->usrDataNascimento;
    }

    function getUsrDataCadastro() {
        return $this->usrDataCadastro;
    }

    function setColDataPostagem($colDataPostagem) {
        $this->colDataPostagem = (new \DateTime($colDataPostagem))->format('d/m/Y');
    }

    function setColDataModificacao($colDataModificacao) {
        $this->colDataModificacao = (new \DateTime($colDataModificacao))->format('d/m/Y');
    }

    function setUsrDataNascimento($usrDataNascimento) {
        $this->usrDataNascimento = (new \DateTime($usrDataNascimento))->format('d/m/Y');
    }

    function setUsrDataCadastro($usrDataCadastro) {
        $this->usrDataCadastro = (new \DateTime($usrDataCadastro))->format('d/m/Y');
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