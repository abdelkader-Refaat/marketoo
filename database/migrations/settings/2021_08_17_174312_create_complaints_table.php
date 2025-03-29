<?php

use App\Enums\ComplaintTypesEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{

    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('user_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->longText('complaint')->nullable();
            $table->text('subject')->nullable();
            $table->tinyInteger('type')->default(ComplaintTypesEnum::Complaint->value);
            $table->nullableMorphs('complaintable');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('complaints');
    }
}
