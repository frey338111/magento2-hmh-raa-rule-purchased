# Hmh Review Auto Approval Rule Verified Purchase

Adds a verified-purchase validation rule to `hmh/magento2-review-auto-approval`.

When enabled, product reviews are auto-approved only when the logged-in customer who submitted the review has purchased the reviewed product in a qualifying order.

## Requirements

- PHP 8.1 or later
- Magento 2
- `Magento_Review`
- `Magento_Sales`
- `Hmh_ReviewAutoApproval`

## Installation

Install the module under:

```text
app/code/Hmh/ReviewAutoApprovalRuleVerifiedPurchase
```

Then run:

```bash
bin/magento module:enable Hmh_ReviewAutoApprovalRuleVerifiedPurchase
bin/magento setup:upgrade
bin/magento cache:flush
```

For production mode, also run the usual deployment commands for your project, such as dependency injection compilation and static content deployment.

## Configuration

In the Magento Admin, go to:

```text
Stores > Configuration > HMH > Review Auto Approval
```

Enable:

```text
Auto Approval by Verified Purchase > Enabled
```

The setting is stored at:

```text
hmh_review_auto_approval/verified_purchased/enabled
```

The rule is disabled by default.

## How It Works

The module registers `PurchasedProductValidator` in the `Hmh_ReviewAutoApproval` validator pool.

When a logged-in storefront customer submits a product review, `ReviewAggregatePlugin` stores a mapping between the review and customer in the `hmh_review_customer` table.

During auto-approval validation, the module checks whether that customer has an order item for the reviewed product. Orders qualify when their state is:

- `processing`
- `complete`

If no matching purchase is found, the validator returns `false` and the review is not auto-approved by this rule.

If the verified-purchase rule is disabled for the current store scope, the validator returns `true` so it does not block the rest of the auto-approval flow.

## Database

The module creates the `hmh_review_customer` table with:

- `review_customer_id`
- `review_id`
- `customer_id`
- `verified_purchase`

The `review_id` column is unique and has a cascading foreign key to `review.review_id`.

## Notes

- Only logged-in storefront customers can be matched to a review.
- Guest reviews cannot pass the verified-purchase rule because no customer mapping is available.
- The verified-purchase flag is updated when the validator runs.
