<?php
declare(strict_types=1);

namespace Nextouch\Inventory\Setup\Patch\Data;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\InventoryApi\Api\Data\SourceInterfaceFactory;
use Magento\InventoryApi\Api\SourceRepositoryInterface;

class InsertSourceForBrescia implements DataPatchInterface
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
        $source = $this->sourceFactory->create();
        $source->setSourceCode('BS1');
        $source->setName('Brescia 1');
        $source->setEnabled(true);
        $source->setLatitude(45.536400);
        $source->setLongitude(10.222020);
        $source->getExtensionAttributes()->setIsPickupLocationActive(true);
        $source->setContactName('');
        $source->setEmail('brescia@andronico.it');
        $source->setPhone('+39 030 6061220');
        $source->setFax('');
        $source->setCountryId('IT');
        $source->setRegion('Brescia');
        $source->setRegionId(699);
        $source->setCity('Brescia');
        $source->setStreet('Corso Magenta 2');
        $source->setPostcode('25121');

        $this->sourceRepository->save($source);

        return $this;
    }
}
