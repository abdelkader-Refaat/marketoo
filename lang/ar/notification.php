<?php

use App\Enums\NegotiationOrderStatusEnum;
use App\Enums\NotificationTypeEnum;

return [

    'TestNotification'   => 'اختبار الاشعارات',
    'title_finish_order' => 'Awamer',
    'body_finish_order'  => 'طلبك تم :order_num',
    'title_admin_notify' => 'اشعار اداري ',
    'title_finish_order' => 'انهاء طلب',
    'body_finish_order'  => 'تم الانتهاء من المنتج رقم :order_num',
    'title_block'        => 'حظر',
    'body_block'         => 'تم حظرك من قبل الادارة',
    // Normal Orders
    'title_' . NotificationTypeEnum::ORDER_WAS_PAID->value => 'تم دفع رسوم الطلب  ',
    'body_' . NotificationTypeEnum::ORDER_WAS_PAID->value  => ' قام العميل بدفع رسوم الطلب رقم  (  :order_num )',
    'title_' . NotificationTypeEnum::New->value => ' طلب جديد ',
    'body_' . NotificationTypeEnum::New->value  => ' طلب منتجات جديد رقم الطلب (  :order_num )',
    'title_' . NotificationTypeEnum::Accepted->value => ' قبول الطلب ',
    'body_' . NotificationTypeEnum::Accepted->value  => ' تم قبول طلب منتجات، رقم الطلب (  :order_num )',
    'title_' . NotificationTypeEnum::Preparing->value => ' طلب قيد التجهيز ',
    'body_' . NotificationTypeEnum::Preparing->value  => ' طلبك دخل مرحلة التجهيز، رقم الطلب (  :order_num )',
    'title_' . NotificationTypeEnum::Prepared->value => ' انتهاء التجهيز ',
    'body_' . NotificationTypeEnum::Prepared->value  => ' تم الانتهاء من تجهيز طلبك، رقم الطلب (  :order_num )',
    'title_' . NotificationTypeEnum::Delivered_to_delegate->value => ' تسليم الطلب للمندوب ',
    'body_' . NotificationTypeEnum::Delivered_to_delegate->value  => ' تم تسليم طلبك للمندوب رقم الطلب (  :order_num )',
    'title_' . NotificationTypeEnum::On_the_way_to_client->value => ' المندوب في الطريق اليك ',
    'body_' . NotificationTypeEnum::On_the_way_to_client->value  => ' المندوب في الطريق إليك، رقم الطلب (  :order_num )',
    'title_' . NotificationTypeEnum::Delegate_at_location->value => ' المندوب قام بتسليم الطلب ',
    'body_' . NotificationTypeEnum::Delegate_at_location->value  => ' المندوب قام بعملية التسليم، رقم الطلب (  :order_num )',
    'title_' . NotificationTypeEnum::Client_delivered->value => ' العميل قام بالاستلام ',
    'body_' . NotificationTypeEnum::Client_delivered->value  => ' العميل قام بالاستلام، رقم الطلب (  :order_num )',
    'title_' . NotificationTypeEnum::Cancelled->value => ' الغاء الطلب ',
    'body_' . NotificationTypeEnum::Cancelled->value  => ' تم الغاء الطب، رقم الطلب (  :order_num )',
    'title_' . NotificationTypeEnum::On_may_way_to_provider->value => ' المندوب في الطريق اليك ',
    'body_' . NotificationTypeEnum::On_may_way_to_provider->value  => ' المندوب في الطريق لإستلام المنتجات، رقم الطلب (  :order_num )',
    // Negotiation Orders
    'title_' . NotificationTypeEnum::Negotiation_Pending->value => 'طلب تفاوض جديد',
    'body_' . NotificationTypeEnum::Negotiation_Pending->value  => 'تم تقديم طلب تفاوض جديد، رقم الطلب (:order_num)',
    'title_' . NotificationTypeEnum::Negotiation_Invoice_declined->value => 'تم رفض الفاتورة',
    'body_' . NotificationTypeEnum::Negotiation_Invoice_declined->value  => 'تم رفض الفاتورة الخاصة بطلب التفاوض، رقم الطلب (:order_num)',
    'title_' . NotificationTypeEnum::Negotiation_Invoice_paid->value => 'تم دفع الفاتورة',
    'body_' . NotificationTypeEnum::Negotiation_Invoice_paid->value  => 'تم دفع الفاتورة الخاصة بطلب التفاوض،، رقم الطلب (:order_num)',
    'title_' . NotificationTypeEnum::Negotiation_Invoice_generated->value => 'تم انشاء فاتورة',
    'body_' . NotificationTypeEnum::Negotiation_Invoice_generated->value  => 'تم انشاء الفاتورة الخاصة بطلب التفاوض،، رقم الطلب (:order_num)',


    'title_' . NotificationTypeEnum::NEW_PROVIDER_REGISTRATION->value => 'تسجيل مزود خدمة جديد',
    'body_' . NotificationTypeEnum::NEW_PROVIDER_REGISTRATION->value  => 'تم تسجيل مزود خدمة جديد رقم :provider_id',



    'title_' . NotificationTypeEnum::ORDER_SETTLEMENT->value => 'تسوية الطلب',
    'body_' . NotificationTypeEnum::ORDER_SETTLEMENT->value  => 'تم طلب تسوية على الطلب رقم :order_num',

];
