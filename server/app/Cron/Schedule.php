<?php

namespace App\Cron;

use App\Cron\Crontab as CronCrontab;
use App\Model\Tool\Crontab;

class Schedule
{
    /**
     * @return Crontab[]
     */
    public function getCrontab(): array
    {
        $list = [];
        $crontabList = Crontab::where('status', 1)->get();
        if ($crontabList->count() === 0) {
            return [];
        }
        foreach ($crontabList as $crontab) {
            $list[] = new CronCrontab($crontab->id);
        }
        return $list;
    }
}
