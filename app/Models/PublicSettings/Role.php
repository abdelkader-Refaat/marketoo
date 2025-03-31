<?php

namespace App\Models\PublicSettings;

use App\Models\Core\BaseModel;
use Modules\Admins\App\Models\Admin;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends BaseModel
{
    use SoftDeletes;
    use HasTranslations;

    public $translatable = ['name'];
    protected $fillable = ['name'];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

}
