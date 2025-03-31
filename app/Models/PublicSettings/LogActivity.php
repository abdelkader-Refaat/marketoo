<?php

namespace App\Models\PublicSettings;

use App\Models\Core\BaseModel;
use Modules\Admins\App\Models\Admin;

class LogActivity extends BaseModel
{
    protected $fillable = [
        'subject',
        'url',
        'method',
        'ip',
        'agent',
        'admin_id',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

}
