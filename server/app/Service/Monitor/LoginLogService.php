<?php

namespace App\Service\Monitor;

use App\Model\Monitor\LoginLog;
use App\Service\BaseService;
use App\Service\IService;

class LoginLogService extends BaseService implements IService
{
    public function __construct(LoginLog $model)
    {
        $this->model = $model;
    }

    public function preQuery(\Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model $builder, array $params = []): \Hyperf\Database\Model\Builder{
        if(isset($params['username']) && !empty($params['username'])){
            $builder->where('username',$params['username']);
        }
        if(isset($params['ip']) && !empty($params['ip'])){
            $builder->where('ip',$params['ip']);
        }
        if(isset($params['status']) && !empty($params['status'])){
            $builder->where('status',$params['status']);
        }
        if(isset($params['login_time']) && !empty($params['login_time']) && is_array($params['login_time'])){
            $builder->whereBetween('login_time',[$params['login_time'][0],$params['login_time'][1]]);
        }
        return $builder;
    }
}
