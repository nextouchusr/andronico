<?php
declare(strict_types=1);

namespace Nextouch\Findomestic\Model\Customer;

use Nextouch\Findomestic\Api\Data\ArrayableInterface;
use Nextouch\Quote\Api\Data\CartInterface;

class Details implements ArrayableInterface
{
    private ?string $firstName;
    private ?string $lastName;
    private ?string $email;
    private ?string $cellPhone;
    private ?string $fiscalCode;
    private ?string $birthDate;
    private ?Address $billing;

    public function __construct(
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $email = null,
        ?string $cellPhone = null,
        ?string $fiscalCode = null,
        ?string $birthDate = null,
        ?Address $billing = null
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->cellPhone = $cellPhone;
        $this->fiscalCode = $fiscalCode;
        $this->birthDate = $birthDate;
        $this->billing = $billing;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getCellPhone(): ?string
    {
        return $this->cellPhone;
    }

    /**
     * @return string|null
     */
    public function getFiscalCode(): ?string
    {
        return $this->fiscalCode;
    }

    /**
     * @return string|null
     */
    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    /**
     * @return \Nextouch\Findomestic\Model\Customer\Address|null
     */
    public function getBilling(): ?Address
    {
        return $this->billing;
    }

    public static function fromDomain(CartInterface $quote): self
    {
        return new self(
            $quote->getBillingAddress()->getFirstname(),
            $quote->getBillingAddress()->getLastname(),
            $quote->getBillingAddress()->getEmail(),
            $quote->getBillingAddress()->getTelephone(),
            $quote->getBillingAddress()->getFiscalCode(),
            $quote->getCustomer()->getDob(),
            Address::fromDomain($quote->getBillingAddress())
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['firstName']) ? (string) $data['firstName'] : null,
            isset($data['lastName']) ? (string) $data['lastName'] : null,
            isset($data['email']) ? (string) $data['email'] : null,
            isset($data['cellPhone']) ? (string) $data['cellPhone'] : null,
            isset($data['fiscalCode']) ? (string) $data['fiscalCode'] : null,
            isset($data['birthDate']) ? (string) $data['birthDate'] : null,
            isset($data['billing']) ? Address::fromArray($data['billing']) : null
        );
    }

    public function toArray(): array
    {
        $arr = [
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'email' => $this->getEmail(),
            'cellPhone' => $this->getCellPhone(),
            'fiscalCode' => $this->getFiscalCode(),
            'birthDate' => $this->getBirthDate(),
            'billing' => $this->getBilling()->toArray(),
        ];

        return array_filter($arr);
    }
}
