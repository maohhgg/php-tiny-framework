<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-26
 * Time: 上午9:16
 */

namespace Framework\Router;

use Framework\App;
use Framework\handles\ConfigHandle;


abstract class Router
{
    /**
     * 框架实例
     * @var App
     */
    public $app;

    /**
     * 默认模块
     * @var string
     */
    public $moduleName;

    /**
     * 默认控制器
     * @var string
     */
    public $controllerName;

    /**
     * 默认操作
     * @var string
     */
    public $actionName;

    /**
     * @var string
     */
    public $requestUri;
}