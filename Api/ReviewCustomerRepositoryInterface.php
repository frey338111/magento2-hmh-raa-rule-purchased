<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Api;

use Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Api\Data\ReviewCustomerInterface;

interface ReviewCustomerRepositoryInterface
{
    /**
     * Save review customer mapping.
     */
    public function save(ReviewCustomerInterface $reviewCustomer): ReviewCustomerInterface;

    /**
     * Get review customer mapping by review ID.
     */
    public function getByReviewId(int $reviewId): ReviewCustomerInterface;

    /**
     * Delete review customer mapping.
     */
    public function delete(ReviewCustomerInterface $reviewCustomer): bool;

    /**
     * Delete review customer mapping by review customer ID.
     */
    public function deleteById(int $reviewCustomerId): bool;
}
