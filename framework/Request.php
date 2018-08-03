<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-27
 * Time: 上午5:23
 */

namespace framework;

use Framework\Exceptions\CoreHttpException;

/**
 * Class Request
 * @package framework
 */
class Request
{
    /**
     * 服务器IP
     * @var string
     */
    private $serverIP = '';

    /**
     * 客户端IP
     * @var string
     */
    private $clientIP = '';

    /**
     * 请求server参数
     * @var array
     */
    private $serverParams = [];

    /**
     * 请求header参数
     * @var array
     */
    private $headerParams = [];

    /**
     * 请求GET参数
     * @var array
     */
    private $getParams = [];

    /**
     * 请求POST参数
     * @var array
     */
    private $postParams = [];

    /**
     * 请求的所有参数
     * @var array
     */
    private $requestParams = [];


    /**
     * 请求的PATH_INFO
     * @var string
     */
    private $pathInfo = '';

    /**
     * cookie
     * @var array
     */
    private $cookie = [];

    /**
     * file
     * @var array
     */
    private $file = [];

    /**
     * http方法名称
     * @var string
     */
    private $method = '';

    /**
     * @var int
     */
    private $beginTime = 0;

    /**
     * @var int
     */
    private $endTime = 0;


    /**
     * Request constructor.
     * 设置环境、请求参数
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->serverParams = $_SERVER;
        $this->method = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_REQUEST['REQUEST_METHOD']) : 'get';
        $this->serverIP = isset($_SERVER['REMOTE_ADDR']) ? strtolower($_SERVER['REMOTE_ADDR']) : '';
        $this->clientIP = isset($_SERVER['SERVER_ADDR']) ? strtolower($_SERVER['SERVER_ADDR']) : '';
        $this->beginTime = isset($_SERVER['REQUEST_TIME']) ? isset($_SERVER['REQUEST_TIME']) : microtime(true);
        $this->pathInfo = isset($_SERVER['PATH_INFO']) ? strtolower($_SERVER['PATH_INFO']) : '';

        if ($app->runningMode === 'cli') {
            // cli 模式
            $this->requestParams = isset($_REQUEST['argv']) ? $_REQUEST['argv'] : [];
            $this->getParams = isset($_REQUEST['argv']) ? $_REQUEST['argv'] : [];
            $this->postParams = isset($_REQUEST['argv']) ? $_REQUEST['argv'] : [];
            return;
        }

        $this->postParams = $_POST;
        $this->requestParams = $_REQUEST;
        $this->getParams = $_GET;

        $uri = explode('/', trim($this->pathInfo, '/'));
        if (!empty($this->pathInfo) and count($uri) > 3) {
            $i = 3;
            while ($i < count($uri)) {
                $temp = isset($uri[$i+1]) ? $uri[$i+1] : null;
                $this->getParams[$uri[$i]] = $temp;
                $this->requestParams[$uri[$i]] = $temp;
                $i += 2;
            }
        }


    }

    /**
     * 魔法函数__get.
     * @param string $name 属性名称
     * @return mixed
     */
    public function __get(string $name = '')
    {
        return $this->$name;
    }

    /**
     * 魔法函数__set.
     * @param string $name 属性名称
     * @param mixed $value 属性值
     */
    public function __set(string $name = '', $value = '')
    {
        $this->$name = $value;
    }


    /**
     * 获取GET参数
     * @param string $value 参数名
     * @param string $default 默认值
     * @param bool $checkEmpty 值为空时是否返回默认值，默认true
     * @return string
     */
    public function get(string $value = '', string $default = '', bool $checkEmpty = true): string
    {
        if (!isset($this->getParams[$value])) {
            return '';
        }
        if (empty($this->getParams[$value]) and $checkEmpty) {
            return $default;
        }
        return htmlspecialchars($this->getParams[$value]);
    }

    /**
     * 获取POST参数
     * @param string $value 参数名
     * @param string $default 默认值
     * @param bool $checkEmpty 值为空时是否返回默认值，默认true
     * @return string
     */
    public function post(string $value = '', string $default = '', bool $checkEmpty = true): string
    {
        if (!isset($this->postParams[$value])) {
            return '';
        }
        if (empty($this->getParams[$value]) and $checkEmpty) {
            return $default;
        }
        return htmlspecialchars($this->postParams[$value]);
    }

    /**
     * 获取REQUEST参数
     * @param string $value 参数名
     * @param string $default 默认值
     * @param bool $checkEmpty 值为空时是否返回默认值，默认true
     * @return string
     */
    public function request($value = '', $default = '', $checkEmpty = true): string
    {
        if (!isset($this->requestParams[$value])) {
            return '';
        }
        if (empty($this->getParams[$value]) and $checkEmpty) {
            return $default;
        }
        return htmlspecialchars($this->requestParams[$value]);
    }

    /**
     * 获取所有参数
     * @return array
     */
    public function all(): array
    {
        $res = array_merge($this->postParams, $this->getParams);
        foreach ($res as &$v) {
            $v = htmlspecialchars($v);
        }
        return $res;
    }

    /**
     * 获取SERVER参数
     * @param  string $value 参数名
     * @return mixed
     */
    public function server(string $value = '')
    {
        if (isset($this->serverParams[$value])) {
            return $this->serverParams[$value];
        }
        return '';
    }


    /**
     * 参数验证
     * 支持必传参数验证，参数长度验证，参数类型验证
     * @param string $paramName 参数名
     * @param string $rule 规则
     * @param int $length 长度
     * @return bool|void
     * @throws CoreHttpException
     */
    public function check(string $paramName = '', string $rule = '', int $length = 0)
    {
        if ($rule === 'require') {
            if (!empty($this->request($paramName))) return;
            throw new CoreHttpException(404, "param {$paramName}");
        }
        if ($rule === 'length') {
            if (strlen($this->request($paramName)) === $length) return;
            throw new CoreHttpException(404, "param {$paramName} length is not {$length}");
        }
        if ($rule === 'number') {
            if (is_numeric($this->request($paramName))) return;
            throw new CoreHttpException(404, "{$paramName} type is not number");
        }

        return !empty($this->request($paramName));
    }


}