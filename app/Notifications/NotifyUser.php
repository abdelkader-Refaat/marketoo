<?php

namespace App\Notifications;

use App\Traits\FirebaseTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NotifyUser extends Notification
{
    use Queueable, FirebaseTrait;

    private $data;

    public function __construct($request)
    {
        $this->data = [
            'body_ar'  => $request['body_ar'],
            'body_en'  => $request['body_en'],
            'title_ar' => $request['title_ar'],
            'title_en' => $request['title_en'],
            'type'     => 'admin_notify',
        ];
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        if ($notifiable->is_notify) {
            $this->sendFcmNotification($notifiable->devices(), $this->data, $notifiable->lang);
        }
        return $this->data;
    }
}
