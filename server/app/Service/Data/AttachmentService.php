<?php

namespace App\Service\Data;

use App\Model\Data\Attachment;
use App\Service\BaseService;
use App\Service\IService;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Model;

class AttachmentService extends BaseService implements IService
{

    public function __construct(Attachment $model)
    {
        $this->model = $model;
    }

    public function preQuery(Builder|Model $builder, array $params = []): Builder
    {
        if (isset($params['storage_mode']) && !empty($params['storage_mode'])) {
            $builder->where('storage_mode', $params['storage_mode']);
        }
        if (isset($params['origin_name']) && !empty($params['origin_name'])) {
            $builder->where('origin_name', 'like', '%' . $params['origin_name'] . '%');
        }
        if(isset($params['mime_type']) && !empty($params['mime_type'])){
            $builder->where('mime_type', 'like', $params['mime_type'].'%');
        }
        if(isset($params['created_at']) && !empty($params['created_at']) && is_array($params['created_at'])){
            $builder->whereBetween('created_at', $params['created_at']);
        }
        return $builder;
    }
}
