---
title: Store setup
taxonomy:
    category: docs
---

Drupal Commerce sites all have one or more store to which products are published and orders belong. The store object also contains configuration like the name and email address used for customer communication, the default currency and timezone, the available billing and shipping countries, and more.

There are several use cases for using multiple stores, such as marketplaces or single sites supporting multiple brands or retail locations. Regardless of how many stores your site has, there will always be a [default store](#changing-the-default-store) that provides context for site navigation or cart interaction unless the current store is determined through some other means.

Stores are also used for invoicing, [tax types](./taxes.md), and any other settings necessary for understanding orders. This has many applications, and it's important to understand what use cases are supported out of the box and how that impacts checkout and other [order workflows](./orders.md).

## Before we begin

To create a store you must have at least one currency imported. When Drupal Commerce is first installed, the Drupal site's default country's currency is imported. For example, if your site's default country was set to United States, USD would be imported. If the default country was set to Germany, EUR would be imported.

See [Adding a currency](./currencies.md#adding-a-new-currency) to import additional currencies as needed.

## Create a store

![Store page](./images/store-landing-page2.png)

Next, we need to create a store. Every product requires one store. Additional details will be shared about the power of having stores baked into the core of Commerce, but for now, all you need to do is define your store’s name, address, and select a few things about taxes and billing.

![Store create](./images/store-add.png)

Once you’ve got all those details filled out, click save and move on to creating products! Woohoo!

## Changing the default store

The default store can be thought of as your primary store. When Drupal Commerce determines the current store for a product or order, this will be used if it cannot be determined.

Go to the Stores management page from the Configuration page

![Commerce Configuration](./images/configuration-store.png)

Click on **Edit** for the store you would like to make default.

![Edit store](./images/stores-edit-a-store.png)

At the bottom of the form, check the **Default** checkout, and then press **Save** to update the store and make it default.

![Make default](./images/edit-store-check-default.png)

## Use Cases

**! This section should be moved to a subsection, possibly. Detailing how to use multiple stores, or setup a marketplace model.**

We optimize for the two use cases:

1. One business that has one or more locations **or** 2. The marketplace model (where you have sellers)

For these use cases and possibly others, it is up to the developer to
fill in the gaps that are present in the order workflow. This is
different from Commerce 1.x in that we will support stores by default,
allowing for community contributions to extend the functionality instead
of trying to build store functionality from scratch.

#### One or more locations

This is the most common eCommerce situation where we have a single
person, company, or organization that is taking payments online.

#### Marketplace model

The marketplace model is where you have many sellers who are taking
payment for unique products.
