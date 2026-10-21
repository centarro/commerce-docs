---
title: Google Tag (GA4 and Tag Manager)
taxonomy:
    category: docs
---

The [Google Tag] module (2.x) adds the Google tag (`gtag.js`) and Google Tag Manager to a Drupal site. It also ships with built-in Commerce support: it sends [GA4 ecommerce events] such as `view_item`, `add_to_cart` and `purchase` with the product, cart and order data, so you don't have to write any code.

This page covers google_tag 2.0.9.

!!! note "Community module"
    Google Tag isn't maintained by the Drupal Commerce maintainers. Report bugs and feature requests in its [issue queue](https://www.drupal.org/project/issues/google_tag).

## Installation and configuration

```bash
composer require drupal/google_tag
drush en google_tag
```

1. Go to *Administration > Configuration > Web services > Google Tag* (`/admin/config/services/google-tag`).
2. In **Google Tag ID(s)**, enter your GA4 measurement ID (`G-XXXXXXXX`), your Tag Manager container ID (`GTM-XXXXXX`), or both.
3. Open the **Events** tabs and check the events to send. A new tag container enables every available event.
4. Optionally, limit where the tag loads under **Conditions** (request path, user role, etc.), then save.

Each event depends on a Commerce module and is only available when that module is installed: `commerce_product`, `commerce_cart`, `commerce_checkout`, `commerce_order`, `commerce_shipping` or `commerce_wishlist`. If you install one of these modules after creating the tag container, edit the container, enable the new events and save it.

!!! note "Tag containers"
    By default, the site has a single tag container. Check **Allow multiple Tag Containers** on the *Advanced* tab to create more of them on the *Additional Tags* tab, each with its own tag IDs, events and conditions. For each request, the enabled container with the lowest weight whose conditions match is used, and events are only collected when a container matches.

### How events are sent

Events are collected in the `google_tag.event_collector` service while Drupal handles the request. The module then sends them with `gtag('event', <name>, <data>)`, which pushes to `window.dataLayer`:

* On regular page loads, through `drupalSettings.gtag.events`, from `hook_page_attachments()`.
* On Ajax responses, through a `gtagEvent` Ajax command.

An event added during a request that ends in a redirect is lost, unless it's *delayed*: delayed events are stored in the session and sent on the next page. The cart events are delayed, because adding to or removing from the cart usually redirects.

Use [Tag Assistant] or the GA4 **DebugView** to check what your site sends.

## Commerce events

| Event | Plugin ID | Module | Sent when |
|-------|-----------|--------|-----------|
| `view_item` | `commerce_view_item` | `commerce_product` | A product is displayed in the `full` view mode, or the customer picks another variation. |
| `view_item_list` | `commerce_view_item_list` | `commerce_product` | A view with the *Google Tag: View item list event* area is rendered. |
| `add_to_cart` | `commerce_add_to_cart` | `commerce_cart` | An order item is added to a cart (delayed). |
| `remove_from_cart` | `commerce_remove_from_cart` | `commerce_cart` | An order item is removed from a cart (delayed). |
| `add_to_wishlist` | `commerce_add_to_wishlist` | `commerce_wishlist` | An item is added to a wishlist. |
| `begin_checkout` | `commerce_begin_checkout` | `commerce_checkout` | The first checkout step is displayed. |
| `purchase` | `commerce_purchase` | `commerce_order` | The checkout `complete` step is displayed, or the order is paid. |
| `add_shipping_info` | `commerce_add_shipping_info` | `commerce_shipping` | Never, out of the box. See [Events that are not sent](#events-that-are-not-sent). |
| `add_payment_info` | `commerce_add_payment_info` | `commerce_checkout` | Never, out of the box. |
| `refund` | `commerce_refund` | `commerce_order` | Never, out of the box. |

The event data is described below. All amounts are strings. `price` and `value` have two decimals (`"12.00"`). `shipping`, `tax` and `discount` don't (`"0"`, `"2.5"`), except `discount` in `begin_checkout` (`"0.00"`). `item_id` is the variation SKU, or the purchased entity ID for other purchasable entity types. `affiliation` is the store name.

### view_item

Sent in two cases:

* When a product is rendered in the `full` view mode on a `GET` request, for the variation selected in the URL (`?v=`) or the default variation. Products with no published variations are skipped.
* When the customer selects another variation on the add to cart form, through the `ProductEvents::PRODUCT_VARIATION_AJAX_CHANGE` event.

```json
{
  "currency": "USD",
  "value": "12.00",
  "items": [
    {"item_name": "T-shirt - Blue, Large", "item_id": "TSHIRT-BLUE-L", "affiliation": "My store"}
  ]
}
```

`value` is the variation price (`getPrice()`), not the calculated price, so it doesn't include promotions or price resolvers.

### view_item_list

Not sent automatically. Add the **Google Tag: View item list event** area handler to the header or footer of a product or product variation view (for example, your catalog view). When the view is rendered, the event lists its results; for product rows, the default variation is used. Check **Display even if view has no result** to also send it for empty results.

```json
{
  "item_list_id": "products",
  "item_list_name": "Products",
  "items": [
    {"item_name": "T-shirt - Blue, Large", "item_id": "TSHIRT-BLUE-L", "affiliation": "My store", "price": "12.00"}
  ]
}
```

`item_list_id` is the view ID and `item_list_name` the view title.

### add_to_cart and remove_from_cart

Sent on the page after the order item is added to or removed from a cart, through the `CartEvents::CART_ORDER_ITEM_ADD` and `CartEvents::CART_ORDER_ITEM_REMOVE` events. Both events send the same data:

```json
{
  "currency": "USD",
  "value": "12.00",
  "items": [
    {
      "item_name": "T-shirt - Blue, Large",
      "affiliation": "My store",
      "discount": "0",
      "price": "12.00",
      "quantity": 1,
      "item_id": "TSHIRT-BLUE-L"
    }
  ]
}
```

* `price` is the unit price and `value` the adjusted unit price (after promotions), **not** multiplied by the quantity.
* `quantity` is the order item quantity. When the added product is combined with an order item already in the cart, this is the new total quantity of that order item, not the quantity that was just added.
* Changing the quantity on the cart page dispatches `CartEvents::CART_ORDER_ITEM_UPDATE`, which sends nothing.

### add_to_wishlist

Sent when an item is added to a wishlist (`WishlistEvents::WISHLIST_ENTITY_ADD`), with the same `items` keys as `view_item` plus `quantity`. `value` is the variation price. This event isn't delayed.

### begin_checkout

Sent each time the first step of the checkout flow is displayed, so it can be sent again if the customer comes back to that step.

```json
{
  "currency": "USD",
  "value": "24.00",
  "items": [
    {
      "item_id": "TSHIRT-BLUE-L",
      "item_name": "T-shirt - Blue, Large",
      "affiliation": "My store",
      "discount": "0.00",
      "price": "12.00",
      "quantity": 2
    }
  ]
}
```

`value` is the order total.

### purchase

Sent in two cases:

* When the `complete` checkout step is displayed. This is the usual case.
* When the order is paid (`OrderEvents::ORDER_PAID`). This event isn't delayed, so it's only sent when the request that pays the order renders a page or an Ajax response, not when it redirects (for example to the `complete` step) or comes from a payment gateway notification.

```json
{
  "currency": "USD",
  "value": "29.00",
  "transaction_id": "42",
  "shipping": "5",
  "tax": "0",
  "items": [
    {
      "item_id": "TSHIRT-BLUE-L",
      "item_name": "T-shirt - Blue, Large",
      "affiliation": "My store",
      "discount": "0",
      "price": "12.00",
      "quantity": 2
    }
  ]
}
```

`value` is the order total, `transaction_id` the order number, and `shipping` and `tax` the sums of the order's `shipping` and `tax` adjustments.

The event is sent again if the customer reloads the `complete` page. GA4 [deduplicates purchases] with the same `transaction_id`.

### Events that are not sent

google_tag 2.0.9 defines three Commerce event plugins that nothing sends:

* `add_shipping_info` and `add_payment_info` have no data of their own: if you send them, they only carry the event name and their plugin configuration.
* `refund` has the same data as `purchase`, except `items` is always empty, and always refunds the full order total.

To use them, send them from your own code (see [Sending events](#sending-events)), and replace the plugin class to send the data you need (see [Altering an event](#altering-an-event)).

## Customizing the events

### Sending events

Add an event with the event collector service, by plugin ID, passing the plugin contexts:

```php
// Sent with the current response: an HTML page or an Ajax response.
\Drupal::service('google_tag.event_collector')->addEvent('commerce_add_payment_info', [
  'order' => $order,
]);
// Stored in the session and sent on the next page, for requests that redirect.
\Drupal::service('google_tag.event_collector')->addDelayedEvent('commerce_add_payment_info', [
  'order' => $order,
]);
```

Nothing is sent if no tag container matches the request, or if the event isn't enabled on it. Delayed events are serialized in the session, so the event plugin must be serializable.

For example, to send `add_shipping_info` and `add_payment_info` when the customer reaches the review step, which comes after the shipping and payment information panes:

```php
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_form_FORM_ID_alter() for commerce_checkout_flow.
 */
function mymodule_form_commerce_checkout_flow_alter(array &$form, FormStateInterface $form_state) {
  if ($form['#step_id'] === 'review') {
    $order = $form_state->getFormObject()->getOrder();
    $collector = \Drupal::service('google_tag.event_collector');
    $collector->addEvent('commerce_add_shipping_info', ['order' => $order]);
    $collector->addEvent('commerce_add_payment_info', ['order' => $order]);
  }
}
```

### Altering an event

Event plugins are discovered in the `Plugin/GoogleTag/Event` namespace and use the `@GoogleTagEvent` annotation. To change the data an event sends, swap the plugin class in `hook_google_tag_event_info_alter()`:

```php
/**
 * Implements hook_google_tag_event_info_alter().
 */
function mymodule_google_tag_event_info_alter(array &$definitions) {
  if (isset($definitions['commerce_add_shipping_info'])) {
    $definitions['commerce_add_shipping_info']['class'] = \Drupal\mymodule\GoogleTag\AddShippingInfoEvent::class;
  }
}
```

The Commerce event classes are `final`, so they can't be extended. To reuse their data, *decorate* them: create the original plugin in your class, and add to or change the data it returns. Your class extends `EventBase` and can use `CommerceEventTrait` for `formatPriceNumber()`. Keep it outside of `Plugin/GoogleTag/Event`, so it isn't discovered as a plugin of its own.

This example sends the `begin_checkout` data, plus the GA4 `shipping_tier` parameter:

```php
<?php

namespace Drupal\mymodule\GoogleTag;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\google_tag\Plugin\GoogleTag\Event\Commerce\BeginCheckoutEvent;
use Drupal\google_tag\Plugin\GoogleTag\Event\EventBase;

/**
 * Sends add_shipping_info with the order items and the shipping method.
 */
final class AddShippingInfoEvent extends EventBase {

  /**
   * {@inheritdoc}
   */
  public function getData(): array {
    $order = $this->getContextValue('order');
    assert($order instanceof OrderInterface);

    // BeginCheckoutEvent has no dependencies. For plugins implementing
    // ContainerFactoryPluginInterface (e.g. PurchaseEvent), use ::create().
    $inner = new BeginCheckoutEvent($this->configuration, $this->pluginId, $this->pluginDefinition);
    $inner->setContextValue('order', $order);
    $data = $inner->getData();

    $labels = [];
    foreach ($order->get('shipments')->referencedEntities() as $shipment) {
      $labels[] = $shipment->getShippingMethod()?->label();
    }
    $data['shipping_tier'] = implode(', ', array_filter($labels));

    return $data;
  }

}
```

To replace an event entirely, return your own data from `getData()` without creating the original plugin.

### Adding an event

To send a GA4 event google_tag doesn't support, create an event plugin in your module's `src/Plugin/GoogleTag/Event` directory. `event_name` is the GA4 event name, `dependency` an optional module that must be installed, and `context_definitions` the values passed to `addEvent()`:

```php
<?php

namespace Drupal\mymodule\Plugin\GoogleTag\Event;

use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\google_tag\Plugin\GoogleTag\Event\EventBase;

/**
 * View cart event plugin.
 *
 * @GoogleTagEvent(
 *   id = "mymodule_view_cart",
 *   event_name = "view_cart",
 *   label = @Translation("View cart"),
 *   dependency = "commerce_cart",
 *   context_definitions = {
 *     "order" = @ContextDefinition("entity:commerce_order")
 *   }
 * )
 */
final class ViewCartEvent extends EventBase {

  /**
   * {@inheritdoc}
   */
  public function getData(): array {
    $order = $this->getContextValue('order');
    assert($order instanceof OrderInterface);
    $total = $order->getTotalPrice();
    return [
      'currency' => $total->getCurrencyCode(),
      'value' => $total->getNumber(),
    ];
  }

}
```

Then enable it on the tag container, and send it with the event collector.

[Google Tag]: https://www.drupal.org/project/google_tag
[GA4 ecommerce events]: https://developers.google.com/analytics/devguides/collection/ga4/ecommerce
[Tag Assistant]: https://tagassistant.google.com/
[deduplicates purchases]: https://support.google.com/analytics/answer/12313109
