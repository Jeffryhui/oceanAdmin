<?php

namespace App\Service\Permission;

use App\Model\Permission\Menu;
use App\Model\Permission\SystemUser;
use App\Service\BaseService;
use App\Service\IService;
use Hyperf\Di\Annotation\Inject;
use FriendsOfHyperf\Cache\Contract\Repository as CacheInterface;
use Hyperf\DbConnection\Db;

class MenuService extends BaseService implements IService
{

    const CACHE_KEY_USER_MENU_CODES = 'user_menu_codes_';
    const CACHE_KEY_USER_MENUS = 'user_menus_';

    #[Inject]
    private CacheInterface $cache;
    public function __construct(Menu $model)
    {
        $this->model = $model;
    }

    /**
     * 批量删除菜单
     * @param array $ids 要删除的菜单ID数组
     * @return int 删除的记录数
     */
    public function batchDelete(array $ids): int
    {
        return Db::transaction(function() use ($ids) {
            // 获取所有要删除的菜单，包括子菜单
            $allMenus = $this->model->whereIn('id', $ids)->get();
            $allMenuIds = [];
            
            // 收集所有要删除的菜单ID（包括子菜单）
            foreach ($allMenus as $menu) {
                $allMenuIds = array_merge($allMenuIds, $menu->getAllChildrenIds());
            }
            $allMenuIds = array_unique($allMenuIds);
            
            // 获取所有受影响的菜单实例
            $menus = $this->model->whereIn('id', $allMenuIds)->get();
            
            // 删除每个菜单及其关联
            foreach ($menus as $menu) {
                // 删除菜单与角色的关联关系
                $menu->roles()->detach();
                
                // 删除菜单
                $menu->delete();
            }
            
            return count($menus);
        });
    }
    
    /**
     * 获取用户对应的菜单code
     * @param int $userId
     * @return array
     */
    public function getUserMenuCodes(int $userId)
    {
        $cacheKey = self::CACHE_KEY_USER_MENU_CODES . $userId;
        // 获取到用户对应的菜单code
        /**
         * @var SystemUser $user
         */
        $user = SystemUser::find($userId);
        if (empty($user)) {
            return [];
        }
        if ($user->id == SystemUser::SUPER_ADMIN_ID) {
            return ['*'];
        }
        $menus = $user->menus()->toArray();
        return $this->cache->remember($cacheKey, 60, function () use ($menus) {
            return array_column($menus, 'code');
        });
    }


    public function getUserMenus(int $userID)
    {
        $cacheKey = self::CACHE_KEY_USER_MENUS . $userID;

        return $this->cache->remember($cacheKey, 60, function () use ($userID) {
            /**
             * @var SystemUser $user
             */
            $user = SystemUser::find($userID);
            if (empty($user)) {
                return [];
            }

            // 如果是超级管理员，返回所有菜单
            if ($user->id == SystemUser::SUPER_ADMIN_ID) {
                $menus = Menu::where('status', 1)
                    ->where('type', 'M')
                    ->orderBy('sort', 'asc')
                    ->get()
                    ->toArray();
            } else {
                // 根据用户角色获取菜单
                $menus = $user->menus()
                    ->where('status', 1)
                    ->where('type', 'M')
                    ->sortBy('sort')
                    ->values()
                    ->toArray();
            }

            // 组装前端需要的数据格式
            $formattedMenus = $this->formatMenusForFrontend($menus);

            // 构建树形结构
            return \App\Utils\Tree::build($formattedMenus);
        });
    }

    /**
     * 格式化菜单数据为前端需要的格式
     * @param array $menus
     * @return array
     */
    private function formatMenusForFrontend(array $menus): array
    {
        return array_map(function ($menu) {
            return [
                'id' => $menu['id'],
                'parent_id' => $menu['parent_id'],
                'name' => $menu['code'], // 路由名称使用code
                'path' => '/' . $menu['route'], // 路由路径
                'component' => $menu['component'] ?? '',
                'redirect' => $menu['redirect'] ?? null,
                'meta' => [
                    'title' => $menu['name'], // 显示标题使用name
                    'type' => $menu['type'],
                    'hidden' => $menu['is_hidden'] == 1, // 1是隐藏=true, 2否=false
                    'layout' => $menu['is_layout'] == 1, // 1是=true, 2否=false
                    'hiddenBreadcrumb' => false, // 默认值
                    'icon' => $menu['icon'] ?? ''
                ]
            ];
        }, $menus);
    }

    public function deleteUserMenuCodesCache(int $userId)
    {
        $cacheKey = self::CACHE_KEY_USER_MENU_CODES . $userId;
        return $this->cache->delete($cacheKey);
    }

    public function deleteUserMenusCache(int $userId)
    {
        $cacheKey = self::CACHE_KEY_USER_MENUS . $userId;
        return $this->cache->delete($cacheKey);
    }

    /**
     * 清理所有用户的菜单缓存
     * @return void
     */
    public function clearAllUsersMenuCache()
    {
        // 获取所有用户ID
        $userIds = \Hyperf\DbConnection\Db::table('system_user')
            ->pluck('id')
            ->toArray();
        
        // 清理所有用户的菜单相关缓存
        foreach ($userIds as $userId) {
            $this->deleteUserMenuCodesCache($userId);
            $this->deleteUserMenusCache($userId);
        }
    }

    /**
     * 清理角色相关用户的菜单缓存
     * @param int $roleId
     * @return void
     */
    public function clearRoleUsersMenuCache(int $roleId)
    {
        // 获取拥有该角色的所有用户
        $userIds = \Hyperf\DbConnection\Db::table('user_role')
            ->where('role_id', $roleId)
            ->pluck('system_user_id')
            ->toArray();
        
        // 清理这些用户的菜单缓存
        foreach ($userIds as $userId) {
            $this->deleteUserMenuCodesCache($userId);
            $this->deleteUserMenusCache($userId);
        }
    }
}
