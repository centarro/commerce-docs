---
title: Customer Profiles
taxonomy:
    category: docs
---

Customer profiles contain collections of data required to process orders, whether it be billing information, shipping information, or other types of details.  These customer profiles are not unique to a user, meaning a single user may have multiple instances of each type of profile.  This allows us to use the data collected to provide address book functionality where a user has a default profile of each type but may create a unique profile for a given order or choose from other previously generated profiles.

Customer profiles come from three different locations:

<ol>
  <li><strong>Checkout</strong> - each profile type has an associated checkout pane that allows the customer to enter their details on the checkout form.  If a customer is not using a past profile or has modified a previously used profile in any way, a new profile is created using the values entered on the form.  This profile is then related to the customer's order via a customer profile reference field.</li>
  <li><strong>Order edit form</strong> - each profile type is also represented on orders via default customer profile reference fields. These reference fields are populated by the relevant checkout panes but may also be filled out by administrators on the order edit form.  Profile creation works the same here as in Checkout.</li>
  <li><strong>Customer profile administration</strong> - the main menu item that lists out all the customer profiles in a View has a local action link allowing administrators to create customer profiles.  These are not linked to orders but may be associated with users to pre-populate orders and address books.  Existing profiles can also be edited, but if a profile is currently referenced by any customer profile reference field, a new profile will be created for it upon save.</li>
</ol>

As indicated above, it is important for customer profiles to be preserved in the state they are in on any given order that references them to prevent data loss.  The only exception is if a customer profile is being edited via a form that represents the only place a profile is being referenced.  This logic will allow us to prevent unnecessary duplication of profile data while ensuring data relevant to previous orders is never lost.

<ul>
  <li><a href="#configuring-creating-customer-profiles">Configuring / Creating Customer Profiles</a></li>
  <li><a href="#extending-the-default-customer-profile">Extending the Default Customer Profile</a></li>
</ul>

## Configuring / Creating Customer Profiles

<p>Customer information is collected for orders on separate entities called customer profiles that
are associated with the order through customer profile reference fields. By default, the Billing
information customer profile type just includes an address field, but it can be edited through
the field interface to include any additional fields you require. These fields will automatically be
visible on the related checkout pane for the customer profile.</p>
<p>Some modules define additional customer profile types, such as the Shipping information
customer profile type defined in the Commerce Shipping module.</p>
<p>During a default checkout process, customers cannot reuse previously created customer
profiles. A new customer profile will be saved each time, though modules like Commerce
Addressbook seek to alleviate this problem with a nice UI. Customer profiles edited through the
back-end UI may also result in duplicate customer profiles being saved to avoid the loss of data
when a customer profile is edited that has been referenced by a previous order.</p>
<h2>Adding and Editing a Customer Profile Field</h2>
<p>We're going to do the following: Add a custom field to the Billing information customer profile type, such as phone
number, and complete checkout with the new field. After completing checkout, go edit
the newly created customer profile and save it to view the resulting message.</p>

![Go to Store, then click Customer Profiles.](../images/Profile-Admin-1.png)

**Navigate to Store**

<p>Customer Profiles are a top-level configuration option in your store. Use this screenshot to help you find it.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li class="last">Store</li>
</ul>

![Let's add phone number](../images/Profile-Admin-2.png)

**Click Manage Fields**

<p>The first tab you will find yourself on is the "List" tab. That will show you all the available customer profiles. If you click "Profile Types" in the upper right, you will see all of your profile types (similar to content types for nodes). If you have installed Shipping, that includes a new profile type. Click Manage Fields to add a phone number.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Customer profiles</li>
    <li class="last">Profile Types</li>
</ul>

![Add a Field](../images/Profile-Admin-3.png)

**Add a Field**

<p>Just like any other Drupal interface, you can add any fields you would like to your customer profiles. For this example, we're just going to use the built-in text field, but you could add the <a href="https://drupal.org/project/phone">phone field module</a> and have access to phone number validation.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Customer profiles</li>
    <li class="last">Profile Types</li>
</ul>

![Checkout Example](../images/Profile-Admin-4.png)

**Checkout Example**

<p>Here on checkout you can see our address field which is a part of the default Commerce Kickstart. Additionally, below that you now see the Phone number field we've added. Let's go ahead and checkout to get to the next step.</p>

![Read the disclaimer on the customer profile edit screen.](../images/Profile-Admin-5.png)

**Edit Customer Profile**

<p>Here we are editing the customer profile. Normally this might need to be updated to account for a customer who needs to change their shipping address or other kinds of updates. Note that the original customer profile does not get lost, we simply clone a copy of the original and make the changes. We are doing this action to show you how this process works. Note that this means, by default, if you edit a customer profile it does not change the original order.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Customer profiles</li>
    <li class="last">Edit Customer Profile</li>
</ul>

![Cloned message.](../images/Profile-Admin-6.png)

**Cloned message.**

<p>This is the message you get after editing the customer profile.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Customer profiles</li>
    <li class="last">Cloned message.</li>
</ul>

## Extending the Default Customer Profile

The only profile type the Customer module defines is a billing information profile. This profile has the default address field and nothing more, but it may be extended to include other fields pertinent to payment (like VAT numbers for B2B sales) by modules or the manage fields UI for that type.