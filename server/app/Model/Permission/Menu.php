<?php

declare(strict_types=1);

namespace App\Model\Permission;

use App\Model\Model;

/**
 * @property int $id 
 * @property int $parent_id 
 * @property string $name 
 * @property string $code 
 * @property string $icon 
 * @property string $route 
 * @property string $component 
 * @property string $redirect 
 * @property int $is_hidden 
 * @property int $is_layout 
 * @property string $type 
 * @property int $generate_id 
 * @property string $generate_key 
 * @property int $status 
 * @property int $sort 
 * @property string $remark 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class Menu extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'menu';

    /**
     * The attributes that are mass assignable.
     */
    protected array $guarded = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'parent_id' => 'integer', 'is_hidden' => 'integer', 'is_layout' => 'integer', 'generate_id' => 'integer', 'status' => 'integer', 'sort' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
