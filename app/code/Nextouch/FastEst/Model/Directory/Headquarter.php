<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Directory;

use Nextouch\FastEst\Api\Data\OutputInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Headquarter implements OutputInterface
{
    private int $headquarterId;
    private string $description;
    private string $address;
    private string $city;
    private string $cap;
    private string $province;
    private string $phone1;
    private string $phone2;
    private string $fax;
    private string $vatId;
    private string $email;
    private string $billingEmail;
    private bool $tractionEnable;
    private string $waybillReport;

    private function __construct(
        int $headquarterId,
        string $description,
        string $address,
        string $city,
        string $cap,
        string $province,
        string $phone1,
        string $phone2,
        string $fax,
        string $vatId,
        string $email,
        string $billingEmail,
        bool $tractionEnable,
        string $waybillReport
    ) {
        $this->headquarterId = $headquarterId;
        $this->description = $description;
        $this->address = $address;
        $this->city = $city;
        $this->cap = $cap;
        $this->province = $province;
        $this->phone1 = $phone1;
        $this->phone2 = $phone2;
        $this->fax = $fax;
        $this->vatId = $vatId;
        $this->email = $email;
        $this->billingEmail = $billingEmail;
        $this->tractionEnable = $tractionEnable;
        $this->waybillReport = $waybillReport;
    }

    /**
     * @return int
     */
    public function getHeadquarterId(): int
    {
        return $this->headquarterId;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getCap(): string
    {
        return $this->cap;
    }

    /**
     * @return string
     */
    public function getProvince(): string
    {
        return $this->province;
    }

    /**
     * @return string
     */
    public function getPhone1(): string
    {
        return $this->phone1;
    }

    /**
     * @return string
     */
    public function getPhone2(): string
    {
        return $this->phone2;
    }

    /**
     * @return string
     */
    public function getFax(): string
    {
        return $this->fax;
    }

    /**
     * @return string
     */
    public function getVatId(): string
    {
        return $this->vatId;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getBillingEmail(): string
    {
        return $this->billingEmail;
    }

    /**
     * @return bool
     */
    public function isTractionEnable(): bool
    {
        return $this->tractionEnable;
    }

    /**
     * @return string
     */
    public function getWaybillReport(): string
    {
        return $this->waybillReport;
    }

    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $headquarterId = (int) $propertyAccessor->getValue($object, 'headquarter_id');
        $description = (string) $propertyAccessor->getValue($object, 'descr');
        $address = (string) $propertyAccessor->getValue($object, 'address');
        $city = (string) $propertyAccessor->getValue($object, 'city');
        $cap = (string) $propertyAccessor->getValue($object, 'cap');
        $province = (string) $propertyAccessor->getValue($object, 'province');
        $phone1 = (string) $propertyAccessor->getValue($object, 'tel1');
        $phone2 = (string) $propertyAccessor->getValue($object, 'tel2');
        $fax = (string) $propertyAccessor->getValue($object, 'fax');
        $vatId = (string) $propertyAccessor->getValue($object, 'piva');
        $email = (string) $propertyAccessor->getValue($object, 'mail');
        $billingEmail = (string) $propertyAccessor->getValue($object, 'mail_fatt');
        $tractionEnable = (bool) $propertyAccessor->getValue($object, 'traction_enable');
        $waybillReport = (string) $propertyAccessor->getValue($object, 'report_lettera_vettura');

        return new self(
            $headquarterId,
            $description,
            $address,
            $city,
            $cap,
            $province,
            $phone1,
            $phone2,
            $fax,
            $vatId,
            $email,
            $billingEmail,
            $tractionEnable,
            $waybillReport
        );
    }
}
