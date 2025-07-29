<?php

namespace App\Service\Permission;

use App\Exception\BusinessException;
use App\Model\Permission\Role;
use App\Model\Permission\SystemUser;

class RbacService
{
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
        return $role->menus()->sync($menuIds);
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
        return $user->roles()->sync($roleIds);
    }
}
