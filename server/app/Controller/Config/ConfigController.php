<?php

namespace App\Controller\Config;

use App\Annotation\Permission;
use App\Controller\CrudController;
use App\Model\Config\Config;
use App\Request\Config\ConfigRequest;
use App\Service\Config\ConfigService;
use App\Utils\Response;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Qbhy\HyperfAuth\Annotation\Auth;

#[Controller('/api/admin/config')]
class ConfigController extends CrudController
{
    public function __construct(ConfigService $service)
    {
        $this->service = $service;
        $this->validator = $this->container->get(ConfigRequest::class);
    }

    #[GetMapping(path: "list")]
    #[Permission('config:list', '配置列表', false, true)]
    #[Auth('admin')]
    public function index()
    {
        $params = $this->request->query();
        $query = Config::query();
        if(isset($params['group_id'])){
            $query->where('group_id', $params['group_id']);
        }
        if(isset($params['name'])){
            $query->where('name', 'like', '%'.$params['name'].'%');
        }
        if(isset($params['key'])){
            $query->where('key', 'like', '%'.$params['key'].'%');
        }
        $list = $query->select(['id','name','group_id','key','value','input_type','config_select_data','remark','sort','created_at'])->get()->toArray();
        return Response::success($list);
    }
    #[PostMapping(path: "store")]
    #[Permission('config:store', '创建配置', false, true)]
    #[Auth('admin')]
    public function store()
    {
        return parent::store();
    }
    #[PutMapping(path: "{id}")]
    #[Permission('config:update', '更新配置', false, true)]
    #[Auth('admin')]
    public function update(int $id)
    {
        return parent::update($id);
    }
    #[DeleteMapping(path: "{id}")]
    #[Permission('config:delete', '删除配置', false, true)]
    #[Auth('admin')]
    public function delete(int $id)
    {
        return parent::delete($id);
    }
    #[PostMapping(path: "batch-delete")]
    #[Permission('config:batch-delete', '批量删除配置', false, true)]
    #[Auth('admin')]
    public function batchDelete()
    {
        return parent::batchDelete();
    }

    #[PostMapping(path: "batch-update")]
    #[Permission('config:batch-update', '批量更新配置', false, true)]
    #[Auth('admin')]
    public function batchUpdate()
    {
        $data = $this->request->post();
        try {
            $configs = $data['config'] ?? [];
            foreach ($configs as $item) {
                Config::where('id',$item['id'])->update(['value'=>$item['value']]);
            }
            return Response::success(null,'更新成功');
        } catch (\Throwable $th) {
            return Response::error('更新失败');
        }
       
    }
}
