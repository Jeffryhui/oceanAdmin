<?php

namespace App\Controller\Data;

use App\Annotation\Permission;
use App\Controller\CrudController;
use App\Request\Data\DictTypeRequest;
use App\Service\Data\DictTypeService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Qbhy\HyperfAuth\Annotation\Auth;

#[Controller('/api/admin/dict-type')]
class DictTypeController extends CrudController
{

    public function __construct(DictTypeService $service,DictTypeRequest $request)
    {
        $this->service = $service;
        $this->validator = $request;
    }

    #[GetMapping(path: 'list')]
    #[Permission( 'data:dict-type:list',  '获取字典类型列表',false,true)]
    #[Auth('admin')]
    public function index()
    {
        return parent::index();
    }

    #[PostMapping(path: 'store')]
    #[Permission( 'data:dict-type:store',  '创建字典类型',false,true)]
    #[Auth('admin')]
    public function store()
    {
        return parent::store();
    }

    #[PutMapping(path: '{id}')]
    #[Permission( 'data:dict-type:update',  '更新字典类型',false,true)]
    #[Auth('admin')]
    public function update(int $id)
    {
        return parent::update($id);
    }

    #[DeleteMapping(path: '{id}')]
    #[Permission( 'data:dict-type:delete',  '删除字典类型',false,true)]
    #[Auth('admin')]
    public function delete(int $id)
    {
        return parent::delete($id);
    }

    #[PostMapping(path: 'batch-delete')]
    #[Permission( 'data:dict-type:batch-delete',  '批量删除字典类型',false,true)]
    #[Auth('admin')]
    public function batchDelete()
    {
        return parent::batchDelete();
    }

    #[PostMapping(path: 'change-status')]
    #[Permission( 'data:dict-type:change-status',  '更新字典类型状态',false,true)]
    #[Auth('admin')]
    public function changeStatus()
    {
        return parent::changeStatus();
    }
}
