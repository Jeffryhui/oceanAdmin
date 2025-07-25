<?php

namespace App\Controller\Monitor;

use App\Annotation\Permission;
use App\Controller\Controller as BaseController;
use App\EsModel\OperateLog;
use App\Utils\Response;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Request;
use Qbhy\HyperfAuth\Annotation\Auth;

#[Controller('/api/admin/monitor')]
class OperateController extends BaseController
{
    #[GetMapping(path: 'operate-log')]
    #[Permission('admin:monitor:operate-log', '获取操作日志', false, true)]
    #[Auth('admin')]
    public function operateLog(Request $request)
    {
        $page = $request->input('page', 1);
        $pageSize = $request->input('limit', 10);
        $params = $request->query();
        $query = OperateLog::query();
        if(isset($params['username']) && !empty($params['username'])){
            $query->where('username',$params['username']);
        }
        if(isset($params['service_name']) && !empty($params['service_name'])){
            // 使用keyword字段进行精确匹配，或者text字段进行模糊匹配
            if (strpos($params['service_name'], '%') !== false) {
                // 包含通配符，使用text字段模糊匹配
                $query->where('service_name', 'like', $params['service_name']);
            } else {
                // 精确匹配，使用keyword字段
                $query->where('service_name.keyword', '=', $params['service_name']);
            }
        }
        if(isset($params['ip']) && !empty($params['ip'])){
            $query->where('ip',$params['ip']);
        }
        if(isset($params['create_time']) && !empty($params['create_time']) && is_array($params['create_time'])){
            $query->whereBetween('operate_time',[strtotime($params['create_time'][0]),strtotime($params['create_time'][1])]);
        }
        $result = $query->orderBy('operate_time',true)->page($pageSize,$page);
        return Response::success([
            'current_page' => $result->currentPage(),
            'data' => $result->items(),
            'has_more' => $result->hasMorePages(),
            'per_page' => $result->perPage(),
            'total' => $result->total(),
        ]);
    }
}
