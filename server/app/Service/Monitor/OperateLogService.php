<?php

namespace App\Service\Monitor;

use App\Model\Monitor\OperateLog;
use App\Service\BaseService;
use App\Service\IService;

class OperateLogService extends BaseService implements IService
{
    public function __construct(OperateLog $model)
    {
        $this->model = $model;
    }

    public function preQuery(\Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model $builder, array $params = []): \Hyperf\Database\Model\Builder{
        if(isset($params['username']) && !empty($params['username'])){
            $builder->where('username',$params['username']);
        }
        if(isset($params['service_name']) && !empty($params['service_name'])){
            $builder->where('service_name',$params['service_name']);
        }
        if(isset($params['ip']) && !empty($params['ip'])){
            $builder->where('ip',$params['ip']);
        }
        if(isset($params['create_time']) && !empty($params['create_time']) && is_array($params['create_time'])){
            $builder->whereBetween('created_at',[$params['create_time'][0],$params['create_time'][1]]);
        }
        return $builder;
    }
}
