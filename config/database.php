<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-27
 * Time: 上午4:40
 */

return [
    /* 主库配置 */
    'database' => [
        'dbtype'   => env('database.dbtype'),
        'dbprefix' => env('database.dbprefix'),
        'dbname'   => env('database.dbname'),
        'dbhost'   => env('database.dbhost'),
        'username' => env('database.username'),
        'password' => env('database.password'),
    ]
];