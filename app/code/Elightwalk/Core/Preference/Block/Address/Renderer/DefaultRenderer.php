<?php

namespace Elightwalk\Core\Preference\Block\Address\Renderer;

use Magento\Customer\Block\Address\Renderer\DefaultRenderer as CustomerDefaultRenderer;
use Magento\Customer\Block\Address\Renderer\RendererInterface;
use Magento\Framework\View\Element\Context;
use Magento\Customer\Model\Metadata\ElementFactory;
use Magento\Directory\Model\CountryFactory;
use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Model\Address\Mapper;
use Magento\Directory\Model\Country\Format;

class DefaultRenderer extends CustomerDefaultRenderer implements RendererInterface
{
    /**
     * Format type object
     *
     * @var \Magento\Framework\DataObject
     */
    protected $_type;

    /**
     * @var ElementFactory
     */
    protected $_elementFactory;

    /**
     * @var CountryFactory
     */
    protected $_countryFactory;

    /**
     * @var AddressMetadataInterface
     */
    protected $_addressMetadataService;

    /**
     * @var Mapper
     */
    protected $addressMapper;

    /**
     * __construct
     *
     * @param Context $context
     * @param ElementFactory $elementFactory
     * @param CountryFactory $countryFactory
     * @param AddressMetadataInterface $metadataService
     * @param Mapper $addressMapper
     * @param array $data
     */
    public function __construct(
        Context $context,
        ElementFactory $elementFactory,
        CountryFactory $countryFactory,
        AddressMetadataInterface $metadataService,
        Mapper $addressMapper,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $elementFactory,
            $countryFactory,
            $metadataService,
            $addressMapper,
            $data
        );

        $this->_elementFactory = $elementFactory;
        $this->_countryFactory = $countryFactory;
        $this->_addressMetadataService = $metadataService;
        $this->addressMapper   = $addressMapper;
        $this->_isScopePrivate = true;
    }

    /**
     * Render address by attribute array
     *
     * @param array $addressAttributes
     * @param Format|null $format
     * @return string
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function renderArray($addressAttributes, $format = null)
    {
        switch ($this->getType()->getCode()) {
            case 'html':
                $dataFormat = ElementFactory::OUTPUT_FORMAT_HTML;
                break;
            case 'pdf':
                $dataFormat = ElementFactory::OUTPUT_FORMAT_PDF;
                break;
            case 'oneline':
                $dataFormat = ElementFactory::OUTPUT_FORMAT_ONELINE;
                break;
            default:
                $dataFormat = ElementFactory::OUTPUT_FORMAT_TEXT;
                break;
        }

        $attributesMetadata = $this->_addressMetadataService->getAllAttributesMetadata();
        $data = [];
        foreach ($attributesMetadata as $attributeMetadata) {
            if (!$attributeMetadata->isVisible()) {
                continue;
            }
            $attributeCode = $attributeMetadata->getAttributeCode();
            if ($attributeCode == 'country_id' && isset($addressAttributes['country_id'])) {
                $data['country'] = $this->_countryFactory->create()->loadByCode($addressAttributes['country_id'])->getName($addressAttributes['locale'] ?? null);
            } elseif ($attributeCode == 'region' && isset($addressAttributes['region'])) {
                $data['region'] = (string)__($addressAttributes['region']);
            } elseif (isset($addressAttributes[$attributeCode])) {

                // Custom code condition
                if (isset($addressAttributes['address_type']) && $addressAttributes['address_type'] == 'shipping') {
                    if ($attributeCode == 'invoice_type') {
                        $addressAttributes[$attributeCode] = '';
                    }
                }

                $value = $addressAttributes[$attributeCode];
                $dataModel = $this->_elementFactory->create($attributeMetadata, $value, 'customer_address');
                $value = $dataModel->outputValue($dataFormat);
                if ($attributeMetadata->getFrontendInput() == 'multiline') {
                    $values = $dataModel->outputValue(ElementFactory::OUTPUT_FORMAT_ARRAY);
                    // explode lines
                    foreach ($values as $k => $v) {
                        $key = sprintf('%s%d', $attributeCode, $k + 1);
                        $data[$key] = $v;
                    }
                }
                $data[$attributeCode] = $value;

                // Custom code condition
                if (isset($addressAttributes['address_type']) && $addressAttributes['address_type'] == 'billing') {
                    if ($attributeCode == 'invoice_type' && isset($data[$attributeCode])) {
                        if (isset($addressAttributes['locale']) && $addressAttributes['locale'] == 'it_IT') {
                            if ($data[$attributeCode] == 'Receipt') {
                                $data[$attributeCode] = __('Ricevuta');
                            } else if ($data[$attributeCode] == 'Invoice') {
                                $data[$attributeCode] = __('Fattura');
                            }
                        }
                    }
                }
            }
        }

        if ($this->getType()->getEscapeHtml()) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->escapeHtml($value);
            }
        }
        $format = $format !== null ? $format : $this->getFormatArray($addressAttributes);

        return $this->filterManager->template($format, ['variables' => $data]);
    }
}
