<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-8-1
 * Time: 上午4:52
 */

namespace Framework\View\Compilers;


interface CompilerInterface
{
    /**
     * Get the path to the compiled version of a view.
     *
     * @param  string  $path
     * @return string
     */
    public function getCompiledPath($path);

    /**
     * Determine if the given view is expired.
     *
     * @param  string  $path
     * @return bool
     */
    public function isExpired($path);

    /**
     * Compile the view at the given path.
     *
     * @param  string  $path
     * @return void
     */
    public function compile($path);
}