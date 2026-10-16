---
title: Payment configuration
taxonomy:
    category: docs
---

To accept payments, you need at least one payment gateway, and a checkout flow that collects and processes payment.

## Terms and concepts

### Payment

A payment is a record of money moving between the customer and your store for an order. Each payment belongs to one order, is processed by one payment gateway and has a state, such as *Authorization*, *Completed* or *Refunded*. An order can have several payments, for example a partial refund or a payment split across methods.

### Payment method

A payment method is the customer's means of paying, such as a credit card or a PayPal account. With gateways that support it, payment methods can be stored and reused for later orders. Customers can manage their stored payment methods from their account, at `/user/{user}/payment-methods`.

### Payment method type

The payment method type defines what kind of payment method it is and what information it holds. Commerce Core provides the *Credit card* and *PayPal* types, and payment gateway modules can add their own.

### Payment gateway

A payment gateway connects your store to a payment provider (such as PayPal, Stripe or Authorize.Net) and holds its configuration: API credentials, test or live mode, and when it's available. Gateways are either:

- **On-site**: customers enter their payment details on your checkout page, usually in fields hosted by the provider.
- **Off-site**: customers are redirected to the provider's site, or shown its payment form in an embedded frame, and then return to your site.
- **Manual**: no provider is involved. The customer pays outside of the site, for example by bank transfer, cash on delivery or check, and an administrator records the payment.

Commerce Core only provides the *Manual* gateway. Other gateways come from contributed modules; see the [list of available payment gateways](../../developer-guide/payments/gateways.md).

### Transaction types

Depending on the gateway, a payment can go through these transactions:

- **Authorize**: reserves the amount on the customer's payment method without taking the money yet.
- **Capture**: takes the money for a previously authorized payment. You can capture less than the authorized amount.
- **Authorize and capture**: both in one step, which is the default.
- **Void**: cancels an authorization that hasn't been captured.
- **Refund**: returns all or part of a captured payment to the customer.

See [Payment administration](payment-administration.md) for how to perform these operations on an order.

## Payment gateway configuration

Payment gateways are managed at *Commerce > Configuration > Payment > Payment gateways* (`/admin/commerce/config/payment-gateways`).

1. Install the module for your payment provider, for example `composer require drupal/commerce_paypal`, then enable it.
2. Click *Add payment gateway*.
3. Fill in the form:
    - **Name**: the administrative name of the gateway.
    - **Plugin**: the gateway type. A module can provide several, for example PayPal Checkout and Fastlane.
    - **Display name**: shown to customers during checkout.
    - **Mode**: *Test* or *Live*, when the provider has a sandbox. Use *Test* while developing.
    - **Payment method types**: which types this gateway accepts, when it supports more than one.
    - **Collect billing information**: whether checkout asks for a billing address with this gateway. Before disabling it, make sure you are not legally required to collect billing information.
    - The provider's own settings, such as API keys. Manual gateways have **Payment instructions** instead, shown to the customer after checkout.
    - **Conditions**: limit when the gateway is offered, for example only to some stores, order totals or customer roles. Choose whether *all conditions must pass* or *only one condition must pass*.
    - **Status**: *Enabled* or *Disabled*.
4. Save the gateway.

When several gateways are available for an order, the customer chooses between them at checkout, in the order of the gateway list.

Instructions for specific gateways: [PayPal](integrations/paypal/paypal.md), [Fastlane by PayPal](integrations/fastlane-by-paypal/fastlane-by-paypal.md), [Stripe](integrations/stripe/stripe.md).

## Checkout configuration

Payment is handled by two checkout panes, configured in your checkout flow at *Commerce > Configuration > Orders > Checkout flows* (`/admin/commerce/config/checkout-flows`). See [Checkout](../checkout.md) for how checkout flows work.

### Payment information

Collects the payment method and, if the gateway requires it, the billing information. Its settings:

- **Always display payment methods**: show the payment method selection even when only one method is available.
- **Collect payment methods on orders with zero balance**: ask for a payment method even when the order total is zero, for example to charge a subscription later. Some modules override this setting.

### Payment process

Creates the payment when the customer completes checkout. By default it sits on its own *Payment* step, after *Review*; keep it after the *Payment information* pane, which is where customers are sent back to if the payment fails. Its setting:

- **Transaction mode**: *Authorize and capture*, or *Authorize only (requires manual capture after checkout)*. This is only respected if the chosen gateway supports authorizations.
