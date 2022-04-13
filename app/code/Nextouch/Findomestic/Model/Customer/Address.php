<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Customer;

use Nextouch\Findomestic\Api\Data\ArrayableInterface;
use Nextouch\Quote\Api\Data\AddressInterface;

class Address implements ArrayableInterface
{
    private ?string $addressLine1;
    private ?string $city;
    private ?string $countryCode;
    private ?string $zipCode;
    private ?string $province;

    public function __construct(
        ?string $addressLine1 = null,
        ?string $city = null,
        ?string $countryCode = null,
        ?string $zipCode = null,
        ?string $province = null
    ) {
        $this->addressLine1 = $addressLine1;
        $this->city = $city;
        $this->countryCode = $countryCode;
        $this->zipCode = $zipCode;
        $this->province = $province;
    }

    /**
     * @return string|null
     */
    public function getAddressLine1(): ?string
    {
        return $this->addressLine1;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * @return string|null
     */
    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    /**
     * @return string|null
     */
    public function getProvince(): ?string
    {
        return $this->province;
    }

    public static function fromDomain(AddressInterface $address): self
    {
        return new self(
            $address->getStreetAsLine(),
            $address->getCity(),
            $address->getCountryId(),
            $address->getPostcode(),
            $address->getRegion()
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['addressLine1']) ? (string) $data['addressLine1'] : null,
            isset($data['city']) ? (string) $data['city'] : null,
            isset($data['countryCode']) ? (string) $data['countryCode'] : null,
            isset($data['zipCode']) ? (string) $data['zipCode'] : null,
            isset($data['province']) ? (string) $data['province'] : null
        );
    }

    public function toArray(): array
    {
        $arr = [
            'addressLine1' => $this->getAddressLine1(),
            'city' => $this->getCity(),
            'countryCode' => $this->getCountryCode(),
            'zipCode' => $this->getZipCode(),
            'province' => $this->getProvince(),
        ];

        return array_filter($arr);
    }
}
