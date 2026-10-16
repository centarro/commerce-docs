---
title: Shipping
taxonomy:
    category: docs
---

## Available shipping methods

!!! simple-card "![Commerce FedEx](../images/Fedex.png)"

    | Project page | <h4>[Commerce FedEx] :fontawesome-solid-share-from-square:</h4> |
    | ------- | ------- |
    | **Description** | This module provides FedEx shipping calculations for Drupal Commerce by extending the Commerce Shipping API. This module should be used by those that want to provide dynamic FedEx shipping rate calculations to their customers. | 

!!! simple-card "![Commerce NZPost](../images/NZ_Post.png)"

    | Project page | <h4>[Commerce NZPost] :fontawesome-solid-share-from-square:</h4> |
    | ---- | ---- |
    | **Description** | Integration of the NZ Post rate estimation API with Commerce Shipping. Provides estimated shipping costs for range of NZ Post international services. Does not currently provide domestic estimates. | 

!!! simple-card "![Commerce UPS](../images/UPS.png)"

    | Project page | <h4>[Commerce UPS] :fontawesome-solid-share-from-square:</h4> |
    | --- | --- |
    | **Description** | Provides UPS shipping estimates in conjunction with the Commerce Shipping and Commerce Physical modules. | 

??? simple-card "[Commerce USPS]"

    | **Description** | Provides USPS shipping rates for Commerce Shipping. |
    | --- | --- |

??? simple-card "[Commerce EasyPost]"

    | **Description** | Integrates EasyPost, a shipping API for multiple carriers, with Commerce Shipping. |
    | --- | --- |

??? simple-card "[Commerce ShipStation]"

    | **Description** | Sends orders to ShipStation for fulfillment, and imports shipment updates back into Drupal Commerce. |
    | --- | --- |

??? simple-card "[Commerce Shipping Weight Tariff]"

    | **Description** | Shipping rates from a table of weight brackets, across multiple shipping services. |
    | --- | --- |

Several of these modules only have alpha or beta releases for Drupal 10 and 11, so check each project page before relying on it.

## Create a shipping method

Shipping methods are created by clicking the *Add shipping method* button on `/admin/commerce/shipping-methods` or by going straight to on `/admin/commerce/shipping-methods/add`.

On this page you assign the method to any of your stores, and you can define the desired title, plugin, labels, description, amount, and conditions for your shipping method. 

The conditions allow you to create dedicated shipping methods for particular roles, countries, stores, products, order types, weight, and much more. 

[Commerce FedEx]: https://www.drupal.org/project/commerce_fedex
[Commerce NZPost]: https://www.drupal.org/project/commerce_nzpost
[Commerce USPS]: https://www.drupal.org/project/commerce_usps
[Commerce EasyPost]: https://www.drupal.org/project/commerce_easypost
[Commerce ShipStation]: https://www.drupal.org/project/commerce_shipstation
[Commerce Shipping Weight Tariff]: https://www.drupal.org/project/commerce_shipping_weight_tariff
[Commerce UPS]: https://www.drupal.org/project/commerce_ups