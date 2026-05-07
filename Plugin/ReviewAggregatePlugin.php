<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Plugin;

use Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Api\ReviewCustomerRepositoryInterface;
use Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Model\Config\ConfigProvider;
use Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Model\ReviewCustomerFactory;
use Magento\Customer\Model\SessionFactory as CustomerSessionFactory;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Review\Model\Review;
use Psr\Log\LoggerInterface;

class ReviewAggregatePlugin
{
    public function __construct(
        private readonly ConfigProvider $configProvider,
        private readonly State $appState,
        private readonly CustomerSessionFactory $customerSessionFactory,
        private readonly ReviewCustomerRepositoryInterface $reviewCustomerRepository,
        private readonly ReviewCustomerFactory $reviewCustomerFactory,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Capture the logged-in storefront customer who submitted the review.
     */
    public function afterAggregate(Review $subject, Review $result): Review
    {
        if (!$this->isFrontendArea() || !$subject->getId()) {
            return $result;
        }

        if (!$this->configProvider->isEnabled($this->getStoreId($subject))) {
            return $result;
        }

        if ((int) $subject->getEntityId() !== (int) $subject->getEntityIdByCode(Review::ENTITY_PRODUCT_CODE)) {
            return $result;
        }

        $customerSession = $this->customerSessionFactory->create();
        if (!$customerSession->isLoggedIn()) {
            return $result;
        }

        $reviewId = (int) $subject->getId();
        $customerId = (int) $customerSession->getCustomerId();
        if (!$reviewId || !$customerId) {
            return $result;
        }

        try {
            $this->reviewCustomerRepository->getByReviewId($reviewId);

            return $result;
        } catch (NoSuchEntityException) {
            $reviewCustomer = $this->reviewCustomerFactory->create();
            $reviewCustomer->setReviewId($reviewId)
                ->setCustomerId($customerId)
                ->setVerifiedPurchase(false);
        }

        try {
            $this->reviewCustomerRepository->save($reviewCustomer);
        } catch (\Throwable $exception) {
            $this->logger->error(
                'Failed to save review customer mapping.',
                [
                    'review_id' => $reviewId,
                    'customer_id' => $customerId,
                    'exception' => $exception->getMessage(),
                ]
            );
        }

        return $result;
    }

    private function isFrontendArea(): bool
    {
        try {
            return $this->appState->getAreaCode() === Area::AREA_FRONTEND;
        } catch (LocalizedException) {
            return false;
        }
    }

    private function getStoreId(Review $review): ?int
    {
        $storeId = (int) $review->getStoreId();
        if ($storeId > 0) {
            return $storeId;
        }

        $stores = array_map('intval', (array) $review->getStores());
        foreach ($stores as $storeId) {
            if ($storeId > 0) {
                return $storeId;
            }
        }

        return null;
    }
}
