<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-26
 * Time: 上午7:34
 */

namespace Framework;

use Closure;
use Framework\Container;

class App
{
    /**
     * 框架实例
     * @var App
     */
    public static $app;

    /**
     * 服务容器
     * @var Container
     */
    public static $container;


    /**
     * @var string
     */
    public $rootPath;

    /**
     * @var string
     */
    public $runtimePath;

    /**
     * @var string
     */
    public $resourcePath;

    /**
     * 框架加载流程一系列处理类集合
     * @var array
     */
    private $handlesList = [];

    /**
     * 运行模式
     * 目前支持fpm/cli/
     * 默认为fpm
     * @var string
     */
    public $runningMode = '';

    /**
     * 是否输出响应结果
     * 默认输出
     * cli模式　访问路径为空　不输出
     * @var boolean
     */
    public $notOutput = false;

    /**
     * 响应对象
     * @var
     */
    public $responseData;

    /**
     * App constructor.
     * @param string $rootPath 框架实例根目录
     * @param Closure $loader 注入自加载实例
     */
    public function __construct(string $rootPath, Closure $loader)
    {
        // 运行模式
        $this->runningMode = getenv('EASY_MODE');
        // 根目录
        $this->rootPath = $rootPath;
        $this->runtimePath = $rootPath.'/runtime/';
        $this->resourcePath = $rootPath.'/resources/';


        $loader();
        Load::register($this);

        self::$app = $this;
        self::$container = new Container();
    }

    /**
     * 注册框架运行过程中一系列处理类
     * @param Closure $handle
     */
    public function load(Closure $handle)
    {
        $this->handlesList[] = $handle;
    }


    /**
     * 运行应用
     * fpm mode
     * @param Closure $request
     */
    public function run(Closure $request)
    {
        self::$container->set('request', $request);
        foreach ($this->handlesList as $handle) {
            $handle()->register($this);
        }
    }

    /**
     * 生命周期结束
     * 响应请求
     * @param  Closure $closure 响应类
     * @throws Exceptions\CoreHttpException
     */
    public function response(Closure $closure)
    {
        if ($this->notOutput === true) {
            return;
        }
        if ($this->runningMode === 'cli') {
            $closure()->cliModeSuccess($this->responseData);
            return;
        }
        $type = config('default_response_type');
        if ($type == 'json') {
            $closure()->responseJson($this->responseData);
            return ;
        }
        $closure()->response($this->responseData);
    }

}