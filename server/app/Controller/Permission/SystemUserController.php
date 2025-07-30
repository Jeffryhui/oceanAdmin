<?php

namespace App\Controller\Permission;

use App\Annotation\Permission;
use App\Controller\CrudController;
use App\Model\Monitor\LoginLog;
use App\Model\Monitor\OperateLog;
use App\Model\Permission\Role;
use App\Model\Permission\SystemUser;
use App\Request\Permission\SystemUserRequest;
use App\Request\System\UpdateUserRequest;
use App\Service\Permission\PermissionCacheService;
use App\Service\Permission\RbacService;
use App\Service\Permission\SystemUserService;
use App\Utils\Response;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Hyperf\HttpServer\Request;
use HyperfExtension\Hashing\Hash;
use Psr\Http\Message\ResponseInterface;
use Qbhy\HyperfAuth\Annotation\Auth;

#[Controller(prefix: '/api/admin/system-user')]
class SystemUserController extends CrudController
{

    protected SystemUserService $systemUserService;
    #[Inject]
    private RbacService $rbacService;
    #[Inject]
    private PermissionCacheService $cacheService;
    public function __construct(SystemUserService $systemUserService)
    {
        $this->service = $systemUserService;
        $this->systemUserService = $systemUserService;
        $this->validator = $this->container->get(SystemUserRequest::class);
    }

    #[GetMapping(path: 'login-log')]
    #[Permission('permission:user:login-log', '获取用户登录日志', true, true)]
    #[Auth('admin')]
    public function loginLog()
    {
        /**
         * @var SystemUser $user
         */
        $user = auth('admin')->user();
        $loginLogs = LoginLog::query()->where('username', $user->username)->orderBy('login_time', 'desc')->paginate(5);
        return Response::success([
            'data' => $loginLogs->items(),
            'current_page' => $loginLogs->currentPage(),
            'per_page' => $loginLogs->perPage(),
            'total' => $loginLogs->total(),
            'has_more' => $loginLogs->hasMorePages(),
        ], '获取用户登录日志成功');
    }

    #[GetMapping(path: 'operate-log')]
    #[Permission('permission:user:operate-log', '获取用户操作日志', true, true)]
    #[Auth('admin')]
    public function operateLog()
    {
        /**
         * @var SystemUser $user
         */
        $user = auth('admin')->user();
        $operateLogs = OperateLog::query()->where('username', $user->username)->orderBy('created_at', 'desc')->paginate(5);
        return Response::success([
            'data' => $operateLogs->items(),
            'current_page' => $operateLogs->currentPage(),
            'per_page' => $operateLogs->perPage(),
            'total' => $operateLogs->total(),
            'has_more' => $operateLogs->hasMorePages(),
        ], '获取用户操作日志成功');
    }

    #[PostMapping(path: 'update-info')]
    #[Permission('permission:user:update-info', '更新用户信息', true, true)]
    #[Auth('admin')]
    public function updateInfo(Request $request)
    {
        $data = $request->only(['avatar', 'nickname', 'email', 'phone', 'signed', 'backend_setting']);
        $this->systemUserService->updateUserInfo($data);
        return Response::success([], '更新用户信息成功');
    }

    #[PostMapping(path: 'change-password')]
    #[Permission('permission:user:change-password', '修改密码', true, true)]
    #[Auth('admin')]
    public function changePassword(UpdateUserRequest $request)
    {
        $data = $request->validated();
        $result = $this->systemUserService->changePassword($data);
        if (!$result) {
            return Response::error('修改密码失败');
        }
        return Response::success([], '修改密码成功');
    }

    /**
     * 获取用户列表
     *
     * @return ResponseInterface
     */
    #[GetMapping(path: 'list')]
    #[Permission('permission:user:list', '获取用户列表', false, true)]
    #[Auth('admin')]
    public function list()
    {
        return parent::index();
    }

    #[PostMapping(path: 'store')]
    #[Permission('permission:user:store', '创建用户', false, true)]
    #[Auth('admin')]
    public function store()
    {
        $data = $this->validator->scene('store')->validated();
        $roleIDS = $data['role_ids'] ?? []; 
        unset($data['role_ids']);
        $data['password'] = Hash::make($data['password']);
        $result = $this->service->create($data);
        if(!empty($roleIDS) && is_array($roleIDS)){
            $this->rbacService->assignRoles($result->id, $roleIDS);
        }
        return Response::success(['id' => $result->id], '创建成功');
    }

    #[PutMapping(path: '{id}')]
    #[Permission('permission:user:update', '更新用户', false, true)]
    #[Auth('admin')]
    public function update(int $id)
    {
        $data = $this->validator->scene('update')->validated();
        $roleIDS = $data['role_ids'] ?? []; 
        unset($data['role_ids']);
        $result = $this->service->update($id, $data);
        if(!$result){
            return Response::error('更新失败');
        }
        if(!empty($roleIDS) && is_array($roleIDS)){
            $this->rbacService->assignRoles($id, $roleIDS);
        }
        return Response::success(['id' => $id], '更新成功');
    }

    #[DeleteMapping(path: '{id}')]
    #[Permission('permission:user:delete', '删除用户', false, true)]
    #[Auth('admin')]
    public function delete(int $id)
    {
        // 先清理缓存
        $this->cacheService->clearUserCache($id);
        return parent::delete($id);
    }

    #[PostMapping(path: 'batch-delete')]
    #[Permission('permission:user:batch-delete', '批量删除用户', false, true)]
    #[Auth('admin')]
    public function batchDelete()
    {
        return parent::batchDelete();
    }


    #[GetMapping(path: 'role-select')]
    #[Permission('permission:user:role-select', '获取角色选择', false, true)]
    #[Auth('admin')]
    public function roleSelect()
    {
        $data = Role::query()->where('status', 1)->select(['name as label', 'id as value'])->get()->toArray();
        return Response::success($data);
    }

    #[GetMapping(path: 'user-roles')]
    #[Permission('permission:user:user-roles', '获取用户角色', false, true)]
    #[Auth('admin')]
    public function userRoles()
    {
        $id = $this->request->query('id',0);
        if(empty($id)){
            return Response::error('用户ID不能为空');
        }
        $user = SystemUser::find($id);
        if(empty($user)){
            return Response::error('用户不存在');
        }
        $roles = $user->roles()->get()->toArray();
        return Response::success($roles);
    }

    #[PostMapping(path: 'change-status')]
    #[Permission('permission:user:change-status', '修改用户状态', false, true)]
    #[Auth('admin')]
    public function changeStatus()
    {
        $id = $this->request->post('id', 0);
        if (!empty($id)) {
            // 状态变更后清理用户缓存
            $this->cacheService->clearUserCache($id);
        }
        return parent::changeStatus();
    }

    #[PostMapping(path: 'update-cache')]
    #[Permission('permission:user:update-cache', '更新缓存', false, true)]
    #[Auth('admin')]
    public function updateCache()
    {
        $id = $this->request->post('id',0);
        if(empty($id)){
            return Response::error('用户ID不能为空');
        }
        // 使用统一的缓存清理服务
        $this->cacheService->clearUserCache($id);
        return Response::success([], '更新缓存成功');
    }

    #[PostMapping(path: 'init-password')]
    #[Permission('permission:user:init-password', '初始化密码', false, true)]
    #[Auth('admin')]
    public function initPassword()
    {
        $id = $this->request->post('id',0);
        if(empty($id)){
            return Response::error('用户ID不能为空');
        }
        $result = $this->systemUserService->initPassword($id);
        if(!$result){
            return Response::error('初始化密码失败');
        }
        return Response::success([], '初始化密码成功');
    }
}
