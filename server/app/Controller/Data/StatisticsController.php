<?php

namespace App\Controller\Data;

use App\Annotation\Permission;
use App\Controller\Controller as BaseController;
use App\EsModel\LoginLog;
use App\EsModel\OperateLog;
use App\Model\Permission\SystemUser;
use App\Utils\Response;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Qbhy\HyperfAuth\Annotation\Auth;

#[Controller('/api/admin/statistics')]
class StatisticsController extends BaseController
{
    #[GetMapping(path: 'dashboard')]
    #[Permission('admin:statistics:dashboard', '获取Dashboard统计数据', true,true)]
    #[Auth('admin')]
    public function dashboard()
    {
        /**
         * @var SystemUser $user
         */
        $user = auth('admin')->user();
        $userCount = SystemUser::query()->count();
        $loginCount = LoginLog::query()->where('username',$user->username)->where('status',LoginLog::STATUS_SUCCESS)->get(1000)->count();
        $operateCount = OperateLog::query()->where('username',$user->username)->get(1000)->count();
        $data = [
            'user' => $userCount, // 用户总数
            'attach' => 10, // 附件总数
            'login' => $loginCount, // 登录次数
            'operate' => $operateCount, // 操作次数
        ];
        return Response::success($data);
    }

    #[GetMapping(path: 'login-chart')]
    #[Permission('admin:statistics:login-chart', '获取登录统计图表数据', true)]
    #[Auth('admin')]
    public function loginChart()
    {
        $data = [
            'login_date' => ['2025-01-01', '2025-01-02', '2025-01-03', '2025-01-04', '2025-01-05', '2025-01-06', '2025-01-07'],
            'login_count' => [1000, 200, 300, 400, 500, 600, 700],
        ];
        return Response::success($data);
    }
}
