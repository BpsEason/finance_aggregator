<?php

use Illuminate\Support\Str;

return [

    #
    #--------------------------------------------------------------------------
    # Default Database Connection Name
    #--------------------------------------------------------------------------
    #
    # Here you may specify which of the database connections below you wish
    # to use as your default connection for all database work. Of course
    # you may use many connections at once using the Database library.
    #

    'default' => env('DB_CONNECTION', 'mysql'),

    #
    #--------------------------------------------------------------------------
    # Database Connections
    #--------------------------------------------------------------------------
    #
    # Here are each of the database connections setup for your application.
    # Of course, a default configuration has been defined for each of the
    # supported database platforms that are currently supported by Laravel.
    #

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            # 'encrypt' => env('DB_ENCRYPT', 'no'), # For SQL Server 2017 (14.x) and older
            # 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'), # For SQL Server 2017 (14.x) and older
        ],

    ],

    #
    #--------------------------------------------------------------------------
    # Migration Repository Table
    #--------------------------------------------------------------------------
    #
    # This table keeps track of all the migrations that have already run for
    # your application. Using this information, we can determine which of
    # the migrations on disk haven't actually been run in the database.
    #

    'migrations' => [
        'table' => 'migrations',
        'update_existing_columns' => true,
    ],

    #
    #--------------------------------------------------------------------------
    # Redis Databases
    #--------------------------------------------------------------------------
    #
    # Redis is an open source, fast, advanced key-value store that also
    # provides support for a wide array of data structures. By default,
    # Redis configures a few databases. You may modify the parameters as
    # needed to tailor it to your application's needs.
    #

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
