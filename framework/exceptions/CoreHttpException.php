<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-26
 * Time: 上午8:16
 */

namespace Framework\Exceptions;

use Exception;

class CoreHttpException extends Exception
{

    /**
     * 响应异常code
     * @var array
     */
    private $httpCode = [
        // 缺少参数或者必传参数为空
        400 => 'Bad Request',
        // 没有访问权限
        403 => 'Forbidden',
        // 访问的资源不存在
        404 => 'Not Found',
        // 代码错误
        500 => 'Internet Server Error',
        // Remote Service error
        503 => 'Service Unavailable'
    ];


    /**
     * CoreHttpException constructor.
     * @param int $code
     * @param string $extra
     */
    public function __construct($code = 200, $extra = '')
    {
        $this->code = $code;
        if (!$extra) {
            $this->message = $this->httpCode[$code];
            return;
        }
        $this->message = $extra . ' ' . $this->httpCode[$code];
    }

    /**
     * rest 风格http响应
     */
    public function reponse()
    {
        $data = [
            '__coreError' => [
                'code' => $this->getCode(),
                'message' => $this->getMessage(),
                'infomations' => [
                    'file' => $this->getFile(),
                    'line' => $this->getLine(),
                    'trace' => $this->getTrace(),
                ]
            ]
        ];

        // reponse
        header('Content-Type:Application/json; Charset=utf-8');
        die(json_encode($data));
    }
}