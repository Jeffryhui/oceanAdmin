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
        Schema::create('system_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username')->comment('用户名')->unique();
            $table->string('password')->comment('密码');
            $table->string('nickname')->comment('昵称');
            $table->string('avatar')->nullable()->comment('头像');
            $table->string('email')->nullable()->comment('邮箱')->unique();
            $table->string('phone')->nullable()->comment('手机号');
            $table->string('status')->default(1)->comment('状态,1->正常,2->禁用');
            $table->string('signed')->nullable()->comment('签名');
            $table->string('dashboard')->nullable()->comment('后台首页类型');
            $table->json('backend_setting')->nullable()->comment('后台设置');
            $table->string('remark')->nullable()->comment('备注');
            $table->string('login_ip')->nullable()->comment('登录IP');
            $table->dateTime('login_time')->nullable()->comment('登录时间');
            $table->datetimes();
            $table->softDeletes();
            $table->comment('系统用户');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_user');
    }
};
