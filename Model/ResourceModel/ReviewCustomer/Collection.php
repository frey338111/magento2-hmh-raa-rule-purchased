<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Model\ResourceModel\ReviewCustomer;

use Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Model\ResourceModel\ReviewCustomer as ReviewCustomerResource;
use Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Model\ReviewCustomer;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(ReviewCustomer::class, ReviewCustomerResource::class);
    }
}
