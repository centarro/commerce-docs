---
title: Sending HTML emails
taxonomy:
    category: docs
---

You can find more information on how to send HTML emails with Symfony mailer on [Sending HTML emails with Symfony mailer](https://www.centarro.io/blog/replace-swift-mailer-symfony-mailer-html-email).

## Debugging emails

The Drupal.org documentation handbook has a great article for working with email in a development and testing environment: [https://www.drupal.org/docs/develop/local-server-setup/managing-mail-handling-for-development-or-testing](https://www.drupal.org/docs/develop/local-server-setup/managing-mail-handling-for-development-or-testing)

The Drupal Commerce team recommends using [Mailpit](https://mailpit.axllent.org/) for local email testing and development. [DDEV will send emails to it by default](https://ddev.readthedocs.io/en/stable/users/usage/developer-tools/#email-capture-and-review-mailpit).

## Structured data for order and shipping emails

Gmail can pull order and delivery details straight out of an email and surface them for the recipient: a highlighted order number, an expected delivery date, a **Track package** button, and an entry in Gmail's Purchases view. Adding [schema.org](https://schema.org) markup to your order and shipping confirmation emails is what makes that happen. For the full picture, see Google's [Email markup overview](https://developers.google.com/workspace/gmail/markup/overview).

Two things are worth knowing before you start:

- Google sometimes extracts this data on its own for large, well-known retailers and carriers, with no markup in the message. You cannot count on that for a typical store, since it favors high-volume recognized senders.
- The supported path is to embed the markup yourself and register your sending domain with Google. That is what this section covers.

### How it works

You add a JSON-LD block (a `<script type="application/ld+json">` element) to the HTML body of the email. Google reads it; the recipient never sees it. Use:

- [`Order`](https://developers.google.com/workspace/gmail/markup/reference/order) on the order confirmation email.
- [`ParcelDelivery`](https://developers.google.com/workspace/gmail/markup/reference/parcel-delivery) on the shipping confirmation email.

The data you emit has to match what the customer sees in the rendered email. Google rejects markup that disagrees with the visible message.

### Order confirmation markup

Emit an `Order` object with the order number, status, merchant, total, and a link back to the order:

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Order",
  "merchant": {
    "@type": "Organization",
    "name": "Your Store"
  },
  "orderNumber": "WB8411597464",
  "orderStatus": "https://schema.org/OrderProcessing",
  "priceCurrency": "USD",
  "price": "129.99",
  "acceptedOffer": [
    {
      "@type": "Offer",
      "itemOffered": { "@type": "Product", "name": "Blue Perma Seal Concrete Screw" },
      "price": "129.99",
      "priceCurrency": "USD",
      "eligibleQuantity": { "@type": "QuantitativeValue", "value": "1" }
    }
  ],
  "url": "https://example.com/user/1/orders/1001",
  "potentialAction": {
    "@type": "ViewAction",
    "url": "https://example.com/user/1/orders/1001"
  }
}
</script>
```

### Shipping confirmation markup

Once the shipment ships and a tracking number exists, emit a `ParcelDelivery` object. The `trackingUrl` and the `TrackAction` are what render the **Track package** button:

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ParcelDelivery",
  "deliveryAddress": {
    "@type": "PostalAddress",
    "streetAddress": "24 Willow Ln",
    "addressLocality": "Toms River",
    "addressRegion": "NJ",
    "postalCode": "08753",
    "addressCountry": "US"
  },
  "expectedArrivalUntil": "2026-06-04T20:00:00-04:00",
  "carrier": {
    "@type": "Organization",
    "name": "FedEx"
  },
  "itemShipped": {
    "@type": "Product",
    "name": "Blue Perma Seal Concrete Screw"
  },
  "trackingNumber": "479140451780",
  "trackingUrl": "https://www.fedex.com/fedextrack/?trknbr=479140451780",
  "potentialAction": {
    "@type": "TrackAction",
    "target": "https://www.fedex.com/fedextrack/?trknbr=479140451780"
  },
  "partOfOrder": {
    "@type": "Order",
    "orderNumber": "WB8411597464",
    "merchant": { "@type": "Organization", "name": "Your Store" },
    "orderStatus": "https://schema.org/OrderInTransit"
  }
}
</script>
```

The address fields (`streetAddress`, `addressLocality`, `addressRegion`, `addressCountry`, `postalCode`) are all required by Google. `expectedArrivalUntil` takes an ISO 8601 date or datetime.

### Populating the markup in Drupal Commerce

The order confirmation email renders from the `commerce-order-receipt.html.twig` template, which receives the `order_entity` variable. A store may also manage order and shipping emails as configurable email entities. Either way, build the JSON-LD from the same data the email already displays:

- The order number, total, and line items come from the order.
- The carrier, tracking number, and tracking URL come from the order's shipments. If your store already renders tracking numbers as links in the shipping confirmation, those same values feed the `ParcelDelivery` markup.

Build the payload server-side in a preprocess hook or a template callback, encode it with Drupal's JSON serializer (rather than assembling JSON by hand in Twig), and pass it to the template. Print it as raw markup so Twig does not escape it, and guard the shipping block so it only renders once tracking data exists:

```twig
{% raw %}{% if parcel_delivery_json %}
  <script type="application/ld+json">
    {{ parcel_delivery_json|raw }}
  </script>
{% endif %}{% endraw %}
```

### Registering with Google

Markup alone is not enough. Google renders it only for registered senders in good standing. To register (full instructions in [Register with Google](https://developers.google.com/workspace/gmail/markup/registering-with-google)):

1. Send a real production email containing the markup to `schema.whitelisting+sample@gmail.com`. Send it directly, since Gmail strips markup from forwarded mail.
2. Authenticate the message with SPF or DKIM, with the domain matching your `From:` address.
3. Confirm the markup passes the [Email Markup Tester](https://www.google.com/webmasters/markup-tester/) with no errors.
4. Fill out Google's registration form, linked from the same page.

Google also expects a consistent sending history (on the order of hundreds of emails a day to Gmail over several weeks) and a very low spam rate. Approval is manual and not guaranteed, and a low-volume sender may not qualify. Set expectations before promising a customer an inbox tracking card.

!!! note
    You do not need to register to test. Any marked-up email you send from a Gmail account to itself renders in Google products right away, so you can confirm the markup end to end before you register.

### Testing

- Validate the JSON-LD in the [Email Markup Tester](https://www.google.com/webmasters/markup-tester/) before sending.
- Send the message to a Gmail account and use **Show original** to confirm the markup survived rendering.
- The card only appears once your domain is registered. A passing test with no card usually means registration is still pending, not that the markup is wrong.
