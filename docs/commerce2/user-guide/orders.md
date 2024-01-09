!!! note "We need help filling out this section! Feel free to follow the *edit this page* link and contribute."

## Create an order

Orders can be created for customers in the administrative areas. This can be done by going to the **Orders** management page from the **Commerce** page from the administrative toolbar. Click on **Create  a new order**.

![Orders](images/order-overview.png)

When creating an order you select the store it is for and specify a customer.

![initial](images/create-order.png)

Instead of re-using a customer, a new one can be created.

![create](images/create-order-new-customer.png)

Click **Create**. You will then be able to provide information about the order. The first items will allow you to provide billing information for the order. You can also specify if the order is a cart or not. By marking the order as a cart, the customer will able to review the cart and go through the checkout with it.

![top](images/order-create-top.png)

Next, order items can be created. The order items are the products the customer is purchasing.

![order-items](images/create-order-order-items.png)

Once satisfied, click **Save**


!!! note "We need help filling out this section! Feel free to follow the *edit this page* link and contribute. Need to add documentation for adjustments and coupons."

## View carts

## View orders, find specific ones.

## Manage and order


!!! note "We need help filling out this section! Feel free to follow the *edit this page* link and contribute."

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
