<?php

declare(strict_types=1);

namespace App\Model\Config;

use App\Model\Model;

/**
 * @property int $id 
 * @property int $group_id 
 * @property string $name 
 * @property string $key 
 * @property string $value 
 * @property string $input_type 
 * @property string $config_select_data 
 * @property int $sort 
 * @property string $remark 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Config extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'config';

    /**
     * The attributes that are mass assignable.
     */
    protected array $guarded = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'group_id' => 'integer', 'sort' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime','config_select_data' => 'array'];
}
