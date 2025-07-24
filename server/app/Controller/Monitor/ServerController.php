<?php

namespace App\Controller\Monitor;

use App\Annotation\Permission;
use App\Controller\Controller as BaseController;
use App\Utils\Response;
use App\Utils\ServerMonitor;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Qbhy\HyperfAuth\Annotation\Auth;

#[Controller('/api/admin/monitor')]
class ServerController extends BaseController
{
    public function __construct(private ServerMonitor $serverMonitor){}

    #[GetMapping(path: 'server')]
    #[Permission('admin:monitor:server', '获取服务器监控信息', false, true)]
    #[Auth('admin')]
    public function server()
    {
        return Response::success([
            'cpu' => $this->serverMonitor->getCpuInfo(),
            'memory' => $this->serverMonitor->getMemInfo(),
            'phpenv' => $this->serverMonitor->getPhpAndEnvInfo(),
        ]);
    }
}
