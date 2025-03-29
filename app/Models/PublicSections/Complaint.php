<?php

namespace App\Models\PublicSections;


use App\Models\Core\BaseModel;
use App\Enums\ComplaintTypesEnum;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Complaint extends BaseModel
{

    protected $fillable = [
        'user_name', 'complaintable_id', 'complaintable_type', 'complaint', 'phone', 'email', 'subject', 'type'
    ];

    public function complaintable(): MorphTo
    {
        // users , providers , employees
        return $this->morphTo();
    }

    public function replays(): HasMany
    {
        return $this->hasMany(ComplaintReplay::class, 'complaint_id', 'id');
    }

    public function getTypeAttribute($val)
    {
        return $val == ComplaintTypesEnum::Complaint->value ? __('admin.complaint') : __('admin.enquiry');
    }
}
