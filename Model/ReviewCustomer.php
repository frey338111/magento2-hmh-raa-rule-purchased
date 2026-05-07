<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Model;

use Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Api\Data\ReviewCustomerInterface;
use Magento\Framework\Model\AbstractModel;

class ReviewCustomer extends AbstractModel implements ReviewCustomerInterface
{
    protected function _construct(): void
    {
        $this->_init(ResourceModel\ReviewCustomer::class);
    }

    public function getReviewCustomerId(): ?int
    {
        $value = $this->getData(self::REVIEW_CUSTOMER_ID);

        return $value === null ? null : (int) $value;
    }

    public function setReviewCustomerId(int $reviewCustomerId): ReviewCustomerInterface
    {
        return $this->setData(self::REVIEW_CUSTOMER_ID, $reviewCustomerId);
    }

    public function getReviewId(): ?int
    {
        $value = $this->getData(self::REVIEW_ID);

        return $value === null ? null : (int) $value;
    }

    public function setReviewId(int $reviewId): ReviewCustomerInterface
    {
        return $this->setData(self::REVIEW_ID, $reviewId);
    }

    public function getCustomerId(): int
    {
        return (int) $this->getData(self::CUSTOMER_ID);
    }

    public function setCustomerId(int $customerId): ReviewCustomerInterface
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    public function isVerifiedPurchase(): bool
    {
        return (bool) $this->getData(self::VERIFIED_PURCHASE);
    }

    public function setVerifiedPurchase(bool $verifiedPurchase): ReviewCustomerInterface
    {
        return $this->setData(self::VERIFIED_PURCHASE, $verifiedPurchase);
    }
}
