<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-27
 * Time: 上午6:27
 */

namespace Framework\router;


interface routeStrategyInterface
{

    /**
     * 路由方法
     * @param Router $entrance
     */
    public function route(Router $entrance);
}