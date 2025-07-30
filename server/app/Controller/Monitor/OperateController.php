<?php

namespace App\Controller\Monitor;

use App\Annotation\Permission;
use App\Controller\CrudController;
use App\Service\Monitor\OperateLogService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Qbhy\HyperfAuth\Annotation\Auth;

#[Controller('/api/admin/operate-log')]
class OperateController extends CrudController
{
    public function __construct(OperateLogService $operateLogService)
    {
        $this->service = $operateLogService;
    }
    #[GetMapping(path: 'list')]
    #[Permission('monitor:operate-log:list', '获取操作日志', false, true)]
    #[Auth('admin')]
    public function index()
    {
        return parent::index();
    }

    #[PostMapping(path: 'batch-delete')]
    #[Permission('monitor:operate-log:batch-delete', '批量删除操作日志', false, true)]
    #[Auth('admin')]
    public function batchDelete()
    {
        return parent::batchDelete();
    }
}
