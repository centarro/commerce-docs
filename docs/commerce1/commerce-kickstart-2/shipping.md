---
title: Shipping
taxonomy:
    category: docs
---

![Shipping services listing in Drupal Commerce Kickstart 2](../images/CK-Shipping-02.png)

<p>Commerce Kickstart ships with the latest release of Commerce Shipping 2.x. Shipping subdivides shipping methods into individual shipping services, giving you granular control over when each individual service from a provider like UPS should be available for an order. For example, you can use USPS for all orders within the U.S.A. and UPS for all other territories while requiring that certain products only ship UPS Next Day Air regardless of where they're shipping to.</p>
<p>Shipping rate calculation functions much like the product sell price calculation in the core of Drupal Commerce. When shipping services are calculated for an order, they are represented as line items that have a base rate defined by the shipping method. The line item is then passed through Rules for additional calculation, allowing you to add taxes and handling fees or perform complex calculations based on weight, distance, quantity, etc. to determine the final calculated rate.</p>

![Shipping services listing in Drupal Commerce Kickstart 2](../images/CK-Shipping-01_0.png)

<p>You are first presented with a listing of shipping services that are available in your store. This is a granular listing showing all of the services that every available shipping method provides. In the Demo Store Shipping Services list above you can see the three services defined using the Flat Rate module.</p>
<p>In Commerce Kickstart, by default, there is a "Flat Rate" shipping method that has many flat rate services (like Free Shipping, Express Shipping, etc). You can also install other shipping methods like UPS or FedEx and they will come with their own ability to handle new services (based on weight or location). The calculation rules handle when and how shipping methods show up and how they get applied.</p>

## Upgrading Shipping from 1.x to 2.x

If you've previously installed Commerce Shipping 1.x and have used it in production, you'll want to ensure you get the update process right to prevent any data from being lost in the update. The full update process is as follows:

<ol><li>Ensure you're running the latest version of Commerce Shipping 1.x. As of right now, that is Commerce Shipping 1.1.</li>
<li>Backup your site - particularly the database.</li>
<li>Disable (but do not uninstall) any shipping method module on your site developed to work with Commerce Shipping 1.x. For example, if you have been using <a href="http://drupal.org/project/commerce_shipping_flat_rate" rel="nofollow">Commerce Shipping Flat Rate</a>, disable that module. You should not disable any of the actual Commerce Shipping modules from this project.</li>
<li>Replace the entire commerce_shipping module folder with the new Commerce Shipping 2.x module. Do not just copy the new files into the old directory, as the file structure has changed and you won't want to leave outdated files in the directory.</li>
<li>Run update.php. If you're updating from Commerce Shipping 1.1, you should see a single update function, update 7100. It will begin to process your existing shipping line items in batches of 25 to update them and the orders they're attached to for the new shipping API.</li>
<li>Uninstall the shipping method modules you disabled in step 3.</li>
<li>If you were using <a href="http://drupal.org/project/commerce_shipping_flat_rate" rel="nofollow">Commerce Shipping Flat Rate</a>, install instead the <a href="http://drupal.org/project/commerce_flat_rate" rel="nofollow">Commerce Flat Rate</a> module and rebuild your flat rate shipping services in the new interface.</li>
<li>Browse to <strong>Administration » Configuration » Workflow » Rules</strong>. Scan the list for "broken" rules that were created by Commerce Shipping 1.x shipping methods. Feel free to delete these rules after copying any necessary logic from them over to the new shipping method / service rule configurations.</li>
</ol>

Please note that because of the drastic differences in this module's API and points of integration with Rules, it simply isn't possible to automate the migration of shipping method rules from Commerce Shipping 1.x into Commerce Shipping 2.x. They will have to be manually recreated, so you should run the update in a test site to ensure you can recreate the shipping configurations used by your site post-update.

Additionally, the names of some fields, Rules conditions / actions, and price component types defined by the module have changed. If you have referenced these things from other Views, Rules, or custom code, you must update them when you migrate to Commerce Shipping 2.x.

We fully stand behind the migration and are happy to assist you if you have any difficulties. You can find us in the Commerce Shipping issue queue or in the *#commerce* [Drupal Slack channel].

## Shipping Services

Shipping services are the various delivery options customers may choose from to have your products shipped to them (e.g. Standard shipping, Free shipping, Ground, Next Day Air).

## Shipping Methods

Shipping methods are used to define available shipping services and calculate their rates (e.g. store defined flat rates, carrier calculated rates).

## Shipping Calculation Rules

Prior to display, shipping rates are calculated for each service using a base rate determined by the shipping method module and the rule configurations listed below. Calculation rules can be used for things like discounting or taxing shipping costs depending on the Rules elements defined by your enabled modules.

[Drupal Slack channel]: https://www.drupal.org/slack