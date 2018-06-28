<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-26
 * Time: 上午9:12
 */

namespace Framework\Handles;

use Framework\App;

interface Handle
{

    /**
     * 注册处理机制
     * @param App $app
     * @return mixed
     */
    public function register(App $app);
}