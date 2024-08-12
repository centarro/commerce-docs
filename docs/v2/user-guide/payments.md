---
title: Payments
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
 
![image5](../user-guide/images/payment_authorization.png)
 
 Click the `Void` link and confirm that you want to void the payment.
 
![image6](../user-guide/images/payment_voided_confirm.png)
 
 Once you confirm, the payment page should look like this, with a "(Voided)" added next to the payment that you just voided.
 
![image7](../user-guide/images/payment_voided.png)

### Capture an authorization

Payments can be authorized during the checkout flow and captured separately by store administrators at a later time (e.g., when a shipment is sent to fulfillment, when a shipment is shipped, or when a shipment is delivered). Note: not all payment gateways support this feature. Store administrators will only have the ability to capture a prior payment authorization if the payment gateway was configured for this type of transaction at the time the order was placed.

To capture a prior payment authorization for an order, navigate to the *Orders* management page from the *Commerce* menu using the administrative toolbar. Search or browse for the order with a prior payment authorization to be captured. Expand the operations popup and click *Payments* from the menu or click the hyperlinked order number to open the order view page then click the *Payments* tab to view the order payments.

![Payments page (pre-capture)](../user-guide/images/payments-list-pre-capture.png)

Click the *Capture* button from the operations popup to view the *Capture payment* page.

![Capture payment page](../user-guide/images/capture-payment-pre-capture-mouseover-capture-button.png)

Update the *Amount* field as needed. Click the *Capture* button to submit the form and transmit the capture request to the payment gateway.

![Payments page (post-capture)](../user-guide/images/payments-list-post-capture.png)

If the capture request was successful, a success message will be displayed on the order payments page. Additionally, the payment state will be updated to *Completed*.

## Manage payments

s a store administrator there are times where you often find yourself having to manage orders and payments on behalf of customers. Some customers might call in to modify their saved credit cards, or you might need to refund or capture payments for orders. With Drupal Commerce, you get a nice interface that let's you manage order payments and authorizations with ease.

### Capturing a payment

Let's assume a scenario where the customer calls in and requests to make a change to their order. Let's say they wanted to modify the quantity ordered for one of the products. Instead of 1 quantity, they now want 2 of this item. As an admin you can go ahead and edit the item and enter "2" for the quantity. However, you now have a changed order total. You need to request more payment from the customer.

Capturing payments for an order is done from the Payments tab above.

![image1](../user-guide/images/order_payment.png)

Notice, the payments that have already been captured for our example order, is displayed in the page. For this order, the customer has already paid $118.08, now a difference of $30.74 needs to be paid. Click on the `Add payment` button.
 
![image2](../user-guide/images/new_payment_for_order.png)
 
 Now, select the payment type and continue with the prompt.
 
![image3](../user-guide/images/capture_payment.png)
 
 Once the payment is successful, you'll be notified and the new payment will be added to the list.
 
![image4](../user-guide/images/new_captured_payment.png)

### Refunding a payment

Payments can be refunded when they've been authorized and captured. There maybe times when you've already taken payment but need to refund an order, either due to lack of stock, damaged product, cancelled order, or some other reason. Similar to voiding payments, refunding payments follow the same process. You locate the order, click the `Payments` tab, find the captured payment and hit the `Refund` link. You'll be taken to a confirmation page.
 
![image8](../user-guide/images/payment_refunded_confirm.png)
 
 Once you confirm the refund, the payment will be refunded to the customer and the refunded payment would look like this.

![image9](../user-guide/images/payment_refunded.png)

## Configuring Gateways
### PayPal

The [https://www.drupal.org/project/commerce_paypal](commerce_paypal) module provides several different integration methods with PayPal which gives you a variety of options to choose from when looking to use PayPal in your checkout flow. The preferred method is to use PayPal checkout to inject the appropriate payment methods into the checkout flow automatically. Depending on the cart contents, user device, browser, and location PayPal will determine what payment methods are available to the customer and smartly display the appropriate payment buttons. This user guide is focused on the PayPal checkout integration as this is the integration that will be continueally developed and supported going forward.

#### General Configuration
Most of the configuration options are standard gateway setup options but there are a few configuration settings which are unique to PayPal that you need to configure.

1. Choose which PayPal platform features to use. We recommend using the "Accep PayPal with Smart Payment Buttons" option as that will allow buttons to be automatically displayed based on the content of the cart, geographical location, and browser and device capabilities.
2. Choose whether or not to collect billing information. If you disable this option then PayPal will collect billing information.
3. Choose whether or not PayPal should collect shipping information

Generally speaking we would not suggest allowing PayPal to collect a shipping or billing address as changing these addresses in PayPal can throw off tax and shipping calculations. If you do decide to allow customers to enter these addresses in the PayPal modal then we would recommend updating the Shipping and Billing profile addresses on the order so that information is kept in sync with the data collected by PayPal. If you don't update the shipping and billing information based on what is collected in PayPal then there is a large risk that you will ship products to the incorrect shipping address since the customer can change the address in PayPal.

###  API Configuration

### Webhooks Configuration
Not all integrations will require webhook setup but there are several instances where setting up webhooks for PayPal would be necessary.

1. On rare occasions, PayPal may return a pending, rather than completed status on a payment during checkout. Checkout will still complete, but the order has not technically be paid in full until a webhook notification tells us so. This does vary per merchant and customer, so some sites might never encounter this, while others may have a higher frequency.
2. If a payment is refunded through the PayPal UX, rather than the site UX, the webhook will send a notification, so that the payment refund is reflected on the order. Note that if this is done by the merchant, it is important to define what the workflow is. e.g. is there a shipment that needs to be pulled? Is there a license that needs to be cancelled? etc...

There are currently five events that the module will respond to. They are listed below:

1. PAYMENT.AUTHORIZATION.VOIDED
2. PAYMENT.CAPTURE.COMPLETED
3. PAYMENT.CAPTURE.REFUNDED
4. PAYMENT.CAPTURE.PENDING
5. PAYMENT.CAPTURE.DENIED

In order to respond to these events configuration in PayPal is necessary.

1. Go to the edit form of the PayPal Payment Gateway of your site.
2. Copy the Webhook Url from there. It is: https://[domain-name]/payment/notify/[payment-gateway-machine-name], but the gateway edit form will take care of displaying this for you.
3. Take note of the 5 events that are supported.
4. Go to your PayPal developer dashboard, and edit your site's application.
5. Choose to add a new Webhook.
6. Specify the URL you got from the Payment Gateway, and enable the 5 events specified.
7. Copy the resulting Webhook Id.
8. Add the Webhook Id to your PayPal Payment Gateway, and save.
   
Note that Webhooks will need to be created for both live and sandbox environments. 
