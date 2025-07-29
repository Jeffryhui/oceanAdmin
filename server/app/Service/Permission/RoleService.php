<?php

namespace App\Service\Permission;

use App\Model\Permission\Role;
use App\Model\Permission\SystemUser;
use App\Service\BaseService;
use App\Service\IService;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Model;
use FriendsOfHyperf\Cache\Contract\Repository as CacheInterface;
use Hyperf\Di\Annotation\Inject;

class RoleService extends BaseService implements IService
{


    const CACHE_KEY_USER_ROLE_CODES = 'user_role_codes_';

    #[Inject]
    private CacheInterface $cache;
    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function preQuery(Builder|Model $builder, array $params = []): Builder
    {
        if(isset($params['name']) && !empty($params['name'])){
            $builder->where('name', 'like', '%'.$params['name'].'%');
        }
        if(isset($params['code']) && !empty($params['code'])){
            $builder->where('code', 'like', '%'.$params['code'].'%');
        }
        if(isset($params['status']) && !empty($params['status'])){
            $builder->where('status', $params['status']);
        }
        return $builder;
    }

    /**
     * 获取用户的角色code
     * @param int $userId
     * @return array
     */
    public function userRoleCodes(int $userId)
    {
        /**
         * @var SystemUser $user
         */
        $user = SystemUser::find($userId);
        if(empty($user)){
            return [];
        }
        if($user->id == SystemUser::SUPER_ADMIN_ID){
            return ['*'];
        }
        $roles = $user->roles()->get()->toArray();
        $cacheKey = self::CACHE_KEY_USER_ROLE_CODES.$userId;
        return $this->cache->remember($cacheKey,60,function() use ($roles){
            return array_column($roles, 'code');
        });
    }

    /**
     * 删除用户角色缓存
     * @param int $userId
     * @return bool
     */
    public function deleteUserRoleCodeCache(int $userId)
    {
        $cacheKey = self::CACHE_KEY_USER_ROLE_CODES . $userId;
        return $this->cache->delete($cacheKey);
    }

    /**
     * 清理角色相关的所有用户缓存（当角色权限变更时调用）
     * @param int $roleId
     * @return void
     */
    public function clearRoleUsersCache(int $roleId)
    {
        // 获取拥有该角色的所有用户
        $userIds = \Hyperf\DbConnection\Db::table('user_role')
            ->where('role_id', $roleId)
            ->pluck('system_user_id')
            ->toArray();
        
        // 清理这些用户的角色缓存
        foreach ($userIds as $userId) {
            $this->deleteUserRoleCodeCache($userId);
        }
    }
}