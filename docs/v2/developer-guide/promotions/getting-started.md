---
title: Getting started
taxonomy:
    category: docs
---

The Promotion module (`commerce_promotion`) provides discounts and coupons. Store administrators create promotions through the UI; see the [Promotions user guide](../../user-guide/promotions.md) for that side. This page explains how promotions work under the hood.

## Architecture

### Promotions

A promotion (`commerce_promotion`) is a content entity. Its main fields:

- **Order types** and **Stores**: where the promotion is available.
- **Offer type**: a single [offer plugin](offers.md) that calculates the discount.
- **Conditions** and **Condition operator**: the [conditions](../core/conditions.md) the order must meet, and whether all of them or only one must pass.
- **Start date** and **End date**: when the promotion is active, in the store's timezone.
- **Usage limit** and **Customer usage limit**: how many times the promotion can be used, in total and per customer.
- **Compatibility with other promotions**: whether the promotion can be combined with others.
- **Require a coupon to apply this promotion**, **Coupons** and **Allow multiple coupons**: see [Coupons](#coupons).
- **Weight**: the order in which promotions are applied.

### Offers

Offers are plugins that apply the discount, as `promotion` adjustments. Order offers adjust the order, and order item offers adjust each matching order item. Commerce Core provides:

- Fixed amount off the order subtotal
- Percentage off the order subtotal
- Fixed amount off each matching product
- Percentage off each matching product
- Buy X Get Y
- Combination offer, which applies several offers at once

Shipping offers (such as a fixed amount or percentage off the shipment amount) are provided by [Commerce Shipping](https://www.drupal.org/project/commerce_shipping). See [Offers](offers.md) to create your own.

### Coupons

A coupon (`commerce_promotion_coupon`) is a code that unlocks a promotion. A promotion that requires a coupon only applies to orders that have one of its coupons. Coupons have their own usage limits, and can be generated in bulk.

Customers enter coupon codes in the *Coupon redemption* checkout pane (`coupon_redemption`), which you can add to your checkout flow. Applied coupons are stored on the order's `coupons` field.

## How promotions are applied

Promotions are applied during the [order refresh](../orders/getting-started.md#order-refresh-and-processing). The `PromotionOrderProcessor` service (`commerce_promotion.promotion_order_processor`):

1. Removes coupons that no longer apply to the order.
2. Loads the promotions available for the order: enabled, active for the order's store and order type, within their dates and below their usage limits. This includes promotions unlocked by the order's coupons.
3. Sorts them by weight, when the *enforce weight ordering* setting is enabled (the default; otherwise promotions unlocked by coupons come first), then applies each one whose conditions pass and that is compatible with the promotions already applied.

Usage is recorded by the `commerce_promotion.usage` service when the order is placed. It isn't released if the order is later canceled.

## Customizing

- **Filter the available promotions** for an order by subscribing to the `PromotionEvents::FILTER_PROMOTIONS` event.
- **React to promotion or coupon changes** with the entity events in `PromotionEvents`, such as `PROMOTION_INSERT` or `COUPON_UPDATE`.
- **Add an offer type** by writing an offer plugin: see [Offers](offers.md).
- **Add a condition** by writing a condition plugin: see [Conditions](../core/conditions.md).
