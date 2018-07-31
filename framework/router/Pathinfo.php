<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-27
 * Time: 上午6:54
 */

namespace Framework\Router;


class Pathinfo implements routeStrategyInterface
{

    /**
     * 路由方法
     * @param Router $entrance
     */
    public function route(Router $entrance)
    {
        /* 匹配出uri */
        if (strpos($entrance->requestUri, '?')) {
            preg_match_all('/^\/(.*)\?/', $entrance->requestUri, $uri);
        } else {
            preg_match_all('/^\/(.*)/', $entrance->requestUri, $uri);
        }

        // 使用默认模块/控制器/操作逻辑
        if (!isset($uri[1][0]) || empty($uri[1][0])) {
            // CLI 模式不输出
            if ($entrance->app->runningMode === 'cli') {
                $entrance->app->notOutput = true;
            }
            return;
        }
        $uri = $uri[1][0];

        /* 自定义路由判断 */
        $uri = explode('/', $uri);
        switch (count($uri)) {
            case 3:
                $entrance->moduleName = $uri['0'];
                $entrance->controllerName = $uri['1'];
                $entrance->actionName = $uri['2'];
                break;

            case 2:
                // 使用默认模块
                $entrance->controllerName = $uri['0'];
                $entrance->actionName = $uri['1'];
                break;
            case 1:
                // 使用默认模块/控制器
                $entrance->actionName = $uri['0'];
                break;

            default:
                // 使用默认模块/控制器/操作逻辑
                break;
        }
    }
}