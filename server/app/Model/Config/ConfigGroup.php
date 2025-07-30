<?php

declare(strict_types=1);

namespace App\Model\Config;

use App\Model\Model;

/**
 * @property int $id 
 * @property string $name 
 * @property string $code 
 * @property string $remark 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class ConfigGroup extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'config_group';

    /**
     * The attributes that are mass assignable.
     */
    protected array $guarded = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
