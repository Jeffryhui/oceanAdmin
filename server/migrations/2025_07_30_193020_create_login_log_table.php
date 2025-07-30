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
        Schema::create('login_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username',255)->nullable()->comment('用户名')->index();
            $table->string('ip',255)->nullable()->comment('IP地址')->index();
            $table->string('os',255)->nullable()->comment('操作系统');
            $table->string('ip_location',255)->nullable()->comment('IP地址位置');
            $table->string('browser',255)->nullable()->comment('浏览器');
            $table->unsignedTinyInteger('status')->nullable()->comment('状态,1->登录成功,2->登录失败')->index();
            $table->string('message',255)->nullable()->comment('消息');
            $table->string('remark',255)->nullable()->comment('备注');
            $table->dateTime('login_time')->nullable()->comment('登录时间')->index();
            $table->datetimes();
            $table->comment('登录日志');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_log');
    }
};
