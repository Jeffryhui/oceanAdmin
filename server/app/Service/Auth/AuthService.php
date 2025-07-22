<?php

namespace App\Service\Auth;

use App\Exception\BusinessException;
use App\Model\Permission\SystemUser;
use App\Service\Permission\MenuService;
use Carbon\Carbon;
use HyperfExtension\Hashing\Hash;

use function Ella123\HyperfCaptcha\captcha_verify;
use function FriendsOfHyperf\Helpers\get_client_ip;
use function Hyperf\Support\env;

class AuthService
{
    public function __construct(private MenuService $menuService){}

    /**
     * 后台用户登录
     * @param array $data
     * @throws \App\Exception\BusinessException
     */
    public function login(array $data)
    {
        // 正式环境必须验证验证码
        if(env('APP_ENV') == 'prod'){
            $captchaResult = captcha_verify($data['code'],$data['key']);
            if(!$captchaResult){
                throw new BusinessException('验证码错误');
            }
        }
        /**
         * @var SystemUser $user
         */
        $user = SystemUser::where('username', $data['username'])->first();
        if (!$user) {
            throw new BusinessException('用户名或密码错误');
        }
        if ($user->status == SystemUser::STATUS_DISABLE) {
            throw new BusinessException('用户已禁用');
        }
        if (!Hash::check($data['password'], $user->password)) {
            throw new BusinessException('用户名或密码错误');
        }
        $token = auth('admin')->login($user);
        if (!$token) {
            throw new BusinessException('登录失败,请稍后重试');
        }
        $res = $user->update([
            'login_ip' => get_client_ip(),
            'login_time' => Carbon::now(),
        ]);
        if (!$res) {
            throw new BusinessException('登录失败,请稍后重试');
        }
        return $token;
    }

    /**
     * 已登录后台用户
     * @return array
     */
    public function loginUser(array $selectField = [])
    {
        /**
         * @var SystemUser $user
         */
        $user = auth('admin')->user();
        if (!empty($selectField)) {
            $result = $user->setVisible($selectField)->toArray();
        } else {
            $result = $user->toArray();
        }
        return $result;
        
    }

    /**
     * 获取用户信息
     * @return array{codes: string[], roles: string[], routers: array, user: array}
     */
    public function userInfo()
    {
        $user = $this->loginUser(['id','username','nickname','avatar','email','phone','status','signed','dashboard','backend_setting','remark','login_ip','login_time','created_at']);
        $menus = $this->menuService->getUserMenus();
        $data = [
            'codes' => ['*'], // TODO 从数据库查询用户对应的权限码
            'roles' => ['superAdmin'], // TODO 从数据库查询用户对应的角色
            'routers' => $menus,
            'user' => $user
        ];
        return $data;
    }

    /**
     * 退出登录
     * @return bool
     */
    public function logout()
    {
        auth('admin')->logout();
        return true;
    }
}
