<?php

use App\Enums\UserTypesEnum;
use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->nullable();
            $table->string('avatar', 50)->nullable();
            $table->string('cover', 50)->nullable();
            $table->string('country_code', 5)->default('966');
            $table->string('phone', 15);
            $table->string('email', 50)->nullable();
            $table->foreignIdFor(Country::class)->nullable();
            $table->foreignIdFor(City::class)->nullable();
            $table->string('password', 100);
            $table->string('lang', 2)->default('ar');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_blocked')->default(false);
            $table->boolean('is_notify')->default(true);
            $table->unsignedTinyInteger('type')->default(UserTypesEnum::INDIVIDUAL->value);
            $table->string('code', 10)->nullable();
            $table->timestamp('code_expire')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
