<?php

namespace App\Controller\Data;

use App\Annotation\Permission;
use App\Controller\CrudController;
use App\Model\Data\Attachment;
use App\Service\Data\AttachmentService;
use App\Utils\Response;
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
    #[Permission("data:attachment:list", "附件列表", false, true)]
    #[Auth('admin')]
    public function index()
    {
        return parent::index();
    }

    #[DeleteMapping(path: "{id}")]
    #[Permission("data:attachment:delete", "删除附件", false, true)]
    #[Auth('admin')]
    public function delete(int $id)
    {
        return parent::delete($id);
    }

    #[PostMapping(path: "batch-delete")]
    #[Permission("data:attachment:batch-delete", "批量删除附件", false, true)]
    #[Auth('admin')]
    public function batchDelete()
    {
        return parent::batchDelete();
    }

    #[GetMapping(path: "download")]
    #[Permission("data:attachment:download", "下载附件", false, true)]
    #[Auth('admin')]
    public function downloadById()
    {
        $id = $this->request->input('id');
        /**
         * @var Attachment $attachment
         */
        $attachment = Attachment::find($id);
        if(empty($attachment)){
            return Response::error('附件不存在',404,404);
        }
        $storagePath = $attachment->storage_path;
        return $this->response->download(BASE_PATH.'/public/'.$storagePath,$attachment->object_name);
    }
}
