<?php

declare(strict_types=1);

namespace App\Model\Monitor;

use App\Model\Model;

/**
 * @property int $id 
 * @property string $username 
 * @property string $ip 
 * @property string $os 
 * @property string $ip_location 
 * @property string $browser 
 * @property int $status 
 * @property string $message 
 * @property string $remark 
 * @property string $login_time 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class LoginLog extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'login_log';

    /**
     * The attributes that are mass assignable.
     */
    protected array $guarded = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    const STATUS_SUCCESS = 1;
    const STATUS_FAIL = 2;
}
