<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-8-1
 * Time: 上午5:46
 */

namespace Framework\View\Engine;


interface Engine
{
    /**
     * Get the evaluated contents of the view.
     *
     * @param  string  $path
     * @param  array   $data
     * @return string
     */
    public function get($path, array $data = []);
}