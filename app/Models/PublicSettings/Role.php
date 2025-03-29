<?php

namespace App\Models\PublicSettings;

use App\Models\AllUsers\Admin;
use App\Models\Core\BaseModel;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends BaseModel
{
    use SoftDeletes;
    use HasTranslations;

    protected $fillable = ['name'];

    public $translatable = ['name'];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

}
