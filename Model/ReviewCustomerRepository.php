<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Model;

use Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Api\Data\ReviewCustomerInterface;
use Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Api\ReviewCustomerRepositoryInterface;
use Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Model\ResourceModel\ReviewCustomer as ReviewCustomerResource;
use Magento\Framework\Exception\NoSuchEntityException;

class ReviewCustomerRepository implements ReviewCustomerRepositoryInterface
{
    public function __construct(
        private readonly ReviewCustomerResource $resource,
        private readonly ReviewCustomerFactory $reviewCustomerFactory
    ) {
    }

    public function save(ReviewCustomerInterface $reviewCustomer): ReviewCustomerInterface
    {
        $this->resource->save($reviewCustomer);

        return $reviewCustomer;
    }

    public function getByReviewId(int $reviewId): ReviewCustomerInterface
    {
        $reviewCustomer = $this->reviewCustomerFactory->create();
        $this->resource->load($reviewCustomer, $reviewId, ReviewCustomerInterface::REVIEW_ID);

        if (!$reviewCustomer->getId()) {
            throw new NoSuchEntityException(__('Review customer with review ID "%1" does not exist.', $reviewId));
        }

        return $reviewCustomer;
    }

    public function delete(ReviewCustomerInterface $reviewCustomer): bool
    {
        $this->resource->delete($reviewCustomer);

        return true;
    }

    public function deleteById(int $reviewCustomerId): bool
    {
        $reviewCustomer = $this->reviewCustomerFactory->create();
        $this->resource->load($reviewCustomer, $reviewCustomerId);

        if (!$reviewCustomer->getId()) {
            throw new NoSuchEntityException(
                __('Review customer with ID "%1" does not exist.', $reviewCustomerId)
            );
        }

        return $this->delete($reviewCustomer);
    }
}
