<?php

namespace App\Controller\Auth;

use App\Annotation\Permission;
use App\Controller\Controller as BaseController;
use App\Utils\Response;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Psr\Http\Message\ResponseInterface;

use function Ella123\HyperfCaptcha\captcha_create;

#[Controller(prefix: '/api/admin/auth')]
class CaptchaController extends BaseController
{
    #[GetMapping(path: 'captcha')]
    #[Permission( 'auth:captcha',  '后台获取验证码',true,false)]
    public function getCaptcha():ResponseInterface
    {
        $data = captcha_create();
        return Response::success($data);
    }
}
