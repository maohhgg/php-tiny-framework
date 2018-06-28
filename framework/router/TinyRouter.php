<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-26
 * Time: 上午9:15
 */

namespace Framework\Router;


use Framework\App;
use Framework\Exceptions\CoreHttpException;
use Framework\handles\ConfigHandle;
use framework\Request;

/**
 * Class TinyRouter
 * @package Framework\Router
 */
class TinyRouter extends Router
{
    /**
     * 框架实例
     * @var App
     */
    public $app;

    /**
     * 配置实例
     * @var ConfigHandle
     */
    private $config;

    /**
     * 请求对象实例
     * @var Request
     */
    private $request;

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
     * 类文件路径.
     * @var string
     */
    private $classPath = '';

    /**
     * 类文件执行类型.
     * @var string
     */
    private $executeType = 'controller';

    /**
     * 请求uri.
     * @var string
     */
    private $requestUri = '';
    /**
     * 路由策略.
     * @var string
     */
    private $routeStrategy = '';

    /**
     * 路由策略映射
     * @var array
     */
    private $routeStrategyMap = [
        'general' => 'Framework\Router\General',
        'pathinfo' => 'Framework\Router\Pathinfo',
    ];


    /**
     * 注册路由处理机制.
     * @param App $app 框架实例
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function init(App $app)
    {
        $app::$container->set('router', $this);

        // request uri
        $this->request = $app::$container->get('request');
        $this->requestUri = $this->request->server('REQUEST_URI');

        // App
        $this->app = $app;

        // get config
        $this->config = $app::$container->getSingle('config');
        // 设置默认模块 set default module
        $this->moduleName = $this->config->config['route']['default_module'];
        // 设置默认控制器 set default controller
        $this->controllerName = $this->config->config['route']['default_controller'];
        // 设置默认操作 set default action
        $this->actionName = $this->config->config['route']['default_action'];

        // 路由决策 judge the router strategy
        $this->strategyJudge();

        // 路由策略 the current router strategy
        (new $this->routeStrategyMap[$this->routeStrategy])->route($this);

        // 获取控制器类
        $this->classPath = ucfirst($this->config->config['application_folder_name']) .
            "\\{$this->moduleName}\\Controllers\\" .
            ucfirst($this->controllerName);

        // 启动路由
        $this->start();
    }

    /**
     * 路由策略决策
     *
     * @param void
     */
    private function strategyJudge()
    {
        // 路由策略
        if (!empty($this->routeStrategy)) return;

        // 任务路由
        // TODO: 任务模块

        // 普通路由
        if (strpos($this->requestUri, 'index.php') or $this->app->runningMode === 'cli') {
            $this->routeStrategy = 'general';
            return;
        }
        $this->routeStrategy = 'pathinfo';
    }

    /**
     * 路由机制
     * @throws CoreHttpException
     */
    private function start()
    {
        // 判断模块存在不?
        if (!in_array(strtolower($this->moduleName), $this->config->config['module'])) {
            throw new CoreHttpException(404, 'Module:' . $this->moduleName);
        }

        // 判断控制器存不存在
        if (!class_exists($this->classPath)) {
            throw new CoreHttpException(404, "{$this->executeType}:{$this->classPath}");
        }

        // 实例化当前控制器
        $controller = new $this->classPath();
        if (!method_exists($controller, $this->actionName)) {
            throw new CoreHttpException(404, 'Action:' . $this->actionName);
        }
        // 调用操作
        $actionName = $this->actionName;

        // 获取返回值
        $this->app->responseData = $controller->$actionName();
    }
}