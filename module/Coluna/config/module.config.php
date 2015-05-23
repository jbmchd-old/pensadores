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
                        'controller'    => 'Colunas',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/[:controller[/:id]]',
                            'constraints' => [
                                'controller'=> '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'        => '[0-9]*',
                            ],
                            'defaults' => [
                            ],
                        ],
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
            'Coluna\Controller\Colunas' => 'Coluna\Controller\ColunasController',
            'Coluna\Controller\PensandoCabeca' => 'Coluna\Controller\PensandoCabecaController',
            'Coluna\Controller\PensandoCoracao' => 'Coluna\Controller\PensandoCoracaoController',
            'Coluna\Controller\PensandoBiblia' => 'Coluna\Controller\PensandoBibliaController',
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'exibe_coluna'  => __DIR__ . '/../view/coluna/partial/exibe_coluna.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
