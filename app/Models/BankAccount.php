<?php

namespace App\Models;

use App\Models\Core\BaseModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankAccount extends BaseModel
{
    use HasFactory;

    const IMAGEPATH = "bank_accounts";

    protected $fillable = [
        'bank_name',
        'account_number',
        'account_name',
        'bankable_type',
        'bankable_id',
        'iban',
        'bank_account_image'
    ];

    //Getters & Setters
    public function getBankAccountImageAttribute(): ?string
    {
        if ($this->attributes['bank_account_image']) {
            $image = $this->getImage($this->attributes['bank_account_image'], self::IMAGEPATH);
        } else {
            $image = $this->defaultImage(self::IMAGEPATH);
        }
        return $image;
    }

    public function setBankAccountImageAttribute($value): void
    {
        if (null != $value && is_file($value)) {
            isset($this->attributes['bank_account_image']) ? $this->deleteFile($this->attributes['bank_account_image'],
                self::IMAGEPATH) : '';
            $this->attributes['bank_account_image'] = $this->uploadAllTypes($value, self::IMAGEPATH);
        }
    }


    public function bankable(): MorphTo
    {
        return $this->morphTo();
    }
}
