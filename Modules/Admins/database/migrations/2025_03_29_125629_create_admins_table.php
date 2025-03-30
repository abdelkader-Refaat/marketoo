<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['admin', 'super_admin'])->default('admin');
            $table->string('name');
            $table->string('avatar', 50)->nullable();
            $table->string('email')->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->integer('role_id')->nullable();
            $table->boolean('is_blocked')->default(0);
            $table->boolean('is_notify')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
