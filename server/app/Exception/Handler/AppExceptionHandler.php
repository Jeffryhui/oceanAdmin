<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Exception\Handler;

use App\Exception\BusinessException;
use App\Utils\Response;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Exception\HttpException;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Qbhy\HyperfAuth\Exception\AuthException;
use Qbhy\SimpleJwt\Exceptions\JWTException;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    public function __construct(protected StdoutLoggerInterface $logger)
    {
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        if($throwable instanceof HttpException){
            $this->stopPropagation();
            return Response::response(null,'Route Not Found',404,404);
        }
        if($throwable instanceof BusinessException){
            $this->stopPropagation();
            return Response::response(null,$throwable->getMessage(),$throwable->getCode(),200);
        }
        if($throwable instanceof AuthException || $throwable instanceof JWTException){
            $this->stopPropagation();
            return Response::unauthorized('认证失败,请重新登录');
        }
        if($throwable instanceof ValidationException){
            $this->stopPropagation();
            return Response::validateError($throwable->validator->errors()->first());
        }
        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->logger->error($throwable->getTraceAsString());
        return Response::response(null,'Internal Server Error',500,500);
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
