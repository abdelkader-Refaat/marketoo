<?php

namespace App\Http\Requests\Admin\Core\Wallet;

use App\Enums\WalletTransactionEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBalanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type'      => ['required', 'in:'. WalletTransactionEnum::DEBT .','. WalletTransactionEnum::CHARGE],
            'balance'   => ['required', 'numeric', 'gt:0'],
        ];
    }

    public function prepareForValidation()
    {
        $this->id = (int) $this->id;
        $this->merge([
            'id' => isset($this->id) && is_numeric($this->id) ? $this->id : null,
        ]);
    }
}
