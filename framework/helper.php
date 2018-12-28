<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-27
 * Time: 上午4:46
 */

use Framework\App;

if (!function_exists('env')) {
    /**
     * 获取环境参数
     * @param string $paramName 参数名
     * @param null $default
     * @return mixed
     * @throws \Framework\Exceptions\CoreHttpException
     */
    function env(string $paramName = '', $default = null)
    {
        $value = '';
        if (strpos($paramName, '.')) {
            $param = explode('.', $paramName);
            $value = App::$container->getSingle('envt')->env($param[0])[$param[1]];
        } else {
            $value = App::$container->getSingle('envt')->env($paramName);
        }

        if (empty($value)) {
            return $default;
        }
        return $value;
    }
}

if (!function_exists('app')) {

    /**
     * @param string $abstract
     * @return App|mixed
     * @throws \Framework\Exceptions\CoreHttpException
     */
    function app(string $abstract = '')
    {
        if ($abstract === '') {
            return App::$app;
        }
        return App::$container->get($abstract);
    }
}

if (!function_exists('request')) {

    /**
     * @return \framework\Request
     * @throws \Framework\Exceptions\CoreHttpException
     */
    function request()
    {
        return app('request');
    }
}

if (!function_exists('config')) {


    /**
     * @param $paramName
     * @param null $paramValue
     * @return array|string
     * @throws \Framework\Exceptions\CoreHttpException
     */
    function config($paramName = null, $paramValue = null)
    {
        $config = &App::$container->getSingle('config')->config;
        if (!$paramName) {
            return $config;
        }

        if ($paramValue) {
            $config = array_replace_recursive($config, array_cursive(explode('.', $paramName), $paramValue));
            return $paramValue;
        }

        $spec = $config;
        foreach (explode('.', $paramName) as $item) {
            $spec = $spec[$item];
        }
        return $spec;
    }
}

/**
 * 递归生成数组
 * e. 'a.b.c.d' = 100
 * array_cursive(exploe('.', 'e.b.c.d'), 100) // retrun ['a' => ['b' => ['c' =>['d' => 100 ]]]]
 * @param array $array
 * @param $value
 * @return array
 */
function array_cursive($array, $value)
{
    if (count($array) == 1) return [$array[0] => $value];
    return [array_shift($array) => array_cursive($array, $value)];
}

