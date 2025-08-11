<?php

declare(strict_types=1);

namespace App\Model\Tool;

use App\Model\Model;

/**
 * @property int $id 
 * @property int $crontab_id 
 * @property string $name 
 * @property int $status 
 * @property string $target 
 * @property string $exception 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class CrontabLog extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'crontab_log';

    /**
     * The attributes that are mass assignable.
     */
    protected array $guarded = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'crontab_id' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
