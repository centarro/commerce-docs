---
title: Store
taxonomy:
    category: docs
---

Everything starts with a store. Products can belong to many stores, and each order belongs to a single store.

A store (`commerce_store`) is a content entity holding the merchant's information: name, email address (used to send order notifications), default currency, timezone (used for promotion and tax availability), address and supported billing countries. Most sites have a single store; marketplaces and multi-brand sites have several. See the [Store setup user guide](../../user-guide/store-setup.md) for creating stores in the UI.

## Store types

Like products and orders, stores are bundled: each store has a **type** (`commerce_store_type`), chosen when the store is created. Use store types when stores need different fields, for example a marketplace with vendor stores and a main store. Store types are managed at `/admin/commerce/config/store-types`.

## The default store and the current store

One store is marked as the **default store**. When Commerce needs to know which store the customer is shopping in, for example to add a product to the cart, it asks the `commerce_store.current_store` service. That service uses a chain of *store resolvers* (services tagged `commerce_store.store_resolver`), and falls back to the default store when no other resolver returns a store.

To pick the store based on the domain, the language or anything else, add your own resolver. See [Resolve the current store](code-recipes.md#resolve-the-current-store) and [Understanding resolvers](../core/understanding_resolvers.md).

## Code recipes

See [Store code recipes](code-recipes.md) for creating and loading stores and store types in code.
