<?php

namespace App\Enums;

enum MessageStatus: string
{
    case PENDING = 'pending';
    case SENDING = 'sending';
    case SENT = 'sent';
    case FAILED_ON_SEND = 'failed_on_send';
    case NEVER_SEND = 'never_send';
    case GETTING_DELIVERY = 'getting_delivery';
    case DELIVERED = 'delivered';
    case UNKNOWN_ON_DELIVER = 'unknown_on_deliver';
    case FAILED_ON_DELIVER = 'failed_on_deliver';
    case BLACK_LIST = 'black_list';
}
