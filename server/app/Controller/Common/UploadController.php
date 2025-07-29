<?php

namespace App\Controller\Common;

use App\Annotation\Permission;
use App\Controller\Controller as BaseController;
use App\Service\Common\UploadService;
use App\Utils\Response;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Request;
use Qbhy\HyperfAuth\Annotation\Auth;

#[Controller('/api/admin/common')]
class UploadController extends BaseController
{
    public function __construct(
        private UploadService $uploadService
    ){}

    #[PostMapping('upload-image')]
    #[Permission('common:uploadImage', '上传图片', true, true)]
    #[Auth('admin')]
    public function uploadImg(Request $request)
    {
        $file = $request->file('image');
        if(empty($file)){
            return Response::error('上传图片不能为空');
        }
        $result = $this->uploadService->uploadImage($file, 'image');
        return Response::success($result);
    }
}
