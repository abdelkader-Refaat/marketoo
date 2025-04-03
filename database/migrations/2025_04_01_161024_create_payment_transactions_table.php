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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Payment gateway information
            $table->string('payment_gateway');
            $table->string('gateway_transaction_id')->nullable();
            $table->string('gateway_reference')->nullable();
            // Transaction details
            $table->decimal('amount', 10, 2);
            $table->string('currency_code', 3)->default('SAR');
            $table->unsignedTinyInteger('status')->default(0);
            // Polymorphic relationship to payer (user, company, etc.)
            $table->morphs('payer');
            // Polymorphic relationship to what's being paid for
            $table->morphs('payable');
            // Payment method using your PayTypeEnum
            $table->unsignedTinyInteger('payment_method')->default(0);
            // Payment method details (for online payments)
            $table->string('card_last_four', 4)->nullable();
            $table->string('card_brand')->nullable();
            // Timestamps
            $table->timestamp('paid_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            // Additional gateway response data
            $table->json('gateway_response')->nullable();
            $table->json('gateway_callback')->nullable();
            // Indexes for performance
            $table->index('gateway_transaction_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
