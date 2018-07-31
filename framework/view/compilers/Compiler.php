<?php

namespace Framework\View\Compilers;


use Framework\Exceptions\CoreHttpException;

abstract class Compiler
{

    protected $files;

    protected $cachePath;

    /**
     * Compiler constructor.
     * @param $cachePath
     * @throws CoreHttpException
     */
    function __construct($cachePath)
    {
        if (! $cachePath) {
            throw new CoreHttpException(400,'Please provide a valid cache path.');
        }
        $this->cachePath = $cachePath;
    }


}
