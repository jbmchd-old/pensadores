<?php
return [
    'router' => [
        'routes' => [
            'coluna' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/coluna',
                    'defaults' => [
                        '__NAMESPACE__' => 'Coluna\Controller',
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
            'Coluna\Controller\Index' => 'Coluna\Controller\IndexController'
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
