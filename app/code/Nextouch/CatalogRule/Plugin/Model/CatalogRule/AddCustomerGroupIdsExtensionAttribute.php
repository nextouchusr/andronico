<?php
declare(strict_types=1);

namespace Nextouch\CatalogRule\Plugin\Model\CatalogRule;

use Magento\CatalogRule\Api\CatalogRuleRepositoryInterface;
use Magento\CatalogRule\Api\Data\RuleExtensionFactory;
use Magento\CatalogRule\Api\Data\RuleInterface;

class AddCustomerGroupIdsExtensionAttribute
{
    private RuleExtensionFactory $ruleExtensionFactory;

    public function __construct(RuleExtensionFactory $ruleExtensionFactory)
    {
        $this->ruleExtensionFactory = $ruleExtensionFactory;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function afterGet(CatalogRuleRepositoryInterface $subject, RuleInterface $rule): RuleInterface
    {
        $customerGroupIds = (array) $rule->getCustomerGroupIds();
        $extensionAttributes = $rule->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ?: $this->ruleExtensionFactory->create();
        $extensionAttributes->setCustomerGroupIds($customerGroupIds);
        $rule->setExtensionAttributes($extensionAttributes);

        return $rule;
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     */
    public function beforeSave(CatalogRuleRepositoryInterface $subject, RuleInterface $rule): array
    {
        $extensionAttributes = $rule->getExtensionAttributes() ?: $this->ruleExtensionFactory->create();

        if ($extensionAttributes->getCustomerGroupIds()) {
            $rule->setCustomerGroupIds($extensionAttributes->getCustomerGroupIds());
        }

        return [$rule];
    }
}
