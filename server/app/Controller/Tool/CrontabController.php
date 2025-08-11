<?php

namespace App\Controller\Tool;

use App\Annotation\Permission;
use App\Controller\CrudController;
use App\Exception\BusinessException;
use App\Request\Tool\CrontabRequest;
use App\Service\Tool\CrontabService;
use App\Utils\Response;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Qbhy\HyperfAuth\Annotation\Auth;

#[Controller('/api/admin/crontab')]
class CrontabController extends CrudController
{
    public function __construct(CrontabService $service)
    {
        $this->service = $service;
        $this->validator = $this->container->get(CrontabRequest::class);
    }

    #[GetMapping('list')]
    #[Permission('tool:crontab:list','定时任务列表',false,true)]
    #[Auth('admin')]
    public function index() {
        return parent::index();
    }

    #[PostMapping('store')]
    #[Permission('tool:crontab:store','创建定时任务',false,true)]
    #[Auth('admin')]
    public function store() {
        $data = $this->validator->validated();
        $second = $data['second'];
        $minute = $data['minute'];
        $hour = $data['hour'];
        $week = $data['week'];
        $day = $data['day'];
        $month = $data['month'];

        // 规则处理
        $rule = match ($data['task_style']) {
            1 => "0 {$minute} {$hour} * * *",
            2 => "0 {$minute} * * * *",
            3 => "0 {$minute} */{$hour} * * *",
            4 => "0 */{$minute} * * * *",
            5 => "*/{$second} * * * * *",
            6 => "0 {$minute} {$hour} * * {$week}",
            7 => "0 {$minute} {$hour} {$day} * *",
            8 => "0 {$minute} {$hour} {$day} {$month} *",
            default => throw new BusinessException("任务类型异常"),
        };
        $insert = [
            'name' => $data['name'],
            'type' => $data['type'],
            'target' => $data['target'],
            'status' => $data['status'],
            'is_on_one_server' => $data['is_on_one_server'],
            'is_singleton' => $data['is_singleton'],
            'remark' => $data['remark'] ?? '',
            'rule' => $rule,
            'task_style' => $data['task_style']
        ];
        $result = $this->service->create($insert);
        if(!$result){
            return Response::error('创建定时任务失败');
        }
        return Response::success(null);
    }

    #[PutMapping('{id}')]
    #[Permission('tool:crontab:update','更新定时任务',false,true)]
    #[Auth('admin')]
    public function update(int $id)
    {
        $data = $this->validator->validated();
        $second = $data['second'];
        $minute = $data['minute'];
        $hour = $data['hour'];
        $week = $data['week'];
        $day = $data['day'];
        $month = $data['month'];

        // 规则处理
        $rule = match ($data['task_style']) {
            1 => "0 {$minute} {$hour} * * *",
            2 => "0 {$minute} * * * *",
            3 => "0 {$minute} */{$hour} * * *",
            4 => "0 */{$minute} * * * *",
            5 => "*/{$second} * * * * *",
            6 => "0 {$minute} {$hour} * * {$week}",
            7 => "0 {$minute} {$hour} {$day} * *",
            8 => "0 {$minute} {$hour} {$day} {$month} *",
            default => throw new BusinessException("任务类型异常"),
        };
        $insert = [
            'name' => $data['name'],
            'type' => $data['type'],
            'target' => $data['target'],
            'status' => $data['status'],
            'is_on_one_server' => $data['is_on_one_server'],
            'is_singleton' => $data['is_singleton'],
            'remark' => $data['remark'] ?? '',
            'rule' => $rule,
            'task_style' => $data['task_style']
        ];
        $result = $this->service->update($id, $insert);
        if(!$result){
            return Response::error('更新定时任务失败'); 
        }
        return Response::success(null);
    }
    
    #[DeleteMapping('{id}')]
    #[Permission('tool:crontab:delete','删除定时任务',false,true)]
    #[Auth('admin')]
    public function delete(int $id)
    {
        return parent::delete($id);
    }

    #[PostMapping('batch-delete')]
    #[Permission('tool:crontab:batch-delete','批量删除定时任务',false,true)]
    #[Auth('admin')]
    public function batchDelete()
    {
        return parent::batchDelete();
    }

    #[PostMapping('change-status')]
    #[Permission('tool:crontab:change-status','修改定时任务状态',false,true)]
    #[Auth('admin')]
    public function changeStatus()
    {
        return parent::changeStatus();
    }
}
