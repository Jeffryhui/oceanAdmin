<?php

namespace App\EsModel;

use Janartist\Elasticsearch\Model;

class OperateLog extends Model
{
    protected $index = 'operate_log';

    protected $casts = [
        'username' => [
            'type' => 'text'
        ],
        'method' => [
            'type' => 'text'
        ],
        'router' => [
            'type' => 'text'
        ],
        'service_name' => [
            'type' => 'text'
        ],
        'ip' => [
            'type' => 'text'
        ],
        'ip_location' => [
            'type' => 'text'
        ],
        'request_data' => [
            'type' => 'object'
        ],
        'remark' => [
            'type' => 'text'
        ],
        'create_time' => [
            'type' => 'date',
            "format" => "yyyy-MM-dd HH:mm:ss||strict_date_optional_time||epoch_millis"
        ],
    ];
}
