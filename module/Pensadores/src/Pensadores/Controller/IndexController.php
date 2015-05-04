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
        foreach ($colunas_raw as $cada_coluna) {
            $ctg_id = $cada_coluna->getCtgId();
            $col_id = $cada_coluna->getColId();
            $imagem_coluna = $cada_coluna->getColEndImagem();
            if(empty(trim($imagem_coluna))){
                $imagem_coluna_end = $this->basePath().'img/colunas/imagem_padrao_coluna.jpg';
            }
            
            $colunas[$ctg_id][$col_id]['col_id']            = $cada_coluna->getColId();
            $colunas[$ctg_id][$col_id]['col_titulo']        = ucfirst(mb_strtolower($cada_coluna->getColTitulo()));
            $colunas[$ctg_id][$col_id]['col_texto']         = $cada_coluna->getColTexto();
            $colunas[$ctg_id][$col_id]['col_data_postagem'] = $cada_coluna->getColDataPostagem();
            $colunas[$ctg_id][$col_id]['col_resumo']        = $cada_coluna->getColResumo();
            $colunas[$ctg_id][$col_id]['col_end_imagem']    = $imagem_coluna_end;
            $colunas[$ctg_id][$col_id]['chv_id']            = $cada_coluna->getChvId();
            $colunas[$ctg_id][$col_id]['chv_nome']          = $cada_coluna->getChvNome();
            $colunas[$ctg_id][$col_id]['ctg_id']            = $cada_coluna->getCtgId();
            $colunas[$ctg_id][$col_id]['ctg_nome']          = $cada_coluna->getCtgNome();
            $colunas[$ctg_id][$col_id]['ctg_apelido']       = $cada_coluna->getCtgApelido();
            $colunas[$ctg_id][$col_id]['usr_id']            = $cada_coluna->getUsrId();
            $colunas[$ctg_id][$col_id]['usr_nome']          = $cada_coluna->getUsrNome();
            $colunas[$ctg_id][$col_id]['usr_sobrenome']     = $cada_coluna->getUsrSobrenome();
            
            $col_id += 5;
            $colunas[$ctg_id][$col_id]['col_id']            = $cada_coluna->getColId();
            $colunas[$ctg_id][$col_id]['col_titulo']        = ucfirst(mb_strtolower($cada_coluna->getColTitulo()));
            $colunas[$ctg_id][$col_id]['col_texto']         = $cada_coluna->getColTexto();
            $colunas[$ctg_id][$col_id]['col_data_postagem'] = $cada_coluna->getColDataPostagem();
            $colunas[$ctg_id][$col_id]['col_resumo']        = $cada_coluna->getColResumo();
            $colunas[$ctg_id][$col_id]['col_end_imagem']    = $imagem_coluna_end;
            $colunas[$ctg_id][$col_id]['chv_id']            = $cada_coluna->getChvId();
            $colunas[$ctg_id][$col_id]['chv_nome']          = $cada_coluna->getChvNome();
            $colunas[$ctg_id][$col_id]['ctg_id']            = $cada_coluna->getCtgId();
            $colunas[$ctg_id][$col_id]['ctg_nome']          = $cada_coluna->getCtgNome();
            $colunas[$ctg_id][$col_id]['ctg_apelido']       = $cada_coluna->getCtgApelido();
            $colunas[$ctg_id][$col_id]['usr_id']            = $cada_coluna->getUsrId();
            $colunas[$ctg_id][$col_id]['usr_nome']          = $cada_coluna->getUsrNome();
            $colunas[$ctg_id][$col_id]['usr_sobrenome']     = $cada_coluna->getUsrSobrenome();
            
            $col_id += 5;
            $colunas[$ctg_id][$col_id]['col_id']            = $cada_coluna->getColId();
            $colunas[$ctg_id][$col_id]['col_titulo']        = ucfirst(mb_strtolower($cada_coluna->getColTitulo()));
            $colunas[$ctg_id][$col_id]['col_texto']         = $cada_coluna->getColTexto();
            $colunas[$ctg_id][$col_id]['col_data_postagem'] = $cada_coluna->getColDataPostagem();
            $colunas[$ctg_id][$col_id]['col_resumo']        = $cada_coluna->getColResumo();
            $colunas[$ctg_id][$col_id]['col_end_imagem']    = $imagem_coluna_end;
            $colunas[$ctg_id][$col_id]['chv_id']            = $cada_coluna->getChvId();
            $colunas[$ctg_id][$col_id]['chv_nome']          = $cada_coluna->getChvNome();
            $colunas[$ctg_id][$col_id]['ctg_id']            = $cada_coluna->getCtgId();
            $colunas[$ctg_id][$col_id]['ctg_nome']          = $cada_coluna->getCtgNome();
            $colunas[$ctg_id][$col_id]['ctg_apelido']       = $cada_coluna->getCtgApelido();
            $colunas[$ctg_id][$col_id]['usr_id']            = $cada_coluna->getUsrId();
            $colunas[$ctg_id][$col_id]['usr_nome']          = $cada_coluna->getUsrNome();
            $colunas[$ctg_id][$col_id]['usr_sobrenome']     = $cada_coluna->getUsrSobrenome();
            
            
        }
        
        return new ViewModel([
            'colunas' => $colunas
        ]);
    }
}
