<?php

declare(strict_types=1);

namespace App\Model\Data;

use App\Model\Model;

/**
 * @property int $id 
 * @property int $storage_mode 
 * @property string $origin_name 
 * @property string $object_name 
 * @property string $hash 
 * @property string $mime_type 
 * @property string $storage_path 
 * @property string $suffix 
 * @property int $size_byte 
 * @property string $size_info 
 * @property string $url 
 * @property string $remark 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Attachment extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'attachment';

    /**
     * The attributes that are mass assignable.
     */
    protected array $guarded = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'storage_mode' => 'integer', 'size_byte' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
