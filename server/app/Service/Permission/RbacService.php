<?php

namespace App\Service\Permission;

use App\Exception\BusinessException;
use App\Model\Permission\Role;
use App\Model\Permission\SystemUser;
use Hyperf\Di\Annotation\Inject;

class RbacService
{
    #[Inject]
    private PermissionCacheService $cacheService;
    /**
     * 分配菜单
     *
     * @param integer $roleId
     * @param array $menuIds
     * @return array
     */
    public function assignMenus(int $roleId, array $menuIds)
    {
        /**
         * @var Role $role
         */
        $role = Role::find($roleId);
        if (empty($role)) {
            throw new BusinessException('角色不存在');
        }
        
        $result = $role->menus()->sync($menuIds);
        
        // 清理角色相关用户的缓存
        $this->cacheService->clearRoleCache($roleId);
        
        return $result;
    }

    /**
     * 分配角色
     *
     * @param integer $userId
     * @param array $roleIds
     * @return array
     */
    public function assignRoles(int $userId, array $roleIds)
    {
        /**
         * @var SystemUser $user
         */
        $user = SystemUser::find($userId);
        if (empty($user)) {
            throw new BusinessException('用户不存在');
        }
        
        $result = $user->roles()->sync($roleIds);
        
        // 清理用户的权限缓存
        $this->cacheService->clearUserCache($userId);
        
        return $result;
    }
}
