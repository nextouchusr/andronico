<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Response\Directory;

use Collections\Collection;
use Collections\Exceptions\InvalidArgumentException;
use Nextouch\FastEst\Api\Data\OutputInterface;
use Nextouch\FastEst\Model\Common\StatusReturn;
use Nextouch\FastEst\Model\Directory\Source;
use Symfony\Component\PropertyAccess\PropertyAccess;
use function Lambdish\Phunctional\map;

class SourceListResponse implements OutputInterface
{
    private StatusReturn $statusReturn;
    private Collection $sources;

    /**
     * @throws InvalidArgumentException
     */
    private function __construct(StatusReturn $statusReturn, array $sources = [])
    {
        $this->statusReturn = $statusReturn;
        $this->sources = new Collection(Source::class, $sources);
    }

    public function getStatusReturn(): StatusReturn
    {
        return $this->statusReturn;
    }

    public function getSources(): Collection
    {
        return $this->sources;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $statusReturn = $propertyAccessor->getValue($object, 'status_return');
        $sources = $propertyAccessor->getValue($object, 'source_ids');
        $sources = is_array($sources) ? $sources : [$sources];

        return new self(
            StatusReturn::fromObject($statusReturn),
            map(fn(\stdClass $item) => Source::fromObject($item), $sources)
        );
    }
}
