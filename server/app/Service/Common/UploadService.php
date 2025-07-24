<?php

namespace App\Service\Common;

use App\Exception\BusinessException;
use App\Model\Data\Attachment;
use Hyperf\Filesystem\FilesystemFactory;
use Hyperf\HttpMessage\Upload\UploadedFile;

use function FriendsOfHyperf\Helpers\logs;
use function Hyperf\Support\env;

class UploadService
{

    public function __construct(private FilesystemFactory $factory)
    {
    }

    public function formatBytes($bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
    public function uploadImage(UploadedFile $file, string $dir): array
    {

        $storageMode = 1; // todo 此处到时候做成后台配置
        $extension = $file->getExtension();
        $originName = $file->getClientFilename();
        $uniqueID = hash_file('md5', $file->getRealPath());
        // 如果图片已经上传过，直接返回上传的路径
        $dbImage = Attachment::where('hash',$uniqueID)->first();
        if(!empty($dbImage)){
            return $dbImage->setVisible(['hash','storage_mode','origin_name','object_name','mime_type','storage_path','suffix','size_byte','size_info','url'])->toArray();
        }
        $objectName = $uniqueID . '.' . $extension;
        switch ($storageMode) {
            case 1: // 本地
                $result = $this->uploadLocal($file, $dir, $objectName);
                break;
            default:
                throw new BusinessException('不支持的存储类型');
        }
        $insert = [
            'hash' => $uniqueID,
            'storage_mode' => $storageMode,
            'origin_name' => $originName,
            'object_name' => $objectName,
            'mime_type' => $file->getClientMediaType(),
            'storage_path' => $result['url'],
            'suffix' => $extension,
            'size_byte' => $file->getSize(),
            'size_info' => $this->formatBytes($file->getSize()),
            'url' => $result['full_url'] ?? ''
        ];
        $attachment = Attachment::create($insert);
        if(!$attachment){
            throw new BusinessException('上传图片失败');
        }
        return $insert;
    }

    public function uploadLocal(UploadedFile $file, string $dir, string $fileName)
    {
        try {
            $disk = $this->factory->get('local');
            $path = '/storage/upload/' . $dir . '/'.$fileName;
            $resource = fopen($file->getRealPath(), 'r+');
            $disk->writeStream($path, $resource);
            fclose($resource);
            return ['url' => $path,'full_url' => env('APP_URL').$path];
        } catch (\Throwable $th) {
            logs()->error('本地上传图片失败,error:'.$th->getMessage());
            throw new BusinessException('图片上传失败');
        }
    }
}
