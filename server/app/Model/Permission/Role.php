<?php

declare(strict_types=1);

namespace App\Model\Permission;

use App\Model\Model;
use Hyperf\Database\Model\SoftDeletes;

/**
 * @property int $id 
 * @property string $name 
 * @property string $code 
 * @property int $status 
 * @property int $sort 
 * @property string $remark 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class Role extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'role';

    /**
     * The attributes that are mass assignable.
     */
    protected array $guarded = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'status' => 'integer', 'sort' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'role_menu', 'role_id', 'menu_id');
    }
}
