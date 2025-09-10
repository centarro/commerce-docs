---
title: Subscriptions
taxonomy:
    category: docs
---

Subscriptions and recurring billing in Commerce 2.x are created and managed via the [Commerce Recurring module](https://www.drupal.org/project/commerce_recurring). It supports a wide variety of types of subscriptions, payment plans, proration, etc. Out-of-the-box, the Commerce Recurring module provides rolling and fixed billing schedules that are either prepaid or postpaid. However, the module can be easily extended with custom plugins. Some sample use cases include...

| Use Case | Billing schedule | Proration? |
| -------- | ---------------- |----------- |
| A software subscription is charged monthly starting on the date the customer purchases. | Prepaid / Rolling | No |
| An ecommerce retailer offers "membership" benefits such as free shipping in exchange for an annually recurring fee. | Prepaid / Rolling | No |
| A maintenance company offers a monthly service plan on equipment, payable on the first of the month. | Prepaid / Fixed | Yes |
| A cosmetics subscription gift box service sends customers product samples each month, charging them a fee on the last day of each month. | Postpaid / Fixed | No |
| A magazine subscription service sends the first issue immediately and then charges customers on the 5th of each month.| Postpaid / Fixed | No |

## Overview

The Commerce Recurring module provides a variety of options for creating subscriptions. You probably have a general idea of what subscriptions are. You might have a monthly magazine subscription or a subscription for a streaming video service, or you might be enrolled in a cheese-of-the-month club. But exactly how are subscriptions different than regular products in Drupal Commerce? Normally, when an order is placed, the customer is charged for the product, just once, at check-out. With the Commerce Recurring module, you can instead charge a customer repeatedly, at regular intervals, starting at the time the order is placed.

<a name="billing-periods"></a>Those regular intervals are defined as **billing periods** within the Commerce Recurring module. Some examples of billing periods are:

 * from January 1st 00:00:00 to January 1st 00:00:00 (yearly)
 * from Oct 14th 14:56:20 to Nov 14th 14:56:20 (monthly)
 * from May 1st 00:00:00 to May 15th 00:00:00 (bi-weekly)
 * from September 13th 03:00:00 to September 14th 03:00:00 (daily)
 * from June 10th 02:30:00 to June 10th 14:30:00 (every 12 hours)

Billing periods are contiguous and represent [half-open ranges](https://wrschneider.github.io/2014/01/07/time-intervals-and-other-ranges-should.html) (i.e., the end date is not included in the duration).

**How is a customer charged "repeatedly"?** The Commerce Recurring module relies on the Drupal's [Cron service](https://api.drupal.org/api/drupal/core%21core.api.php/function/hook_cron/8.5.x) and the [AdvancedQueue module](https://www.drupal.org/project/advancedqueue) to run recurring tasks in the background. Specifically, at the end of each billing period, one AdvancedQueue job is created for charging the customer for the current billing period, and a second AdvancedQueue job is created for renewing the subscription. See [Close and renew subscriptions](../subscriptions/#close-and-renew-subscriptions) for additional details.


## Subscription types

Out of the box, the Commerce Recurring module provides two types of subscriptions: `product variation` and `standalone`. Product variation type subscriptions have an underlying product variation that defines the base price for the subscription. Customers can place orders for subscriptions of this type by adding those product variations to their carts. Standalone type subscriptions can be created for a customer through the Admin UI; the base price for a standalone type subscription is set directly through the Admin UI.  Subscription types contain billing logic that defines how much the customer should be charged.

See also:

 * Product variation subscriptions: [Setting up subscriptions](#setting-up-subscriptions-first-steps)
 * Standalone subscriptions: [Create standalone subscriptions](../subscriptions/#create-standalone-subscriptions) 
 * Subscription Type plugin and charges

## Billing Schedules

Regardless of its subscription type, every subscription also has a Billing schedule. A billing schedule defines when and how to bill a customer for the subscription. Should a customer be billed on an annual, monthly, or daily basis? Or should some other billing period be used? Should the customer be charged right when the order is placed or not until the end of the billing period? Does the billing period always start on the first day of the month or if, for example, the customer initially places an order on the 10th of the month, should the subscription be billed and automatically renewed on the 10th of every month? And what about proration? If a customer places an order in June for an annualy renewable subscription, should he be billed for a full year or should the charges be *prorated* so that the customer is only charged for half the standard price? All of this and more is defined by the billing schedule. So let's look a little more closely at billing schedules.


A billing schedule is a configuration entity that stores settings on how/when a product variation will be billed. Billing schedules are created through the admin UI: `Commerce >> Configuration >> Payment`. The primary components that define the funtionality of a billing schedule are:

* [Billing type](#billing-types)
* [Billing schedule plugin](#billing-schedule-plugin)
* [Prorater](#prorater)
* [Dunning](#dunning)

![Billing schedule admin ui](../images/billing_schedule_admin_ui.png) 


### Billing types

Each billing schedule has a billing type, which is either prepaid or postpaid (charge at the beginning or at the end of the billing period). If a subscription is postpaid, then the customer will not be charged for the subscription at the time the order is placed. Instead, the customer will be charged for the first billing period at the end of that period. If a subscription is prepaid, the customer will be charged at the time the order is placed and will subsequently be charged at the start of each billing period for the duration of the subscription.


### Billing schedule plugin

A billing schedule is powered by a billing schedule plugin, which is responsible for generating the billing period. Out of the box, the Commerce Recurring module supports rolling and fixed billing periods. Rolling periods start at the moment of subscription and last until the configured interval passes. Fixed periods start at the beginning of the configured interval (some number of hours, days, weeks, months, or years). 

Fixed billing schedules with monthly intervals are defined with a Start day (1 - 31). Fixed billing schedules with yearly intervals are defined with both a Start day and a Start month (Jaunuary - December). The billing schedule plugin is configured through the admin UI for the billing schedule configuration entity. 

Suppose a new annual subscription is purchased on October 12th. If the first billing period should be October 12 - October 12, then you should set up a "Rolling" billing schedule. All subsequent billing periods for this subscription will also begin and end on October 12th. Suppose, on the other hand, that you want the annual subscription to start on January 1st every year. Then you should set up a "Fixed" billing schedule; the first billing period will run from October 12 to January 1st, and subsequent billing periods will start and end on January 1st. Here is the configuration for this example "Fixed" annual subscription: 

![Fixed_annual_billing_schedule](../images/Fixed_annual_billing_schedule.png)


### Prorater

In some cases, it may be appropriate to prorate, or adjust, the price charged for a subscription in shorter-than-usual billing periods. For example, if the subscription is a time-based service (such as a streaming video service), and the subscription is only active for a portion of the billing period, then proration can be used to reduce the price charged. In the Commerce Recurring module, this functionality is managed by the billing schedule's `Prorater plugin`. 

The `Proportional` prorater plugin adjusts the subscription price based on the duration of the subscription's usage. For example, suppose a subscription that charges $1,000 per year is only active for 3 months of a year. The proportional prorater plugin will reduce the charge for that year to just a quarter of the full amount: $250.

In other cases, such as a magazine subscription, proration might not be appropriate. A magazine is a physical product, and its usage is not time-based, so using the proportional prorater plugin doesn't make sense. Instead, the `Fixed-Price` prorater plugin should be selected for the billing schedule. The fixed-price prorater plugin will not reduce a subscription's price in shorter-than-usual billing periods; the full price will always be charged.


### Dunning

As long as a subscription is active, the customer is automatically charged at regular intervals for the continued usage of the subscription. If the attempt to collect payment from the customer fails, the billing schedule's Dunning settings are used to determine what should happen next:

* How many retries should be attempted? (The default is 3 retries; the minimum is 1, and the maximum is 8.)
* How many days should pass between retries?
* After the final retry, should be subscription remain active or be canceled?

![billing_schedule_dunning-1](../images/billing_schedule_dunning.png)

## Setting up subscriptions: First steps

<h3>Step 1 - Create/enable a payment method</h3>

Each subscription has a Payment method, so you'll need to set up at least one enabled payment gateway that can be used by a customer to create a payment method for subscriptions. Note that the payment gateway must support stored payment methods, e.g. card on file, continuous authorizations, etc. Please refer to [the list of known payment gateways that are being supported by their maintainers](../../payments/gateways) to identify "onsite" gateways that can be used.

<h3>Step 2 - Create a billing schedule.</h3>

Billing schedules are configuration entities that can be created through the admin UI: `Commerce >> Configuration >> Payment`. See [Subscriptions overview](#overview) for additional information on billing schedules.

<h3>Step 3 - Enable subscriptions for a product variation type</h3>

While it is possible to [create standalone subscriptions](../subscriptions/#create-standalone-subscriptions), the more typical case is to create a product variation that will trigger the subscription. The Commerce Recurring module defines a `PurchasableEntitySubscriptionTrait` for product variations. This trait can be enabled by checking the "Allow subscriptions" checkbox on the Product variation type edit form. 

When the trait is enabled for a product variation type, two new fields are added to product variations of this type. One is a reference to the Billing schedule configuration entity. The second additional field is a reference to a Subscription Type plugin. Unless a custom subscription type plugin has been created, the only option you'll see for the variation's subscription type is, "Product variation."

<h3>Step 4 - Create a new product and variation</h3>

Create a new product with a variation of the type you added in step 3. Select the billing schedule you added in step 2 and the "product variation" subscription type. You should now be able to place an order for a subscription by adding this new product to your cart.


<h3>Adding a subscription product variation to the cart</h3>

After the product variation is added to the cart, the price charged for the order item may need to be adjusted if prorating is required or the item has a "Postpaid" billing schedule. The Commerce Recurring module does this by adding a "subscription" type Adjustment to the order. (The order item's price remains unchanged.) This order processing takes place in the `process()` method of the `InitialOrderProcessor` service.

If the billing type for the variation's billing schedule is "prepaid", the billing schedule's plugin is used to generate the "first" billing period. And the billing schedule's prorater plugin uses that billing period to calculate a prorated unit price. If the prorated unit price is less than the order item's unit price, an adjustment is created in the amount of the difference between the two prices.

If the variation's billing schedule is "postpaid", then the customer should not be charged for the subscription order item at all. So a negative adjustment in the amount of the order's unit prices is created.


In the next section, we'll look at what happens when checkout completes for an order that includes a subscription/recurring product.