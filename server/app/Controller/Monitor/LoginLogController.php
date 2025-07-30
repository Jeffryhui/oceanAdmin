<?php

namespace App\Controller\Monitor;

use App\Annotation\Permission;
use App\Controller\CrudController;
use App\Service\Monitor\LoginLogService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Qbhy\HyperfAuth\Annotation\Auth;


#[Controller('/api/admin/login-log')]
class LoginLogController extends CrudController
{
    public function __construct(LoginLogService $loginLogService)
    {
        $this->service = $loginLogService;
    }

    #[GetMapping(path: 'list')]
    #[Permission('monitor:login-log:list', '获取登录日志', false, true)]
    #[Auth('admin')]
    public function index()
    {
        return parent::index();
    }

    #[PostMapping(path: 'batch-delete')]
    #[Permission('monitor:login-log:batch-delete', '批量删除登录日志', false, true)]
    #[Auth('admin')]
    public function batchDelete()
    {
        return parent::batchDelete();
    }
}
