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
        Schema::create('dict_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->comment('字典名称');
            $table->string('code', 255)->comment('字典编码');
            $table->string('remark', 255)->comment('备注');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态,1->正常，2->禁用');
            $table->datetimes();
            $table->softDeletes();
            $table->index('code');
            $table->index('status');
            $table->comment('字典类型');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dict_type');
    }
};
