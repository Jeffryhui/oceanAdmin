<?php

namespace App\Service\Tool;

use App\Model\Tool\CrontabLog;
use App\Service\BaseService;
use App\Service\IService;

class CrontabLogService extends BaseService implements IService
{
    public function __construct(CrontabLog $model)
    {
        $this->model = $model;
    }

    public function preQuery(\Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model $builder, array $params = []): \Hyperf\Database\Model\Builder{
        return $builder;
    }
}
