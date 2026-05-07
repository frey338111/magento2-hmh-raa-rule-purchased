<?php

declare(strict_types=1);

namespace Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Model\Validator\Rules;

use Hmh\ReviewAutoApproval\Model\Validator\Rules\AbstractValidator;
use Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Api\Data\ReviewCustomerInterface;
use Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Api\ReviewCustomerRepositoryInterface;
use Hmh\ReviewAutoApprovalRuleVerifiedPurchase\Model\Config\ConfigProvider;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Review\Model\Review;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory as OrderItemCollectionFactory;

class PurchasedProductValidator extends AbstractValidator
{
    private const VALID_ORDER_STATES = [
        Order::STATE_PROCESSING,
        Order::STATE_COMPLETE,
    ];

    public function __construct(
        private readonly ConfigProvider $configProvider,
        private readonly ReviewCustomerRepositoryInterface $reviewCustomerRepository,
        private readonly OrderItemCollectionFactory $orderItemCollectionFactory
    ) {
    }

    public function isValid(Review $review): bool
    {
        $storeId = $this->getStoreId($review);
        if (!$this->configProvider->isEnabled($storeId)) {
            return true;
        }

        try {
            $reviewCustomer = $this->reviewCustomerRepository->getByReviewId((int) $review->getId());
        } catch (NoSuchEntityException) {
            return false;
        }

        $hasPurchased = $this->hasPurchasedProduct(
            $reviewCustomer->getCustomerId(),
            (int) $review->getEntityPkValue()
        );

        $this->updateVerifiedPurchase($reviewCustomer, $hasPurchased);

        return $hasPurchased;
    }

    private function hasPurchasedProduct(int $customerId, int $productId): bool
    {
        if ($customerId <= 0 || $productId <= 0) {
            return false;
        }

        $orderItemCollection = $this->orderItemCollectionFactory->create();
        $orderItemCollection->addFieldToSelect('item_id')
            ->addFieldToFilter('main_table.product_id', $productId)
            ->setPageSize(1);

        $orderItemCollection->getSelect()
            ->joinInner(
                ['sales_order' => $orderItemCollection->getTable('sales_order')],
                'sales_order.entity_id = main_table.order_id',
                []
            )
            ->where('sales_order.customer_id = ?', $customerId)
            ->where('sales_order.state IN (?)', self::VALID_ORDER_STATES);

        return (bool) $orderItemCollection->getFirstItem()->getId();
    }

    private function updateVerifiedPurchase(ReviewCustomerInterface $reviewCustomer, bool $verifiedPurchase): void
    {
        if ($reviewCustomer->isVerifiedPurchase() === $verifiedPurchase) {
            return;
        }

        $reviewCustomer->setVerifiedPurchase($verifiedPurchase);
        $this->reviewCustomerRepository->save($reviewCustomer);
    }
}
