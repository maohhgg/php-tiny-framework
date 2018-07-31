<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-28
 * Time: 下午8:31
 */

namespace Framework\View;


use Framework\App;
use Framework\View\Compilers\TinyCompiler;
use Framework\View\Engine\CompilersEngine;

class View
{
    private $cachePath;
    private $suffix;
    private $compiler;
    private $engine;

    /**
     * View constructor.
     * @param App $app
     * @param array $config
     * @throws \Framework\Exceptions\CoreHttpException
     */
    public function __construct(App $app, array $config)
    {
        $this->app = $app;
        $this->cachePath = $app->resourcePath.$config['view_path'] .'/';
        $this->suffix = '.'.$config['view_suffix'];
        $this->compiler = new TinyCompiler($app->runtimePath);
        $this->engine = new CompilersEngine($this->compiler);
    }

    public function make($view, $data)
    {
        $path = $this->cachePath.$view.$this->suffix;
        return $this->engine->get($path, $data);
    }

}