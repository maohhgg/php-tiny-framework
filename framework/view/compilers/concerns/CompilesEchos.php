<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-8-1
 * Time: 上午4:49
 */

namespace Framework\View\Compilers\Concerns;


trait CompilesEchos
{
    protected function compileEchos($content)
    {
        foreach ($this->echoCompilers as $type) {
            $content = $this->{"compile{$type}"}($content);
        }
        return $content;
    }

    protected function compileEscapedEchos($content)
    {
        return preg_replace('/{{(.*)}}/', '<?php echo htmlentities(isset($1) ? $1 : null) ?>', $content);
    }

    protected function compileRawEchos($content)
    {
        return preg_replace('/{!!(.*)!!}/', '<?php echo isset($1) ? $1 : null ?>', $content);
    }

    protected function compileSampleEchos($content)
    {
        return preg_replace('/{!(.*)!}/', '<?php echo $1 ?>', $content);
    }
}