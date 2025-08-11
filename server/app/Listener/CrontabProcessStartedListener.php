<?php

declare(strict_types=1);

namespace App\Listener;

use App\Cron\Crontab;
use App\Cron\Schedule;
use Hyperf\Context\Context;
use Hyperf\Coroutine\Coroutine;
use Hyperf\Crontab\CrontabManager;
use Hyperf\Crontab\Event\CrontabDispatcherStarted;
use Hyperf\Event\Annotation\Listener;
use Psr\Container\ContainerInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Process\ProcessManager;

use function FriendsOfHyperf\Helpers\logs;

#[Listener]
class CrontabProcessStartedListener implements ListenerInterface
{
    public static int $sleep = 30;
    public function __construct(
        protected readonly ContainerInterface $container,
        protected readonly CrontabManager $crontabManager,
        protected readonly Schedule $schedule
    ) {
    }

    public function listen(): array
    {
        return [
            CrontabDispatcherStarted::class
        ];
    }

    public function process(object $event): void
    {
        Coroutine::create(function () {
            while (ProcessManager::isRunning()) {
                $this->registerCrontab();
                sleep(self::$sleep);
            }
        });
    }

    public function registerCrontab()
    {
        // 获取当前所有状态为1（启用）的定时任务
        $crontabs = $this->schedule->getCrontab();
        // logs()->info('当前数据库中的定时任务：', ['count' => count($crontabs)]);
        
        // 获取所有已注册的任务
        // $registeredTasks = $this->crontabManager->getCrontabs();
        // logs()->info('当前已注册的定时任务：', ['count' => count($registeredTasks)]);
        
        // 当前有效的任务名称
        $currentTasks = [];
        
        /**
         * @var Crontab $crontab
         */
        foreach ($crontabs as $crontab) {
            $currentTasks[] = $crontab->getName();
            
            // 如果任务已经在Context中，说明已经注册过了
            if(Context::has($crontab->getName())) {
                continue;
            }
            
            $result = $this->crontabManager->register($crontab);
            if($result){
                Context::set($crontab->getName(),$crontab->getTarget());
                // logs()->info(sprintf("定时任务%s注册成功", $crontab->getName()));
            }else{
                logs()->error(sprintf("定时任务%s注册失败", $crontab->getName()));
            }
        }
        
        // 删除已经被禁用或删除的任务
        $reflection = new \ReflectionClass($this->crontabManager);
        $property = $reflection->getProperty('crontabs');
        $property->setAccessible(true);
        $currentCrontabs = $property->getValue($this->crontabManager);
        
        foreach ($currentCrontabs as $taskName => $task) {
            if (!in_array($taskName, $currentTasks)) {
                unset($currentCrontabs[$taskName]);
                if (Context::has($taskName)) {
                    Context::destroy($taskName);
                }
                // logs()->info(sprintf("定时任务%s已被移除", $taskName));
            }
        }
        
        // 更新CrontabManager中的任务列表
        $property->setValue($this->crontabManager, $currentCrontabs);
        
        // 打印当前注册的任务信息
        // $currentRegistered = $this->crontabManager->getCrontabs();
        // logs()->info('注册后的定时任务列表：', [
        //     'count' => count($currentRegistered),
        //     'tasks' => array_keys($currentRegistered),
        //     'currentTasks' => $currentTasks
        // ]);
    }
}
