<?php

namespace App\Cron;

use App\Model\Tool\Crontab as ModelCrontab;
use Hyperf\Crontab\Crontab as BaseCrontab;
class Crontab extends BaseCrontab
{

    public const PRIMARY_KEY = 'id';

    public const NAME_COLUMN = 'name';

    public const RULE_COLUMN = 'rule';

    public const TYPE_COLUMN = 'type';
    public const TARGET_COLUMN = 'target';

    public const STATUS_COLUMN = 'status';

    public const IS_ON_ONE_SERVER_COLUMN = 'is_on_one_server';
    public const IS_SINGLETON_COLUMN = 'is_singleton';

    public const REMARK_COLUMN = 'remark';

    public function __construct(private readonly int $cronId)
    {
        $model = ModelCrontab::find($cronId);
        if ($model) {
            $this->name = $model->name;
            $this->rule = $model->rule;
            $this->callback = $model->target;
            $this->memo = $model->remark;
            $this->enable = true;
            $this->singleton = (bool)$model->is_singleton;
            $this->onOneServer = (bool)$model->is_on_one_server;
        }
    }

    public function __serialize(): array
    {
        $parent = parent::__serialize();
        $parent['\x00*\x00id'] = $this->getCronId();
        return $parent;
    }

    public function getCronId(): int
    {
        return $this->cronId;
    }

    public function __unserialize(array $data): void
    {
        $this->cronId = $data['\x00*\x00id'];
        parent::__unserialize($data);
    }

    public function getBuilder()
    {
        return ModelCrontab::query()->where(self::PRIMARY_KEY, $this->getCronId());
    }

    public function getName(): string|null
    {
        return $this->getBuilder()->value(self::NAME_COLUMN);
    }

    public function isEnable(): bool
    {
        $status = $this->getBuilder()->value(self::STATUS_COLUMN);
        return $status == 1;
    }

    public function getType(): string
    {
        $type = $this->getBuilder()->value(self::TYPE_COLUMN);
        return match ($type) {
            'url', 'class' => 'callback',
            default => $type
        };
    }

    public function getMemo(): string|null
    {
        return (string) $this->getBuilder()->value(self::REMARK_COLUMN);
    }

    public function getCallback(): mixed
    {
        $type = $this->getBuilder()->value(self::TYPE_COLUMN);
        $value = $this->getBuilder()->value(self::TARGET_COLUMN);
        switch ($type) {
            case 'eval':
                return $value;
            case 'url':
                return [
                    CrontabUrl::class,
                    'execute',
                    explode(',', $value),
                ];
            case 'class':
                return [$value, 'execute'];
            case 'command':
            case 'callback':
                return json_decode($value, true, 512, \JSON_THROW_ON_ERROR);
        }
        return $value;
    }

    public function getTarget()
    {
        return $this->getBuilder()->value(self::TARGET_COLUMN);
    }

    public function getRule(): ?string
    {
        return $this->getBuilder()->value(self::RULE_COLUMN);
    }

    public function isOnOneServer(): bool
    {
        return (bool) $this->getBuilder()->value(self::IS_ON_ONE_SERVER_COLUMN);
    }

    public function isSingleton(): bool
    {
        return (bool) $this->getBuilder()->value(self::IS_SINGLETON_COLUMN);
    }
}
