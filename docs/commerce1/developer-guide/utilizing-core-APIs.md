## Instantiating core forms in contributed modules

One of the primary development standards of Drupal Commerce is a strict separation of core APIs from the default user interfaces.  This means when you install the Product module you actually don't have any place to add, edit, or view products without enabling the Product UI module.  This allows site builders and module developers to provide completely customized user interfaces for Drupal Commerce sites without having to undo or work around an existing user interface.  To achieve this behavior, we had to make some special considerations with entity and bundle forms.

The gist of it is forms necessary to add, edit, and delete core entities / bundles are included in the API module but are only "instantiated" by the UI module.  If you enable the Product module, even though the forms are included in that module's includes/commerce_product.forms.inc file, there are no URLs you can browse to to actually submit those forms.  Instead you have to enable the Product UI module which provides menu items for each of these forms.

It does this by using Drupal's <a href="http://api.drupal.org/api/function/hook_form/7">hook_forms()</a> to create unique form IDs for each form it wants to create a menu item for.  The menu items call wrapper functions which include the commerce_product.forms.inc file and then use <a href="http://api.drupal.org/api/function/drupal_get_form/7">drupal_get_form()</a> on the UI module's unique form ID.  This allows the UI module to use <a href="http://api.drupal.org/api/function/hook_form_FORM_ID_alter/7">hook_form_FORM_ID_alter()</a> to specifically add form elements and submit handlers to the forms to make them fit in the menu items.  This includes things like cancel links going to proper URLs and redirect paths being set on form submission.

If you have a need to instantiate an entity / bundle form at a different URL, you should follow the same method.  This will allow you to assume one form ID exists for each path at which the form is displayed.  It is much easier to make assumptions about alterations and redirection using a form ID than parsing a URL or some other method.  Even if you're using the default Product UI module, you should still use this method to add additional instances of the form to your site's IA if necessary.

For a complete example, refer to the following functions to see how the product type form is instantiated by the Product UI module:

<ul>
<li>commerce_product_product_type_form() is defined in commerce_product.forms.inc. Notice it includes the name of the file that contains the form builder function in the $form_state['build_info'].</li>
<li>commerce_product_ui_forms() implements hook_forms() for the Product UI module to add a form ID commerce_product_ui_product_type_form for the product type form.</li>
<li>commerce_product_ui_menu() defines a menu item at the path admin/commerce/products/types/add which calls the commerce_product_ui_product_type_form_wrapper() callback. This function includes the form.inc file and outputs the form using the custom form ID.</li>
<li>commerce_product_ui_form_commerce_product_ui_product_type_form_alter() implements hook_form_FORM_ID_alter() on the custom form ID to add a submit callback and additional action buttons to the form that connect the form to other paths defined by the Product UI module.</li>
</ul>

## Writing SimpleTests for Commerce modules

Drupal Commerce has an automated testing suite based on the SimpleTest module in Drupal Core. 

SimpleTest resources:

<ul>
<li><a href="http://drupal.org/node/890654">SimpleTest tutorial</a></li>
<li><a href="http://drupal.org/node/265828">SimpleTest Assertions</a></li>
<li><a href="http://drupal.org/node/265762">SimpleTest API Functions</a></li>
<li><a href="http://drupal.org/project/test_notifier">Test Notifier</a> user script. Notifies you when a test has been completed so you don't have to sit and stare at your browser for 2 minutes while the test runs.</li>
</ul>

All tests should extend the class <code>CommerceBaseTestCase</code> which adds helper functions to <a href="http://api.drupal.org/api/drupal/modules--simpletest--drupal_web_test_case.php/7/source"><code>DrupalWebTestCase</code></a>. The Commerce base test class lets you quickly create products and set up different store environments for other tests.

Take a look at the <code>CommerceBaseTestCase</code> to see which functions are available when writing other Commerce tests. <em>(Need a link once the code is committed. Could also use it's own API page eventually).</em>


<h2>Best Practices for Test Development</h2>
Here are some best practices that came about while the base class was being developed.

<ul>
<li>Follow the <a href="http://www.drupalcommerce.org/developer-guide">Drupal Commerce Developer Guide</a></li>
<li>Ease your test development with: http://drupal.org/project/test_notifier</li>
<li>Save user creation for the test unless it is used more than once (user creation leads to many page loads!)</li>
<li>Capitalize module names in the comments</li>
<li>Test the default state of Drupal Commerce before considering edge cases
<ul><li>Example: Provide test coverage and consideration to the default Product entity before considering custom product entities</li></ul></li>
<li>Functional tests vs. Unit tests
<ul><li>Functional tests (UI tests) - These test the UI using form submissions and other UI interaction.
<ul><li>Test coverage should begin with functional tests since it tests the UI and the API simultaneously. If a test fails when there are only functional tests available, it is not always clear if the error resides in the UI or the API module. Because of this, test coverage should quickly expand to include unit tests.</li></ul></li>
<li>Unit tests (API tests) - These test the API using API functions and database queries.
<ul><li>Note: Unit tests do not have to extend DrupalUnitTestCase. That class is only for tests that do not require the Drupal database. Unit tests (in gneral) can extend DrupalWebTestCase to have access to the Drupal database and then use API functions and databse queries to check if the functions are behaving as expected.</li>
<li>Drupal Commerce unit tests sould extend CommerceBaseTestCase just like anything else would.</li></ul></li></ul></li>
</ul>

<h2>Separation of Tests &amp; Files</h2>

<ul>
<li>commerce_base.test - contains CommerceBaseTestCase class that all Commerce test classes should extend.</li>
<li>commerce_full.test - contains tests that are cross-system</li>
<li>[module].test - contains tests specific to code inside [module].module. These are unit tests (see description above).</li>
<li>[module]_ui.test - contains tests specific to code inside [module]_ui.module. These are usually functional tests (see description above).</li>
</ul>

## Creating orders with the Drupal Commerce API

Ryan posted an article on this topic on the Commerce Guys blog:

<ul> <li><a href="http://www.commerceguys.com/resources/articles/245">http://www.commerceguys.com/resources/articles/245</a></li></ul>

## Working with entity metadata wrappers

Many of our core API functions and hooks work with and expect you to understand the use of entity metadata wrappers. The wrapper is an object defined by the Entity API module that takes an entity type and entity object and produces a new object that can be used to access and manipulate the entity's properties and fields. Additionally, it makes it easy to work with multiple value fields and entities referenced by properties or fields of the wrapped entity.

!!! example Examples

    A product line item contains a product reference field.  Business logic often calls for different types of products to interact with the cart or checkout process differently.  Given a line item object, you could access the product information like so:


    ```php
    <?php
    $line_item_wrapper = entity_metadata_wrapper('commerce_line_item', $line_item);
    $product_type = $line_item_wrapper->commerce_product->type->value();
    ?>
    ```

    If you just had the order but not an individual line item, you'd want to loop over the line items referenced by the order.  The following example extracts the full line item object from the line item reference field attached to orders.

    ```php
    <?php
    $order_wrapper = entity_metadata_wrapper('commerce_order', $order);
    foreach ($order_wrapper->commerce_line_items as $delta => $line_item_wrapper) {
    $line_item = $line_item_wrapper->value();
    }
    ?>
    ```

    In some cases, you may need access to the raw value of a property or field. This is true in cases where you just want the ID of a referenced entity instead of the entity itself. In these instances, use the ->raw() method of a wrapper instead of ->value().

## Writing a payment method module

There are two broad categories of payment methods enabled by the payment system out of the box: on-site payment and off-site payment.  On-site payment is either processed via a third party web service using data collected through a checkout or administration form or simply entails presenting payment information to the customer for them to remit payment offline upon checkout completion.  Off-site payment is enabled through a redirect from the Payment checkout page to the payment service, with customers ideally being returned back to the Payment page upon success or failure so they can be moved forward or backward in the checkout process as the case may require.  Because off-site payment often requires direct customer input (such as a username and password), it is not normally possible to enact this type of payment through the administration form.

Payment gateways tend to offer multiple types of payment services, often providing on-site and off-site options.  Each service should be represented by a different payment method in Drupal Commerce.  Using PayPal as an example, this means the <a href="http://drupal.org/project/commerce_paypal">Commerce PayPal</a> project will include modules defining their services a separate payment methods, including PayPal WPS (off-site service), WPP (on-site service), and EC (a mixture between off-site and on-site).  When a payment method module is enabled, a default payment method rule will be defined that you can use to configure settings for your payment methods.  During the checkout process, all the active payment method rules will be evaluated and given a chance to enable their respective payment methods for use on the checkout form.  You can actually use more than one rule to enable any given payment method using a different set of API credentials or transaction settings based on conditions of the order being paid for.  The important thing to ensure is that no payment method is enabled by more than one rule.

The Payment module includes functions designed to support common types of payment services, the most common being credit card payments.  The file commerce_payment.credit_card.inc includes helper functions for building and validating credit card forms and data.  Payment method modules integrating with credit card processing services should use these functions and can use the <a href="http://drupal.org/project/commerce_authnet">Commerce Authorize.Net project</a> as an example for the integration.

Whenever a payment is attempted, a payment transaction should be created that references the order for which payment was attempted.  This includes transaction attempts that failed, that require further action, or were processed successfully as reflected in the transaction's status: Failure, Pending, Success.  The transaction functions as a log indicating while a payment failed or a basic receipt for successful payments.  The initial amount of the transaction should be the requested payment or authorization amount, but if this changes prior to completion (such as performing a prior authorization capture for a different amount than was originally authorized), the transaction should be updated to reflect the final amount of money collected.  The sum of all successful payment transactions is used to track the outstanding balance on an order.

It is quite likely that additional actions may be available to be performed on payment transactions.  These include things like voiding transactions, applying a credit to the account, or performing a prior authorization capture.  All such functions should be accessible from the "Operations" column of an order's Payment tab.  Links appear in this column through the use of Drupal's contextual link system as demonstrated by the Commerce Authorize.Net module for prior authorization captures of authorization only transactions.  Depending on the nature of the operation, you will either update the transaction (e.g. update its amount and status after capture) or create a new transaction (e.g. creating a new transaction with a  negative amount to reflect a credit).

The responsibilities of a payment method module include the following main points:

1. Defining the payment method via hook_commerce_payment_method_info(). This hook is [documented in the specification](../core-architecture/#payment-info-hooks) and allows you to define the titles and display name for the payment method along with various callback functions used to integrate with the payment system.
2. Defining your callback functions to add a settings form to the payment method's rules, collect the necessary information on the checkout and administrative payment forms, and accommodate the redirection process for off-site payment methods (see below).
3. Integrating with the payment service to actually process payments, validate payment notifications, and otherwise interact with the available APIs.
4. Defining menu items for additional payment transaction operations and providing the forms and API integration necessary to perform the operations.
5. Regular maintenance to ensure the module remains up to date with changes in the payment service's API, changes in Drupal Commerce, and security reports. Payment method modules should be managed through drupal.org's project hosting infrastructure where they benefit from version control, issue tracking, and community feedback and security oversight.

For more information and examples of these, refer to the [Payment info hook documentation](../core-architecture/#payment-info-hooks) and the proof-of-concept modules <a href="http://drupal.org/project/commerce_authnet">Commerce Authorize.Net</a> and <a href="http://drupal.org/project/commerce_paypal">Commerce PayPal</a>.  Refer to the <a href="https://web.archive.org/web/20150618020813/https://drupalcommerce.org/faq/payment-methods">payment method FAQ (wayback machine link)</a> for other modules that are developed or in development supporting different types of payment services and representing different countries.

<h3>Special notes for off-site payment methods</h3>

When a user is directed off-site to submit payment, their order remains in the Checkout: Payment status that by default is not designated as a shopping cart order status. This means if the user presses the browser back button to return to your site instead of the appropriate link on the page, they will arrive at an inappropriate checkout page for their order status and in fact may no longer have their order recognized by the checkout process.  The order is locked down to prevent manipulations to the order in a separate tab while payment is potentially being submitted.

Additionally, most off-site payment methods will return the user to your site via a POST that includes some basic payment information, such as whether or not the payment succeeded or failed and what the actual payment amount was. You should not depend on this information to actually create and update payment transactions, though this information may be used to progress through the checkout process.  This is to mitigate the risk of simulated payment completion being logged in the database as actual payments, even if the scammer ends up seeing the checkout completion page after attempting to scam the site.  This is why we make the "When an order is first paid in full" event available for use alongside the "Customer completes checkout" event.  Sites relying on off-site payment methods (and sites using authorization only transactions with delayed capture after the fact) should use the "When an order is first paid in full" event to perform business logic that is dependent on actual payment and not just the appearance of checkout completion.

For off-site payment methods, payment transactions should be created and updated based on secure notification from the payment gateway, such as PayPal's Instant Payment Notifications (IPN).  These notifications should be properly validated according to the payment gateway's specification, and upon validation, payment transactions can be created associated with the order in question or updated as necessary.

This specification actually may result in payment notifications being processed before the customer actually returns to your site.  This is especially true in the event that the customer never actually returns to your site but either closes the browser window before the payment gateway's JavaScript redirection sends them back to your site or browses to some other link on the page.  As of Drupal Commerce 1.0, when you receive a payment notification and create the associated payment transaction, you should also be calling one of two API functions designed to move the related order forward or backward in the checkout process.  The checkout router was updated to permit customers returning to the checkout/%commerce_order/payment page to be redirected to the appropriate checkout page upon their eventual return.

The two API functions are:

<ul>
<li><strong>commerce_payment_redirect_pane_next_page($order)</strong> - call this function if your payment notification is for a successful authorization or complete payment; the order will be moved to the next checkout page, and the checkout completion event will be invoked if necessary.</li>
<li><strong>commerce_payment_redirect_pane_previous_page($order)</strong> - call this function if the payment notification represents failed or canceled payment; the order will be moved to the previous checkout page so the customer can update try again or choose a different payment method.</li>
</ul>

To see an example of these functions implemented by an off-site payment method module, refer to the function <strong>commerce_paypal_wps_paypal_ipn_process()</strong> in the commerce_wps.module of the <a href="http://drupal.org/project/commerce_paypal">Commerce PayPal</a> module.
