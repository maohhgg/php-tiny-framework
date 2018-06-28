<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-27
 * Time: 上午4:30
 */

namespace Framework\handles;


use Framework\App;

class ConfigHandle implements Handle
{
    /**
     * 框架实例
     * @var App
     */
    private $app;

    /**
     * 配置
     * @var array
     */
    public $config = [];

    /**
     * 魔法函数__get.
     * @param string $name 属性名称
     * @return mixed
     */
    public function __get(string $name = '')
    {
        return $this->$name;
    }

    /**
     * 魔法函数__set.
     * @param string $name 属性名称
     * @param mixed $value 属性值
     */
    public function __set(string $name = '', $value = '')
    {
        $this->$name = $value;
    }

    /**
     * 注册处理机制
     * @param App $app
     * @return void
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function register(App $app)
    {
        // load helper
        require($app->rootPath.'/framework/helper.php');


        $this->app = $app;
        $app::$container->setSingle('config', $this);
        $this->loadConfig($app);

        // 设置时区
        // define time zone
        date_default_timezone_set($this->config['default_timezone']);
    }


    /**
     * 加载配置文件
     * @param  App $app 框架实例
     * @return void
     * @throws \Framework\Exceptions\CoreHttpException
     */
    private function loadConfig($app)
    {
        $defaultCommon = require($app->rootPath . '/config/common.php');
        $defaultDatabase = require($app->rootPath . '/config/database.php');

        $this->config = array_merge($defaultCommon, $defaultDatabase);

        /* 加载模块自定义配置 */
        $module = $app::$container->getSingle('config')->config['module'];
        foreach ($module as $v) {
            $file = $app->rootPath . "/config/{$v}/config.php";
            if (file_exists($file)) {
                $this->config = array_merge($this->config, require($file));
            }
        }
    }
}