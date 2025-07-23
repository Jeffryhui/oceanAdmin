<?php

namespace App\EsModel;

use Janartist\Elasticsearch\Model;

class LoginLog extends Model
{
    protected $index = 'login_log';

    protected $casts = [
        'username' => [
            'type' => 'text'
        ],
        'ip' => [
            'type' => 'text'
        ],
        'ip_location' => [
            'type' => 'text'
        ],
        'browser' => [
            'type' => 'text'
        ],
        'os' => [
            'type' => 'text'
        ],
        'status' => [
            'type' => 'integer'
        ],
        'message' => [
            'type' => 'text'
        ],
        'login_time' => [
            'type' => 'date',
            "format" => "yyyy-MM-dd HH:mm:ss||strict_date_optional_time||epoch_millis"
        ],
    ];

    const STATUS_SUCCESS = 1;
    const STATUS_FAIL = 2;
}
