---
title: Adapting from 1.x
taxonomy:
    category: docs
---

This section is for helping bridge the 1.x to 2.x gap for developers who have previously used Drupal Commerce.

## Understanding Drupal 8

!!! note "We need help filling out this section! Feel free to follow the *edit this page* link and contribute."

Link to resources for getting used to D8.

Topics like: dependency injecting, event subscribers. We shouldn't recreate existing efforts here, but link to them.

## Product information structure

!!! note "We need help filling out this section! Feel free to follow the *edit this page* link and contribute."

No more nodes.

Products -> Product variations.

1.x Products == 2.x variations. No directly edit for variations anymore.

Attributes are their own entity, not taxonomy terms.

## Taxes and VAT

!!! note "We need help filling out this section! Feel free to follow the *edit this page* link and contribute."

Core tax deprecates all VAT contrib.

## Shipping

!!! note "We need help filling out this section! Feel free to follow the *edit this page* link and contribute."

Explain

* Shipping has flat rate in it, now.
* 2.x shipping combines many contrib efforts
* Packaging logic, boxing, fulfillment.

## Discounts and coupons

!!! note "We need help filling out this section! Feel free to follow the *edit this page* link and contribute."

Now in core for discount + coupon as promotions.

## Price calculation

The base price of a product variation is the price without adjustments (i.e. without promotions, taxes, etc...). Price
resolving is the process of finding the base price of the variation. Price resolvers are services. The default price
resolver simply returns the price in the variation's price field.

To actually resolve a price, commerce uses the `commerce_price.chain_price_resolver` service which finds all price
resolver services and calls their `resolve()` method until one of them returns a price. For example, to support multiple
currencies one could implement a custom price resolver service fetching the price with the appropriate currency from a
multivalue price field.

Price resolvers do not know about the order the product has been added to. Enter order processors.

[Order refresh](../orders/getting-started/#order-refresh-and-processing) runs order processors to (among others) add
adjustments (e.g. promotions, taxes) to the order. An adjustment can rely on order data when changing the price.

The `commerce_order.price_calculator` service can be used to find out the variation price including adjustments. It is
used by the `commerce_price_calculated` field formatter implemented in the `commerce_order` module. It works by creating
an unsaved order and applying adjustments on it.

## Commerce without Rules

![](../images/RulesInCommerce2.png)

Commerce 2.x no longer relies on the Rules module. We now have event subscribers (example: order state changes),
resolvers (example: calculating sell price and calculating VAT), and entities configured with conditions (example:
payment gateways).

Be aware that some events correspond to state transitions, and
the [State Machine](https://drupal.org/project/state_machine) module fires them upon a transition. You can find the
metadata for these in `MODULENAME.workflows.yml`. Events are also documented in the `\Drupal\MODULENAME\Event` namespace
as a final class containing constants. For an example of using these, see
`\Drupal\commerce_log\EventSubscriber\OrderEventSubscriber::getSubscribedEvents()`.

<h3>Partial list of where the Rules went</h3>

| Commerce 1.x                                            | Commerce 2.x                                                                                                                                                                                                          |
|---------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Completing the checkout process** Rules event         | `commerce_order.place` **transition**; modules react to this and alter the order as needed                                                                                                                            |
| **When an order is first paid in full** Rules event     | `commerce_order.fulfill` transition (**note: does not correspond 1:1 to Drupal 7, but can be used for many of the same purposes**)                                                                                    |
| **After adding a product to the cart** Rules event      | `commerce_cart.entity.add` **event**                                                                                                                                                                                  |
| **After updating an existing commerce order**           | `commerce_order.commerce_order.update` event                                                                                                                                                                          |
| **Calculating the sell price of a product** event       | Now handled through price resolvers, tax resolvers, and order processors. Search for `tag: commerce_price.price_resolver` in the codebase for examples.                                                               |
| **Select available payment methods for an order** event | Now handled through payment gateway configurations at _Administration » Commerce » Configuration » Payment gateways_. These support various conditions to define when payment gateways should be usable by customers. |

<h3>More on resolvers</h3>

[Understanding Resolvers](../core/understanding_resolvers)

@todo: Link to relevant resolver docs, as well.

!!! note "We need help filling out this section! Feel free to follow the *edit this page* link and contribute."

Link to books and tutorials:

[Drupalize.me](https://drupalize.me/tutorials?taxonomy_versions_tid%5B%5D=1046)