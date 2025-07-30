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
        Schema::create('config', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('group_id')->nullable()->comment('配置组ID')->index();
            $table->string('name',255)->nullable()->comment('配置名称');
            $table->string('key')->nullable()->comment('配置键');
            $table->text('value')->nullable()->comment('配置值');
            $table->string('input_type',50)->nullable()->comment('数据输入类型');
            $table->text('config_select_data')->nullable()->comment('配置选项数据');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->string('remark',255)->nullable()->comment('备注');
            $table->datetimes();
            $table->comment('参数配置信息表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config');
    }
};
