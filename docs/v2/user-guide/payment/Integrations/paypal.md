
# PayPal Configuration

The [https://www.drupal.org/project/commerce_paypal](commerce_paypal) module provides several different integration methods with PayPal which gives you a variety of options to choose from when looking to use PayPal in your checkout flow. The preferred method is to use PayPal checkout to inject the appropriate payment methods into the checkout flow automatically. Depending on the cart contents, user device, browser, and location PayPal will determine what payment methods are available to the customer and smartly display the appropriate payment buttons. This user guide is focused on the PayPal checkout integration as this is the integration that will be continueally developed and supported going forward.

## General Configuration
Most of the configuration options are standard gateway setup options but there are a few configuration settings which are unique to PayPal that you need to configure.

1. Choose which PayPal platform features to use. We recommend using the "Accep PayPal with Smart Payment Buttons" option as that will allow buttons to be automatically displayed based on the content of the cart, geographical location, and browser and device capabilities.
2. Choose whether or not to collect billing information. If you disable this option then PayPal will collect billing information.
3. Choose whether or not PayPal should collect shipping information

Generally speaking we would not suggest allowing PayPal to collect a shipping or billing address as changing these addresses in PayPal can throw off tax and shipping calculations. If you do decide to allow customers to enter these addresses in the PayPal modal then we would recommend updating the Shipping and Billing profile addresses on the order so that information is kept in sync with the data collected by PayPal. If you don't update the shipping and billing information based on what is collected in PayPal then there is a large risk that you will ship products to the incorrect shipping address since the customer can change the address in PayPal.

##  API Configuration

## Webhooks Configuration
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
![image](/images/paypal-application-configuration.png)
5. Choose to add a new Webhook.
6. Specify the URL you got from the Payment Gateway, and enable the 5 events specified.
7. Copy the resulting Webhook Id.
8. Add the Webhook Id to your PayPal Payment Gateway, and save.

Note that Webhooks will need to be created for both live and sandbox environments. 