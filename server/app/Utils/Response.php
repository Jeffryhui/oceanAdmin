<?php

namespace App\Utils;

use Hyperf\Context\ApplicationContext;
use Hyperf\HttpServer\Contract\ResponseInterface;

class Response
{
    /**
     * response
     * @param mixed $data
     * @param mixed $message
     * @param mixed $code
     * @param mixed $status
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function response($data = null, $message = '', $code = 200, $status = 200)
    {
        $response = ApplicationContext::getContainer()->get(ResponseInterface::class);
        return $response->json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ])->withStatus($status);
    }

    /**
     * 成功响应
     * @param mixed $data
     * @param mixed $message
     * @param mixed $code
     * @param mixed $status
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function success($data = [], $message = '操作成功', $code = 200, $status = 200)
    {
        return self::response($data, $message, $code, $status);
    }

    /**
     * 错误响应
     * @param mixed $message
     * @param mixed $code
     * @param mixed $status
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function error($message = '操作失败', $code = 400, $status = 200)
    {
        return self::response(null, $message, $code, $status);
    }

    /**
     * 未授权响应
     * @param mixed $message
     * @param mixed $code
     * @param mixed $status
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function unauthorized($message = '登录验证失效,请重试!', $code = 401, $status = 401)
    {
        return self::response(null, $message, $code, $status);
    }

    /**
     * 参数验证失败
     * @param mixed $message
     * @param mixed $code
     * @param mixed $status
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function validateError($message = '参数验证失败', $code = 422, $status = 422)
    {
        return self::response(null, $message, $code, $status);
    }
}
