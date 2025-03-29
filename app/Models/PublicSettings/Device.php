<?php

namespace App\Models\PublicSettings;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['device_type','device_id','morph_id' , 'morph_type'];

    public function morph(){
        return $this->morphTo();
    }
}
