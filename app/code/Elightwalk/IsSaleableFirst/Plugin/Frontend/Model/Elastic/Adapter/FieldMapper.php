<?php

namespace Elightwalk\IsSaleableFirst\Plugin\Frontend\Model\Elastic\Adapter;

use Magento\Elasticsearch\Model\Adapter\FieldMapperInterface;

class FieldMapper
{
    /**
     * @param FieldMapperInterface $subject
     * @param $result
     * @param array $context
     * @return mixed
     */
    public function afterGetAllAttributesTypes($subject, $result, $context = [])
    {
        $result['available_qty'] = [
            'type'   => 'float',
            'fields' => [
                'sort_available_qty' => [
                    'type'  => 'keyword',
                    'index' => false
                ]
            ]
        ];

        return $result;
    }

    public function afterBuildEntityFields($subject, array $result)
    {
        return $this->afterGetAllAttributesTypes($subject, $result);
    }
}
