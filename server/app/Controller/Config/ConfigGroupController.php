<?php

namespace App\Controller\Config;

use App\Annotation\Permission;
use App\Controller\CrudController;
use App\Model\Config\ConfigGroup;
use App\Request\Config\ConfigGroupRequest;
use App\Service\Config\ConfigGroupService;
use App\Utils\Response;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Qbhy\HyperfAuth\Annotation\Auth;

#[Controller('/api/admin/config-group')]
class ConfigGroupController extends CrudController
{
    public function __construct(ConfigGroupService $service)
    {
        $this->service = $service;
        $this->validator = $this->container->get(ConfigGroupRequest::class);
    }

    #[GetMapping(path: "list")]
    #[Permission('config-group:list', '配置组列表', false, true)]
    #[Auth('admin')]
    public function index()
    {
        $data = ConfigGroup::query()->select(['id','name','code','remark','created_at'])->get()->toArray();
        return Response::success($data);
    }

    #[PostMapping(path: "store")]
    #[Permission('config-group:store', '创建配置组', false, true)]
    #[Auth('admin')]
    public function store()
    {
        return parent::store();
    }
    #[PutMapping(path: "{id}")]
    #[Permission('config-group:update', '更新配置组', false, true)]
    #[Auth('admin')]
    public function update(int $id)
    {
        return parent::update($id);
    }
    #[DeleteMapping(path: "{id}")]
    #[Permission('config-group:delete', '删除配置组', false, true)]
    #[Auth('admin')]
    public function delete(int $id)
    {
        return parent::delete($id);
    }
    #[PostMapping(path: "batch-delete")]
    #[Permission('config-group:batch-delete', '批量删除配置组', false, true)]
    #[Auth('admin')]
    public function batchDelete()
    {
        return parent::batchDelete();
    }
}
