<?php

namespace App\Service\Data;

use App\Model\Data\DictData;
use App\Model\Data\DictType;
use App\Service\BaseService;
use App\Service\IService;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Model;

class DictDataService extends BaseService implements IService
{
    public function __construct(DictData $model)
    {
        $this->model = $model;
    }

    public function preQuery(Builder|Model $builder, array $params = []): Builder
    {
        if(isset($params['type_id']) && !empty($params['type_id'])){
            $builder->where('type_id', $params['type_id']);
        }
        if(isset($params['code']) && !empty($params['code'])){
            $builder->where('code', $params['code']);
        }
        if(isset($params['label']) && !empty($params['label'])){
            $builder->where('label', $params['label']);
        }
        if(isset($params['value']) && !empty($params['value'])){
            $builder->where('value', $params['value']);
        }
        if(isset($params['status']) && !empty($params['status'])){
            $builder->where('status', $params['status']);
        }
        return $builder;
    }

    public function all(): array
    {
        // 1. 先查询启用状态的字典类型
        $enabledTypes = DictType::where('status', 1)
            ->orderBy('id', 'asc')
            ->get();

        if ($enabledTypes->isEmpty()) {
            return [];
        }

        // 2. 获取启用类型的code列表
        $enabledCodes = $enabledTypes->pluck('code')->toArray();

        // 3. 查询这些类型下启用状态的字典数据
        $dictData = $this->model->where('status', 1)
            ->whereIn('code', $enabledCodes)
            ->orderBy('sort', 'desc')
            ->get();

        // 4. 按 code 分组，保持字典类型的排序
        $groupedData = [];
        
        // 按字典类型的顺序来组织数据
        foreach ($enabledTypes as $type) {
            $code = $type->code;
            $groupedData[$code] = [];
            
            // 找到该类型下的所有字典数据
            $typeData = $dictData->where('code', $code);
            
            foreach ($typeData as $item) {
                $groupedData[$code][] = [
                    'id' => $item->id,
                    'type_id' => $item->type_id,
                    'label' => $item->label,
                    'value' => $item->value,
                    'color' => $item->color,
                    'code' => $item->code,
                    'sort' => $item->sort,
                    'status' => $item->status,
                    'remark' => $item->remark,
                ];
            }
            
            // 如果该类型下没有数据，移除空数组
            if (empty($groupedData[$code])) {
                unset($groupedData[$code]);
            }
        }

        return $groupedData;
    }
}
