<?php

declare(strict_types=1);

namespace App\Model\Data;

use App\Model\Model;
use Hyperf\Database\Model\SoftDeletes;

/**
 * @property int $id 
 * @property int $type_id 
 * @property string $label 
 * @property string $value 
 * @property string $color 
 * @property string $code 
 * @property int $sort 
 * @property int $status 
 * @property string $remark 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class DictData extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'dict_data';

    /**
     * The attributes that are mass assignable.
     */
    protected array $guarded = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'type_id' => 'integer', 'sort' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
