<?php

namespace App\Controller\Data;

use App\Annotation\Permission;
use App\Controller\CrudController;
use App\Service\Data\AttachmentService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Qbhy\HyperfAuth\Annotation\Auth;

#[Controller(prefix: "/api/admin/attachment")]
class AttachmentController extends CrudController
{
    public function __construct(AttachmentService $service)
    {
        $this->service = $service;
    }


    #[GetMapping(path: "list")]
    #[Permission("attachment.list", "附件列表", false, true)]
    #[Auth('admin')]
    public function index()
    {
        return parent::index();
    }

    #[DeleteMapping(path: "{id}")]
    #[Permission("attachment.delete", "删除附件", false, true)]
    #[Auth('admin')]
    public function delete(int $id)
    {
        return parent::delete($id);
    }

    #[PostMapping(path: "batch-delete")]
    #[Permission("attachment.batch-delete", "批量删除附件", false, true)]
    #[Auth('admin')]
    public function batchDelete()
    {
        return parent::batchDelete();
    }
}
