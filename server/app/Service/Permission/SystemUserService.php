<?php

namespace App\Service\Permission;

use App\Exception\BusinessException;
use App\Model\Permission\SystemUser;
use App\Service\BaseService;
use App\Service\IService;
use Hyperf\DbConnection\Db;
use HyperfExtension\Hashing\Hash;

class SystemUserService extends BaseService implements IService
{

    public function __construct(SystemUser $model)
    {
        $this->model = $model;
    }
    /**
     * 更新用户信息
     * @param array $data
     * @throws \Exception
     * @return bool
     */
    public function updateUserInfo(array $data)
    {
        $update = [];
        if (isset($data['avatar']) && !empty($data['avatar'])) {
            $update['avatar'] = $data['avatar'];
        }
        if (isset($data['nickname']) && !empty($data['nickname'])) {
            $update['nickname'] = $data['nickname'];
        }
        if (isset($data['email']) && !empty($data['email'])) {
            $update['email'] = $data['email'];
        }
        if (isset($data['phone']) && !empty($data['phone'])) {
            $update['phone'] = $data['phone'];
        }
        if (isset($data['signed']) && !empty($data['signed'])) {
            $update['signed'] = $data['signed'];
        }
        if (isset($data['backend_setting']) && !empty($data['backend_setting'])) {
            $update['backend_setting'] = $data['backend_setting'];
        }
        /**
         * @var SystemUser $user
         */
        $user = auth('admin')->user();
        if (isset($update['email'])) {
            if (SystemUser::where('email', $update['email'])->where('id', '!=', $user->id)->exists()) {
                throw new BusinessException('邮箱已存在');
            }
        }
        if (isset($update['phone'])) {
            if (SystemUser::where('phone', $update['phone'])->where('id', '!=', $user->id)->exists()) {
                throw new BusinessException('手机号已存在');
            }
        }
        if (empty($update)) {
            throw new BusinessException('没有需要更新的信息');
        }
        return $user->update($update);
    }

    /**
     * 修改密码
     * @param array $data
     * @throws \App\Exception\BusinessException
     * @return bool
     */
    public function changePassword(array $data)
    {
        /**
         * @var SystemUser $user
         */
        $user = auth('admin')->user();
        if (!Hash::check($data['old_password'], $user->password)) {
            throw new BusinessException('旧密码错误');
        }
        $user->password = Hash::make($data['new_password']);
        return $user->save();
    }

    public function preQuery(\Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model $builder, array $params = []): \Hyperf\Database\Model\Builder
    {
        if (isset($params['username']) && !empty($params['username'])) {
            $builder->where('username', 'like', '%' . $params['username'] . '%');
        }
        if (isset($params['email']) && !empty($params['email'])) {
            $builder->where('email', 'like', '%' . $params['email'] . '%');
        }
        if (isset($params['phone']) && !empty($params['phone'])) {
            $builder->where('phone', 'like', '%' . $params['phone'] . '%');
        }
        if (isset($params['status']) && !empty($params['status'])) {
            $builder->where('status', $params['status']);
        }
        if (isset($params['create_time']) && !empty($params['create_time']) && is_array($params['create_time'])) {
            $builder->whereBetween('created_at', [$params['create_time'][0], $params['create_time'][1]]);
        }
        return $builder;
    }

    public function initPassword(int $id)
    {
        $user = $this->model->find($id);
        if (empty($user)) {
            throw new BusinessException('用户不存在');
        }
        $user->password = Hash::make('123456');
        return $user->save();
    }

    public function batchDelete(array $ids): int
    {
        return Db::transaction(function () use ($ids) {
            $users = $this->model->whereIn('id', $ids)->get();
            /**
             * @var SystemUser $user
             */
            foreach ($users as $user) {
                // 删除用户与角色的关联关系
                $user->roles()->detach();
                // 删除用户
                $user->delete();
            }
            return count($ids);
        });

    }
}
