<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-27
 * Time: 上午4:46
 */

use Framework\App;

/**
 * 获取环境参数
 * @param string $paramName 参数名
 * @return mixed
 * @throws \Framework\Exceptions\CoreHttpException
 */
function env(string $paramName = '')
{
    return App::$container->getSingle('envt')->env($paramName);
}