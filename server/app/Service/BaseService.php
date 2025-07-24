<?php

namespace App\Service;

use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Model;
use Hyperf\Contract\LengthAwarePaginatorInterface;

class BaseService
{
    protected Model $model;

    public function preQuery(Builder|Model $builder, array $params = []): Builder{
       return $builder;
    }

    public function getList(array $params = [], int $page = 1, int $pageSize = 20, $orderField = 'id', string $orderType = 'desc'): LengthAwarePaginatorInterface{
        $query = $this->model->query();
        $query = $this->preQuery($query,$params);
        return $query->orderBy($orderField, $orderType)->paginate($pageSize, ['*'], 'page', $page);
    }

    public function create(array $data): mixed{
        return $this->model->create($data);
    }

    public function update(int $id,array $data): int{
        return $this->model->where('id', $id)->update($data);
    }

    public function delete(int $id): int{
        return $this->model->where('id', $id)->delete();
    }

    public function batchDelete(array $ids): int{
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function changeStatus(int $id, int $status): int{
        return $this->model->where('id', $id)->update(['status' => $status]);
    }
}
