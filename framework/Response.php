<?php
/**
 * Created by PhpStorm.
 * User: pi
 * Date: 18-6-27
 * Time: 上午7:09
 */

namespace Framework;


class Response
{
    /**
     * @param mixed $response
     * @return string
     */
    public function response($response)
    {
        header('Content-Type:text/html; Charset=utf-8');
        die($response);
    }

    /**
     * @param mixed $response
     * @return string
     */
    public function responseJson($response)
    {
        header('Content-Type:Application/json; Charset=utf-8');
        die(json_encode($response,JSON_UNESCAPED_UNICODE));
    }


    /**
     * REST风格 成功响应
     * @param  mixed $response 响应内容
     * @return string
     */
    public function restSuccess($response)
    {
        header('Content-Type:Application/json; Charset=utf-8');
        die(json_encode([
            'code'    => 200,
            'message' => 'OK',
            'result'  => $response
        ],JSON_UNESCAPED_UNICODE));
    }

    /**
     * cli模式成功响应
     * @param  mixed $response 响应内容
     */
    public function cliModeSuccess($response)
    {
        var_dump([
            'code'    => 200,
            'message' => 'OK',
            'result'  => $response
        ]);
    }

    /**
     * REST风格 失败响应
     * 默认500 服务器解析错误
     * @param int $code
     * @param string $message
     * @param  mixed $response 响应内容
     * @return string
     */
    public function restFail(int $code = 500, string $message = 'Internet Server Error', $response)
    {
        header('Content-Type:Application/json; Charset=utf-8');
        die(json_encode([
            'code'    => $code,
            'message' => $message,
            'result'  => $response
        ],JSON_UNESCAPED_UNICODE));
    }
}