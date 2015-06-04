<?php

namespace Pensadores\Controller;

use Zend\View\Model\ViewModel;
use Nucleo\Controller\ControllerGenerico;

class IndexController extends ControllerGenerico{
    
    public function indexAction(){
        $srv_vcolunas_ultimas = $this->p()->getEntity('Coluna','VColUltimasPostadas');
        $col_ultimas = $this->objetosParaArray($srv_vcolunas_ultimas->getAll());
        
        $srv_vcolunas_sem_ultimas = $this->p()->getEntity('Coluna','VColSemUltimasPostadas');
        $col_sem_ultimas = $this->objetosParaArray($srv_vcolunas_sem_ultimas->getAll());
        
        $colunas = [];
        foreach ($col_ultimas as $key => $cada_coluna) {
            $col_ultimas[$key]['col_titulo']        = ucfirst(mb_strtolower($cada_coluna['col_titulo']));
            if(empty($cada_coluna['col_end_imagem'])){
                $col_ultimas[$key]['col_end_imagem']    = $this->atualPath().'img/colunas/imagem_padrao_coluna_paisagem.jpg';
                $col_ultimas[$key]['orient_imagem'] = 'paisagem';
            } else {
                $col_ultimas[$key]['col_end_imagem'] = getcwd().DIRECTORY_SEPARATOR.$cada_coluna['col_end_imagem'];
                $col_ultimas[$key]['orient_imagem'] = $this->getOrientacao($col_ultimas[$key]['col_end_imagem'] );
            }
        }
        
        return new ViewModel([
            'ultimas_colunas' => $col_ultimas,
            'colunas' => $col_sem_ultimas
        ]);
    }
    
    private function getOrientacao($endereco){
        $endereco = $this->p()->getCaminhoUniversal($endereco);
        $img_dados = getimagesize($endereco);
        $orientacao = 'retrato';
        if($img_dados[0]/$img_dados[1]>1.5){
            $orientacao = 'paisagem';
        } 
        return $orientacao;
    }
}
