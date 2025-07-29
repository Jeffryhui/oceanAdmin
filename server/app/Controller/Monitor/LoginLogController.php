<?php

namespace App\Controller\Monitor;

use App\Annotation\Permission;
use App\Controller\Controller as BaseController;
use App\EsModel\LoginLog;
use App\Utils\Response;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Request;
use Qbhy\HyperfAuth\Annotation\Auth;


#[Controller('/api/admin/monitor')]
class LoginLogController extends BaseController
{
    #[GetMapping(path: 'login-log')]
    #[Permission('monitor:login-log:list', '获取登录日志', false, true)]
    #[Auth('admin')]
    public function loginLog(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = $request->input('limit', 10);
        $params = $request->query();
        $query = LoginLog::query();
        if(isset($params['username']) && !empty($params['username'])){
            $query->where('username',$params['username']);
        }
        if(isset($params['ip']) && !empty($params['ip'])){
            $query->where('ip',$params['ip']);
        }
        if(isset($params['status']) && !empty($params['status'])){
            $query->where('status',$params['status']);
        }else{
            $query->where('status',1);
        }
        if(isset($params['login_time']) && !empty($params['login_time']) && is_array($params['login_time'])){
            $query->whereBetween('create_time',[strtotime($params['login_time'][0]),strtotime($params['login_time'][1])]);
        }
        $result = $query->orderBy('create_time',true)->page($pageSize,$page);
        return Response::success([
            'current_page' => $result->currentPage(),
            'data' => $result->items(),
            'has_more' => $result->hasMorePages(),
            'per_page' => $result->perPage(),
            'total' => $result->total(),
        ]);
    }
}
