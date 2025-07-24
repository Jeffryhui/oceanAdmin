<?php

namespace App\Controller\Data;

use App\Annotation\Permission;
use App\Controller\Controller as BaseController;
use App\EsModel\LoginLog;
use App\EsModel\OperateLog;
use App\Model\Data\Attachment;
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
        $attachmentCount = Attachment::query()->count();
        $data = [
            'user' => $userCount, // 用户总数
            'attach' => $attachmentCount, // 附件总数
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
        /**
         * @var SystemUser $user
         */
        $user = auth('admin')->user();
        $loginDates = [];
        $loginCounts = [];
        
        // 生成最近7天的日期数组并统计每天登录次数
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $loginDates[] = $date;
            
            // 查询当天的成功登录次数
            $startTime = $date . ' 00:00:00';
            $endTime = $date . ' 23:59:59';
            
            $count = LoginLog::query()
                ->where('status', LoginLog::STATUS_SUCCESS)
                ->where('username',$user->username)
                ->whereBetween('create_time', [strtotime($startTime), strtotime($endTime)])
                ->get(10000) 
                ->count();
            
            $loginCounts[] = $count;
        }
        
        $data = [
            'login_date' => $loginDates,
            'login_count' => $loginCounts,
        ];
        
        return Response::success($data);
    }
}
