<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model\Order;

class Status
{
    private const PROCESSING_ORDER_STATUS = 'processing';
    private const PAID_ORDER_STATUS = 'paid';
    private const INVOICED_ORDER_STATUS = 'invoiced';
    private const SHIPPED_ORDER_STATUS = 'shipped';

    private const PROCESSING_ORDER_STATE = 'processing';
    private const PAID_ORDER_STATE = 'processing';
    private const INVOICED_ORDER_STATE = 'processing';
    private const SHIPPED_ORDER_STATE = 'processing';

    public const PROCESSING = [
        'status' => self::PROCESSING_ORDER_STATUS,
        'state' => self::PROCESSING_ORDER_STATE,
    ];

    public const PAID = [
        'status' => self::PAID_ORDER_STATUS,
        'state' => self::PAID_ORDER_STATE,
    ];

    public const INVOICED = [
        'status' => self::INVOICED_ORDER_STATUS,
        'state' => self::INVOICED_ORDER_STATE,
    ];

    public const SHIPPED = [
        'status' => self::SHIPPED_ORDER_STATUS,
        'state' => self::SHIPPED_ORDER_STATE,
    ];
}
