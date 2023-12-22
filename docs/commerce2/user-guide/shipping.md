---
title: Shipping
taxonomy:
    category: docs
---

**@todo! We need help filling out this section! Feel free to follow the *edit this page* link and contribute.**

Shipping is enabled in Drupal Commerce 2.x with an external module
[Commerce Shipping](https://drupal.org/project/commerce_shipping). This
module only provides an API and plugins for flat rate (both per order and per
item) shipping functionality. As with Drupal Commerce 1.x, you will need a plugin or plugins 
provided by other module(s) for calculating actual shipping costs with shipping
services. Currently available plugins with status as of 6 November 2018:

* Flat Rate/Flate per Item - Beta, included in Commerce Shipping.
* [Fedex](https://www.drupal.org/project/commerce_fedex) - Alpha.
* [Commerce Australia Post](https://www.drupal.org/project/commerce_auspost) - Beta
* [Commerce NZPost](https://www.drupal.org/project/commerce_nzpost) - V8.x-3.3
* [USPS](https://www.drupal.org/project/commerce_usps) - In progress, not yet functional.
* [Canada Post](https://www.drupal.org/project/commerce_canadapost) - Alpha
* [UPS](https://www.drupal.org/project/commerce_ups) - Alpha
* Other modules/plugins such as Kiala have yet to be ported or created for Drupal Commerce 2.x.

## Before you begin

**@todo! We need help filling out this section! Feel free to follow the *edit this page* link and contribute.**

If you only need flat rates for shipments, you will be able to install only
Commerce Shipping. The recommended way to install Commerce shipping is with Composer.

```bash
composer require drupal/commerce_shipping
```

Then enable the module, via Drush, Drupal Console or the UI.

```bash
drupal module:install commerce_shipping
```

However, you will likely also want a specific plugin for calculating shipping rates
for your shipper(s) of choice. For example, Fedex, at this time, there are limited
plugins created and you may need to create your own. See the list [above](./shipping.md).
In general, installing those plugins is as simple as installing the Drupal module
that includes them, although they may have specific installation instructions, in
which case, please follow them. For example, to install the Fedex plugin, you would:

```bash
composer require drupal/commerce_fedex
drupal module:install commerce_fedex
```

**@todo Explain core concepts (packaging, boxes, shipments.)**


## Enable shipping for products

! We need help filling out this section! Feel free to follow the *edit this page* link and contribute.

Whatever shipping method you are using, you will need to set any shippable
product variation types as shippable. Go to `/admin/commerce/config/product-variation-types/`
and edit each variation type that you want to enable shipping for.
![Product Variation Type Edit Form](./images/product-variation-edit.png)

Depending on your shipping plugin, you may also need to enable dimensions there.

Edit your order type:

* Select one of the fulfilment workflows.
* Enable shipping and choose a shipment type.
* Select the 'Shipping' checkout flow

## Configure shipping methods

**@todo! We need help filling out this section! Feel free to follow the *edit this page* link and contribute.**

### Using FedEx, USPS, UPS

**@todo Explain these are modules which can be enabled, follow their instructions.**

### Adding custom shipping rates

## Managing shipments

**@todo We need help filling out this section! Feel free to follow the *edit this page* link and contribute.**

Workflow for handling shipments
