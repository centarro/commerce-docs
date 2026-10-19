---
title: Introduction
taxonomy:
    category: docs
---

# What is Drupal Commerce?

Drupal Commerce is an open-source eCommerce platform that lets you sell any type of product to anyone in the world. It powers _billions_ in sales across thousands of online stores.

Drupal Commerce 3.x runs on Drupal 10.3 and later, including Drupal 11. Adding the [Commerce Core](https://www.drupal.org/project/commerce) project to a Drupal site gives you a foundation of eCommerce data objects, user interfaces, and subsystems. You’ll add configuration and custom code to meet your requirements, then build and deploy as you go.

Check out the [Drupal Commerce Features Overview](https://www.centarro.io/drupal-commerce/features) to see all the platform can do.

## Start with Commerce Kickstart

[Commerce Kickstart](https://www.drupal.org/project/commerce_kickstart) is the fastest way to start building. It installs Drupal 11, Commerce Core and a curated set of Centarro maintained modules and themes, with an optional full demo store, so you can develop and test in a feature-rich environment:

```bash
composer create-project centarro/commerce-kickstart-project kickstart
```

Already have a Drupal site, or prefer starting from scratch? Just add Commerce to it. See the [installation guide](./v2/installation.md#installation).

## Try it out!

If you want a quick look at the customer experience, the [demo store](https://commerce.demo.centarro.io) shows the out-of-the-box functionality you get with Commerce Kickstart: Layout Builder landing and product pages, faceted search built with Search API, and the default shopping cart and checkout forms (with guest checkout enabled).

!!! info "Latest version"

    - Read the release notes: {{ commerce_latest_release }}

<div class="grid cards" markdown>

-   [:material-flash:{ .lg .middle } __Getting started__](v2/getting-started.md)

    ---

    [__Install__](v2/installation.md#installation), [__update__](v2/installation.md#updating) and [__uninstall__](v2/installation.md#uninstalling)

</div>

<hr>

## User Guide

<div class="grid cards icon-grid" markdown>

- [:centarro-store:{ .lg .middle }  __Set up your store__](v2/user-guide/store-setup.md)

- [:centarro-box-seam-fill:{ .lg .middle }  __Manage your products__](v2/user-guide/products.md)

- [:centarro-clipboard-check-fill:{ .lg .middle }  __Manage your orders__](v2/user-guide/orders.md)

- [:centarro-truck-front-fill:{ .lg .middle } __Set up your shipping__](v2/user-guide/shipping.md)

- [:fontawesome-solid-credit-card:{ .lg .middle }  __Manage your payment gateways__](v2/user-guide/payment/payment-administration.md)

- [:centarro-bookmark-star-fill:{ .lg .middle } __Set up your promotions__](v2/user-guide/promotions.md)

</div>

<hr>

## Developer Guide

#### Get started with development


<div class="grid cards three-grid" markdown>

  - [__Events__](./v2/developer-guide/events.md)

  - [__Core__](v2/developer-guide/core/core.md)

  - [__Products__](v2/developer-guide/products/getting-started.md)

  - [__Cart__](./v2/developer-guide/cart/cart-events.md)

  - [__Customers__](./v2/developer-guide/customers/getting-started.md)

  - [__Payments__](./v2/developer-guide/payments/getting-started.md)

  - [__Stores__](./v2/developer-guide/stores/getting-started.md)

  - [__Orders__](./v2/developer-guide/orders/getting-started.md)

  - [__Checkout__](./v2/developer-guide/checkout/checkout.md)

  - [__Pricing__](./v2/developer-guide/pricing/getting-started.md)

  - [__Promotions__](./v2/developer-guide/promotions/getting-started.md)

  - [__Activity log__](./v2/developer-guide/activity-log/getting-started.md)

  - [__Shipping__](./v2/developer-guide/shipping/getting-started.md)

  - [__Recurring__](./v2/developer-guide/recurring/getting-started.md)

</div>

<hr>

!!! question "Need help customizing your store? Hire Centarro."

    Our vision is **merchant success** and we offer a suite of [Drupal Commerce Development services]{target=_blank} to make it happen.

[Drupal Commerce Development services]: https://www.centarro.io
