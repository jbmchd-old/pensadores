<?php

mb_internal_encoding('UTF-8');

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