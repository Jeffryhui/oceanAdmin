<?php

namespace App\Service\Permission;

use App\Exception\BusinessException;
use App\Model\Permission\SystemUser;
use HyperfExtension\Hashing\Hash;

class SystemUserService
{
    /**
     * 更新用户信息
     * @param array $data
     * @throws \Exception
     * @return bool
     */
    public function updateUserInfo(array $data)
    {
        $update = [];
        if(isset($data['avatar']) && !empty($data['avatar'])){
            $update['avatar'] = $data['avatar'];
        }
        if(isset($data['nickname']) && !empty($data['nickname'])){
            $update['nickname'] = $data['nickname'];
        }
        if(isset($data['email']) && !empty($data['email'])){  
            $update['email'] = $data['email'];
        }
        if(isset($data['phone']) && !empty($data['phone'])){
            $update['phone'] = $data['phone'];
        }
        if(isset($data['signed']) && !empty($data['signed'])){
            $update['signed'] = $data['signed'];
        }
        if(isset($data['backend_setting']) && !empty($data['backend_setting'])){
            $update['backend_setting'] = $data['backend_setting'];
        }
        /**
         * @var SystemUser $user
         */
        $user = auth('admin')->user();
        if(SystemUser::where('email',$data['email'])->where('id','!=',$user->id)->exists()){
            throw new BusinessException('邮箱已存在');
        }
        if(SystemUser::where('phone',$data['phone'])->where('id','!=',$user->id)->exists()){
            throw new BusinessException('手机号已存在');
        }
        if(empty($update)){
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
        if(!Hash::check($data['old_password'], $user->password)){
            throw new BusinessException('旧密码错误');
        }
        $user->password = Hash::make($data['new_password']);
        return $user->save();
    }
}
