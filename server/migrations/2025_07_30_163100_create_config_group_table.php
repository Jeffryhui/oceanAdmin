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
        Schema::create('config_group', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable()->comment('配置组名称');
            $table->string('code')->nullable()->comment('配置组编码')->unique();
            $table->string('remark')->nullable()->comment('配置组备注');
            $table->datetimes();
            $table->comment('配置组表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config_group');
    }
};
