<?php

namespace App\Models\Core;

use App\Traits\UploadTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use UploadTrait;

    public static function boot()
    {
        parent::boot();
        /* creating, created, updating, updated, deleting, deleted, forceDeleted, restored */

        static::deleted(function ($model) {
            if (isset($model->attributes['image'])) {
                $model->deleteFile($model->attributes['image'], static::IMAGEPATH);
            }
        });
    }

    public function scopeSearch($query, $searchArray = [])
    {
        $query->where(function ($query) use ($searchArray) {
            if ($searchArray) {
                foreach ($searchArray as $key => $value) {
                    if (str_contains($key, '_id')) {
                        if ($value != null) {
                            $query->Where($key, $value);
                        }
                    } elseif ($key == 'order') {
                    } elseif ($key == 'created_at_min') {
                        if ($value != null) {
                            $query->WhereDate('created_at', '>=', Carbon::createFromFormat('m-d-Y', $value));
                        }
                    } elseif ($key == 'created_at_max') {
                        if ($value != null) {
                            $query->WhereDate('created_at', '<=', Carbon::createFromFormat('m-d-Y', $value));
                        }
                    } else {
                        if ($value != null) {
                            $query->Where($key, 'like', '%'.$value.'%');
                        }
                    }
                }
            }
        });

        return $query->orderBy('created_at',
            request()->searchArray && request()->searchArray['order'] ? request()->searchArray['order'] : 'DESC');
    }

    public function getImageAttribute()
    {
        if ($this->attributes['image']) {
            $image = $this->getImage($this->attributes['image'], static::IMAGEPATH);
        } else {
            $image = $this->defaultImage(static::IMAGEPATH);
        }

        return $image;
    }

    public function setImageAttribute($value)
    {
        if ($value != null && is_file($value)) {
            isset($this->attributes['image']) ? $this->deleteFile($this->attributes['image'], static::IMAGEPATH) : '';
            $this->attributes['image'] = $this->uploadAllTypes($value, static::IMAGEPATH);
        }
    }
}
