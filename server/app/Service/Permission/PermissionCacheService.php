<?php

namespace App\Service\Permission;

use Hyperf\Di\Annotation\Inject;

/**
 * 权限缓存管理服务
 * 统一处理用户权限相关的缓存清理
 */
class PermissionCacheService
{
    #[Inject]
    private MenuService $menuService;

    #[Inject]
    private RoleService $roleService;

    /**
     * 清理单个用户的所有权限缓存
     * @param int $userId
     * @return void
     */
    public function clearUserCache(int $userId)
    {
        // 清理用户角色缓存
        $this->roleService->deleteUserRoleCodeCache($userId);
        
        // 清理用户菜单缓存
        $this->menuService->deleteUserMenuCodesCache($userId);
        $this->menuService->deleteUserMenusCache($userId);
    }

    /**
     * 清理多个用户的权限缓存
     * @param array $userIds
     * @return void
     */
    public function clearUsersCache(array $userIds)
    {
        foreach ($userIds as $userId) {
            $this->clearUserCache($userId);
        }
    }

    /**
     * 清理角色相关的所有用户缓存
     * @param int $roleId
     * @return void
     */
    public function clearRoleCache(int $roleId)
    {
        // 清理角色相关用户的角色缓存
        $this->roleService->clearRoleUsersCache($roleId);
        
        // 清理角色相关用户的菜单缓存
        $this->menuService->clearRoleUsersMenuCache($roleId);
    }

    /**
     * 清理多个角色相关的缓存
     * @param array $roleIds
     * @return void
     */
    public function clearRolesCache(array $roleIds)
    {
        foreach ($roleIds as $roleId) {
            $this->clearRoleCache($roleId);
        }
    }

    /**
     * 清理菜单相关的所有缓存
     * 当菜单发生变化时调用
     * @return void
     */
    public function clearMenuCache()
    {
        // 菜单变化影响所有用户，清理所有用户菜单缓存
        $this->menuService->clearAllUsersMenuCache();
    }

    /**
     * 清理所有权限相关缓存
     * 用于紧急情况或系统维护
     * @return void
     */
    public function clearAllCache()
    {
        $this->menuService->clearAllUsersMenuCache();
        
        // 清理所有用户的角色缓存
        $userIds = \Hyperf\DbConnection\Db::table('system_user')
            ->pluck('id')
            ->toArray();
        
        foreach ($userIds as $userId) {
            $this->roleService->deleteUserRoleCodeCache($userId);
        }
    }
} 