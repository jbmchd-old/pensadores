<?php

namespace Coluna\Controller;

use Zend\View\Model\ViewModel;
use Coluna\Controller\ColunasController;

class PensandoBibliaController extends ColunasController{
    
    public function indexAction()
    {
        $params = $this->params()->fromRoute();

        if(isset($params['id']) && $params['id']>0){
            $coluna = $this->buscaColuna($params['id']);
            
            $dados_view = [
                'coluna'=>$coluna
            ];
        } else {
            $this->getResponse()->setStatusCode(404);
            return;
            
        }
        return new ViewModel($dados_view);
    }
}
