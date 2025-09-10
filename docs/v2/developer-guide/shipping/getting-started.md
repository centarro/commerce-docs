---
title: Shipping
taxonomy:
    category: docs
---

!!! note "We need help filling out this section! Feel free to follow the *edit this page* link and contribute."

## Available shipping methods

!!! simple-card "![Commerce FedEx](../images/Fedex.png)"

    | Project page | <h4>[Commerce FedEx] :fontawesome-solid-share-from-square:</h4> |
    | ------- | ------- |
    | **Description** | This module provides FedEx shipping calculations for Drupal Commerce by extending the Commerce Shipping API. This module should be used by those that want to provide dynamic FedEx shipping rate calculations to their customers. | 

!!! simple-card "![Commerce NZPost](../images/NZ_Post.png)"

    | Project page | <h4>[Commerce NZPost] :fontawesome-solid-share-from-square:</h4> |
    | ---- | ---- |
    | **Description** | Integration of the NZ Post rate estimation API with Commerce Shipping. Provides estimated shipping costs for range of NZ Post international services. Does not currently provide domestic estimates. | 

!!! simple-card "![Commerce ShipEngine](../images/Shipengine_logo.png)"

    | Project page | <h4>[Commerce ShipEngine] :fontawesome-solid-share-from-square:</h4> |
    | ---- | ---- |
    | **Description** | ShipEngine provides a common API for multiple shipping carriers. A company using multiple carriers (UPS, USPS, FedEx) may find their services useful. | 

!!! simple-card "![Commerce UPS](../images/UPS.png)"

    | Project page | <h4>[Commerce UPS] :fontawesome-solid-share-from-square:</h4> |
    | --- | --- |
    | **Description** | Provides UPS shipping estimates in conjunction with the Commerce Shipping and Commerce Physical modules. | 

## Create a shipping method

Shipping methods are created by clicking the *Add shipping method* button on `/admin/commerce/shipping-methods` or by going straight to on `/admin/commerce/shipping-methods/add`.

On this page you assign the method to any of your stores, and you can define the desired title, plugin, labels, description, amount, and conditions for your shipping method. 

The conditions allow you to create dedicated shipping methods for particular roles, countries, stores, products, order types, weight, and much more. 

[Commerce Australia Post]: https://www.drupal.org/project/commerce_auspost
[Commerce FedEx]: https://www.drupal.org/project/commerce_fedex
[Commerce NZPost]: https://www.drupal.org/project/commerce_nzpost
[Commerce ShipEngine]: https://www.drupal.org/project/commerce_shipengine
[Commerce UPS]: https://www.drupal.org/project/commerce_ups