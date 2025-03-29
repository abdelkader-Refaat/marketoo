<?php

namespace App\Notifications\Admin\Settlement;

use App\Enums\NotificationTypeEnum;
use App\Traits\FirebaseTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderSettlementNotification extends Notification
{
    use Queueable , FirebaseTrait;
    protected $data;

    public function __construct($settlement)
    {

        $this->data     = [
            'order_id'    => $settlement->id,
            'type'        => NotificationTypeEnum::ORDER_SETTLEMENT->value ,
            'order_num'   => $settlement->order_num,
            'url'         => route('admin.settlements.show', $settlement->id),
        ];
    }
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        if ($notifiable->is_notify) {
            $this->sendFcmNotification($notifiable?->devices() ?? [] , $this->data, $notifiable->lang);
        }
        return $this->data;
    }
}
