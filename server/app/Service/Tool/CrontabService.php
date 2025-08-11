<?php

namespace App\Service\Tool;

use App\Model\Tool\Crontab;
use App\Service\BaseService;
use App\Service\IService;

class CrontabService extends BaseService implements IService
{
    public function __construct(Crontab $model)
    {
        $this->model = $model;
    }

    public function preQuery(\Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model $builder, array $params = []): \Hyperf\Database\Model\Builder{
        if(isset($params['name']) && !empty($params['name'])) {
            $builder->where('name', 'like', '%' . $params['name'] . '%');
        }
        if(isset($params['type']) && !empty($params['type'])) {
            $builder->where('type', $params['type']);
        }
        if(isset($params['status']) && !empty($params['status'])) {
            $builder->where('status', $params['status']);
        }
        return $builder;
    }
}
