<?php
declare(strict_types=1);

namespace Nextouch\Sales\Model\Order;

class Status
{
    private const ACCEPTED_ORDER_STATUS = 'accepted';
    private const PENDING_ORDER_STATUS = 'pending';
    private const PROCESSING_ORDER_STATUS = 'processing';
    private const PAID_ORDER_STATUS = 'paid';
    private const READY_TO_SHIP_ORDER_STATUS = 'ready_to_ship';
    private const IN_CHARGE_ORDER_STATUS = 'in_charge';
    private const SCHEDULED_ORDER_STATUS = 'scheduled';
    private const SHIPPING_ORDER_STATUS = 'shipping';
    private const SHIPPED_ORDER_STATUS = 'shipped';
    private const IN_DELIVERY_ORDER_STATUS = 'in_delivery';
    private const PARTIALLY_COMPLETE_ORDER_STATUS = 'partially_complete';
    private const COMPLETE_ORDER_STATUS = 'complete';
    private const CANCELED_ORDER_STATUS = 'canceled';

    private const NEW_ORDER_STATE = 'new';
    private const PROCESSING_ORDER_STATE = 'processing';
    private const COMPLETE_ORDER_STATE = 'complete';
    private const CANCELED_ORDER_STATE = 'canceled';

    public const ACCEPTED = [
        'status' => self::ACCEPTED_ORDER_STATUS,
        'state' => self::NEW_ORDER_STATE,
    ];

    public const PENDING = [
        'status' => self::PENDING_ORDER_STATUS,
        'state' => self::NEW_ORDER_STATE,
    ];

    public const PROCESSING = [
        'status' => self::PROCESSING_ORDER_STATUS,
        'state' => self::PROCESSING_ORDER_STATE,
    ];

    public const PAID = [
        'status' => self::PAID_ORDER_STATUS,
        'state' => self::PROCESSING_ORDER_STATE,
    ];

    public const READY_TO_SHIP = [
        'status' => self::READY_TO_SHIP_ORDER_STATUS,
        'state' => self::PROCESSING_ORDER_STATE,
    ];

    public const IN_CHARGE = [
        'status' => self::IN_CHARGE_ORDER_STATUS,
        'state' => self::COMPLETE_ORDER_STATE,
    ];

    public const SCHEDULED = [
        'status' => self::SCHEDULED_ORDER_STATUS,
        'state' => self::COMPLETE_ORDER_STATE,
    ];

    public const SHIPPING = [
        'status' => self::SHIPPING_ORDER_STATUS,
        'state' => self::COMPLETE_ORDER_STATE,
    ];

    /**
     * @deprecated
     */
    public const IN_DELIVERY = [
        'status' => self::IN_DELIVERY_ORDER_STATUS,
        'state' => self::COMPLETE_ORDER_STATE,
    ];

    public const SHIPPED = [
        'status' => self::SHIPPED_ORDER_STATUS,
        'state' => self::COMPLETE_ORDER_STATE,
    ];

    public const PARTIALLY_COMPLETE = [
        'status' => self::PARTIALLY_COMPLETE_ORDER_STATUS,
        'state' => self::COMPLETE_ORDER_STATE,
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
