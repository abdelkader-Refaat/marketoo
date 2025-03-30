<?php

namespace App\Models\PublicSettings;

use App\Models\Core\BaseModel;
use App\Traits\UploadTrait;

class Social extends BaseModel
{
    use UploadTrait;

    const IMAGEPATH = 'socials';
    protected $fillable = ['link', 'icon', 'name'];

    public function getIconAttribute()
    {
        if ($this->attributes['icon']) {
            $image = $this->getImage($this->attributes['icon'], 'socials');
        } else {
            $image = '';
        }
        return $image;
    }

    public function setIconAttribute($value)
    {
        if (null != $value && is_file($value)) {
            isset($this->attributes['icon']) ? $this->deleteFile($this->attributes['icon'], static::IMAGEPATH) : '';
            $this->attributes['icon'] = $this->uploadAllTypes($value, static::IMAGEPATH);
        }
    }

}
