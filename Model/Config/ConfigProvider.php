<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ConfigProvider
{
    public const XML_PATH_ENABLED = 'hmh_review_auto_approval/verified_purchased/enabled';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function isEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
