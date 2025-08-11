<?php

declare(strict_types=1);

namespace App\Model\Tool;

use App\Model\Model;

/**
 * @property int $id 
 * @property string $name 
 * @property string $rule 
 * @property string $type 
 * @property string $target 
 * @property int $status 
 * @property int $is_on_one_server 
 * @property int $is_singleton 
 * @property string $remark 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Crontab extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'crontab';

    /**
     * The attributes that are mass assignable.
     */
    protected array $guarded = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'status' => 'integer', 'is_on_one_server' => 'integer', 'is_singleton' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
