<?php

namespace App\Enums;

enum NotificationTypeEnum: string
{
    case USER_ACTIVE = 'user_active';

    case USER_BLOCKED = 'user_blocked';
    case DELETE_ACCOUNT = 'delete_account';

    case NEW_USER_REGISTRATION = 'new_user_registration';
    case NEW_PROVIDER_REGISTRATION = 'new_provider_registration';

    case NEW_COMPLAINT = 'new_complaint';
    case COMPLAINT_REPLAY = 'complaint_replay';

    // Orders
    case New = 'new_order';
    case Accepted = 'accepted_order';
    case Preparing = 'preparing_order';
    case Prepared = 'prepared_order';
    case Delivered_to_delegate = 'delivered_to_delegate_order';
    case Provider_delivered_to_client = 'provider_delivered_to_client_order'; //at from store orders
    case Order_with_delegate = 'order_with_delegate_order';
    case On_the_way_to_client = 'on_the_way_to_client_order';
    case Delegate_at_location = 'delegate_at_location_order';
    case Delegate_delivered_to_client = 'delegate_delivered_to_client_order'; // at from home orders
    case Client_delivered = 'client_delivered_order';
    case Cancelled = 'cancelled_order'; // after provider acceptance and after provider prepared the order only
    case On_may_way_to_provider = 'on_my_way_to_provider_order';

    // Negotiation Orders
    case Negotiation_Pending = 'pending_negotiation_order';
    case Negotiation_Invoice_declined = 'invoice_declined_negotiation_order';
    case Negotiation_Invoice_paid = 'invoice_paid_negotiation_order';
    case Negotiation_Invoice_generated = 'generated_invoice_negotiation_order';

    case ORDER_WAS_PAID = 'paid_order';
    case ORDER_SETTLEMENT = 'order_settlement';
    case ACCEPT_ORDER_SETTLEMENT = 'accept_order_settlement';
    case REJECT_ORDER_SETTLEMENT = 'reject_order_settlement';

    // Ratings
    case Rate_From_User = 'rate_from_user';
    case Rate_From_Provider = 'rate_from_provider';
}
