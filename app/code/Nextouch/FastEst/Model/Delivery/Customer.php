<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Delivery;

use Nextouch\FastEst\Api\Data\InputInterface;
use Nextouch\Sales\Api\Data\OrderAddressInterface;

class Customer implements InputInterface
{
    private string $name;
    private string $surname;
    private string $address;
    private string $province;
    private string $city;
    private string $postCode;
    private int $floor;
    private bool $limitedTrafficZone;
    private bool $stair;
    private bool $lift;
    private string $email;
    private string $phone;
    private string $mobilePhone;
    private string $vatId;

    private function __construct(
        string $name,
        string $surname,
        string $address,
        string $province,
        string $city,
        string $postCode,
        int $floor,
        bool $limitedTrafficZone,
        bool $stair,
        bool $lift,
        string $email,
        string $phone,
        string $mobilePhone,
        string $vatId
    ) {
        $this->name = $name;
        $this->surname = $surname;
        $this->address = $address;
        $this->province = $province;
        $this->city = $city;
        $this->postCode = $postCode;
        $this->floor = $floor;
        $this->limitedTrafficZone = $limitedTrafficZone;
        $this->stair = $stair;
        $this->lift = $lift;
        $this->email = $email;
        $this->phone = $phone;
        $this->mobilePhone = $mobilePhone;
        $this->vatId = $vatId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getProvince(): string
    {
        return $this->province;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getPostCode(): string
    {
        return $this->postCode;
    }

    public function getFloor(): int
    {
        return $this->floor;
    }

    public function isLimitedTrafficZone(): bool
    {
        return $this->limitedTrafficZone;
    }

    public function hasStair(): bool
    {
        return $this->stair;
    }

    public function hasLift(): bool
    {
        return $this->lift;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getMobilePhone(): string
    {
        return $this->mobilePhone;
    }

    public function getVatId(): string
    {
        return $this->vatId;
    }

    public static function fromDomain(OrderAddressInterface $address): self
    {
        return new self(
            $address->getFirstname(),
            $address->getLastname(),
            $address->getStreetAsLine(),
            $address->getRegionCode(),
            $address->getCity(),
            $address->getPostcode(),
            $address->getFloor(),
            $address->isLimitedTrafficZone(),
            $address->hasStair(),
            $address->hasLift(),
            $address->getEmail(),
            $address->getTelephone(),
            $address->getMobilePhone(),
            $address->getVatId()
        );
    }

    public function asObject(): \stdClass
    {
        $object = new \stdClass();
        $object->customer_name = $this->getName();
        $object->customer_surname = $this->getSurname();
        $object->customer_address = $this->getAddress();
        $object->customer_province = $this->getProvince();
        $object->customer_city = $this->getCity();
        $object->customer_cap = $this->getPostCode();
        $object->customer_floor = $this->getFloor();
        $object->customer_ztl = (int) $this->isLimitedTrafficZone();
        $object->customer_stair = (int) $this->hasStair();
        $object->customer_lift = (int) $this->hasLift();
        $object->customer_email = $this->getEmail();
        $object->customer_tel = $this->getPhone();
        $object->customer_cell = $this->getMobilePhone();
        $object->customer_codfis = $this->getVatId();

        return $object;
    }
}
