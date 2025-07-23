<?php

declare(strict_types=1);

namespace App\Model\Permission;

use App\Model\Model;
use Qbhy\HyperfAuth\AuthAbility;
use Qbhy\HyperfAuth\Authenticatable;

use function Hyperf\Support\env;

/**
 * @property int $id 
 * @property string $username 
 * @property string $password 
 * @property string $nickname 
 * @property string $avatar 
 * @property string $email 
 * @property string $phone 
 * @property string $status 
 * @property string $signed 
 * @property string $dashboard 
 * @property string $backend_setting 
 * @property string $remark 
 * @property string $login_ip 
 * @property string $login_time 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class SystemUser extends Model implements Authenticatable
{
    use AuthAbility;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'system_user';

    /**
     * The attributes that are mass assignable.
     */
    protected array $guarded = [];

    public array $hidden = ['password'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'backend_setting' => 'array', 'status' => 'integer', 'login_time' => 'datetime', 'deleted_at' => 'datetime'];

    const STATUS_NORMAL = 1;
    const STATUS_DISABLE = 2;

    const DEFAULT_DASHBOARD = 'statistics';
    const DEFAULT_AVATAR = '/images/avatar.png';

    const SUPER_ADMIN_ID = 1;
    const SUPER_ADMIN_USERNAME = 'admin';

    public function getAvatarAttribute($value)
    {
        if(empty($value)){
            return null;
        }
        if(str_starts_with($value,'http')){
            return $value;
        }
        return env('APP_URL', 'http://localhost:9501').$value;
    }
}
