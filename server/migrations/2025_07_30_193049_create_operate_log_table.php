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
        Schema::create('operate_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username',255)->nullable()->comment('用户名')->index();
            $table->string('method',255)->nullable()->comment('请求方法');
            $table->string('router',255)->nullable()->comment('请求路由');
            $table->string('service_name')->nullable()->comment('业务名称');
            $table->string('ip',255)->nullable()->comment('IP地址')->index();
            $table->string('ip_location',255)->nullable()->comment('IP地址位置');
            $table->text('request_data')->nullable()->comment('请求数据');
            $table->text('remark')->nullable()->comment('备注');
            $table->datetimes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operate_log');
    }
};
