<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-29
 * Time: 上午5:55
 */

namespace Framework\View\Compilers;


use Framework\View\Compilers\Concerns\CompilesConditionals;
use Framework\View\Compilers\Concerns\CompilesEchos;
use Framework\View\Compilers\Concerns\CompilesLoops;

class TinyCompiler extends Compiler implements CompilerInterface
{

    use CompilesConditionals,
        CompilesEchos,
        CompilesLoops;


    protected $compilers = [
        'Statements',
        'Echos',
    ];

    protected $echoCompilers = [
        'RawEchos',
        'EscapedEchos',
        'SampleEchos'
    ];

    public function isExpired($path)
    {
        $compiled = $this->getCompiledPath($path);
        if (!file_exists($compiled)) {
            return true;
        }

        return filemtime($path) >= filemtime($compiled);
    }

    public function getCompiledPath($path)
    {
        return $this->cachePath.'/'.sha1($path).'.php';
    }

    public function compile($path)
    {
        if (!is_null($this->cachePath)) {
            $contents = $this->compileString(file_get_contents($path));
            file_put_contents($this->getCompiledPath($path), $contents);
        }
    }

    protected function compileString($value){
        $result = '';
        foreach (token_get_all($value) as $token) {
            $result .= is_array($token) ? $this->parseToken($token) : $token;
        }
        return $result;
    }

    protected  function parseToken($token){
        list($id, $content) = $token;
        if ($id == T_INLINE_HTML) {
            foreach ($this->compilers as $type) {
                $content = $this->{"compile{$type}"}($content);
            }
        }
        return $content;
    }

    protected function compileStatements($content)
    {
        return preg_replace_callback(
            '/\B@(@?\w+(?:::\w+)?)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x', function ($match) {
            return $this->compileStatement($match);
        }, $content);
    }

    protected function compileStatement($match)
    {
        if (strpos($match[1], '@') !== false) {
            $match[0] = isset($match[3]) ? $match[1].$match[3] : $match[1];
        } elseif (method_exists($this, $method = 'compile'.ucfirst($match[1]))) {
            $match[0] = $this->$method(isset($match[3]) ? $match[3] : null);
        }

        return isset($match[3]) ? $match[0] : $match[0].$match[2];
    }
}