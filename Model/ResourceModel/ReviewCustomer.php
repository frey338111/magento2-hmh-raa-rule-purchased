<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ReviewCustomer extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('hmh_review_customer', 'review_customer_id');
    }
}
