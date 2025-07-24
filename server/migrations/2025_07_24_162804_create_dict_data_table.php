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
        Schema::create('dict_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('type_id')->comment('字典类型ID')->index();
            $table->string('label', 255)->comment('字典标签');
            $table->string('value', 255)->comment('字典值');
            $table->string('color', 100)->comment('颜色');
            $table->string('code')->comment('字典编码')->index();
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态,1->正常，2->禁用');
            $table->string('remark', 255)->nullable()->comment('备注');
            $table->datetimes();
            $table->softDeletes();
            $table->index('status');
            $table->comment('字典数据');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dict_data');
    }
};
