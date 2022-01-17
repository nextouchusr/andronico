<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Directory;

use Nextouch\FastEst\Api\Data\OutputInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Van implements OutputInterface
{
    private int $vanId;
    private int $headquarterId;
    private string $plate;
    private string $brand;
    private string $model;
    private string $phone;
    private int $driveLoginId;
    private int $helpLoginId;

    private function __construct(
        int $vanId,
        int $headquarterId,
        string $plate,
        string $brand,
        string $model,
        string $phone,
        int $driveLoginId,
        int $helpLoginId
    ) {
        $this->vanId = $vanId;
        $this->headquarterId = $headquarterId;
        $this->plate = $plate;
        $this->brand = $brand;
        $this->model = $model;
        $this->phone = $phone;
        $this->driveLoginId = $driveLoginId;
        $this->helpLoginId = $helpLoginId;
    }

    /**
     * @return int
     */
    public function getVanId(): int
    {
        return $this->vanId;
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
    public function getPlate(): string
    {
        return $this->plate;
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @return int
     */
    public function getDriveLoginId(): int
    {
        return $this->driveLoginId;
    }

    /**
     * @return int
     */
    public function getHelpLoginId(): int
    {
        return $this->helpLoginId;
    }

    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $vanId = (int) $propertyAccessor->getValue($object, 'van_id');
        $headquarterId = (int) $propertyAccessor->getValue($object, 'headquarter_id');
        $plate = (string) $propertyAccessor->getValue($object, 'targa');
        $brand = (string) $propertyAccessor->getValue($object, 'brand');
        $model = (string) $propertyAccessor->getValue($object, 'model');
        $phone = (string) $propertyAccessor->getValue($object, 'cell');
        $driveLoginId = (int) $propertyAccessor->getValue($object, 'drive_login_id');
        $helpLoginId = (int) $propertyAccessor->getValue($object, 'help_login_id');

        return new self(
            $vanId,
            $headquarterId,
            $plate,
            $brand,
            $model,
            $phone,
            $driveLoginId,
            $helpLoginId
        );
    }
}
