<?php

namespace App\Service\Config;

use App\Model\Config\Config;
use App\Service\BaseService;
use App\Service\IService;

class ConfigService extends BaseService implements IService
{
    public function __construct(Config $model)
    {
        $this->model = $model;
    }
}
