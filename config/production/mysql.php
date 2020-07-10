<?php

return [
    'midwares' => [
        'entity' => 'local',
        'default' => 'local',
    ],

    'resources' => [

        'local' => [
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'database' => 'data_demo',
            'username' => 'data_demo',
            'password' => 'data_demo',

            'read' => [
                '/var/run/mysqld/mysqld.sock',
            ],
            'write' => [
                '/var/run/mysqld/mysqld.sock',
            ],
            'schema' => [
                '/var/run/mysqld/mysqld.sock',
            ],

            'options' => [
                PDO::ATTR_CASE => PDO::CASE_NATURAL,
                PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => false,
            ],
        ],
    ],
];

