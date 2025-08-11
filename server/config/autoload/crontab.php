<?php

use App\Task\ExampleTask;
use Hyperf\Crontab\Crontab;

return [
    'enable' => true,
    // 'crontab' => [
    //     (new Crontab())->setName('example_task')
    //         ->setRule('*/5 * * * * *')
    //         ->setCallback([ExampleTask::class,'execute'])
    //         ->setMemo('Example Task'),
    // ]
];