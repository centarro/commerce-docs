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
