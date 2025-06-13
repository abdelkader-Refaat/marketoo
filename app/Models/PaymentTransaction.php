<?php

namespace App\Models;

use App\Enums\PayStatusEnum;
use App\Enums\PayTypeEnum;
use App\Models\Core\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PaymentTransaction extends BaseModel
{
    use HasUuids;

    protected $fillable = [
        'id',
        'payment_gateway',
        'gateway_transaction_id',
        'gateway_reference',
        'amount',
        'currency_code',
        'status',
        'payer_type',
        'payer_id',
        'payable_type',
        'payable_id',
        'payment_method',
        'card_last_four',
        'card_brand',
        'paid_at',
        'gateway_response',
        'gateway_callback',
    ];

    /**
     * Get the payer model (polymorphic relationship)
     */
    public function payer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the payable model (polymorphic relationship)
     */
    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->whereStatus(PayStatusEnum::PENDING->value);
    }

    /**
     * Scope for paid payments
     */
    public function scopePaid($query)
    {
        return $query->where('status', PayStatusEnum::PAID->value);
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === PayStatusEnum::PENDING;
    }

    /**
     * Check if payment is paid
     */
    public function isPaid(): bool
    {
        return $this->status === PayStatusEnum::PAID;
    }

    /**
     * Mark payment as paid
     */
    public function markAsPaid(array $gatewayData = null): self
    {
        $this->update([
            'status' => PayStatusEnum::PAID,
            'paid_at' => now(),
            'gateway_callback' => $gatewayData,
        ]);

        return $this;
    }

    /**
     * Mark payment as unpaid
     */
    public function markAsUnpaid(array $gatewayData = null): self
    {
        $this->update([
            'status' => PayStatusEnum::UNPAID,
            'gateway_callback' => $gatewayData,
        ]);

        return $this;
    }

    /**
     * Get payment method as enum
     */
    public function getPaymentMethod(): PayTypeEnum
    {
        return PayTypeEnum::from($this->payment_method);
    }

    /**
     * Set payment method from enum
     */
    public function setPaymentMethod(PayTypeEnum $method): void
    {
        $this->payment_method = $method->value;
    }

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'gateway_response' => 'json',
            'gateway_callback' => 'json',
            'status' => PayStatusEnum::class,
            'payment_method' => PayTypeEnum::class,
        ];
    }
}
