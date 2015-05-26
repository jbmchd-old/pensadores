<?php

namespace Acesso\Service;

use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage,
    ZeDb\DatabaseManager;

use  Nucleo\Service\GenericService;

/**
 * Classe responsavel por gerenciar a autenticacao do sistema
 * 
 * @author João Paulo Constantino <joaopaulo@doctum.edu.br>
 * @version 1.1
 */
class Authenticate extends GenericService {

    /**
     * Nome da sessão a ser criada
     * 
     * @var string $nomeSession
     */
    private $nomeSession = "Pensadores";

    /**
     * Objeto que contem os metodo para trabalhar com sessao
     * 
     * @var SessionStorage $sessionStorage
     */
    private $sessionStorage;

    /**
     * Objeto que contem os metodos de autenticaca
     * 
     * @var \Acesso\Authenticate\AuthenticateAdapter $authenticateAdapter
     */
    private $authenticateAdapter;

    /**
     * Servico de authenticacao do ZF2
     * 
     * @var AuthenticationService $authenticateService
     */
    private $authenticateService;

    /**
     *
     * @param DatabaseManager $em        	
     */
    public function __construct(DatabaseManager $em) {
        parent::__construct($em);
        
        $this->setAuthenticateAdapter( new \Acesso\Service\AuthenticateAdapter($em) );
        $this->setAuthenticateService(new AuthenticationService());
        $this->setSessionStorage(new SessionStorage($this->nomeSession));
        $this->getAuthenticateService()->setStorage($this->getSessionStorage());
    }

    public function getNomeSession() {
        return $this->nomeSession;
    }

    public function setNomeSession($nomeSession) {
        $this->nomeSession = $nomeSession;
    }

    public function getSessionStorage() {
        return $this->sessionStorage;
    }

    public function setSessionStorage(SessionStorage $sessionStorage) {
        $this->sessionStorage = $sessionStorage;
    }

    public function getAuthenticateAdapter() {
        return $this->authenticateAdapter;
    }

    public function setAuthenticateAdapter(\Acesso\Service\AuthenticateAdapter $authenticateAdapter) {
        $this->authenticateAdapter = $authenticateAdapter;
    }

    public function getAuthenticateService() {
        return $this->authenticateService;
    }

    public function setAuthenticateService(AuthenticationService $authenticateService) {
        $this->authenticateService = $authenticateService;
    }

    /**
     * @param string $login        	
     * @param string $passwd        	
     * @return array
     */
    public function validaAutenticacao($login, $passwd) {
        $this->authenticateAdapter->setLogin($login);
        $this->authenticateAdapter->setPasswd($passwd);

        $result = $this->authenticateService->authenticate($this->authenticateAdapter);
        if ($result->isValid()) {
            
            $cod_usuario = $result->getIdentity()['acs_usuario']->getUsrId();
            $usuario = $result->getIdentity()['acs_usuario']->getUsrLogin();
            
            $dados = array(
                'usuario' => [
                    'cod_usuario' => $cod_usuario,
                    'usuario' => $usuario,
                ]
            );
            
            $this->escreveSessao($dados);
            
            return $dados;
        } else {
            $this->escreveSessao([]);
            return array(
                'erro' => $result->getMessages()
            );
        }
    }

    /**
     * Escreve os dados recebidos por parametro na sessao
     * 
     * @param string $contents        	
     */
    public function escreveSessao($contents) {
        $this->sessionStorage->write($contents, null);
    }

    /**
     * Destroi a sessao
     */
    public function destroiSessao() {
        $this->authenticateService->setStorage($this->getSessionStorage());
        $this->authenticateService->clearIdentity();

    }

    /**
     * Retorna o conteudo escrito na sessao quando o usuairo faz a autenticacao
     * 
     * @return type
     */
    public function getUserAuth() {
        $this->authenticateService->setStorage($this->getSessionStorage());
        return $this->authenticateService->getIdentity();
    }

    private function buscaUsuarioAplicativos($cod_usuario) {
        $srv_usuario_aplicativos = $this->getEntity('Acesso', 'DwvUsuarioAplicativosUnidade');
        $aplicativos_raw = $srv_usuario_aplicativos->getAllByCodUsuario($cod_usuario);
        $aplicativos_raw = $this->objetosParaArray($aplicativos_raw);
        $aplicativos = [];
        foreach ($aplicativos_raw as $cada_app) {
            $cod_aplicativo = $cada_app['cod_sistema'];
            $cod_unid = $cada_app['cod_unidade'];
            
            $aplicativos[$cod_aplicativo]['cod_sistema'] = $cada_app['cod_sistema'];
            $aplicativos[$cod_aplicativo]['nome'] = $cada_app['nome'];
            $aplicativos[$cod_aplicativo]['acesso_dpc_online'] = $cada_app['acesso_dpc_online'];

            $aplicativos[$cod_aplicativo]['unidades'][$cod_unid]['cod_unidade'] = $cod_unid;
            $aplicativos[$cod_aplicativo]['unidades'][$cod_unid]['acesso_liberado'] = $cada_app['acesso_liberado'];
            $aplicativos[$cod_aplicativo]['unidades'][$cod_unid]['acesso_local'] = $cada_app['acesso_local'];
            $aplicativos[$cod_aplicativo]['unidades'][$cod_unid]['acesso_remoto'] = $cada_app['acesso_remoto'];
        }
        
        return $aplicativos;
    }
}

?>