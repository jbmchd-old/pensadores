<?php

namespace Coluna\Controller;

use Zend\View\Model\ViewModel;
use Nucleo\Controller\ControllerGenerico;

class ColunasController extends ControllerGenerico {
    
    public function indexAction()
    {
        return new ViewModel();
    }
    
    protected function buscaColuna($col_id){
        $srv_vcolunas = $this->p()->getEntity('Coluna','VColColuna');
        $coluna = $this->objetosParaArray( $srv_vcolunas->getAllByColId($col_id) );
        return $coluna[0];
    }
}
