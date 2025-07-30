<?php

declare(strict_types=1);

namespace App\Model\Monitor;

use App\Model\Model;

/**
 * @property int $id 
 * @property string $username 
 * @property string $method 
 * @property string $router 
 * @property string $service_name 
 * @property string $ip 
 * @property string $ip_location 
 * @property string $request_data 
 * @property string $remark 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class OperateLog extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'operate_log';

    /**
     * The attributes that are mass assignable.
     */
    protected array $guarded = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime','request_data' => 'array'];
}
