<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-8-1
 * Time: 上午4:43
 */

namespace Framework\View\Compilers\Concerns;


trait CompilesConditionals
{
    protected function compileIf($expression)
    {
        return "<?php if{$expression}: ?>";
    }

    protected function compileElseif($expression)
    {
        return "<?php elseif{$expression}: ?>";
    }

    protected function compileElse($expression)
    {
        return "<?php else{$expression}: ?>";
    }

    protected function compileEndif($expression)
    {
        return '<?php endif; ?>';
    }
}