<?php

namespace App\Traits\Provider;

trait SetterGetterTrait
{
    public function setCommercialRegisterFileAttribute($value)
    {
        if (null != $value && is_file($value)) {
            isset($this->attributes['commercial_register_file']) ? $this->deleteFile($this->attributes['commercial_register_file'], static::IMAGEPATH) : '';
            $this->attributes['commercial_register_file'] = $this->uploadAllTyps($value, static::IMAGEPATH);
        }
    }

    public function getCommercialRegisterFileAttribute()
    {
        if ($this->attributes['commercial_register_file']) {
            $image = $this->getImage($this->attributes['commercial_register_file'], static::IMAGEPATH);
        } else {
            $image = null;
        }
        return $image;
    }

    public function setCertificatesFileAttribute($value)
    {
        if (null != $value && is_file($value)) {
            isset($this->attributes['certificates_file']) ? $this->deleteFile($this->attributes['certificates_file'], static::IMAGEPATH) : '';
            $this->attributes['certificates_file'] = $this->uploadAllTyps($value, static::IMAGEPATH);
        }
    }

    public function getCertificatesFileAttribute()
    {
        if ($this->attributes['certificates_file']) {
            $image = $this->getImage($this->attributes['certificates_file'], static::IMAGEPATH);
        } else {
            $image = null;
        }
        return $image;
    }

    public function setAddressFileAttribute($value)
    {
        if (null != $value && is_file($value)) {
            isset($this->attributes['address_file']) ? $this->deleteFile($this->attributes['address_file'], static::IMAGEPATH) : '';
            $this->attributes['address_file'] = $this->uploadAllTyps($value, static::IMAGEPATH);
        }
    }
    public function getAddressFileAttribute()
    {
        if ($this->attributes['address_file']) {
            $image = $this->getImage($this->attributes['address_file'], static::IMAGEPATH);
        } else {
            $image = null;
        }
        return $image;
    }
}
