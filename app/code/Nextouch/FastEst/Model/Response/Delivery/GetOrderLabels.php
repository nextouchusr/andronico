<?php
declare(strict_types=1);

namespace Nextouch\FastEst\Model\Response\Delivery;

use Nextouch\FastEst\Api\Data\OutputInterface;
use Nextouch\FastEst\Model\Common\StatusReturn;
use Symfony\Component\PropertyAccess\PropertyAccess;

class GetOrderLabels implements OutputInterface
{
    private StatusReturn $statusReturn;
    private string $base64PdfLabel;

    private function __construct(StatusReturn $statusReturn, string $base64PdfLabel)
    {
        $this->statusReturn = $statusReturn;
        $this->base64PdfLabel = $base64PdfLabel;
    }

    public function getStatusReturn(): StatusReturn
    {
        return $this->statusReturn;
    }

    public function getBase64PdfLabel(): string
    {
        return $this->base64PdfLabel;
    }

    public static function fromObject(\stdClass $object): self
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $statusReturn = $propertyAccessor->getValue($object, 'status_return');
        $base64PdfLabel = (string) $propertyAccessor->getValue($object, 'label_pdf_base64');

        return new self(
            StatusReturn::fromObject($statusReturn),
            $base64PdfLabel
        );
    }
}
