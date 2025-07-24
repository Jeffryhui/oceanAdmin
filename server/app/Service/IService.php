<?php

namespace App\Service;

use App\Model\Model;
use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Database\Model\Builder;

interface IService
{

    /**
     * 获取列表
     * @param array $params
     * @param int $page
     * @param int $pageSize
     * @return LengthAwarePaginatorInterface
     */
    public function getList(array $params = [], int $page = 1, int $pageSize = 20,$orderField = 'id',string $orderType = 'desc'): LengthAwarePaginatorInterface;

    /**
     * 创建
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed;

    public function update(int $id,array $data): int;


    public function delete(int $id): int;
    
    public function batchDelete(array $ids): int;

    public function changeStatus(int $id, int $status): int;

    public function preQuery(Builder|Model $builder,array $params = []): Builder;
}
