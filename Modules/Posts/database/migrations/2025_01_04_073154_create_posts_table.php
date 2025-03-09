<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Posts\Enums\PostPrivacyEnum;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->string('slug')->unique();
            $table->enum('privacy', array_column(PostPrivacyEnum::cases(), 'value'))
                ->default(PostPrivacyEnum::Public ->value);
            $table->boolean('is_promoted')->default(false);

            // type => event
            $table->string('event_name', 50)->nullable();
            $table->dateTime('event_date_time')->nullable();
            $table->text('event_description')->nullable();

            // type => celebration

            $table->foreignId('repost_id')->nullable()->index()->constrained('posts')->cascadeOnDelete();
            $table->text('repost_text')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
