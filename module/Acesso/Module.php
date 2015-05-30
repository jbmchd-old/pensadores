<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Acesso;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
 
    public function getServiceConfig(){
        return [
            'factories' => [
                'Acesso\Service\Authenticate' => function ($sm, $namespace_lower, $namespace){
                    return new \Acesso\Service\Authenticate( $sm->get('ZeDbManager') );
                }
            ]
        ];
    }

    //====================================================

    /**
     * Método que é executado quando o modulo é carregado
     * Este método esta atribuindo um evento para verificar se o cara está logado no sistema, validaAutenticacao
     * @param \Zend\ModuleManager\ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager)
    {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        
        $sharedEvents->attach("Zend\Mvc\Controller\AbstractActionController", 
                MvcEvent::EVENT_DISPATCH,
                array($this,'validaAutenticacao'),100);
    }
    
    /**
     * Método que verifica se o usuario está logado
     * @param type $e
     */
    public function validaAutenticacao( $e ){
        $authenticateService = new \Zend\Authentication\AuthenticationService();
        $authenticateService->setStorage( new \Zend\Authentication\Storage\Session("Pensadores") );
        $sessao = new \Zend\Session\Container("Pensadores");
        
        
        $controller = $e->getTarget();
        $em = $controller->getServiceLocator()->get( 'ZeDbManager' );

        $rotaAcessada = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();
        
        //erro 404: http://pensadores.local:8080/colunas/pensando-cabeca/100
        
        /** Liberando rota para não precisar de autenticação */
        $rota_livre = in_array($rotaAcessada, ['pensadores', 'coluna/default', 'acesso/login', 'acesso/logout','acesso/nao-autorizado']);
        if ( $rota_livre ){ 
            return true; 
        } else if ( !$authenticateService->hasIdentity()){
            $controller->redirect()->toRoute("acesso/login");
        } else {
            $controlador = $controller->params()->fromRoute('controller');
            $action      = $controller->params()->fromRoute('action');
            $user        = $authenticateService->getIdentity()['usuario'];
            
            $esta_autorizado = TRUE;
            
            if ( ! $esta_autorizado ) { 
                return $controller->redirect()->toRoute("acesso/nao-autorizado", array('controlador' => $controlador, 'acao' => $action));
            }
            

        }

    }
    
}

