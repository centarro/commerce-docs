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
