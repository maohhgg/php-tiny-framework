<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-8-1
 * Time: 上午5:46
 */

namespace Framework\View\Engine;


use Framework\View\Compilers\CompilerInterface;

class CompilersEngine implements Engine
{

    /**
     * @var CompilerInterface
     */
    protected $compiler;

    /**
     * @var array
     */
    protected $lastCompiled = [];

    public function __construct(CompilerInterface $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * @param $path
     * @param array $data
     * @return string
     */
    public function get($path, array $data = [])
    {
        $this->lastCompiled[] = $path;

        if ($this->compiler->isExpired($path)) {
            $this->compiler->compile($path);
        }

        $compiled = $this->compiler->getCompiledPath($path);

        $results = $this->evaluatePath($compiled, $data);

        array_pop($this->lastCompiled);

        return $results;
    }

    protected function evaluatePath($__path, $__data)
    {
        ob_start();
        ob_implicit_flush(0);

        extract($__data, EXTR_SKIP);

        include $__path;

        return ltrim(ob_get_clean());
    }
}