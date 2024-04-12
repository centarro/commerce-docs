---
title: Code Recipes
taxonomy:
    category: docs
---

## Create a store

!!! Example "Example: Creating a store"

    ```php

        /**
         * type [String] - [DEFAULT = 'online']
         *   Foreign key for the store type to yse.
         *
         * uid [Integer]
         *   The user id that created the store.
         *
         * name [String]
         *   The store's name.
         *
         * mail [String]
         *   The store's email address.
         *
         * address [\Drupal\address\AddressInterface]
         *   The store's address.
         *
         * default_currency [String]
         *   The currency the store uses.
         *
         * billing_countries [Array(String)]
         *   Array of country codes selected for the store.
         */

        // The store's address.
        $address = [
        'country_code' => 'US',
        'address_line1' => '123 Street Drive',
        'locality' => 'Beverly Hills',
        'administrative_area' => 'CA',
        'postal_code' => '90210',
        ];

        // The currency code.
        $currency = 'USD';

        // If needed, this will import the currency.
        $currency_importer = \Drupal::service('commerce_price.currency_importer');
        $currency_importer->import($currency);

        $store = \Drupal\commerce_store\Entity\Store::create([
        'type' => 'custom_store_type',
        'uid' => 1,
        'name' => 'My Store',
        'mail' => 'admin@example.com',
        'address' => $address,
        'default_currency' => $currency,
        'billing_countries' => ['US'],
        ]);
        $store->save();

        // If needed, this sets the store as the default store.
        $store_storage = \Drupal::service('entity_type.manager')->getStorage('commerce_store');
        $store_storage->markAsDefault($store);
    
    ```

### Create a store using Drupal Console

You can use a Drupal Console command to create a store and import new currencies.

![Workflow](../images/drupal-commerce-create-store.gif)

### How to load a store

!!! Example "Example: Loading a store"

    ```php

        // Loading is based off of the primary key [Integer]
        //   1 would be the first one saved, 2 the next, etc.
        $store = \Drupal\commerce_store\Entity\Store::load(1);

    ```

## Create a store type

!!! Example "Example: Creating a store type"

    ```php
    <?php
    use Drupal\commerce_store\Entity\StoreType;

    /**
     * id [String]
     *   The primary key for this store type.
     *
     * label [String]
     *   The label for this store type.
     *
     * description [String]
     *   The description for this store type.
     */
    $store_type = StoreType::create([
    'id' => 'custom_store_type',
    'label' => 'My custom store type',
    'description' => 'This is my first custom store type!',
    ]);

    $store_type->save();
    ```

### How to load a store type

!!! Example "Example: Loading a store type"

    ```php
    // Loading is based off of the primary key [String] that was defined when creating it.
    $store_type = \Drupal\commerce_store\Entity\StoreType::load('custom_store_type');
    ```

## Resolve the current store

The first store created in Commerce is saved as the default store in `commerce_store.settings.yml`. This is used in `\Drupal\commerce_store\Resolver\DefaultStoreResolver` to load the site's default store.

In order to load a different store we must create a new StoreResolver. In this example, use a store ID set in a cookie to load the relevant store.

Copy DefaultStoreResolver to your module (in `/src/Resolver/`) and rename to CookieStoreResolver.
Change the code in `resolve()` to load the store ID from the cookie and return it.

```php
<?php

namespace Drupal\cookie_store\Resolver;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\commerce_store\Resolver\StoreResolverInterface;
use Drupal\commerce_store\Entity\Store;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Returns the store for an ID set in a cookie.
 */
class CookieStoreResolver implements StoreResolverInterface {

  /**
   * The store storage.
   *
   * @var \Drupal\commerce_store\StoreStorageInterface
   */
  protected $storage;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Constructs a new CookieStoreResolver object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, RequestStack $request_stack) {
    $this->storage = $entity_type_manager->getStorage('commerce_store');
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public function resolve() {
      $current_request = $this->requestStack->getCurrentRequest();
      $store_id = $current_request->cookies->get('Drupal_visitor_store_id');

      if ($store_id) {
          $store = $this->storage->load($store_id);
          return $store;
      }
  }
}
```

In your module's services file, add CookieStoreResolver with the `commerce_store.store_resolver` tag.

```YAML
cookie_store.cookie_store_resolver:
  class: Drupal\cookie_store\Resolver\CookieStoreResolver
  arguments: ['@entity_type.manager', '@request_stack']
  tags:
    - { name: commerce_store.store_resolver, priority: 100 }
```

For more info about service tags: https://www.drupal.org/docs/8/api/services-and-dependency-injection/service-tags.

ChainStoreResolver is a `service_collector`, and defines the `commerce_store.store_resolver` service tag. DefaultStoreResolver and CookieStoreResolver are services tagged with `commerce_store.store_resolver`. 

Note that CookieStoreResolver has a higher priority (100) than DefaultStoreResolver (-100), which allows it to override. Look at `commerce_store.services.yml` to see how this is defined. Having a higher priority means that the CookieStoreResolver has the first opportunity to determine the current store.

For more information about resolvers, see [Understanding resolvers](../../core/understanding_resolvers)
