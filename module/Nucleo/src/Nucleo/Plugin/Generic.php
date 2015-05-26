<?php
/**
 * Created by PhpStorm.
 * User: jb
 * Date: 05/02/14
 * Time: 12:03
 */

namespace Nucleo\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Nucleo\Service\ServiceManager;

class Generic extends AbstractPlugin {
    
    public function getEntity($module, $entity_name){
        $service = $this->getController()->getServiceLocator()->get('Nucleo\ServiceManager')->getService($module, $entity_name, ServiceManager::TYPE_ENTITY);
        return $service;
    }
    
    public function getService($module, $service_name){
        $service = $this->getController()->getServiceLocator()->get('Nucleo\ServiceManager')->getService($module, $service_name, ServiceManager::TYPE_SERVICE);
        return $service;
    }

    public function getResponseReason($cod) {
        $resposta = [
            'cod' => $cod,
            'message' => $this->getResponseReasonMessage($cod),
        ];
        return array_merge($resposta, $this->getResponseReasonType($cod));
    }
    
    public function getResponseReasonMessage($cod) {
        
        $reason = [
            1 => 'info',
//            2 => 'ok',
            3 => 'redirect',
            4 => 'error',
//            100 => "Continue",
//            101 => "Switching Protocols",
//            102 => "Processing",
            200 => "OK",
//            201 => "Created",
//            202 => "Accepted",
//            203 => "Non-Authoritative Information",
//            204 => "No Content",
//            205 => "Reset Content",
//            206 => "Partial Content",
//            207 => "Multi-status",
//            208 => "Already Reported",
//            300 => "Multiple Choices",
//            301 => "Moved Permanently",
//            302 => "Found",
//            303 => "See Other",
//            304 => "Not Modified",
//            305 => "Use Proxy",
//            306 => "Switch Proxy",
//            307 => "Temporary Redirect",
//            400 => "Bad Request",
//            401 => "Unauthorized",
//            402 => "Payment Required",
//            403 => "Forbidden",
//            404 => "Not Found",
//            405 => "Method Not Allowed",
//            406 => "Not Acceptable",
//            407 => "Proxy Authentication Required",
//            408 => "Request Time-out",
//            409 => "Conflict",
//            410 => "Gone",
//            411 => "Length Required",
//            412 => "Precondition Failed",
//            413 => "Request Entity Too Large",
//            414 => "Request-URI Too Long",
//            415 => "Unsupported Media Type",
//            416 => "Requested range not satisfiable",
//            417 => "Expectation Failed",
//            418 => "I'm a teapot",
//            422 => "Unprocessable Entity",
//            423 => "Locked",
//            424 => "Failed Dependency",
//            425 => "Unordered Collection",
//            426 => "Upgrade Required",
//            428 => "Precondition Required",
//            429 => "Too Many Requests",
//            431 => "Request Header Fields Too Large",
//            500 => "Internal Server Error",
//            501 => "Not Implemented",
//            502 => "Bad Gateway",
//            503 => "Service Unavailable",
//            504 => "Gateway Time-out",
//            505 => "HTTP Version not supported",
//            506 => "Variant Also Negotiates",
//            507 => "Insufficient Storage",
//            508 => "Loop Detected",
            511 => "Falha na autenticação",
        ];
        
        if(isset($reason[$cod])) {
            return $reason[$cod];
        } else {
            throw new \Exception('Response Code Incorrect in PluginGenerico (getResponseReasonMessage()).');
        }
        
    }
    
    public function getResponseReasonType($cod){
        if($cod >= 400 || $cod = 4){
            $array = [ 'type'=>'error', 'css-type'=>'danger' ];
        } else if($cod >= 300 || $cod = 3){
            $array = [ 'type'=>'redirect', 'css-type'=>'warning' ];
        } else if($cod >= 200 || $cod = 2){
            $array = [ 'type'=>'ok', 'css-type'=>'success' ];
        } else {
            $array = [ 'type'=>'info', 'css-type'=>'info' ];
        } 
        
        return $array;
    }
} 