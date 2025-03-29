<?php

namespace App\Models\Core;

use App\Traits\NotificationMessageTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Notifications\DatabaseNotification;

class Notification extends DatabaseNotification
{
    use NotificationMessageTrait;

    public function getTypeAttribute($value)
    {
        return $this->data['type'];
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getTitleAttribute($value)
    {
        return isset($this->data['title_' . lang()]) ?
            $this->data['title_' . lang()] :
            $this->getTitle($this->data['type'], lang());
    }

    public function getBodyAttribute($value)
    {
        return $this->getBody($this->data, lang());
    }

    public function getSenderAttribute($value)
    {
        $def = 'App\Models\\' . $this->data['sender_model'];
        $sender = $def::find($this->data['sender']);
        return [
            'name'   => $sender->name,
            'avatar' => $sender->avatar,
        ];
    }
}
