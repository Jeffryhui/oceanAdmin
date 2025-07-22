<?php

namespace App\Controller\Auth;

use App\Annotation\Permission;
use App\Utils\Response;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Psr\Http\Message\ResponseInterface;

use function Ella123\HyperfCaptcha\captcha_create;

#[Controller(prefix: '/api/admin/auth')]
class CaptchaController extends Controller
{
    #[GetMapping(path: 'captcha')]
    #[Permission( 'admin:captcha:get',  '后台获取验证码',true)]
    public function getCaptcha():ResponseInterface
    {
        $data = captcha_create();
        return Response::success($data);
    }
}
