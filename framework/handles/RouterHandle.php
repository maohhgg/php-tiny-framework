<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-26
 * Time: 上午9:11
 */

namespace Framework\Handles;

use Framework\App;
use Framework\Router\TinyRouter;

class RouterHandle implements Handle
{

    /**
     * 注册处理机制
     * @param App $app
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function register(App $app)
    {
        (new TinyRouter())->init($app);
    }
}