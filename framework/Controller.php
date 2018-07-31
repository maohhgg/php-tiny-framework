<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-28
 * Time: 下午8:30
 */

namespace Framework;

use Framework\View\View;

class Controller
{

    /**
     * @var App
     */
    protected $app;

    /**
     * @var View
     */
    protected $view;


    /**
     * Controller constructor.
     * @throws Exceptions\CoreHttpException
     */
    public function __construct()
    {
        $this->app = app();
        $this->initialize();
        $this->view = app('view');
    }

    private function initialize()
    {
        $app = $this->app;
        $app::$container->set('view', function () use ($app) {
            return new View($app, config('template'));
        });
    }

    protected function fetch($template = '', $vars = [])
    {
        return $this->view->make($template, $vars);
    }

}