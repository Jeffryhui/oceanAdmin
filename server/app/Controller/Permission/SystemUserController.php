<?php

namespace App\Controller\Permission;

use App\Annotation\Permission;
use App\Controller\Controller as BaseController;
use App\EsModel\LoginLog;
use App\EsModel\OperateLog;
use App\Model\Permission\SystemUser;
use App\Service\Permission\SystemUserService;
use App\Utils\Response;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Request;
use Qbhy\HyperfAuth\Annotation\Auth;

#[Controller(prefix: '/api/admin/system-user')]
class SystemUserController extends BaseController
{


    public function __construct(private SystemUserService $systemUserService)
    {
    }

    #[GetMapping(path: 'login-log')]
    #[Permission('admin:system-user:login-log', '获取用户登录日志', true, true)]
    #[Auth('admin')]
    public function loginLog()
    {
        /**
         * @var SystemUser $user
         */
        $user = auth('admin')->user();
        $loginLogs = LoginLog::query()->where('username', $user->username)->orderBy('create_time', true)->page(5);
        return Response::success([
            'data' => $loginLogs->items(),
            'current_page' => $loginLogs->currentPage(),
            'per_page' => $loginLogs->perPage(),
            'total' => $loginLogs->total(),
            'has_more' => $loginLogs->hasMorePages(),
        ], '获取用户登录日志成功');
    }

    #[GetMapping(path: 'operate-log')]
    #[Permission('admin:system-user:operate-log', '获取用户操作日志', true, true)]
    #[Auth('admin')]
    public function operateLog()
    {
        /**
         * @var SystemUser $user
         */
        $user = auth('admin')->user();
        $operateLogs = OperateLog::query()->where('username', $user->username)->orderBy('operate_time', true)->page(5);
        return Response::success([
            'data' => $operateLogs->items(),
            'current_page' => $operateLogs->currentPage(),
            'per_page' => $operateLogs->perPage(),
            'total' => $operateLogs->total(),
            'has_more' => $operateLogs->hasMorePages(),
        ], '获取用户操作日志成功');
    }

    #[PostMapping(path: 'update-info')]
    #[Permission('admin:system-user:update-info', '更新用户信息', true, true)]
    #[Auth('admin')]
    public function updateInfo(Request $request)
    {
        $data = $request->only(['avatar', 'nickname', 'email', 'phone', 'signed', 'backend_setting']);
        $this->systemUserService->updateUserInfo($data);
        return Response::success([], '更新用户信息成功');
    }
}
