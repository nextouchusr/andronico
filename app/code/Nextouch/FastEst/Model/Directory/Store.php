<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Directory;

use Nextouch\FastEst\Api\Data\OutputInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Store implements OutputInterface
{
    private int $storeId;
    private int $chainId;
    private int $headquarterId;
    private string $description;
    private string $address;
    private string $city;
    private string $cap;
    private string $province;
    private string $phone1;
    private string $phone2;
    private string $fax;
    private string $email;
    private string $billingEmail;

    private function __construct(
        int $storeId,
        int $chainId,
        int $headquarterId,
        string $description,
        string $address,
        string $city,
        string $cap,
        string $province,
        string $phone1,
        string $phone2,
        string $fax,
        string $email,
        string $billingEmail
    ) {
        $this->storeId = $storeId;
        $this->chainId = $chainId;
        $this->headquarterId = $headquarterId;
        $this->description = $description;
        $this->address = $address;
        $this->city = $city;
        $this->cap = $cap;
        $this->province = $province;
        $this->phone1 = $phone1;
        $this->phone2 = $phone2;
        $this->fax = $fax;
        $this->email = $email;
        $this->billingEmail = $billingEmail;
    }

    public function getStoreId(): int
    {
        return $this->storeId;
    }

    public function getChainId(): int
    {
        return $this->chainId;
    }

    public function getHeadquarterId(): int
    {
        return $this->headquarterId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCap(): string
    {
        return $this->cap;
    }

    public function getProvince(): string
    {
        return $this->province;
    }

    public function getPhone1(): string
    {
        return $this->phone1;
    }

    public function getPhone2(): string
    {
        return $this->phone2;
    }

    public function getFax(): string
    {
        return $this->fax;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getBillingEmail(): string
    {
        return $this->billingEmail;
    }

    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $storeId = (int) $propertyAccessor->getValue($object, 'store_id');
        $chainId = (int) $propertyAccessor->getValue($object, 'chain_id');
        $headquarterId = (int) $propertyAccessor->getValue($object, 'headquarter_id');
        $description = (string) $propertyAccessor->getValue($object, 'descr');
        $address = (string) $propertyAccessor->getValue($object, 'address');
        $city = (string) $propertyAccessor->getValue($object, 'city');
        $cap = (string) $propertyAccessor->getValue($object, 'cap');
        $province = (string) $propertyAccessor->getValue($object, 'province');
        $phone1 = (string) $propertyAccessor->getValue($object, 'tel1');
        $phone2 = (string) $propertyAccessor->getValue($object, 'tel2');
        $fax = (string) $propertyAccessor->getValue($object, 'fax');
        $email = (string) $propertyAccessor->getValue($object, 'mail');
        $billingEmail = (string) $propertyAccessor->getValue($object, 'mail_fatt');

        return new self(
            $storeId,
            $chainId,
            $headquarterId,
            $description,
            $address,
            $city,
            $cap,
            $province,
            $phone1,
            $phone2,
            $fax,
            $email,
            $billingEmail
        );
    }
}
