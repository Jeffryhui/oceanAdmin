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

    /**
     * 菜单与角色的多对多关联
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_menu', 'menu_id', 'role_id');
    }

    /**
     * 获取所有子菜单ID（包括自己）
     */
    public function getAllChildrenIds(): array
    {
        $ids = [$this->id];
        $children = self::where('parent_id', $this->id)->get();
        foreach ($children as $child) {
            $ids = array_merge($ids, $child->getAllChildrenIds());
        }
        return $ids;
    }
}
