<?php

namespace App\Models\PublicSections;

use App\Models\Core\BaseModel;

class ComplaintReplay extends BaseModel
{

    protected $fillable = ['replay','replayer_id','replayer_type' , 'complaint_id'];

    public function replayer()
    {
        return $this->morphTo();
    }

    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id', 'id');
    }
}
