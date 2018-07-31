<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-8-1
 * Time: 上午4:49
 */

namespace Framework\View\Compilers\Concerns;


trait CompilesLoops
{
    protected function compileFor($expression)
    {
        return "<?php for{$expression}: ?>";
    }

    protected function compileEndfor()
    {
        return '<?php endfor; ?>';
    }

    protected function compileForeach($expression)
    {
        return "<?php foreach{$expression}: ?>";
    }

    protected function compileEndforeach()
    {
        return '<?php endforeach; ?>';
    }

    protected function compileWhile($expression)
    {
        return "<?php while{$expression}: ?>";
    }

    protected function compileEndwhile()
    {
        return '<?php endwhile; ?>';
    }

    protected function compileContinue()
    {
        return '<?php continue; ?>';
    }

    protected function compileBreak()
    {
        return '<?php break; ?>';
    }
}