<?php

namespace App\Service\Auth;

use App\Exception\BusinessException;
use App\Model\Monitor\LoginLog;
use App\Model\Permission\SystemUser;
use App\Service\Permission\MenuService;
use App\Service\Permission\RoleService;
use App\Utils\IpLocationUtils;
use App\Utils\ServerInfo;
use Carbon\Carbon;
use HyperfExtension\Hashing\Hash;

use function Ella123\HyperfCaptcha\captcha_verify;
use function FriendsOfHyperf\Helpers\get_client_ip;
use function Hyperf\Support\env;

class AuthService
{
    public function __construct(private MenuService $menuService,private IpLocationUtils $ipLocationUtils,private RoleService $roleService){}


    /**
     * 登录日志
     * @param string $username
     * @param int $status
     * @param string $message
     * @return 
     */
    public function loginLog($username,$status = 1,$message = '登录成功')
    {
        $browser = ServerInfo::browser();
        $os = ServerInfo::os();
        $data = [
            'username' => $username,
            'ip' => get_client_ip(),
            'ip_location' => $this->ipLocationUtils->getSimpleLocation(get_client_ip()),
            'browser' => $browser['full'],
            'os' => $os['full'],
            'status' => $status,
            'message' => $message,
            'login_time' => Carbon::now(),
        ];
        $res = LoginLog::query()->create($data);
        return $res;
    }
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
            $this->loginLog($data['username'],LoginLog::STATUS_FAIL,'账户不存在');
            throw new BusinessException('用户名或密码错误');
        }
        if ($user->status == SystemUser::STATUS_DISABLE) {
            $this->loginLog($data['username'],LoginLog::STATUS_FAIL,'账户已禁用');
            throw new BusinessException('用户已禁用');
        }
        if (!Hash::check($data['password'], $user->password)) {
            $this->loginLog($data['username'],LoginLog::STATUS_FAIL,'密码错误');
            throw new BusinessException('用户名或密码错误');
        }
        $token = auth('admin')->login($user);
        if (!$token) {
            $this->loginLog($data['username'],LoginLog::STATUS_FAIL,'Token获取失败');
            throw new BusinessException('登录失败,请稍后重试');
        }
        $res = $user->update([
            'login_ip' => get_client_ip(),
            'login_time' => Carbon::now(),
        ]);
        if (!$res) {
            $this->loginLog($data['username'],LoginLog::STATUS_FAIL,'更新登录信息失败');
            throw new BusinessException('登录失败,请稍后重试');
        }
        $this->loginLog($data['username'],LoginLog::STATUS_SUCCESS,'登录成功');
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
        $user = $this->loginUser(['id','username','nickname','avatar','avatar_url','email','phone','status','signed','dashboard','backend_setting','remark','login_ip','login_time','created_at']);
        $menus = $this->menuService->getUserMenus($user['id']);
        $roles = $this->roleService->userRoleCodes($user['id']);
        $menuCodes = $this->menuService->getUserMenuCodes($user['id']);
        $data = [
            'codes' => $menuCodes,
            'roles' => $roles,
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
