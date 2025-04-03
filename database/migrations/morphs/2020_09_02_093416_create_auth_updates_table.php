<?php

use App\Enums\AuthUpdatesAttributesEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_updates', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->nullable()->default(AuthUpdatesAttributesEnum::Phone->value);
            $table->string('attribute')->nullable();
            $table->string('country_code')->nullable();
            $table->string('code')->nullable();
            $table->morphs('updatable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_updates');
    }
}
