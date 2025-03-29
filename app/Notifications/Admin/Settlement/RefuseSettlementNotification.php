<?php

namespace App\Notifications\Admin\Settlement;

use App\Enums\NotificationTypeEnum;
use App\Traits\FirebaseTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RefuseSettlementNotification extends Notification
{
    use Queueable, FirebaseTrait;

    protected $data;

    public function via($notifiable)
    {
        return ['database'];
    }

    public function __construct($settlement)
    {

        $this->data = [
            'sender'       => auth()->id(),
            'sender_model' => 'Admin',
            'order_id'     => $settlement->id,
            'order_num'    => $settlement->order_num,
            'type'         => NotificationTypeEnum::REJECT_ORDER_SETTLEMENT->value,
        ];
    }

    public function toArray($notifiable)
    {
        if ($notifiable->is_notify) {
            $this->sendFcmNotification($notifiable->devices(), $this->data, $notifiable->lang);
        }
        return $this->data;
    }
}
