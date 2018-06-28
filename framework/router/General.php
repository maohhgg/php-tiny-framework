<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-27
 * Time: 上午6:30
 */

namespace Framework\Router;


class General implements RouterInterface
{

    /**
     * 路由方法
     * @param Router $entrance
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function route(Router $entrance)
    {
        $app = $entrance->app;
        $request = $app::$container->get('request');
        $moduleName = $request->request('m');
        $controllerName =  $request->request('c');
        $actionName =  $request->request('a');

        if (! empty($moduleName)) {
            $entrance->moduleName = $moduleName;
        }
        if (! empty($controllerName)) {
            $entrance->controllerName = $controllerName;
        }
        if (! empty($actionName)) {
            $entrance->actionName = $actionName;
        }
        // CLI 模式不输出
        if (empty($actionName) && $entrance->app->runningMode === 'cli') {
            $entrance->app->notOutput = true;
        }
    }
}