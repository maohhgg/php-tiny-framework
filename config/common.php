<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-27
 * Time: 上午4:40
 */

return [
    /* 项目目录名称 */
    'app_path' => env('env.app_path','app'),

    /* 应用目录名称 */
    'application_folder_name' => 'app',

    /* 默认模块 */
    'module' => [
        'demo'
    ],

    /* 路由默认配置 */
    'route' => [
        // 默认模块
        'default_module' => 'demo',
        // 默认控制器
        'default_controller' => 'index',
        // 默认操作
        'default_action' => 'hello',
    ],

    /* 默认时区 */
    'default_timezone' => 'Asia/Shanghai',

    /* 默认输出格式 */
    'default_response_type' => 'html',

    /* 模板引擎配置 */
    'template' => [
        // 默认模板引擎
        'default_template' => 'tiny',
        // 模板引擎编译器模块
        'compilers' => [
            'Comments',
            'Statements',
            'Echos',
        ],
        // 原生PHP数据标签
        'rawTags' => ['{!!', '!!}'],
        // 默认标签
        'contentTags' => ['{{', '}}'],
        // 注释标签 不输出内容
        'escapedTags' => ['{{{', '}}}'],
        // echo 函数的简写方法
        'echoFormat' => 'e(%s)'
    ],

];
