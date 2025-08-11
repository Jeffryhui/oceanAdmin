<?php

namespace App\Task;

use function FriendsOfHyperf\Helpers\logs;

class ExampleTask
{
    public function execute()
    {
        logs()->info('ExampleTask executed');
    }
}
