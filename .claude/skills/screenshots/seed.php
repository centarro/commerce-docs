<?php

/**
 * @file
 * Demo data on top of the Kickstart demo, so screenshots match the docs.
 *
 * Usage: ddev drush php:script <path>/seed.php
 */

use Drupal\commerce_store\Entity\Store;

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
