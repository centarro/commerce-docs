---
title: Core Architecture
taxonomy:
    category: docs
---

Drupal Commerce is constantly evolving, but we're working hard to keep this specification current so it expresses in plain text how the various pieces of Commerce have been structured and how they work together.  This handbook is good for getting a big picture understanding of how we have developed Drupal Commerce and should be a handy reference for writing your own modules to integrate with the core Commerce modules.

Currently, the Info hooks and API utilization guides contain the only complete content.  The specification is not a tutorial or guide for new users, nor is it a complete set of API documentation.  We'll post links here to more appropriate resources for those things as they develop.

<ul>
  <li><a href="#systems">Systems</a></li>
  <li><a href="#entities">Entities</a></li>
  <li><a href="#fields">Fields</a></li>
  <li><a href="#info-hooks">Info hooks</a></li>
</ul>

## Systems

This section of the specification will provide an overview of the various systems with links to pertinent pages describing the entities, fields, and info hooks that make up each system.

<ul>
  <li><a href="#customer-profile-system">Customer profile system</a></li>
  <li><a href="#shopping-cart-system">Shopping cart system</a></li>
</ul>

### Customer profile system

!!! note "This section is an outdated description of a system that was about to be written. The new home for this documentation is [here](../../user-guide/customer-profiles)"

Customer profiles contain collections of data required to process orders, whether it be billing information, shipping information, or other types of details.  These customer profiles are not unique to a user, meaning a single user may have multiple instances of each type of profile.  This allows us to use the data collected to provide address book functionality where a user has a default profile of each type but may create a unique profile for a given order or choose from other previously generated profiles.

Customer profiles come from three different locations:

<ol>
<li>Checkout - each profile type has an associated checkout pane that allows the customer to enter their details on the checkout form.  If a customer is not using a past profile or has modified a previously used profile in any way, a new profile is created using the values entered on the form.  This profile is then related to the customer's order via a customer profile reference field.</li>
<li>Order edit form - each profile type is also represented on orders via default customer profile reference fields. These reference fields are populated by the relevant checkout panes but may also be filled out by administrators on the order edit form.  Profile creation works the same here as in Checkout.</li>
<li>Customer profile administration - the main menu item that lists out all the customer profiles in a View has a local action link allowing administrators to create customer profiles.  These are not linked to orders but may be associated with users to pre-populate orders and address books.  Existing profiles can also be edited, but if a profile is currently referenced by any customer profile reference field, a new profile will be created for it upon save.
</ol>

As indicated above, it is important for customer profiles to be preserved in the state they are in on any given order that references them to prevent data loss.  The only exception is if a customer profile is being edited via a form that represents the only place a profile is being referenced.  This logic will allow us to prevent unnecessary duplication of profile data while ensuring data relevant to previous orders is never lost.

The only profile type the Customer module defines is a billing information profile.  This profile has the default address field and nothing more, but it may be extended to include other fields pertinent to payment (like VAT numbers for B2B sales) by modules or the manage fields UI for that type.

An example of a module that extending the billing information profile is commerce_firstdata.  The payment method required a phone number and the module added a field instance to the existing "Billing" profile type.  If extending a field consider using the payment method settings form to let the admin select the appropriate field to pull a phone number from in case they had already made one.

### Shopping cart system

Shopping carts in Drupal Commerce are nothing more than orders in statuses that indicate they are cart statuses. By default, this includes the cart status that comes with the cart order state and a couple of the checkout page statuses.

When a user adds an item to his shopping cart, the action will create a new shopping cart order for the customer with the product in it. If the user is logged in, the order will contain his uid so it may be loaded on subsequent pageloads.  If the user is not logged in, the order ID is stored in the user's session until he logs in and the order is updated to point to the account uid.

As long as an order is still considered to be a shopping cart, its line items will be re-validated on load against the latest product prices / availability, etc. (This still needs to be <a href="https://drupal.org/node/736488">implemented</a>.)

For Drupal Commerce 1.0, we are not planning on supporting shopping carts with products in multiple currencies.  While it is technically possible for line items to have differing currencies, it is impossible to provide a general total / payment solution.

The Cart module defines a shopping cart block via Views and a form where the user can manage the contents of his cart.  If an item is removed from the shopping cart, its line item is deleted from the order.  Even if all the items are removed from the order, the order will persist to preserve any data in the order object.

The shopping cart is totally optional, meaning checkout is implemented in a separate module and will still function properly for orders created by the administrator. This allows for sites to do invoicing and payment collection where users shouldn't be allowed to create and manage their own orders via a cart.

## Entities

This page will be used as a container to describe all the entities defined by the various modules and their properties / usage in core.

<ul>
  <li><a href="#product-entity">Product entity</a></li>	
  <li><a href="#customer-profile-entity">Customer profile entity</a></li>
  <li><a href="#line-item-entity">Line item entity</a></li>
  <li><a href="#order-entity">Order entity</a></li>
  <li><a href="#payment-transaction-entity">Payment transaction entity</a></li>
</ul>

### Product entity

More information on the Product entity forthcoming.

See commerce_product.module for now.

### Customer profile entity

More information on the Customer profile entity forthcoming.

See commerce_customer.module for now.

### Line item entity

More information on the Line item entity forthcoming.

See commerce_line_item.module for now.

### Order entity

More information on the Order entity forthcoming.

See commerce_order.module for now.

### Payment transaction entity

More information on the Payment transaction entity forthcoming.

See commerce_payment.module for now.

<b>From commerce_payment.module 7.x-1.2:</b>

```php
// Local payment transaction status definitions:

// Pending is used when a transaction has been initialized but is still awaiting
// resolution; e.g. a CC authorization awaiting capture or an e-check payment
// pending at the payment provider.

define('COMMERCE_PAYMENT_STATUS_PENDING', 'pending');

// Success is used when a transaction has completed resulting in money being
```

## Fields

This page will serve as a container describing the various fields and widgets defined by the core Commerce modules and how they are used on the core entities.

<ul>
  <li><a href="#price-field">Price field</a></li>
  <li><a href="#product-reference-field">Product reference field</a></li>
  <li><a href="#address-field-contrib">Address field (contrib)</a></li>
  <li><a href="#line-item-reference-field">Line item reference field</a></li>
  <li><a href="#customer-profile-reference">Customer profile reference</a></li>
</ul>

### Price field

More information on the Price field forthcoming.

See commerce_price.module for now.

### Product reference field

More information on the Product reference field forthcoming.

See commerce_product_reference.module for now.


The product display field (aka 'Add to Cart field') is a fieldAPI field which is used to display a product/products to the end-user, typically as an Add-to-cart form which is displayed on a node.

Typically, this field will be used on a node type. For example, a site may have a product node type which includes images, description, and a product display field. The end user will view this node and be able to add a product to the cart.

### Address field (contrib)

More information on the Address field forthcoming.

See addressfield.module in contrib for now.

### Line item reference field

More information on the Line item reference field forthcoming.

See commerce_line_item.module for now.

### Customer profile reference

More information on the Customer profile reference field forthcoming.

See commerce_customer.module for now.

## Info Hooks

Info hooks of the format hook_commerce_*_info() are used to define a variety of non-entity / field data structures and even some default entity bundles in Drupal Commerce.  In most cases, the structures defined in these hooks are alterable by other modules.  
Currently every info hook is expected to return an associative array keyed by a unique ID after all the info hooks have been modified by this issue: <a href="https://drupal.org/node/875034">an issue</a>.

The hooks are categorized by module with information on all the properties acceptable to a data structure (including the unique key used as the array key in the hook’s return value) and notes regarding alteration and “magic” properties (i.e. properties that receive default values based on other properties in the structure).  In lists of properties, the unique key will be placed first and italicized.  This key serves as the key in the array returned by the info hooks and is also present as a property on the object itself.

<ul>
  <li><a href="#currency-info-hooks">Currency info hooks</a></li>
  <li><a href="#payment-info-hooks">Payment info hooks</a></li>
  <li><a href="#checkout-info-hooks">Checkout info hooks</a></li>
  <li><a href="#customer-info-hooks">Customer info hooks</a></li>
  <li><a href="#line-item-info-hooks">Line Item info hooks</a></li>
  <li><a href="#order-info-hooks">Order info hooks</a></li>
</ul>

### Currency info hooks

<ol>
  <li><a href="#currency-info">hook_commerce_currency_info()</a></li>
  <li><a href="#currency-info-alter">hook_commerce_currency_info_alter(&$currencies)</a></li>
</ol>

<a id="currency-info"> </a>
<h3>hook_commerce_currency_info()</h3>

Commerce module endeavors to define all legal currencies, so please <a href="https://drupal.org/project/issues/commerce">open an issue</a> if yours is missing.  However, modules can use this hook to add custom / site-specific currencies, such as user points or credits.

The currency data structure is as follows:

<ul>
<li><em>code</em> - the three letter currency code as defined by <a href="https://en.wikipedia.org/wiki/ISO_4217">ISO 4217</a></li>
<li>numeric_code - the three digit numeric code in string format with leading zeros</li>
<li>name - translatable full name of the currency in plural format</li>
<li>symbol - the symbol used to denote the currency if one exists</li>
<li>major_unit - the translatable name of the major currency unit in single format</li>
<li>minor_unit - the translatable name of the minor currency unit in single format</li>
<li>decimals - the number of decimals expressed when displaying the currency; defaults to 2</li>
<li>thousands_separator - the character used to denote thousands in the currency; defaults to ','</li>
<li>decimal_separator - the character used as a decimal marker in the currency; defaults to '.'</li>
<li>symbol_placement - 'before' or 'after' indicating where the symbol is placed in relation to the amount of a price when formatted for the currency; if not specified, the symbol will not be displayed</li>
<li>code_placement - like symbol_placement but used for displaying the currency code
<li>format_callback - function used for formatting a price in this currency for display; if not specified, the formatting will happen within commerce_currency_format()</li>
<li>conversion_rate - conversion rate of this currency calculated against the base currency, expressed as a decimal value denoting the value of one majur unit of this currency when converted to the base currency; defaults to 1</li>
</ul>

!!! example Example currency definition

    ```php
    <?php
    $currencies['USD'] = array(
      'code' => 'USD',
      'numeric_code' => '840',
      'name' => t('United States Dollars'),
      'symbol' => '$',
      'major_unit' => t('Dollar'),
      'minor_unit' => t('Cent'),
      'symbol_placement' => 'before',
    );
    ?>
    ```

<a id="currency-info-alter"> </a>
<h3>hook_commerce_currency_info_alter(&$currencies)</h3>

Modules can use this hook to alter properties of any defined currencies. This hook should be used with extreme caution, as the currency code and decimals values of each currency are intimately tied into price field values.  Changing codes and decimals values may cause wide disruption of store price values, so such alterations should be made prior to setting prices and collecting orders and not after moving to production.

Additionally, because every currency's default conversion rate is 1, this hook can be used to populate currency conversion rates with meaningful values. Conversion rates can be calculated using any currency as the base currency as long as the same base currency is used for every rate.

A single currency array is referred to as $currency.
An array of currency arrays keyed by code is referred to as $currencies.
The code of a currency is referred to as $currency_code.

### Payment info hooks

<ol>
<li><a href="#payment-method">hook_commerce_payment_method_info()</a></li>
<li><a href="#payment-transaction-status">hook_commerce_payment_transaction_status_info()</a></li>
</ol>

<a id="payment-method"> </a>
<h3>hook_commerce_payment_method_info()</h3>

The Payment module uses this hook to gather information on payment methods defined by enabled modules.  Drupal Commerce doesn’t maintain Ubercart’s separation of payment methods from payment gateways but rather defines payment methods as any single way of collecting payment from a customer per payment provider.  This means there will not be a single Credit Card payment method with plugin modules for CyberSource, Authorize.Net, etc. but a separate CC payment method for each payment provider with a common base set of code for building credit card forms and handling the data securely.

Payment methods depend on a variety of callbacks that are used to configure the payment methods via Rules actions, integrate the payment method with the checkout form, handle display and manipulation of transactions after the fact, and allow for administrative payment entering after checkout.  The Payment module ships with payment method modules useful for testing and learning, but all integrations with real payment providers will be provided as contributed modules.  The Payment module will include helper code designed to make different types of payment services easier to integrate as mentioned above.

The payment method data structure is as follows:

<ul>
<li><em>method_id</em> - string identifying the payment method, lowercase using alphanumerics, -, and _</li>
<li>base - string used as the base for the magically constructed callback names, each of which will be defaulted to [base]_[callback] unless explicitly set; defaults to the method_id</li>
<li>title - the translatable full title of the payment method, used in administrative interfaces</li>
<li>display_title - the title to display on forms where the payment method is selected and may include HTML for methods that require images and special descriptions; defaults to the title</li>
<li>short_title - an abbreviated title that may simply include the payment provider’s name as it makes sense to the customer (i.e. you would display PayPal, not PayPal WPS to a customer); also defaults to the title</li>
<li>description - a translatable description of the payment method, including the nature of the payment and the payment gateway that actually captures the payment</li>
<li>active - TRUE of FALSE indicating whether the default payment method rule configuration for this payment method should be enabled by default</li>
<li>terminal - TRUE or FALSE indicating whether payments can be processed via this payment method through the administrative payment terminal on an order’s Payment tab</li>
<li>offsite - TRUE or FALSE indicating whether the customer must be redirected offsite to put in their payment information; used specifically by the Off-site payment redirect checkout pane</li>
<li>offsite_autoredirect - TRUE or FALSE indicating whether the customer should be automatically redirected to an offsite payment site on the payment step of checkout</li>
<li>callbacks - an array of callback function names for the various types of callback required for all the payment method operations, arguments per callback in parentheses:
<ul>
<li>settings_form - ($settings = NULL) - returns form elements for the payment method’s settings form included as part of the payment method’s enabling action in Rules</li>
<li>submit_form - ($payment_method, $pane_values, $checkout_pane, $order) - returns form elements to collect details from the customer required to process the payment</li>
<li>submit_form_validate - ($payment_method, $pane_form, $pane_values, $order, $form_parents = array()) - validates data inputted via the payment details form elements and returns TRUE or FALSE indicating whether all the data passed validation</li>
<li>submit_form_submit - ($payment_method, $pane_form, $pane_values, $order, $charge) - processes payment as necessary using data inputted via the payment details form elements on the form, resulting in the creation of a payment transaction</li>
<li>redirect_form - ($form, &$form_state, $order, $payment_method) - returns form elements that should be submitted to the redirected payment service; because of the array merge that happens upon return, the service’s URL that should receive the POST variables should be set in the #action property of the returned form array</li>
<li>redirect_form_validate - ($order, $payment_method) - upon return from a redirected payment service, this callback provides the payment method an opportunity to validate any returned data before proceeding to checkout completion; should return TRUE or FALSE indicating whether the customer should proceed to checkout completion or go back a step in the checkout process from the payment page</li>
<li>redirect_form_submit - ($order, $payment_method) - upon return from a redirected payment service, this callback provides the payment method an opportunity to perform any submission functions necessary before the customer is redirected to checkout completion</li>
</ul></li>
<li>file - the filepath of an include file relative to the method's module containing the callback functions for this method, allowing modules to store payment method code in include files that only get loaded when necessary (like the menu item file property)</li>
</ul>

!!! example Example payment method definition

    ```php
    <?php
    $payment_methods['paypal_wps'] = array(
    'base' => 'commerce_paypal_wps',
    'title' => t('PayPal WPS'),
    'short_title' => t('PayPal'),
    'description' => t('PayPal Website Payments Standard'),
    'terminal' => FALSE,
    'offsite' => TRUE,
    'offsite_autoredirect' => TRUE,
    );
    ?>
    ```

Payment methods may be altered using hook_commerce_payment_method_info_alter(&$payment_methods) before default values and magic callbacks have been set.

A single payment method object is referred to as $payment_method.
An array of payment method objects keyed by method_id is referred to as $payment_methods.
The method_id of a payment method is referred to as $method_id.

<a id="payment-transaction-status"> </a>
<h3>hook_commerce_payment_transaction_status_info()</h3>

The Payment module uses this hook to gather information on payment transaction statuses defined by enabled modules.  A payment transaction represents any attempted payment via a payment method and includes a variety of properties used for tracking the amount, outcome, and parameters of the transaction.  One of these is the transaction’s local status, not to be confused with its remote_status that stores the exact status of the transaction at the payment provider.

Transaction statuses are used to visually represent in the order’s Payment tab whether the payment should be considered a success (meaning money was actually collected) and are accordingly considered when calculating the remaining balance of an order.  Because payment statuses are critical functionality components, the default statuses listed below are actually defined in the function used to load all payment transaction statuses:

<ul>
<li>Pending - further action is required to determine if the attempted payment was a success or failure; used for payment methods like e-checks that may require time to clear or credit card authorizations that haven’t been captured yet</li>
<li>Success - the transaction is complete and a success, meaning the amount of this transaction will be subtracted from the order total to determine the outstanding balance on the order</li>
<li>Failure - the attempted transaction failed and will not be counted in totals</li>
</ul>

Additional statuses may be defined via this hook, but there is no general alteration.  The properties of the default statuses may be altered as long as the actual status key is preserved via the use of array merging.  For more information, check out the comments for commerce_payment_transaction_statuses().

The payment transaction status data structure is as follows:

<ul>
<li><em>status</em> - string identifying the transaction status, lowercase using alphanumerics, -, and _</li>
<li>title - the translatable title of the transaction status, used in administrative interfaces</li>
<li>icon - the path to the status’s icon relative to the Drupal root directory</li>
<li>total - TRUE or FALSE indicating whether transactions in this status should be totaled to determine the balance of an order</li>
</ul>

!!! example "Example payment transaction status definition"

    ```php
    <?php
    $statuses[COMMERCE_PAYMENT_STATUS_SUCCESS] = array(
    'status' => COMMERCE_PAYMENT_STATUS_SUCCESS,
    'title' => t('Success'),
    'icon' => drupal_get_path('module', 'commerce_payment') . '/theme/icon-success.png',
    ‘total' => TRUE,
    );
    ?>
    ```

!!! note "COMMERCE_PAYMENT_STATUS_SUCCESS is a string constant defined in the Payment module"

A single payment transaction status object is referred to as $transaction_status.
An array of payment transaction status objects keyed by status is referred to as $transaction_statuses.
The status of a payment transaction status is referred to as $status.

### Checkout info hooks

<ol>
<li><a href="#checkout-page">hook_commerce_checkout_page_info()</a></li>
<li><a href="#checkout-pane">hook_commerce_checkout_pane_info()</a></li>
</ol>

<a id="checkout-page"> </a>
<h3>hook_commerce_checkout_page_info()</h3>

The Checkout module uses this hook to collect information on all the available pages in the checkout process.  The checkout form is not a true multistep form in the Drupal sense, but it does use a series of connected menu items and the same form builder function to present the contents of each checkout page.  Furthermore, as the customer progresses through checkout, their order’s status will be updated to reflect their current step in checkout.

The Checkout module defines several checkout pages in its own implementation of this hook, commerce_checkout_commerce_checkout_page_info():

<ul>
<li>Checkout - the basic page where the customer will enter their order information</li>
<li>Review - a page where they can verify that the details of their order are correct (and the default location of the payment checkout pane if the Payment module is enabled)</li>
<li>Complete - the final step in checkout displaying pertinent order details and links</li>
</ul>

The Payment module adds an additional page:
<ul>
<li>Payment - a page that only appears when the customer selected an offsite payment method; the related checkout pane handles building the form and automatically submitting it to send the customer to the payment provider</li>
</ul>

The checkout page array contains properties that define how the page should interact with the shopping cart and order status systems.  It also contains properties that define the appearance and use of buttons on the page.  

The full list of properties is as follows:

<ul>
<li><em>page_id</em> - string identifying the page, lowercase using alphanumerics, -, and _</li>
<li>title - the Drupal page title used for this checkout page</li>
<li>name - the translatable name of the page, used in administrative displays and the page’s corresponding order status; if not specified, defaults to the title</li>
<li>help - the translatable help text displayed in a .checkout-help div at the top of the checkout page (defined as part of the form array, not displayed via hook_help())</li>
<li>weight - integer weight of the page used for determining the page order; populated automatically if not specified</li>
<li>status_cart - TRUE or FALSE indicating whether this page’s corresponding order status should be considered a shopping cart order status (this is necessary because the shopping cart module relies on order status to identify the user’s current shopping cart); defaults to TRUE</li>
<li>buttons - TRUE or FALSE indicating whether the checkout page should have buttons for continuing and going back in the checkout process; defaults to TRUE</li>
<li>back_value - the translatable value of the submit button used for going back in the checkout process; defaults to ‘Back’</li>
<li>submit_value - the translatable value of the submit button used for going forward in the checkout process; defaults to ‘Continue’</li>
<li>prev_page - the page_id of the previous page in the checkout process; should not be set by the hook but will be populated automatically when the page is loaded</li>
<li>next_page - the page_id of the next page in the checkout process; should not be set by the hook but will be populated automatically when the page is loaded</li>
</ul>

!!! example "Example checkout page definition"

    ```php
    <?php
    $checkout_pages['complete'] = array(
    'name' => t('Complete'),
    'title' => t('Checkout complete'),
    'weight' => 50,
    'status_cart' => FALSE,
    'buttons' => FALSE,
    );
    ?>
    ```

At this point there is no way to add checkout pages via the UI, so sites wishing to add extra steps to the checkout process will need to define custom pages.  Existing pages may be altered using hook_commerce_checkout_page_info_alter(&$checkout_pages) before the defaults are merged in and the pages are sorted by weight.

A single checkout page array is referred to as $checkout_page.
An array of checkout page array keyed by page_id is referred to as $checkout_pages.
The page_id of a checkout page is referred to as $page_id.

<a id="checkout-pane"> </a>
<h3>hook_commerce_checkout_pane_info()</h3>

The Checkout module uses this hook to collect information on all the available checkout panes that may be assigned to checkout pages to build the checkout form.  Any number of panes may be assigned to a page and reordered using the checkout form builder.  Each pane may also have its own settings form accessible from the builder.  On the checkout page, a pane is represented as a fieldset.  Panes possess a variety of callbacks used to define settings and checkout form elements and validate / process submitted data.

The Checkout module defines a couple of checkout panes in its own implementation of this hook, commerce_checkout_commerce_checkout_pane_info():

<ul>
<li>Review - the main pane on the default Review page that displays details from other checkout panes for the user to review prior to completion</li>
<li>Completion message - the main pane on the default Complete page that displays the checkout completion message and links</li>
</ul>

Other checkout panes are defined by the Cart, Customer, and Payment modules as follows:

<ul>
<li>Shopping cart contents - displays a View listing the contents of the shopping cart order with a summary including the total cost and number of items but no links (as used in the cart block)</li>
<li>Customer profile panes - the Customer module defines one for each type of customer information profile using the name of the profile type as the title of the pane</li>
<li>Payment - the main payment pane that lets the customer select a payment method and supply any necessary payment details; appears on the Review page beneath the Review pane by default, allowing payments to be processed immediately on submission for security purposes</li>
<li>Off-site payment redirect - a pane that handles redirected payment services with some specialized behavior; should be the only pane on the actual payment page</li>
</ul>

The checkout pane array contains properties that directly affect the pane’s fieldset display on the checkout form.  It also contains a property used to automatically populate an array of callback function names.  The full list of properties is as follows:

<ul>
<li><em>pane_id</em> - string identifying the pane, lowercase using alphanumerics, -, and _</li>
<li>title - the translatable title used for this checkout pane as the fieldset title in checkout</li>
<li>name - the translatable name of the pane, used in administrative displays; if not specified, defaults to the title</li>
<li>page - the page_id of the checkout page the pane should appear on by default; defaults to ‘checkout’</li>
<li>collapsible - TRUE or FALSE indicating whether the checkout pane’s fieldset should be collapsible; defaults to FALSE</li>
<li>collapsed - TRUE or FALSE indicating whether the checkout pane’s fieldset should be collapsed by default; defaults to FALSE</li>
<li>weight - integer weight of the page used for determining the page order; defaults to 0</li>
<li>enabled - TRUE or FALSE indicating whether the pane is enabled by default; defaults to TRUE</li>
<li>review - TRUE or FALSE indicating whether the pane should be included in the review checkout pane; defaults to TRUE</li>
<li>module - the name of the module that defined the pane; should not be set by the hook but will be populated automatically when the pane is loaded</li>
<li>file - the filepath of an include file relative to the pane’s module containing the callback functions for this pane, allowing modules to store checkout pane code in include files that only get loaded when necessary (like the menu item file property)</li>
<li>base - string used as the base for the magically constructed callback names, each of which will be defaulted to [base]_[callback] unless explicitly set; defaults to the pane_id</li>
<li>callbacks - an array of callback function names for the various types of callback required for all the checkout pane operations, arguments per callback in parentheses:
<ul>
<li>settings_form - ($checkout_pane) - returns form elements for the pane’s settings form</li>
<li>checkout_form - ($form, &$form_state, $checkout_pane, $order) - returns form elements for the pane’s checkout form fieldset</li>
<li>checkout_form_validate - ($form, &$form_state, $checkout_pane, $order) - validates data inputted via the pane’s elements on the checkout form and returns TRUE or FALSE indicating whether all the data passed validation</li>
<li>checkout_form_submit - ($form, &$form_state, $checkout_pane, $order) - processes data inputted via the pane’s elements on the checkout form, often updating parts of the order object based on the data</li>
<li>review - ($form, $form_state, $checkout_pane, $order) - returns data used in the construction of the Review checkout pane</li>
</ul></li>
</ul>

The helper function commerce_checkout_pane_callback() will include a checkout pane’s include file if specified and check for the existence of a callback, returning either the name of the function or FALSE if the specified callback does not exist for the specified pane.

!!! example "Example checkout pane definition"

    ```php
    <?php
    $checkout_panes['checkout_completion_message'] = array(
    'title' => t('Completion message'),
    'file' => 'includes/commerce_checkout.checkout_pane.inc',
    'base' => 'commerce_checkout_completion_message_pane',
    'page' => 'complete',
    );
    ```

Checkout panes may be altered using hook_commerce_checkout_pane_info_alter(&$checkout_panes) before the defaults are merged in and the panes are sorted by weight (but after the module has been set).

A single checkout pane array is referred to as $checkout_pane.
An array of checkout pane array keyed by pane_id is referred to as $checkout_panes.
The pane_id of a checkout pane is referred to as $pane_id.

### Customer info hooks

<h3>hook_commerce_customer_profile_type_info()</h3>

The Customer module uses this hook to gather information on the types of customer information profiles defined by enabled modules.  Each type is represented as a new bundle of the customer information profile entity and will have a corresponding checkout pane defined for it that may be used in the checkout form to collect information from the customer like name, address, tax information, etc.  Every bundle comes with a locked address field and additional fields may be added as necessary.

The Customer module defines a single customer information profile type in its own implementation of this hook, commerce_customer_commerce_commerce_customer_profile_type_info():

<ul>
<li>Billing information - used to collect a billing name and address from the customer for use in processing payments.</li>
</ul>

The full list of properties for a profile type is as follows:

<ul>
<li><em>type</em> - string identifying the profile type, lowercase using alphanumerics, -, and _</li>
<li>name - the translatable name of the profile type, used as the title of the corresponding checkout pane</li>
<li>description - a translatable description of the intended use of data contained in this type of customer information profile</li>
<li>help - a translatable help message to be displayed at the top of the administrative add / edit form for profiles of this type</li>
<li>addressfield - boolean indicating whether the profile type should have a default address field; defaults to TRUE</li>
<li>module - the name of the module that defined the profile type; should not be set by the hook but will be populated automatically when the pane is loaded</li>
</ul>

!!! example "Example customer information profile type definition"

    ```php
    <?php
    $profile_types['billing'] = array(
    'type' => 'billing',
    'name' => t('Billing information'),
    'description' => t('The profile used to collect billing information on the checkout and order forms.'),
    );
    ?>
    ```

Customer information profile types may be altered using hook_commerce_customer_profile_info_alter(&$profile_types) after the module has been set.

A single customer profile type array is referred to as $profile_type.
An array of customer profile type arrays keyed by type is referred to as $profile_types.
The type of a customer profile type is referred to as $type.

### Line Item info hooks

<h3>hook_commerce_line_item_type_info()</h3>

Note: [Commerce Examples](https://drupal.org/project/commerce_examples) has a line item example demonstrating this, and [Commerce Custom Line Items](https://drupal.org/project/commerce_custom_line_items) actually will create custom line items for you. And here are two screencasts on custom line items: [Introduction to Custom Line Items](https://www.commerceguys.com/resources/articles/237) and [Using Custom Line Items to Provide a Donation Feature](https://www.commerceguys.com/resources/articles/238).

The Line Item module uses this hook to gather information on the types of line items defined by enabled modules.  Each type is represented as a new bundle of the line item entity.  Every bundle comes with a locked price field and additional fields may be added by the defining modules as necessary.  For example, the product line item type gets a product reference field attached to it that relates the line item to the product it represents.

The Line Item module doesn’t define any line item types on its own.  However, when other modules are enabled that implement hook_commerce_line_item_type_info(), the Line Item module will detect it and perform initial configuration via the use of a special callback in the line item type’s definition.  Currently defined line item types include:

<ul>
<li>Product - actually defined by the Product Reference module, this line item type references a product and uses the SKU and special view modes for display in line item interfaces</li>
</ul>

The full list of properties for a line item type is as follows (callback properties show arguments passed to the callbacks in parentheses):

<ul>
<li><em>type</em> - string identifying the line item type, lowercase using alphanumerics, -, and _</li>
<li>name - the translatable name of the line item type, used in administrative interfaces including the “Add line item” form on the order edit page</li>
<li>description - a translatable description of the intended use of line items of this type</li>
<li>product - boolean indicating whether this line item type can be used as a product line item type in interfaces like the Add to Cart form</li>
<li>add_form_submit_value - the translatable value of the submit button used for adding the line item</li>
<li>base - string used as the base for the magically constructed callback names, each of which will be defaulted to [base]_[callback] unless explicitly set; defaults to the type</li>
<li>callbacks - an array of callback function names for the various types of callback required for all the line item type operations, arguments per callback in parentheses:
<ul>
<li>configuration - () - configures the line item type for use, typically by adding additional fields to the line item type besides the default price field</li>
<li>title - ($line_item) - returns a sanitized title of the line item for use in Views and other displays</li>
<li>add_form - () - returns the form elements necessary to add a line item of this type to an order via a line item manager widget</li>
<li>add_form_submit - (&$line_item, $element, &$form_state, $form) - processes data inputted via the add line item form elements for this line item type, adding data to the new line item object; should return an error message if the line item should not be added for some reason</li>
</ul></li>
</ul>

!!! example "Example line item type definition"

    ```php
    <?php
    $line_item_types[‘product’] = array(
    'type' => 'product',
    'name' => t('Product'),
    'description' => t('References a product and displays it with the SKU as the label.'),
    'add_form_submit_value' => t('Add product'),
    'base' => 'commerce_product_line_item',
    'callbacks' => array(
        'configuration' => 'commerce_product_reference_configure_line_item',
    ),
    );
    ?>
    ```

Line item types may be altered using hook_commerce_line_item_type_info_alter(&$line_item_types).

A single line item type array is referred to as $line_item_type.
An array of line item type arrays keyed by type is referred to as $line_item_types.
The type of a line item type is referred to as $type.

### Order info hooks

<ol>
<li><a href="#order-state">hook_commerce_order_state_info()</a></li>
<li><a href="#order-status">hook_commerce_order_status_info()</a></li>
</ol>

<a id="order-state"> </a>
<h3>hook_commerce_order_state_info()</h3>

The Order module uses this hook to gather information on order states defined by enabled modules.  An order state is a particular phase in the life-cycle of an order that is comprised of one or more order statuses.  In that regard, an order state is little more than a container for order statuses with a default status per state.  This is useful for categorizing orders and advancing orders from one state to the next without needing to know the particular status an order will end up in.

The Order modules defines several order states in its own implementation of this hook, commerce_order_commerce_order_state_info():

<ul>
<li>Canceled - Orders in this state have been canceled through some user action.</li>
<li>Pending - Orders in this state have been created and are awaiting further action.</li>
<li>Completed - Orders in this state have been completed as far as the customer is concerned.</li>
</ul>

Additionally, the Cart and Checkout modules define the following order states:

<ul>
<li>Shopping cart - Orders in this state have not been completed by the customer yet.</li>
<li>Checkout - Orders in this state have begun but not completed the checkout process.</li>
</ul>

The order state data structure is as follows:

<ul>
<li><em>name</em> - string identifying the order state, lowercase using alphanumerics, -, and _</li>
<li>title - the translatable title of the order state, used in administrative interfaces</li>
<li>description - a translatable description of the types of orders that would be in this state</li>
<li>weight - integer weight of the state used for determining the state order; defaults to 0</li>
<li>default_status - name of the default order status for this state</li>
</ul>

!!! example "Example order state definition"

    ```php
    <?php
    $order_states['completed'] = array(
    'name' => 'complete',
    'title' => t('Completed'),
    'description' => t('Orders in this state have been completed as far as the customer is concerned.'),
    'weight' => 10,
    'default_status' => 'complete',
    );

    // return $order_states //from hook function
    ?>
    ```

Order states may be altered using hook_commerce_order_state_info_alter(&$order_states) before being sorted by weight.

A single order state array is referred to as $order_state.
An array of order state arrays keyed by name is referred to as $order_states.
The name of an order state is referred to as $name.

<a id="order-status"> </a>
<h3>hook_commerce_order_status_info()</h3>

The Order module uses this hook to gather information on order statuses defined by enabled modules.  An order status is a single step in the life-cycle of an order that administrators can use to know at a glance what has occurred to the order already and/or what the next step in processing the order will be.

The Order modules defines several order statuses in its own implementation of this hook, commerce_order_commerce_order_status_info():

<ul>
<li>Canceled - default status of the Canceled state, used for orders that are marked as canceled via the administrative user interface</li>
<li>Pending - default status of the Pending state, used to indicate the order has completed checkout and is awaiting further action before being considered complete</li>
<li>Completed - default status of the Completed state, used for orders that don’t require any further attention or customer interaction</li>
</ul>

The Cart and Checkout modules also define order statuses and interact with them in special ways.  The Cart module actually uses the order status to identify an order as a user’s shopping cart order based on the special cart property of order statuses.  The Checkout module uses the order status to determine which page of the checkout process a customer is currently on when they go to the checkout URL.  As the order progresses through checkout, the order status is updated to reflect the new page.  The statuses defined for these things are as follows:

<ul>
<li>Shopping cart - default status of the Shopping cart state, used for orders that are pure shopping cart orders that have not begun the checkout process at all</li>
<li>Checkout: [page name] - each checkout page has a related order status containing the name of the checkout page the order has progressed to; orders in this status are either in checkout or have been abandoned at the indicated step of the checkout process</li>
</ul>

The order status data structure is as follows:

<ul>
<li><em>name</em> - string identifying the order status, lowercase using alphanumerics, -, and _</li>
<li>title - the translatable title of the order status, used in administrative interfaces</li>
<li>state - the name of the order state the order status belongs to</li>
<li>cart - TRUE or FALSE indicating whether orders with this status should be considered shopping cart orders</li>
<li>weight - integer weight of the state used for determining the status order; defaults to 0</li>
<li>status - TRUE or FALSE indicating the enabled status of this order status, with disabled statuses not being available for use; defaults to TRUE</li>
</ul>

!!! example "Example order status definition"

    ```php
    <?php
    $order_statuses['completed'] = array(
    'name' => 'completed',
    'title' => t('Completed'),
    'state' => 'completed',
    );

    // return $order_statuses;  // from hook fn
    ?>
    ```

Order statuses may be altered using hook_commerce_order_status_info_alter(&$order_statuses) after default values have been set and before being sorted by weight.

A single order status array is referred to as $order_status.
An array of order status arrays keyed by name is referred to as $order_statuses.
The name of an order status is referred to as $name.