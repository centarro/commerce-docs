!!! note "We need help filling out this section! Feel free to follow the *edit this page* link and contribute."

## Create an order

Orders can be created for customers in the administrative areas. This can be done by going to the **Orders** management page from the **Commerce** page from the administrative toolbar. Click on **Create  a new order**.

![Orders](images/order-overview.png)

When creating an order you select the store it is for and specify a customer.

![initial](images/create-order.png)

Instead of re-using a customer, a new one can be created.

![create](images/create-order-new-customer.png)

Click **Create**. You will then be able to provide information about the order. The first items will allow you to provide billing information for the order. You can also specify if the order is a cart or not. By marking the order as a cart, the customer will be able to review the cart and go through the checkout with it.

![top](images/order-create-top.png)

Next, order items can be created. The order items are the products the customer is purchasing.

![order-items](images/create-order-order-items.png)

Once satisfied, click **Save**


!!! note "We need help filling out this section! Feel free to follow the *edit this page* link and contribute. Need to add documentation for adjustments and coupons."

## Viewing carts
Carts are created whenever a customer submits an Add to Cart form or when an admin begins an order via the _Create order_ link on the _Orders_ page and checks the _Cart_ checkbox before saving the order. Carts have an order ID but no order number, as those are not generated until the order is placed. You can view the current carts in your store by navigating to the Commerce > Orders page and clicking the _Carts_ tab. From the _Carts_ tab you can view, edit, reassign, add payment information to, or delete a cart using the dropdown operations menu based on your account's permissions. 

When a customer completes checkout or an admin places the order, the cart option is toggled off and the order ID remains the same but an order number is generated that follows the number pattern setup for the order type. Until checkout completion or order placement, carts are in a unique order status called _Draft_ that allows the order items to be periodically refreshed, updating the pricing and availability of the products in the cart. Once a cart is placed and moves out of the draft status, the order will no longer be periodically refreshed. 

### Purging abandoned carts

Periodically purging abandoned carts will minimize junk carts in the database and alleviate performance issues caused by hundreds of thousands of stale carts hanging around indefinitely. You can set the system to automatically purge abandoned carts after a certain period of time by enabling the _Delete abandoned carts_ option on the order type configuration screen. The setting needs to be enabled for each order type, and the time period should be configured. The default when enabling this option is 30 days. Carts that have not been updated within the configured time frame will be deleted when the cron job runs. Note that the configured time frame is a rolling interval, meaning the counter is reset whenever the cart is modified.

You can also manually purge carts en masse via the bulk operation available to you on the Cart page in the admin interface.

## View orders, find specific ones.

## Manage an order

!!! note "We need help filling out this section! Feel free to follow the *edit this page* link and contribute."

### Setting the order number pattern

In Drupal Commerce, you have the flexibility to tailor order numbers to your business’s requirements. Number patterns are configurable per order type and can be shared or set to be unique for each store.

To begin, navigate to Commerce > Configuration > Orders > Number patterns to create a number pattern. On this page, you can establish new patterns, each requiring a unique name. The number patterns form offers several configuration options, including regeneration settings, what number to start from, and whether numbers are shared between stores.

The strength of number patterns lies in the versatility provided as to how the numbers are generated. You can incorporate a blend of custom strings and tokens, allowing for prefixed or suffixed numbers based on fixed strings or structured data from your site. This gives you the ability to brand your order numbers and segment your orders based on business needs without writing custom code.

After configuring your number pattern, it’s time to make use of it on one or more order types. Proceed to Commerce > Configuration > Orders > Order types. Here, edit the order type that requires the new number pattern. Within the order type’s edit page, select your previously created number pattern from the dropdown menu then save the form. New orders will now make use of your custom number pattern.

### Order workflows

High level explanation of "draft" orders, workflows.

!!! note "We need help filling out this section! Feel free to follow the *edit this page* link and contribute."

Explain what a workflow is (states and transitions) at a high level. 

Detail what ones are provided by Drupal Commerce and when or why you may use one.

How to change an order's workflow.

### Processing an order

!!! note We need help filling out this section! Feel free to follow the *edit this page* link and contribute.""

How someone would process an order in a workflow.

Use the example of "with validation" for "in validation, ensure payment is not fraudulent / captured, then mark validated"

Use the example of shipping, validated because it has stock. Completed because shipped.

## Receipt emails

These settings are managed on an Order Type basis so that you can have different settings for different types of orders and manage order notifications in a granular way.


### Enable / Disable Customer Order Receipts & Store Notification Emails

Visit the Commerce configuration page and go to the Order types page in the Orders section.

![Select Order types](./images/commerce2-order-configuration.png)

Click on **Edit** on the order type you wish to configure

![Select Order Type](./images/commerce2-order-type-selection.png)

Locate the **Emails** section

![Check / uncheck notification](./images/commerce2-email-section.png)

 - Check / uncheck the **Email the customer a receipt when an order is placed** box.
 - Enter the store notification email address into the **Send a copy of the receipt to this email:** field. You can enter multiple comma separated addresses into this field eg "one@example.com, two@example.com, three@example.com"

### Editing / Translating the Email Text

Use the template file located in `/commerce/modules/order/templates/commerce-order-receipt.html.twig`.

You can copy this file to your theme and then edit the text as desired. You can also use the file as a translation reference when searching for strings to translate in the user interface translation UI.


