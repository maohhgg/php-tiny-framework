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
        'type'   => env('database.type','mysql'),
        'prefix' => env('database.prefix'),
        'name'   => env('database.name'),
        'host'   => env('database.host'),
        'username' => env('database.username'),
        'password' => env('database.password'),
    ]
];