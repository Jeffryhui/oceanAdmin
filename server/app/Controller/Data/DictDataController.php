<?php

namespace App\Controller\Data;

use App\Annotation\Permission;
use App\Controller\CrudController;
use App\Model\Data\DictType;
use App\Request\Data\DictDataRequest;
use App\Service\Data\DictDataService;
use App\Utils\Response;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Qbhy\HyperfAuth\Annotation\Auth;

#[Controller('/api/admin/dict-data')]
class DictDataController extends CrudController
{
    protected DictDataService $dictDataService;

    public function __construct(DictDataService $service,DictDataRequest $request)
    {
        $this->service = $service;
        $this->dictDataService = $service; // 额外保存具体类型
        $this->validator = $request;
    }

    #[GetMapping(path: 'all')]
    #[Permission( 'admin:dict:all',  '获取所有字典',false,true)]
    #[Auth('admin')]
    public function all()
    {
        $list = $this->dictDataService->all();
        return Response::success($list);
    }

    #[GetMapping(path: 'list')]
    #[Permission( 'admin:dict:index',  '获取字典列表',false,true)]
    #[Auth('admin')]
    public function index()
    {
        return parent::index();
    }

    #[PostMapping(path: 'store')]
    #[Permission( 'admin:dict:store',  '创建字典',false,true)]
    #[Auth('admin')]
    public function store()
    {
        return parent::store();
    }

    #[PutMapping(path: '{id}')]
    #[Permission( 'admin:dict:update',  '更新字典',false,true)]
    #[Auth('admin')]
    public function update(int $id)
    {
        return parent::update($id);
    }

    #[DeleteMapping(path: '{id}')]
    #[Permission( 'admin:dict:delete',  '删除字典',false,true)]
    #[Auth('admin')]
    public function delete(int $id)
    {
        return parent::delete($id);
    }

    #[PostMapping(path: 'batch-delete')]
    #[Permission( 'admin:dict:batch-delete',  '批量删除字典',false,true)]
    #[Auth('admin')]
    public function batchDelete()
    {
        return parent::batchDelete();
    }

    #[PostMapping(path: 'change-status')]
    #[Permission( 'admin:dict:change-status',  '更新字典状态',false,true)]
    #[Auth('admin')]
    public function changeStatus()
    {
        return parent::changeStatus();
    }
}
