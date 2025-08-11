<?php

declare(strict_types=1);

namespace App\Aspect;

use Hyperf\Crontab\Strategy\Executor;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Psr\Container\ContainerInterface;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use App\Cron\Crontab;
use App\Model\Tool\CrontabLog;

#[Aspect]
class CrontabExecutorAspect extends AbstractAspect
{
   

    public array $classes = [
        Executor::class . '::logResult',
    ];
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        /**
         * @var Crontab $crontab
         * @var bool $isSuccess
         * @var null|\Throwable $throwable
         */
        [$crontab, $isSuccess, $throwable] = $proceedingJoinPoint->getArguments();
        if($crontab instanceof Crontab){
            $callback = $crontab->getCallback();
            if (is_array($callback)) {
                $callback = json_encode($callback, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
            }
            CrontabLog::create([
                'crontab_id' => $crontab->getCronId(),
                'name' => $crontab->getName(),
                'target' => $callback,
                'status' => $isSuccess ? 1 : 2,
                'exception' => $throwable ? $throwable->getMessage() : null,
            ]);
        }
        return $proceedingJoinPoint->process();
    }
}
