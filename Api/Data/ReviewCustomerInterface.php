<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Api\Data;

interface ReviewCustomerInterface
{
    public const REVIEW_CUSTOMER_ID = 'review_customer_id';
    public const REVIEW_ID = 'review_id';
    public const CUSTOMER_ID = 'customer_id';
    public const VERIFIED_PURCHASE = 'verified_purchase';

    public function getReviewCustomerId(): ?int;

    public function setReviewCustomerId(int $reviewCustomerId): ReviewCustomerInterface;

    public function getReviewId(): ?int;

    public function setReviewId(int $reviewId): ReviewCustomerInterface;

    public function getCustomerId(): int;

    public function setCustomerId(int $customerId): ReviewCustomerInterface;

    public function isVerifiedPurchase(): bool;

    public function setVerifiedPurchase(bool $verifiedPurchase): ReviewCustomerInterface;
}
