<?php

/**
 * @file
 * Demo data on top of the Kickstart demo, so screenshots match the docs.
 *
 * Usage: ddev drush php:script <path>/seed.php
 */

use Drupal\commerce_store\Entity\Store;

// The developer guide's example code (copied to web/modules/custom/docs_examples).
\Drupal::service('module_installer')->install(['docs_examples']);

// Neutral defaults instead of the machine's timezone and no default country.
\Drupal::configFactory()->getEditable('system.date')
  ->set('timezone.default', 'America/New_York')
  ->set('country.default', 'US')
  ->save();

// The docs show four "Centorium" stores, New York being the default.
$default = Store::load(1);
$default->setName('The Centorium New York')->save();
$importer = \Drupal::service('commerce_price.currency_importer');
$stores = [
  ['Paris', 'FR', 'EUR', ['postal_code' => '75001', 'locality' => 'Paris', 'address_line1' => '1 Rue de Rivoli']],
  ['London', 'GB', 'GBP', ['postal_code' => 'SW1A 1AA', 'locality' => 'London', 'address_line1' => '10 Downing Street']],
  ['Tokyo', 'JP', 'JPY', ['postal_code' => '100-0001', 'locality' => 'Chiyoda-ku', 'administrative_area' => 'Tokyo', 'address_line1' => '1-1 Chiyoda']],
];
foreach ($stores as [$city, $country, $currency, $address]) {
  $name = "The Centorium $city";
  if (\Drupal::entityQuery('commerce_store')->accessCheck(FALSE)->condition('name', $name)->count()->execute()) {
    continue;
  }
  $importer->import($currency);
  Store::create([
    'type' => $default->bundle(),
    'uid' => 1,
    'name' => $name,
    'mail' => $default->getEmail(),
    'default_currency' => $currency,
    'address' => ['country_code' => $country] + $address,
    'billing_countries' => [$country],
  ])->save();
}

// Orders for the order screens: two placed orders and a draft one.
// This is a throwaway site: orders without items (left over from trying
// the "Create a new order" form) are deleted.
$order_storage = \Drupal::entityTypeManager()->getStorage('commerce_order');
foreach ($order_storage->loadMultiple() as $order) {
  if (!$order->hasItems()) {
    $order->delete();
  }
}
// The customer the orders page creates as a "New customer".
if ($user = user_load_by_mail('alex.martin@example.com')) {
  $user->delete();
}
$variation_storage = \Drupal::entityTypeManager()->getStorage('commerce_product_variation');
$order_item_storage = \Drupal::entityTypeManager()->getStorage('commerce_order_item');
$orders = [
  ['john.smith', 'John Smith', '-3 days', ['000002' => 1], ['address_line1' => '350 5th Ave', 'locality' => 'New York', 'administrative_area' => 'NY', 'postal_code' => '10118']],
  ['emma.johnson', 'Emma Johnson', '-1 day', ['MF-WhiteWoodChair-001' => 2, 'candle4' => 1], ['address_line1' => '1 Market St', 'locality' => 'San Francisco', 'administrative_area' => 'CA', 'postal_code' => '94105']],
  ['jane.doe', 'Jane Doe', NULL, ['WoodCo-BBS-001' => 2, '3plantpots' => 1], ['address_line1' => '20 W 34th St', 'locality' => 'New York', 'administrative_area' => 'NY', 'postal_code' => '10001']],
];
foreach ($orders as [$name, $full_name, $placed, $skus, $address]) {
  $mail = "$name@example.com";
  if ($order_storage->loadByProperties(['mail' => $mail])) {
    continue;
  }
  $users = \Drupal::entityTypeManager()->getStorage('user')->loadByProperties(['mail' => $mail]);
  $user = $users ? reset($users) : \Drupal\user\Entity\User::create(['name' => $name, 'mail' => $mail, 'status' => 1]);
  $user->save();
  [$given_name, $family_name] = explode(' ', $full_name);
  $profile = \Drupal\profile\Entity\Profile::create([
    'type' => 'customer',
    'uid' => 0,
    'address' => ['country_code' => 'US', 'given_name' => $given_name, 'family_name' => $family_name] + $address,
  ]);
  $profile->save();
  $items = [];
  foreach ($skus as $sku => $quantity) {
    $item = $order_item_storage->createFromPurchasableEntity($variation_storage->loadBySku($sku), ['quantity' => $quantity]);
    $item->save();
    $items[] = $item;
  }
  $order = $order_storage->create([
    'type' => 'default',
    'store_id' => 1,
    'uid' => $user->id(),
    'mail' => $mail,
    'billing_profile' => $profile,
    'order_items' => $items,
    'state' => 'draft',
  ]);
  $order->save();
  if ($placed) {
    $order->setPlacedTime(strtotime($placed));
    $order->getState()->applyTransitionById('place');
    $order->save();
  }
}
// The developer guide's orders page walks a draft order through the
// "Fulfillment, with validation" workflow: recreate it as a draft, and put the
// order type back on its default workflow.
$order_type = \Drupal::entityTypeManager()->getStorage('commerce_order_type')->load('default');
if ($order_type->getWorkflowId() !== 'order_default') {
  $order_type->setWorkflowId('order_default')->save();
}
foreach ($order_storage->loadByProperties(['mail' => 'sam.lee@example.com']) as $order) {
  $order->delete();
}
$users = \Drupal::entityTypeManager()->getStorage('user')->loadByProperties(['mail' => 'sam.lee@example.com']);
$user = $users ? reset($users) : \Drupal\user\Entity\User::create(['name' => 'sam.lee', 'mail' => 'sam.lee@example.com', 'status' => 1]);
$user->save();
// The payment shot enters a new card: forget the ones saved by previous runs.
foreach (\Drupal::entityTypeManager()->getStorage('commerce_payment_method')->loadByProperties(['uid' => $user->id()]) as $payment_method) {
  $payment_method->delete();
}
$profile = \Drupal\profile\Entity\Profile::create([
  'type' => 'customer',
  'uid' => 0,
  'address' => ['country_code' => 'US', 'given_name' => 'Sam', 'family_name' => 'Lee', 'address_line1' => '233 S Wacker Dr', 'locality' => 'Chicago', 'administrative_area' => 'IL', 'postal_code' => '60606'],
]);
$profile->save();
$item = $order_item_storage->createFromPurchasableEntity($variation_storage->loadBySku('WoodCo-BBS-001'), ['quantity' => 2]);
$item->save();
$order = $order_storage->create([
  'type' => 'default',
  'store_id' => 1,
  'uid' => $user->id(),
  'mail' => 'sam.lee@example.com',
  'billing_profile' => $profile,
  'order_items' => [$item],
  'state' => 'draft',
]);
// Refresh on save, so the storewide promotion is applied.
$order->setRefreshState(\Drupal\commerce_order\Entity\OrderInterface::REFRESH_ON_SAVE);
$order->save();

// The activity log page: placed orders show the customer adding the items to
// the cart and placing the order, rather than drush (anonymous) placing them.
$log_storage = \Drupal::entityTypeManager()->getStorage('commerce_log');
foreach ($order_storage->loadByProperties(['state' => 'completed']) as $order) {
  $logs = $log_storage->loadMultipleByEntity($order);
  if (!array_filter($logs, fn ($log) => $log->getTemplateId() === 'cart_entity_added')) {
    foreach ($order->getItems() as $item) {
      $logs[] = $log_storage->generate($order, 'cart_entity_added', ['purchased_entity_label' => $item->label()]);
    }
  }
  foreach ($logs as $log) {
    $minutes = $log->getTemplateId() === 'cart_entity_added' ? 5 : 0;
    $log->set('uid', $order->getCustomerId())->set('created', $order->getPlacedTime() - 60 * $minutes)->save();
  }
}

// The dashboard shots expand an inbox message, which marks it as read.
\Drupal::database()->update('commerce_inbox_message')->fields(['state' => 'unread'])->execute();

// The products page walks through a T-shirt with Color and Size attributes,
// set up as the attribute, variation type and product type forms would.
$entity_type_manager = \Drupal::entityTypeManager();
$attribute_storage = $entity_type_manager->getStorage('commerce_product_attribute');
$attributes = [
  'color' => ['Color', 'select', ['Grey', 'White', 'Black']],
  'size' => ['Size', 'select', ['Small', 'Medium', 'Large', 'Extra Large']],
];
foreach ($attributes as $id => [$label, $element_type, $values]) {
  if ($attribute_storage->load($id)) {
    continue;
  }
  $attribute_storage->create(['id' => $id, 'label' => $label, 'elementType' => $element_type])->save();
  foreach ($values as $weight => $name) {
    $entity_type_manager->getStorage('commerce_product_attribute_value')->create(['attribute' => $id, 'name' => $name, 'weight' => $weight])->save();
  }
}
// The "Adding fields to attributes" section: a Hex value field on colors.
if (!\Drupal\field\Entity\FieldStorageConfig::loadByName('commerce_product_attribute_value', 'field_hex_value')) {
  \Drupal\field\Entity\FieldStorageConfig::create(['field_name' => 'field_hex_value', 'entity_type' => 'commerce_product_attribute_value', 'type' => 'string'])->save();
  \Drupal\field\Entity\FieldConfig::create(['field_name' => 'field_hex_value', 'entity_type' => 'commerce_product_attribute_value', 'bundle' => 'color', 'label' => 'Hex value'])->save();
  commerce_get_entity_display('commerce_product_attribute_value', 'color', 'form')->setComponent('field_hex_value', ['type' => 'string_textfield'])->save();
  $hex = ['Grey' => '#808080', 'White' => '#ffffff', 'Black' => '#000000'];
  foreach ($entity_type_manager->getStorage('commerce_product_attribute_value')->loadByProperties(['attribute' => 'color']) as $value) {
    $value->set('field_hex_value', $hex[$value->getName()])->save();
  }
}
$variation_type_storage = $entity_type_manager->getStorage('commerce_product_variation_type');
if (!$variation_type_storage->load('t_shirt')) {
  $order_item_types = $entity_type_manager->getStorage('commerce_order_item_type')->loadMultiple();
  $variation_type = $variation_type_storage->create([
    'id' => 't_shirt',
    'label' => 'T-shirt',
    'orderItemType' => isset($order_item_types['default']) ? 'default' : array_key_first($order_item_types),
    'generateTitle' => TRUE,
  ]);
  $variation_type->save();
  $trait_manager = \Drupal::service('plugin.manager.commerce_entity_trait');
  $trait_manager->installTrait($trait_manager->createInstance('purchasable_entity_shippable'), 'commerce_product_variation', 't_shirt');
  $variation_type->setTraits(['purchasable_entity_shippable'])->save();
  foreach ($attribute_storage->loadMultiple(['color', 'size']) as $attribute) {
    \Drupal::service('commerce_product.attribute_field_manager')->createField($attribute, 't_shirt');
  }
}
$product_type_storage = $entity_type_manager->getStorage('commerce_product_type');
if (!$product_type_storage->load('t_shirt')) {
  $product_type = $product_type_storage->create([
    'id' => 't_shirt',
    'label' => 'T-shirt',
    'variationTypes' => ['t_shirt'],
    'multipleVariations' => TRUE,
    'injectVariationFields' => TRUE,
  ]);
  $product_type->save();
  commerce_product_add_body_field($product_type);
  // Multiple variations are managed on the Variations tab, not in the form.
  commerce_get_entity_display('commerce_product', 't_shirt', 'form')->removeComponent('variations')->save();
}
// The product the "Edit a product" and "Delete a product" sections show.
$product_storage = $entity_type_manager->getStorage('commerce_product');
if (!$product_storage->loadByProperties(['title' => 'Supreme Graphics Shirt'])) {
  $value_ids = [];
  foreach ($entity_type_manager->getStorage('commerce_product_attribute_value')->loadMultiple() as $value) {
    $value_ids[$value->getAttributeId()][$value->getName()] = $value->id();
  }
  $variations = [];
  foreach ([['Grey', 'Small'], ['Black', 'Medium']] as [$color, $size]) {
    $variation = $entity_type_manager->getStorage('commerce_product_variation')->create([
      'type' => 't_shirt',
      'sku' => strtoupper("SUPREME-GRAPHIC-$color-$size"),
      'price' => new \Drupal\commerce_price\Price('10.00', 'USD'),
      'attribute_color' => $value_ids['color'][$color],
      'attribute_size' => $value_ids['size'][$size],
      'weight' => ['number' => '0.4', 'unit' => 'lb'],
    ]);
    $variation->save();
    $variations[] = $variation;
  }
  $product_storage->create([
    'type' => 't_shirt',
    'title' => 'Supreme Graphics Shirt',
    'uid' => 1,
    'body' => ['value' => '<p>A shirt of supreme quality and a supreme graphic in multiple sizes and colors.</p>', 'format' => 'basic_html'],
    'stores' => [1],
    'variations' => $variations,
    'path' => ['alias' => '/supreme-graphic-t'],
  ])->save();
}

// The promotions page: a few promotions for the table, and a coupon promotion
// for the checkout shots. Kickstart doesn't enable coupon redemption at checkout.
$flow = $entity_type_manager->getStorage('commerce_checkout_flow')->load('shipping');
$flow_configuration = $flow->get('configuration');
$flow_configuration['panes']['coupon_redemption'] = ['step' => '_sidebar', 'weight' => 10, 'allow_multiple' => FALSE];
// The checkout page: its shots change the login pane, restore the defaults.
$flow_configuration['panes']['login'] = ['allow_guest_checkout' => TRUE, 'allow_registration' => FALSE] + $flow_configuration['panes']['login'];
$flow->set('configuration', $flow_configuration)->save();
$promotion_storage = $entity_type_manager->getStorage('commerce_promotion');
$order_total = fn ($amount) => ['target_plugin_id' => 'order_total_price', 'target_plugin_configuration' => ['operator' => '>=', 'amount' => ['number' => $amount, 'currency_code' => 'USD']]];
$promotions = [
  '10% off on $50 or more orders' => [
    'display_name' => '10% Off Storewide Sale',
    'offer' => ['target_plugin_id' => 'order_percentage_off', 'target_plugin_configuration' => ['percentage' => '0.10']],
    'conditions' => [$order_total('50')],
    'start_date' => '2026-10-01T00:00:00',
    'end_date' => '2027-01-07T23:59:59',
  ],
  'Free shipping over $100' => [
    'display_name' => 'Free shipping',
    'offer' => ['target_plugin_id' => 'shipment_percentage_off', 'target_plugin_configuration' => ['percentage' => '1']],
    'conditions' => [$order_total('100')],
    'start_date' => '2026-09-01T00:00:00',
  ],
  'Two per customer' => [
    'offer' => ['target_plugin_id' => 'order_fixed_amount_off', 'target_plugin_configuration' => ['amount' => ['number' => '5', 'currency_code' => 'USD']]],
    'usage_limit' => 1000,
    'usage_limit_customer' => 2,
    'start_date' => '2026-06-02T00:00:00',
    'status' => FALSE,
  ],
  'Newsletter 10% off' => [
    'display_name' => 'Newsletter discount',
    'offer' => ['target_plugin_id' => 'order_percentage_off', 'target_plugin_configuration' => ['percentage' => '0.10']],
    'conditions' => [$order_total('50')],
    'compatibility' => 'none',
    'start_date' => '2026-10-01T00:00:00',
  ],
];
foreach ($promotions as $name => $values) {
  if ($existing = $promotion_storage->loadByProperties(['name' => $name])) {
    // The coupon shots disable the storewide promotion: turn it back on.
    reset($existing)->setEnabled($values['status'] ?? TRUE)->save();
    continue;
  }
  $promotion_storage->create($values + [
    'name' => $name,
    'order_types' => ['default'],
    'stores' => [1],
    'status' => TRUE,
  ])->save();
}
$coupon_storage = $entity_type_manager->getStorage('commerce_promotion_coupon');
[$newsletter] = array_values($promotion_storage->loadByProperties(['name' => 'Newsletter 10% off']));
if (!$coupon_storage->loadEnabledByCode('SAVE-10')) {
  $coupon = $coupon_storage->create(['code' => 'SAVE-10', 'promotion_id' => $newsletter->id(), 'usage_limit_customer' => 1, 'status' => TRUE]);
  $coupon->save();
  $newsletter->addCoupon($coupon)->save();
}
// The bulk generation shot creates coupons: delete the previous run's.
$generated = $coupon_storage->getQuery()->accessCheck(FALSE)->condition('code', 'SAVE-10-%', 'LIKE')->execute();
$coupon_storage->delete($coupon_storage->loadMultiple($generated));
// The admin's cart, over $50 and without coupons.
$cart_provider = \Drupal::service('commerce_cart.cart_provider');
$admin = \Drupal\user\Entity\User::load(1);
$store = Store::load(1);
if ($cart = $cart_provider->getCart('default', $store, $admin)) {
  $cart->delete();
  $cart_provider->clearCaches();
}
$cart = $cart_provider->createCart('default', $store, $admin);
foreach (['level-headphones', 'UT-KnitHat-Green'] as $sku) {
  \Drupal::service('commerce_cart.cart_manager')->addEntity($cart, $variation_storage->loadBySku($sku));
}
