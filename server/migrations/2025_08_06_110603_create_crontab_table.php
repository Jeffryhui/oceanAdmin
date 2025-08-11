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
        Schema::create('crontab', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('任务名称')->unique();
            $table->string('rule')->comment('执行规则');
            $table->string('type')->nullable()->comment('任务类型');
            $table->string('target')->nullable()->comment('执行目标');
            $table->unsignedTinyInteger('status')->default(0)->comment('1->执行成功,2->执行失败');
            $table->unsignedTinyInteger('is_on_one_server')->default(0)->comment('1->只在一台服务器执行,0->所有服务器执行');
            $table->unsignedTinyInteger('is_singleton')->default(0)->comment('1->单例执行,0->多实例执行');
            $table->string('remark')->nullable()->comment('备注');
            $table->datetimes();
            $table->index('status');
            $table->comment('定时任务表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crontab');
    }
};
