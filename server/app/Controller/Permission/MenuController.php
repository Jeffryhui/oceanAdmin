<?php

namespace App\Controller\Permission;

use App\Annotation\Permission;
use App\Controller\CrudController;
use App\Model\Permission\Menu;
use App\Request\Permission\MenuRequest;
use App\Service\Permission\MenuService;
use App\Service\Permission\PermissionCacheService;
use App\Utils\Response;
use App\Utils\Tree;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Qbhy\HyperfAuth\Annotation\Auth;

use function FriendsOfHyperf\Helpers\logs;

#[Controller('/api/admin/menu')]
class MenuController extends CrudController
{
    #[Inject]
    private PermissionCacheService $cacheService;

    public function __construct(MenuService $service){
        $this->service = $service;
        $this->validator = $this->container->get(MenuRequest::class);
    }

    #[GetMapping(path: 'list')]
    #[Permission('permission:menu:list', '菜单列表', false, true)]
    #[Auth('admin')]
    public function index()
    {
        $params = $this->request->query();
        $query = Menu::query();
        if(isset($params['name']) && !empty($params['name'])){
            $query->where('name', $params['name']);
        }
        if(isset($params['code']) && !empty($params['code'])){
            $query->where('code', $params['code']);
        }
        if(isset($params['status']) && !empty($params['status'])){
            $query->where('status', $params['status']);
        }
        $data = $query->orderBy('sort', 'asc')->get()->toArray();
        if(count($data) == 1){
            return Response::success($data);
        }
        $tree = Tree::build($data);
        return Response::success($tree);
    }

    #[PostMapping(path: 'store')]
    #[Permission('permission:menu:store', '创建菜单', false, true)]
    #[Auth('admin')]
    public function store()
    {
        $result = parent::store();
        // 菜单创建后清理所有用户的菜单缓存
        $this->cacheService->clearMenuCache();
        return $result;
    }

    #[PutMapping(path: '{id}')]
    #[Permission('permission:menu:update', '更新菜单', false, true)]
    #[Auth('admin')]
    public function update(int $id)
    {
        $result = parent::update($id);
        // 菜单更新后清理所有用户的菜单缓存
        $this->cacheService->clearMenuCache();
        return $result;
    }

    #[PostMapping(path: 'batch-delete')]
    #[Permission('permission:menu:batch-delete', '批量删除菜单', false, true)]
    #[Auth('admin')]
    public function batchDelete()
    {
        $ids = $this->request->post('ids',[]);
        try {
            // 删除菜单和子菜单对应的角色关联
            $this->service->batchDelete($ids);
            // 菜单删除后清理所有用户的菜单缓存
            $this->cacheService->clearMenuCache();
            return Response::success(['ids' => $ids], '批量删除成功');
        } catch (\Throwable $th) {
            logs()->error($th->getMessage());
            return Response::error('批量删除失败');
        }
    }

}
