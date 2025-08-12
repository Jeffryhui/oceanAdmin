<?php

namespace App\Controller\Permission;

use App\Annotation\Permission;
use App\Controller\CrudController;
use App\Model\Permission\Menu;
use App\Model\Permission\Role;
use App\Request\Permission\RoleRequest;
use App\Service\Permission\PermissionCacheService;
use App\Service\Permission\RbacService;
use App\Service\Permission\RoleService;
use App\Utils\Response;
use App\Utils\Tree;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Hyperf\Di\Annotation\Inject;
use Qbhy\HyperfAuth\Annotation\Auth;

#[Controller('/api/admin/role')]
class RoleController extends CrudController
{
    #[Inject]
    private RbacService $rbacService;
    #[Inject]
    private PermissionCacheService $cacheService;
    
    public function __construct(RoleService $service)
    {
        $this->service = $service;
        $this->validator = $this->container->get(RoleRequest::class);
    }

    #[GetMapping(path: 'list')]
    #[Permission('permission:role:list', '角色列表', false, true)]
    #[Auth('admin')]
    public function index()
    {
        return parent::index();
    }

    #[PostMapping(path: 'store')]
    #[Permission('permission:role:store', '创建角色', false, true)]
    #[Auth('admin')]
    public function store()
    {
        return parent::store();
    }

    #[PutMapping(path: '{id}')]
    #[Permission('permission:role:update', '更新角色', false, true)]
    #[Auth('admin')]
    public function update(int $id)
    {
        $result = parent::update($id);
        // 角色更新后清理相关缓存
        $this->cacheService->clearRoleCache($id);
        return $result;
    }

    #[DeleteMapping(path: '{id}')]
    #[Permission('permission:role:delete', '删除角色', false, true)]
    #[Auth('admin')]
    public function delete(int $id)
    {
        // 先清理角色相关缓存
        $this->cacheService->clearRoleCache($id);
        return parent::delete($id);
    }

    #[PostMapping(path: 'batch-delete')]
    #[Permission('permission:role:batch-delete', '批量删除角色', false, true)]
    #[Auth('admin')]
    public function batchDelete()
    {
        $ids = $this->request->post('ids',[]);
        $result = $this->service->batchDelete($ids);
        if($result){
            return Response::success(['ids' => $ids], '批量删除成功');
        }
        return Response::error('批量删除失败');
    }


    #[GetMapping(path: 'role-menus')]
    #[Permission('permission:role:role-menus', '获取角色菜单', false, true)]
    #[Auth('admin')]
    public function roleMenus()
    {
        $id = $this->request->query('id',0);
        if(empty($id)){
            return Response::error('角色ID不能为空');
        }    
        /**
         * @var Role $role
         */
        $role = Role::find($id);
        if(empty($role)){
            return Response::error('角色不存在');
        }
        $menus = $role->menus()->get()->toArray();
        $result = [
            'id' => $id,
            'menus' =>$menus
        ];    
        return Response::success($result);
    }

    #[PostMapping(path: 'assign-menus')]
    #[Permission('permission:role:assign-menus', '分配菜单', false, true)]
    #[Auth('admin')]
    public function assignMenus()
    {
        $id = $this->request->query('id',0);
        if(empty($id)){
            return Response::error('角色ID不能为空');
        }
        $menuIDS = $this->request->post('menu_ids',[]);
        if(!is_array($menuIDS)){
            return Response::error('菜单ID必须为数组');
        }
        $res = $this->rbacService->assignMenus($id, $menuIDS);
        return Response::success($res, '分配菜单成功');
    }

    #[GetMapping(path: 'menu-tree')]
    #[Permission('permission:role:menu-tree', '菜单树', false, true)]
    #[Auth('admin')]
    public function menuTree()
    {
        $data =  Menu::query()
        ->select(['id', 'parent_id', 'name as label', 'id as value']) // value 用 id
        ->where('status', 1)
        ->orderBy('sort')
        ->get()
        ->toArray();
        $tree = Tree::build($data);
        return Response::success($tree);
    }
}
