<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model\Order;

class Status
{
    private const ACCEPTED_ORDER_STATUS = 'accepted';
    private const PROCESSING_ORDER_STATUS = 'processing';
    private const PAID_ORDER_STATUS = 'paid';
    private const SHIPPED_ORDER_STATUS = 'shipped';
    private const IN_DELIVERY_ORDER_STATUS = 'in_delivery';
    private const COMPLETE_ORDER_STATUS = 'complete';
    private const CANCELED_ORDER_STATUS = 'canceled';

    private const ACCEPTED_ORDER_STATE = 'new';
    private const PROCESSING_ORDER_STATE = 'processing';
    private const PAID_ORDER_STATE = 'processing';
    private const SHIPPED_ORDER_STATE = 'complete';
    private const IN_DELIVERY_ORDER_STATE = 'complete';
    private const COMPLETE_ORDER_STATE = 'complete';
    private const CANCELED_ORDER_STATE = 'canceled';

    public const ACCEPTED = [
        'status' => self::ACCEPTED_ORDER_STATUS,
        'state' => self::ACCEPTED_ORDER_STATE,
    ];

    public const PROCESSING = [
        'status' => self::PROCESSING_ORDER_STATUS,
        'state' => self::PROCESSING_ORDER_STATE,
    ];

    public const PAID = [
        'status' => self::PAID_ORDER_STATUS,
        'state' => self::PAID_ORDER_STATE,
    ];

    public const SHIPPED = [
        'status' => self::SHIPPED_ORDER_STATUS,
        'state' => self::SHIPPED_ORDER_STATE,
    ];

    public const IN_DELIVERY = [
        'status' => self::IN_DELIVERY_ORDER_STATUS,
        'state' => self::IN_DELIVERY_ORDER_STATE,
    ];

    public const COMPLETE = [
        'status' => self::COMPLETE_ORDER_STATUS,
        'state' => self::COMPLETE_ORDER_STATE,
    ];

    public const CANCELED = [
        'status' => self::CANCELED_ORDER_STATUS,
        'state' => self::CANCELED_ORDER_STATE,
    ];
}
