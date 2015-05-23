<?php

namespace Pensadores\Controller;

use Zend\View\Model\ViewModel;
use Nucleo\Controller\ControllerGenerico;

class IndexController extends ControllerGenerico{
    
    public function indexAction()
    {
        $srv_vcolunas_ultimas = $this->p()->getEntity('Coluna','VColUltimasPostadas');
        $col_ultimas = $this->objetosParaArray($srv_vcolunas_ultimas->getAll());
        
        $srv_vcolunas_sem_ultimas = $this->p()->getEntity('Coluna','VColSemUltimasPostadas');
        $col_sem_ultimas = $this->objetosParaArray($srv_vcolunas_sem_ultimas->getAll());
        
        $colunas = [];
        foreach ($col_ultimas as $key => $cada_coluna) {
            $col_ultimas[$key]['col_titulo']        = ucfirst(mb_strtolower($cada_coluna['col_titulo']));
            $col_ultimas[$key]['col_end_imagem']    = $this->basePath().'img/colunas/imagem_padrao_coluna_paisagem2.jpg';
        }
        
        return new ViewModel([
            'ultimas_colunas' => $col_ultimas,
            'colunas' => $col_sem_ultimas
        ]);
    }
}
