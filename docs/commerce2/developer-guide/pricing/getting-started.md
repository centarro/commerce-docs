---
title: Pricing
taxonomy:
    category: docs
---

Pricing is an important aspect of any eCommerce application. Drupal Commerce supports complex business requirements for prices that vary based on context through its use of [Price resolvers](#price-resolvers) and [Adjustments](#adjustments). Additionally, Drupal Commerce uses the internationally-recognized standard of [CLDR] data to support every language and every denomination of currency.

## [Prices](../prices)

- Overview of the Price value object and methods
- Price Rounder service
- Price field type and usage

### [Pricing formatters](../prices/#pricing-formatters)

- Overview of Commerce Price repositories
- Number format value object and how to alter format definitions
- Overview of Commerce Price formatters

### [Displaying prices](../prices/#displaying-prices)

- Plain, Default, and Calculated price formatters
- Rendering prices in Twig

### [Price resolvers](../prices/#price-resolvers)

- Example code for a custom price resolver for a multi-store site
- Links and resources for custom price resolvers

## [Adjustments](../adjustments)

- Adjustment types and how to customize them
- Overview of the Adjustment value object and methods
- Price Splitter and Adjustment Transformer services
- Adjustment field type and default widget

## [Currencies](../currencies)

- Overview of currency support in Drupal Commerce
- Currency configuration entity
- Importing currencies

[CLDR]: http://cldr.unicode.org/
