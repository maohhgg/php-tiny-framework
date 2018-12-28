<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-27
 * Time: 上午6:30
 */

namespace Framework\Router;

class General implements RouteStrategyInterface
{

    /**
     * 路由方法
     * @param Router $entrance
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function route(Router $entrance)
    {
        $request = request();

        $moduleName = $request->check('m') ?
            $request->request('m') :
            ($request->check('module') ? $request->request('module') : null);

        $controllerName =  $request->check('c') ?
            $request->request('c') :
            ($request->check('controller') ? $request->request('controller') : null);

        $actionName =  $request->request('a') ?
            $request->request('a') :
            ($request->check('action') ? $request->request('action') : null);

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