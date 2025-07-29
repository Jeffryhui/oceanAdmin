<?php

namespace App\Controller\Auth;

use App\Annotation\Permission;
use App\Controller\Controller as BaseController;
use App\Request\Auth\LoginRequest;
use App\Service\Auth\AuthService;
use App\Utils\Response;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Qbhy\HyperfAuth\Annotation\Auth;

#[Controller(prefix: '/api/admin/auth')]
class AuthController extends BaseController
{
    public function __construct(private AuthService $authService){}

    /**
     * 用户登录接口
     *
     * @param LoginRequest $request
     * @return ResponseInterface
     */
    #[PostMapping(path: 'login')]
    #[Permission( 'auth:login',  '后台登录',true,false)]
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $result = $this->authService->login($data);
        return Response::success(['access_token' => $result]);
    }

    /**
     * 获取后台用户信息
     *
     * @return ResponseInterface
     */
    #[GetMapping(path: 'user-info')]
    #[Permission( 'auth:user-info',  '获取后台用户信息',true,true)]
    #[Auth('admin')]
    public function userInfo()
    {
        $result = $this->authService->userInfo();
        return Response::success($result);
    }

    /**
     * 退出登录
     *
     * @return ResponseInterface
     */
    #[PostMapping(path: 'logout')]
    #[Permission( 'auth:logout',  '退出登录',true,true)]
    #[Auth('admin')]
    public function logout()
    {
        $this->authService->logout();
        return Response::success(null, '退出登录成功');
    }
}
