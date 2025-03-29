<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Model;

class RoomMember extends Model {

  protected $fillable = [
    'room_id',
    'memberable_id',
    'memberable_type',
  ];

  public function memberable() {
    return $this->morphTo();
  }

  public function room() {
    return $this->belongsTo(Room::class);
  }

}
