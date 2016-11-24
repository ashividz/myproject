<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => storage_path('database.sqlite'),
            'prefix'   => '',
        ],

        //Default

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'localhost'),
            'database'  => env('DB_DATABASE', 'forge'),
            'username'  => env('DB_USERNAME', 'forge'),
            'password'  => env('DB_PASSWORD', ''),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        //NHS
        'mysql2' => [
            'driver'    => 'mysql',
            'host'      => 'query11.db.11345018.hostedresource.com',
            'port'      => '3306',
            'database'  => 'query11',
            'username'  => 'query11',
            'password'  => 'Nutr!w3l',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

         //NHS
        'mysql3' => [
            'driver'    => 'mysql',
            'host'      => 'newwebwp.db.11345018.hostedresource.com',
            'port'      => '3306',
            'database'  => 'newwebwp',
            'username'  => 'newwebwp',
            'password'  => 'NwH@987#',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],
        /*
        sudo apt-get install php5-pgsql php5enmod pgsql
        sudo service apache2 restart
        */
        'pgsql' => [
            'driver'   => env('DB_DIALER_CONNECTION_NGUCC', 'pgsql'),
            'host'     => env('DB_DIALER_HOST_NGUCC', 'localhost'),
            'database' => env('DB_DIALER_DATABASE_NGUCC', 'homestead'),
            'username' => env('DB_DIALER_USERNAME_NGUCC', 'forge'),
            'password' => env('DB_DIALER_PASSWORD_NGUCC', 'forge'),
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ],

        'pgsql2' => [
            'driver'   => env('DB_DIALER_CONNECTION_REPORTS', 'pgsql'),
            'host'     => env('DB_DIALER_HOST_REPORTS', 'localhost'),
            'database' => env('DB_DIALER_DATABASE_REPORTS', 'homestead'),
            'username' => env('DB_DIALER_USERNAME_REPORTS', 'forge'),
            'password' => env('DB_DIALER_PASSWORD_REPORTS', 'forge'),
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ],


        'sqlsrv' => [
            'driver'   => 'sqlsrv',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset'  => 'utf8',
            'prefix'   => '',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'cluster' => false,

        'default' => [
            'host' => env('REDIS_HOST', 'localhost'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];
