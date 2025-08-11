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
        Schema::create('crontab_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('crontab_id')->comment('任务ID');
            $table->string('name')->comment('任务名称');
            $table->unsignedTinyInteger('status')->default(0)->comment('1->执行成功,2->执行失败');
            $table->string('target')->nullable()->comment('执行目标');
            $table->text('exception')->nullable()->comment('异常信息');
            $table->datetimes();
            $table->index('crontab_id');
            $table->index('status');
            $table->comment('定时任务日志表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crontab_log');
    }
};
