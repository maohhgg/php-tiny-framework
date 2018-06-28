<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-26
 * Time: 上午7:30
 */
use Framework\Exceptions\CoreHttpException;
use Framework\handles\ConfigHandle;
use framework\handles\EnvHandle;
use Framework\Handles\RouterHandle;
use framework\Request;
use Framework\Response;


require(__DIR__ . '/App.php');


try {

    $app = new Framework\App(realpath(__DIR__ . '/..'), function (){
        return require(__DIR__.'/Load.php');
    });

    /**
     * 挂载各种 Handle
     */
    $app->load(function () {
        // 加载预环境参数机制 Loading env handle
        return new EnvHandle();
    });

    $app->load(function () {
        // 加载预定义配置机制 Loading config handle
        return new ConfigHandle();
    });
    $app->load(function (){
        // 加载路由机制 Loading route handle
        return new RouterHandle();
    });


    /**
     * 启动应用
     */
    $app->run(function () use ($app) {
        return new Request($app);
    });

    /**
     * 响应结果
     * 应用生命周期结束
     */
    $app->response(function () {
        return new Response();
    });


} catch (CoreHttpException $e) {

    /**
     * 捕获异常
     * Catch exception
     */
    $e->reponse();
}