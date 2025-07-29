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
        Schema::create('menu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->default(0)->index()->comment('父级ID');
            $table->string('name')->comment('菜单名称');
            $table->string('code')->comment('菜单编码')->unique();
            $table->string('icon')->nullable()->comment('菜单图标');
            $table->string('route')->nullable()->comment('路由');
            $table->string('component')->nullable()->comment('组件');
            $table->string('redirect')->nullable()->comment('跳转地址');
            $table->unsignedTinyInteger('is_hidden')->default(1)->comment('1是,2否');
            $table->unsignedTinyInteger('is_layout')->default(1)->comment('1是,2否');
            $table->char('type',1)->comment('类型,M:菜单,B:按钮,L:链接,I:iframe');
            $table->unsignedInteger('generate_id')->default(0)->comment('生成ID');
            $table->string('generate_key')->nullable()->comment('生成KEY');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态 1:启用 2:禁用')->index();
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->string('remark')->nullable()->comment('备注');
            $table->datetimes();
            $table->softDeletes();
            $table->comment('菜单表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
