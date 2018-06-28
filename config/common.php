<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-27
 * Time: 上午4:40
 */

return [
    /* 应用目录名称 */
    'application_folder_name' => 'app',

    /* 默认模块 */
    'module' => [
        'demo'
    ],

    /* 路由默认配置 */
    'route'  => [
        // 默认模块
        'default_module'     => 'demo',
        // 默认控制器
        'default_controller' => 'index',
        // 默认操作
        'default_action'     => 'hello',
    ],

    /* 默认时区 */
    'default_timezone' => 'Asia/Shanghai',

];
