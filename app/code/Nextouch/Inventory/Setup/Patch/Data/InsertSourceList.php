<?php
declare(strict_types=1);

namespace Nextouch\Inventory\Setup\Patch\Data;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\InventoryApi\Api\Data\SourceInterfaceFactory;
use Magento\InventoryApi\Api\SourceRepositoryInterface;
use function Lambdish\Phunctional\each;

class InsertSourceList implements DataPatchInterface
{
    private SourceInterfaceFactory $sourceFactory;
    private SourceRepositoryInterface $sourceRepository;

    public function __construct(
        SourceInterfaceFactory $sourceFactory,
        SourceRepositoryInterface $sourceRepository
    ) {
        $this->sourceFactory = $sourceFactory;
        $this->sourceRepository = $sourceRepository;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    /**
     * @throws LocalizedException
     */
    public function apply(): self
    {
        each(function (array $data) {
            $source = $this->sourceFactory->create();
            $source->setSourceCode($data['code']);
            $source->setName($data['name']);
            $source->setEnabled($data['is_enabled']);
            $source->setLatitude($data['latitude']);
            $source->setLongitude($data['longitude']);
            $source->getExtensionAttributes()->setIsPickupLocationActive($data['is_pickup_location_active']);
            $source->setContactName($data['contact_name']);
            $source->setEmail($data['email']);
            $source->setPhone($data['phone']);
            $source->setFax($data['fax']);
            $source->setCountryId($data['country_id']);
            $source->setRegion($data['region']);
            $source->setRegionId($data['region_id']);
            $source->setCity($data['city']);
            $source->setStreet($data['street']);
            $source->setPostcode($data['post_code']);

            $this->sourceRepository->save($source);
        }, $this->getSourceList());

        return $this;
    }

    private function getSourceList(): array
    {
        return [
            [
                'code' => 'ecommerce',
                'name' => 'E-commerce',
                'is_enabled' => true,
                'latitude' => null,
                'longitude' => null,
                'is_pickup_location_active' => false,
                'contact_name' => '',
                'email' => 'info@nextouch.it',
                'phone' => '+39 348 6037050',
                'fax' => '',
                'country_id' => 'IT',
                'region' => 'Milano',
                'region_id' => 739,
                'city' => 'Milano',
                'street' => 'Via Fabio Filzi 27',
                'post_code' => '20124',
            ],
            [
                'code' => 'MI1',
                'name' => 'Milano 1',
                'is_enabled' => false,
                'latitude' => 45.466944,
                'longitude' => 9.19,
                'is_pickup_location_active' => true,
                'contact_name' => '',
                'email' => 'info@nextouch.it',
                'phone' => '',
                'fax' => '',
                'country_id' => 'IT',
                'region' => 'Milano',
                'region_id' => 739,
                'city' => 'Milano',
                'street' => 'Via Roma 15',
                'post_code' => '20100',
            ],
            [
                'code' => 'MB1',
                'name' => 'Monza 1',
                'is_enabled' => true,
                'latitude' => 45.580895,
                'longitude' => 9.273731,
                'is_pickup_location_active' => true,
                'contact_name' => '',
                'email' => 'info@nextouch.it',
                'phone' => '+39 393 761 1588',
                'fax' => '',
                'country_id' => 'IT',
                'region' => 'Monza-Brianza',
                'region_id' => 863,
                'city' => 'Monza',
                'street' => 'Via Italia 41',
                'post_code' => '20900',
            ],
        ];
    }
}
