<?php

// set the default timezone to use. Available since PHP 5.1
date_default_timezone_set('America/Sao_Paulo');

return array(
    'zendexperts_zedb' => array(
        'adapter' => [
            'driver' => 'Pdo_mysql',
            'driver_options' => [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
            ],
        ],
    ),
);