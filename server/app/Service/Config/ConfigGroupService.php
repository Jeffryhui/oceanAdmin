<?php

namespace App\Service\Config;

use App\Exception\BusinessException;
use App\Model\Config\Config;
use App\Model\Config\ConfigGroup;
use App\Service\BaseService;
use App\Service\IService;

class ConfigGroupService extends BaseService implements IService
{
    public function __construct(ConfigGroup $model)
    {
        $this->model = $model;
    }
    
    public function batchDelete(array $ids): int
    {
        // 先删除配置
        $res = Config::whereIn('group_id', $ids)->delete();
        if(!$res){
            throw new BusinessException('删除配置失败');
        }
        return $this->model->whereIn('id', $ids)->delete();
    }
}
