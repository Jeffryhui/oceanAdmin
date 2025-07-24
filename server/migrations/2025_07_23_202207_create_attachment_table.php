<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attachment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('storage_mode')->comment('存储类型,1->本地,2->七牛云,3->阿里云,4->腾讯云，5->S3,6->minio');
            $table->string('origin_name')->comment('原始文件名');
            $table->string('object_name')->comment('新文件名');
            $table->string('hash')->comment('文件hash')->unique();
            $table->string('mime_type')->comment('文件mime类型');
            $table->string('storage_path')->comment('存储路径');
            $table->string('suffix')->comment('文件后缀');
            $table->bigInteger('size_byte')->comment('字节数');
            $table->string('size_info')->comment('文件大小');
            $table->string('url')->comment('文件url');
            $table->string('remark')->nullable()->comment('备注');
            $table->datetimes();
            $table->index('storage_path');
            $table->index('storage_mode');
            $table->comment('附件表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachment');
    }
};
