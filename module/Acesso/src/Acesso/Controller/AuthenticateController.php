<?php

namespace Acesso\Controller;

use Zend\View\Model\ViewModel;
use Nucleo\Controller\ControllerGenerico;

class AuthenticateController extends ControllerGenerico {

    public function loginAction(){
        
        $request = $this->getRequest();
        $retorno = [];
        if ( $request->isPost() ){
            $dados_form = $request->getPost()->toArray();
            /* faz a validação e retorna o resultado para a tela de login */
            $dados = $this->validaUsuarioSenha($dados_form);
            if($dados['cod'] == 511){
                $retorno = [
                    'cod'=>$dados['cod'], 
                    'messageLogin' => 'Usuario/senha incorretos.'
                ];
            } else {
                return $this->redirect()->toUrl('/coluna/admin');
            }
            
            
        }
        
        return new ViewModel($retorno);
        
    }

    public function logoutAction($redirect = true){
        $autenticaService = $this->getServiceLocator()->get('Acesso\Service\Authenticate');
        $autenticaService->destroiSessao();
        if($redirect){
            return $this->redirect()->toUrl('/');
        }
            
    }
    
    public function naoAutorizadoAction(){}
    
    private function validaUsuarioSenha($dados_form){
        $autenticaService = $this->getServiceLocator()->get('Acesso\Service\Authenticate');
        $auth = $autenticaService->validaAutenticacao($dados_form['user'], $dados_form['pass']);
        
        if ( is_object($auth) ){
            $dados = $this->p()->getResponseReason(4);
            $dados['message'] = 'Erro desconhecido.';
        }

        if ( isset($auth['erro']) ){
            $dados = $this->p()->getResponseReason(511);
            $dados['message'] = 'Usuário/senha inválidos, verifique.';
        } else {
            $dados = $this->p()->getResponseReason(200);
            $dados['message'] = 'Login efetuado com sucesso.';
            $dados['result'] = $auth['usuario'];
        }
        
        return $dados;
    }
}
