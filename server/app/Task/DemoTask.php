<?php

namespace App\Task;

use function FriendsOfHyperf\Helpers\logs;

class DemoTask
{
    public function execute()
    {
        logs()->info('DemoTask executed');
    }
}
