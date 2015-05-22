<?php
return [
    'router' => [
        'routes' => [
            'pensadores' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        '__NAMESPACE__' => 'Pensadores\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'abstract_factories' => [
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Pensadores\Controller\Index' => 'Pensadores\Controller\IndexController'
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'bloco/destaque'  => __DIR__ . '/../view/pensadores/partial/bloco-destaque.phtml',  
            'bloco/comum'  => __DIR__ . '/../view/pensadores/partial/bloco-comum.phtml',  
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
