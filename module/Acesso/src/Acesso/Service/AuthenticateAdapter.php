<?php
 
namespace Acesso\Service;
 
use Zend\Authentication\Adapter\AdapterInterface,
    Zend\Authentication\Result;
 
use ZeDb\DatabaseManager;
 
class AuthenticateAdapter extends \Nucleo\Service\GenericService implements AdapterInterface {
     
    /**
     * Login de acesso no sistema
     * @var string $login
     */
    private $login;
     
    /**
     * Senha de acesso ao sistema
     * @var string $passwd
     */
    private $passwd;
 
    /**
     * Nome da entidade que contem o login e senha do usuario
     * @var string $entidadeInfoAcesso
     */
    private $srvAcsUsuario;
     
    /**
     * @var \ZeDb\DatabaseManager $em
     */
//    private $em;
 
    public function __construct(DatabaseManager $em ) {
        $this->em = $em;
        $this->srvAcsUsuario = $this->getEntity('Acesso', 'AcsUsuario');
    }
 
    public function getLogin() {
        return $this->login;
    }
 
    public function setLogin($login) {
        $this->login = $login;
    }
 
    public function getPasswd() {
        return $this->passwd;
    }
 
    public function setPasswd($passwd) {
        $this->passwd = sha1($passwd);
    }
 
    /**
     * Realiza a autenticação do usuario
     * @return Result
     */
    public function authenticate() {
        $srv_acs_usuario = $this->srvAcsUsuario;
        $user = $srv_acs_usuario->getByUsrLoginAndUsrSenha($this->getLogin(),$this->getPasswd());
        
        if( is_object($user) ){
            return new Result(Result::SUCCESS, array('acs_usuario'=>$user), array('OK'));
        }
        else {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, array('user'=>$user));
        }
    }    
}
 