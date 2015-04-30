<?php

namespace Pensadores\Controller;

use Zend\View\Model\ViewModel;
use Nucleo\Controller\ControllerGenerico;

class IndexController extends ControllerGenerico{
    
    public function indexAction()
    {
        $srv_vcolunas = $this->p()->getEntity('Coluna','VColColuna');
        $colunas_raw = $srv_vcolunas->getAllOrderByCtgIdAndColDataPostagemDesc();
        
        $colunas = [];
        foreach ($colunas_raw as $key => $cada_coluna) {
            $ctg_id = $cada_coluna->getCtgId();
            $imagem_coluna = $cada_coluna->getColEndImagem();
            if(empty(trim($imagem_coluna))){
                $imagem_coluna_end = $this->basePath().'img/colunas/imagem_padrao_coluna.jpg';
            }
            
            $colunas[$ctg_id][$key]['col_id'] = $cada_coluna->getColId();
            $colunas[$ctg_id][$key]['col_titulo'] = ucfirst(mb_strtolower($cada_coluna->getColTitulo()));
            $colunas[$ctg_id][$key]['col_texto'] = $cada_coluna->getColTexto();
            $colunas[$ctg_id][$key]['col_data_postagem'] = $cada_coluna->getColDataPostagem();
            $colunas[$ctg_id][$key]['col_resumo'] = $cada_coluna->getColResumo();
            $colunas[$ctg_id][$key]['col_end_imagem'] = $imagem_coluna_end;
            $colunas[$ctg_id][$key]['chv_id'] = $cada_coluna->getChvId();
            $colunas[$ctg_id][$key]['chv_nome'] = $cada_coluna->getChvNome();
            $colunas[$ctg_id][$key]['ctg_id'] = $cada_coluna->getCtgId();
            $colunas[$ctg_id][$key]['ctg_nome'] = $cada_coluna->getCtgNome();
            $colunas[$ctg_id][$key]['ctg_apelido'] = $cada_coluna->getCtgApelido();
            $colunas[$ctg_id][$key]['usr_id'] = $cada_coluna->getUsrId();
            $colunas[$ctg_id][$key]['usr_nome'] = $cada_coluna->getUsrNome();
            $colunas[$ctg_id][$key]['usr_sobrenome'] = $cada_coluna->getUsrSobrenome();
            
        }
        
        return new ViewModel([
            'colunas' => $colunas
        ]);
    }
}
