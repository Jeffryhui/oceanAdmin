<?php

namespace App\Service\Data;

use App\Model\Data\DictType;
use App\Service\BaseService;
use App\Service\IService;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Model;

class DictTypeService extends BaseService implements IService
{
    public function __construct(DictType $model)
    {
        $this->model = $model;
    }

    public function preQuery(Builder|Model $builder, array $params = []): Builder
    {
        if(isset($params['name']) && !empty($params['name'])){
            $builder->where('name', 'like', '%' . $params['name'] . '%');
        }
        if(isset($params['code']) && !empty($params['code'])){
            $builder->where('code', 'like', '%' . $params['code'] . '%');
        }
        if(isset($params['status']) && !empty($params['status'])){ 
            $builder->where('status', $params['status']);
         }
        return $builder;
    }
}
