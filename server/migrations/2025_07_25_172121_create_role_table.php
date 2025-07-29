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
        Schema::create('role', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('角色名称');
            $table->string('code')->comment('角色编码')->unique();
            $table->unsignedTinyInteger('status')->default(1)->comment('状态 1:启用 2:禁用');
            $table->unsignedInteger('sort')->default(0)->comment('排序');
            $table->string('remark')->nullable()->comment('备注');
            $table->datetimes();
            $table->softDeletes();
            $table->comment('角色表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role');
    }
};
