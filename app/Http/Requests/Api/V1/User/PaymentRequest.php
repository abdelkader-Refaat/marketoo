<?php

namespace App\Http\Requests\Api\V1\User;

use app\Http\Requests\Api\V1\BaseApiRequest;
use App\Enums\MyFatoorahPaymentMethod;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class PaymentRequest extends BaseApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.1'],
            'currency' => ['sometimes', 'string', 'size:3'],
            'payment_method_id' => [
                Rule::requiredIf(config('payments.active_gateway') === 'Myfatoorah'),
                new Enum(MyFatoorahPaymentMethod::class)
            ],
            // Enum validation
            'name' => [
                'required_if:payment_method_id,'.implode(',', $this->nameRequiredMethods()), 'string', 'max:255',
                'min:3'
            ],
            'email' => ['required_if:payment_method_id,'.implode(',', $this->nameRequiredMethods()), 'email:rfc,dns']
        ];
    }

    /**
     * Get the payment methods that require name and email.
     */
    private function nameRequiredMethods(): array
    {
        return [
            MyFatoorahPaymentMethod::VISA_MASTERCARD->value,
            MyFatoorahPaymentMethod::APPLE_PAY->value,
        ];
    }
}
