<?php

use App\Enums\NegotiationOrderStatusEnum;
use App\Enums\NotificationTypeEnum;

return [
    'TestNotification'   => 'Test Notification Message En',
    'title_finish_order' => 'Awamer',
    'body_finish_order'  => 'Your order has been finished :order_num',
    'title_admin_notify' => 'Administrative notice',
    'title_block' => 'Block',
    'body_block'  => 'Your Account Is Block From Administrative',

    // Products Orders
    'title_' . NotificationTypeEnum::ORDER_WAS_PAID->value => 'Order payment completed',
    'body_' . NotificationTypeEnum::ORDER_WAS_PAID->value => 'The customer has paid for order number (:order_num)',
    'title_' . NotificationTypeEnum::New->value => 'New order',
    'body_' . NotificationTypeEnum::New->value  => 'A new products order, order number (:order_num)',
    'title_' . NotificationTypeEnum::Accepted->value => 'Order accepted',
    'body_' . NotificationTypeEnum::Accepted->value  => 'The product order has been accepted, order number (:order_num)',
    'title_' . NotificationTypeEnum::Preparing->value => 'Order in preparation',
    'body_' . NotificationTypeEnum::Preparing->value  => 'Your order is being prepared, order number (:order_num)',
    'title_' . NotificationTypeEnum::Prepared->value => 'Preparation completed',
    'body_' . NotificationTypeEnum::Prepared->value  => 'Your order has been prepared, order number (:order_num)',
    'title_' . NotificationTypeEnum::Delivered_to_delegate->value => 'Order delivered to delegate',
    'body_' . NotificationTypeEnum::Delivered_to_delegate->value  => 'Your order has been delivered to the delegate, order number (:order_num)',
    'title_' . NotificationTypeEnum::On_the_way_to_client->value => 'Delegate on the way to you',
    'body_' . NotificationTypeEnum::On_the_way_to_client->value  => 'The delegate is on the way to you, order number (:order_num)',
    'title_' . NotificationTypeEnum::Delegate_at_location->value => 'Order delivered by delegate',
    'body_' . NotificationTypeEnum::Delegate_at_location->value  => 'The delegate has delivered your order, order number (:order_num)',
    'title_' . NotificationTypeEnum::Client_delivered->value => 'Order received by client',
    'body_' . NotificationTypeEnum::Client_delivered->value  => 'The client has received the order, order number (:order_num)',
    'title_' . NotificationTypeEnum::Cancelled->value => 'Order cancelled',
    'body_' . NotificationTypeEnum::Cancelled->value  => 'The order has been cancelled, order number (:order_num)',
    'title_' . NotificationTypeEnum::On_may_way_to_provider->value => 'Delegate on the way to provider',
    'body_' . NotificationTypeEnum::On_may_way_to_provider->value  => 'The delegate is on the way to pick up the products, order number (:order_num)',
    // Negotiation Orders
    'title_' . NotificationTypeEnum::Negotiation_Pending->value => 'New Negotiation order',
    'body_' . NotificationTypeEnum::Negotiation_Pending->value  => 'A new Negotiation order, order number (:order_num)',
    'title_' . NotificationTypeEnum::Negotiation_Invoice_declined->value => 'Invoice declined',
    'body_' . NotificationTypeEnum::Negotiation_Invoice_declined->value  => 'The invoice for the product order has been declined, order number (:order_num)',
    'title_' . NotificationTypeEnum::Negotiation_Invoice_paid->value => 'Invoice paid',
    'body_' . NotificationTypeEnum::Negotiation_Invoice_paid->value  => 'The invoice for your order has been paid, order number (:order_num)',





];
