<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-27
 * Time: 上午4:50
 */

namespace framework\handles;

use Framework\App;
use Framework\Exceptions\CoreHttpException;

class EnvHandle implements Handle
{
    /**
     * 请求参数
     * @var array
     */
    private $envParams = [];

    /**
     * 注册处理机制
     * @param App $app
     * @return void
     * @throws CoreHttpException
     */
    public function register(App $app)
    {
        // 加载环境参数
        $this->loadEnv($app);

        App::$container->setSingle('envt', $this);
    }

    /**
     * 加载环境参数
     * @param App $app
     * @throws CoreHttpException
     */
    public function loadEnv(App $app)
    {
        $env = parse_ini_file($app->rootPath . '/.env', true);
        if ($env === false) {
            throw new CoreHttpException(500, 'load env fail');
        }
        $this->envParams = array_merge($_ENV, $env);
    }

    /**
     * 获取env参数
     * @param string $value
     * @return mixed|string
     */
    public function env(string $value = '')
    {
        if (isset($this->envParams[$value])) {
            return $this->envParams[$value];
        }
        return '';
    }
}