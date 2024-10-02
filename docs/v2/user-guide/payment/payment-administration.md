---
title: Payment administration
taxonomy:
    category: docs
---

In order to accept payments you need to create a payment gateway for your store. Unless you choose a manual gateway you need to first install the relevant module for your chosen payment provider (PayPal, Stripe, Braintree etc.).

After you've installed the module(s) go to /admin/commerce/config/payment-gateways to create and configure your payment gateway(s).

Click 'Add payment gateway', choose your provider from the plugin list and complete the form fields that appears below.

## Authorization
### Create an authorization

!!! note "We need help filling out this section! Feel free to follow the *edit this page* link and contribute.""

Some gateways will support backend payment entry. Especially manual payments.

### Voiding an authorization

In order to cancel an authorization we 'Void' it. For example, on travel sites, normally when a customer adds a trip request, a payment authorization is added to the order. It is only when the trip is confirmed that the authorization becomes a charge.

Similary, in our case, let's say we had added a payment authorization for an order. However, upon processing the order, we notice that the item is out of stock. We now need to 'Void' the payment. Voiding payments are quite easy. Just as before, you first need to locate the order. Then, as you did before, click on the `Payments` tab and locate the authorized payment.

![image5](/v2/user-guide/images/payment_authorization.png)

 Click the `Void` link and confirm that you want to void the payment.

![image6](/v2/user-guide/images/payment_voided_confirm.png)

 Once you confirm, the payment page should look like this, with a "(Voided)" added next to the payment that you just voided.

![image7](/v2/user-guide/images/payment_voided.png)

### Capture an authorization

Payments can be authorized during the checkout flow and captured separately by store administrators at a later time (e.g., when a shipment is sent to fulfillment, when a shipment is shipped, or when a shipment is delivered). Note: not all payment gateways support this feature. Store administrators will only have the ability to capture a prior payment authorization if the payment gateway was configured for this type of transaction at the time the order was placed.

To capture a prior payment authorization for an order, navigate to the *Orders* management page from the *Commerce* menu using the administrative toolbar. Search or browse for the order with a prior payment authorization to be captured. Expand the operations popup and click *Payments* from the menu or click the hyperlinked order number to open the order view page then click the *Payments* tab to view the order payments.

![Payments page (pre-capture)](/v2/user-guide/images/payments-list-pre-capture.png)

Click the *Capture* button from the operations popup to view the *Capture payment* page.

![Capture payment page](/v2/user-guide/images/capture-payment-pre-capture-mouseover-capture-button.png)

Update the *Amount* field as needed. Click the *Capture* button to submit the form and transmit the capture request to the payment gateway.

![Payments page (post-capture)](/v2/user-guide/images/payments-list-post-capture.png)

If the capture request was successful, a success message will be displayed on the order payments page. Additionally, the payment state will be updated to *Completed*.

## Manage payments

s a store administrator there are times where you often find yourself having to manage orders and payments on behalf of customers. Some customers might call in to modify their saved credit cards, or you might need to refund or capture payments for orders. With Drupal Commerce, you get a nice interface that let's you manage order payments and authorizations with ease.

### Capturing a payment

Let's assume a scenario where the customer calls in and requests to make a change to their order. Let's say they wanted to modify the quantity ordered for one of the products. Instead of 1 quantity, they now want 2 of this item. As an admin you can go ahead and edit the item and enter "2" for the quantity. However, you now have a changed order total. You need to request more payment from the customer.

Capturing payments for an order is done from the Payments tab above.

![image1](/v2/user-guide/images/order_payment.png)

Notice, the payments that have already been captured for our example order, is displayed in the page. For this order, the customer has already paid $118.08, now a difference of $30.74 needs to be paid. Click on the `Add payment` button.

![image2](/v2/user-guide/images/new_payment_for_order.png)

 Now, select the payment type and continue with the prompt.

![image3](/v2/user-guide/images/capture_payment.png)

 Once the payment is successful, you'll be notified and the new payment will be added to the list.

![image4](/v2/user-guide/images/new_captured_payment.png)

### Refunding a payment

Payments can be refunded when they've been authorized and captured. There maybe times when you've already taken payment but need to refund an order, either due to lack of stock, damaged product, cancelled order, or some other reason. Similar to voiding payments, refunding payments follow the same process. You locate the order, click the `Payments` tab, find the captured payment and hit the `Refund` link. You'll be taken to a confirmation page.

![image8](/v2/user-guide/images/payment_refunded_confirm.png)

 Once you confirm the refund, the payment will be refunded to the customer and the refunded payment would look like this.

![image9](/v2/user-guide/images/payment_refunded.png)
