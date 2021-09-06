<?php
declare(strict_types=1);

namespace Nextouch\ImportExport\Model\Wins\Property;

use Nextouch\ImportExport\Model\Wins\Property;
use Symfony\Component\PropertyAccess\PropertyAccess;

class TextualProperty extends Property
{
    public const TYPE = 'Testo libero';

    public static function fromArray(array $data): Property
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $code = $propertyAccessor->getValue($data, '[CodiceCaratteristica]');
        $description = $propertyAccessor->getValue($data, '[DescrizioneCaratteristica]');
        $sortOrder = (int) $propertyAccessor->getValue($data, '[Ordinamento]');

        return new self($code, $description, $sortOrder);
    }
}
