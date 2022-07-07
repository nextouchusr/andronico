<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Request\Delivery;

use Nextouch\FastEst\Api\Data\InputInterface;
use Nextouch\FastEst\Model\Common\Login;

class GetOrderLabels implements InputInterface
{
    private Login $login;
    private string $storeOrder;
    private ?string $barcode;
    private ?string $row1;
    private ?string $row2;
    private ?string $row3;
    private ?string $row4;

    public function __construct(
        Login $login,
        string $storeOrder,
        ?string $barcode = null,
        ?string $row1 = null,
        ?string $row2 = null,
        ?string $row3 = null,
        ?string $row4 = null
    ) {
        $this->login = $login;
        $this->storeOrder = $storeOrder;
        $this->barcode = $barcode;
        $this->row1 = $row1;
        $this->row2 = $row2;
        $this->row3 = $row3;
        $this->row4 = $row4;
    }

    public function getLogin(): Login
    {
        return $this->login;
    }

    public function getStoreOrder(): string
    {
        return $this->storeOrder;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function getRow1(): ?string
    {
        return $this->row1;
    }

    public function getRow2(): ?string
    {
        return $this->row2;
    }

    public function getRow3(): ?string
    {
        return $this->row3;
    }

    public function getRow4(): ?string
    {
        return $this->row4;
    }

    public function asObject(): \stdClass
    {
        $object = new \stdClass();
        $object->login = $this->getLogin()->asObject();
        $object->store_order = $this->getStoreOrder();
        $object->barcode_c128 = $this->getBarcode();
        $object->row1 = $this->getRow1();
        $object->row2 = $this->getRow2();
        $object->row3 = $this->getRow3();
        $object->row4 = $this->getRow4();

        return $object;
    }
}
