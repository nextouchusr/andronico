<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model\Order;

class Status
{
    private const PROCESSING_ORDER_STATUS = 'processing';
    private const PAID_ORDER_STATUS = 'paid';
    private const SHIPPED_ORDER_STATUS = 'shipped';
    private const IN_DELIVERY_ORDER_STATUS = 'in_delivery';
    private const DELIVERED_ORDER_STATUS = 'delivered';

    private const PROCESSING_ORDER_STATE = 'processing';
    private const PAID_ORDER_STATE = 'processing';
    private const SHIPPED_ORDER_STATE = 'complete';
    private const IN_DELIVERY_ORDER_STATE = 'complete';
    private const DELIVERED_ORDER_STATE = 'complete';

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

    public const DELIVERED = [
        'status' => self::DELIVERED_ORDER_STATUS,
        'state' => self::DELIVERED_ORDER_STATE,
    ];
}
