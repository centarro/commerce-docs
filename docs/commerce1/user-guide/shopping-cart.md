---
title: Shopping Cart
taxonomy:
    category: docs
---

Most basic eCommerce stores use a "cart" to collect items/services and a "checkout" to collect payment and customer information. At the heart of Drupal Commerce is the ubiquitous Order. This Order is first created when a user adds a product to a cart. The Order is actually the "cart" when it has a status of "Shopping Cart." Since Drupal Commerce is an open eCommerce framework, these statuses can be added with additional modules (like shipping or customer profiles). 

Below are some topics related to the add to cart form.

<ul>
    <li><a href="#add-to-cart-form">Add to Cart Form</a></li>	
    <li><a href="#anonymous-carts-and-logged-in-users">Anonymous Carts and Logged In Users</a></li>
    <li><a href="#cart-refresh">Cart Refresh</a></li>
    <li><a href="#modifying-the-shopping-cart-using-views">Modifying the Shopping Cart using Views</a></li>
    <li><a href="#shopping-cart-and-multi-currency">Shopping Cart and Multi-Currency</a></li>
    <li><a href="#shopping-carts-orders-and-line-items">Shopping Carts, Orders, and Line Items</a></li>
    <li><a href="#working-with-cart-rules-events">Working with Cart Rules events</a></li>
</ul>

## Add to Cart Form

![Where do you configure the Add-to-Cart button in Drupal Commerce?](../images/Add-to-Cart-Drupal-Commerce-1.png)

<p>Almost everything in Drupal Commerce is put together using a common Drupal
pattern: Content (<a href="http://drupal.org/node/1261744">entity and fields
system</a>), <a href="http://dev.nodeone.se/en/taming-the-beast-learn-views-with-nodeone">Views</a>
(listing things), and <a href="http://dev.nodeone.se/node/984">Rules</a> (taking
actions on events). The Add-to-Cart button and form are no different. We use the
fields and entities to format product references into what is known as the "Add
to Cart Form." There are really two topics we are discussing here.</p>

<ul>
    <li><strong>Add-to-Cart Button</strong> - The button that literally creates
    an order with a status of "Shopping Cart." (Sidenote&mdash;Commerce
    Guys has a blog post on <a
    href="http://commerceguys.com/blog/creating-orders-drupal-commerce-api">creating
    an order using PHP and the API</a>)</li>
    <li><strong>Add-to-Cart Form</strong> - The form that is displayed along
    with the Add-to-Cart button. This is the area that gets ajaxified when you
    select different product attributes (like color or size of shirt) and
    updates the image and price.</li>
</ul>

<h3>Button & Form Configuration using Fields</h3>

<p>Since Drupal Commerce 1.2, we've enhanced the Add-to-Cart magic with
dependable configuration settings. By altering forms in the Field UI, we now
embed options to help you create complex Add-to-Cart forms. Read on to learn how
to access, alter, and create Add-to-Cart buttons.</p>

<ol class="inpagenav">
    <li><a href="#product-reference-field">Product Reference Field</a> -
    Configuring the Add-to-Cart button that shows up in a display node.</li>
    <li><a href="#add-to-cart-examples">Add-to-Cart Examples</a> - Helping you
    understand the power of being able to configure these buttons on a product
    entity level.</li>
    <li><a href="#line-item-field">Line Item Field</a> - An Add-to-Cart
    button that is configured on the Line Item level.</li>
</ol>

<h4 id="product-reference-field">Product Reference Fields</h4>

![Product Reference Field Configuration Screen](../images/Add-to-Cart-Drupal-Commerce-Product-Reference-Field.png)

**Product Reference Screenshot**

When you add a <a href="../products/Prod-Disp-Node.html">product reference field to an entity</a>, you have the option of injecting fields from the referenced products into the display of the referencing entity.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Content Types</li>
    <li>Manage Product Display</li>
    <li>Fields</li>
    <li class="last">field_product</li>
</ul>

<p>Any field attached to a product may be rendered into the display of an entity
(i.e. node). This feature must be enabled in the product reference field's
settings form. We call this <em>field injection</em>, because the field is
attached to the product itself but can be displayed in the referencing entities
to make this extra product data visible to customers.</p>

![Product Reference Field Configuration Screen](../images/Add-to-Cart-Drupal-Commerce-Product-Display-Formatter.png)

**Product Display Formatter**

Because these are fields attached to the product itself, you must update the field display settings on the product type to change the display formatter and/or the field's global visibility.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Products</li>
    <li>Product Types</li>
    <li class="last">Manage Display</li>
</ul>

<p>Then in the field display settings for
each entity type that contains a product reference field, you will see all
available product reference fields to reorder them relative to other fields on
the entity or hide them.</p>
<p>A great example of a product reference field is the one found in the Product
display node type that Commerce Kickstart creates. The product reference field
enables prices, images, and other fields attached to your products to be
displayed in the node along with an Add-to-Cart button.</p>

<h4 id="add-to-cart-examples">Attribute Fields & Add-to-Cart Form Examples</h4>

![Changing an attribute
    should update the price and picture.](../images/Add-to-Cart-form-goal.png)

<p>The most obvious example is the basic price field attached to products. In
most cases, you will need the price displayed along with the product description
and Add to Cart form.</p>

![Custom Field Configuration](../images/Add-to-Cart-Custom-Field.png)

**Custom Field**

If you need to create a dropdown that will change the price, you can easily accomplish this by creating a custom field for your product entity and making sure to click the "Attribute Field Settings" on the field form.
Be sure not to add this field to your Product Display content type, you want to add it to the Product Type.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Content Types</li>
    <li>Product Display</li>
    <li class="last">Manage fields</li>
</ul>

<p>Another use case is the product image field created during the Commerce
Kickstart installation. This is especially the case for groups of products where
each variation contains a unique image (e.g. for a different color of the
product, such as a t-shirt color or t-shirt size). You will want this image
rendered into the node display that groups these products together.</p>

![Add to Cart Manage Display Form](../images/Add-to-Cart-Form-Manage-Display.png)

**Manage Display Form**

How does it know that I want my sizes to show up in the drop down? The Product Display node is setup to aggregate the various attribute fields into one "Add to Cart form" as pictured here.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Products</li>
    <li class="last">Product Types</li>
</ul>

<p>When the customer updates the Add to Cart form for a group of products to
select a different product from the group, the price, image, and other injected
fields will be updated to represent the newly selected product.</p>

<h5 id="line-item-field">Custom Line Items (AKA Customizable Products/Donations/Services)</h5>

<p>Occasionally you may want to customize an attribute of the product for each
order. For example, if you wanted someone to add their own name to your t-shirts
or add an engraving for an additional charge. We cover this thoroughly in the <a
href="../lineitems/LineItem-Customize.html">Customizable Line Items</a>
topic.</p>
<p>There is also a video that goes through this idea of customizing the
product.</p>

<iframe src="https://player.vimeo.com/video/31459435?byline=0&amp;portrait=0" width="640" height="480" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe> <p><a href="http://vimeo.com/31459435">Introduction to Line Items in Drupal Commerce</a> from <a href="https://vimeo.com/user5912539">Randy Fay</a> on <a href="http://vimeo.com">Vimeo</a>.</p>


## Anonymous Carts and Logged In Users

<p>There are many topics covered in this Drupal Commerce Anonymous Carts
article. The experience you want to give your customers can greatly depend on
how well you handle the Anonymous to Authenticated conversion process. Much of
this is automated, but there can be a few hang ups.</p>
<h2>Cart Conversion</h2>
<p>What happens when an anonymous customer finds a product and clicks "Add to
Cart?" An order is created for that customer's anonymous session. Like most
customers, they realize that logging in with their account can give them a
certain advantage during the checkout process. Perhaps they don't have to fill
out their customer profile information or perhaps they are a part of a "Free
Shipping" user role. What happens to their cart?</p>
<p>When anonymous users login to the site, if they have a shopping cart, that
order is moved to be a part of the authenticated session. Anonymous cart
conversion is that simple! To borrow from a certain hardware manufacturer, it
should just work. </p>

<h3>Hiding the Cart</h3>

<p>The methods described in this section show you how to hide the shopping carts
in various ways, but we do not describe how to keep users from purchasing
content if they are anonymous. That is a much more involved topic and possibly a
feature request for the <a
href="http://drupal.org/project/commerce_checkout_login">commerce_checkout_login
module</a>.</p>

<h4>Hide Shopping Cart Block from Anonymous</h4>

<p>In most cases, if you want the shopping cart to be hidden from anonymous
users, hiding the block is the easiest way to pull this off. Below are two
screenshots that will help you with this functionality. If you prefer the more
robust method of hiding the shopping cart using the Views interface, skip down
to that section below.</p>

![Shopping Cart Block](../images/Cart-Anon2Auth-Hide-Block.png)

**Shopping Cart Block**

Like all blocks in Drupal 7 with the core "<a href="http://drupal.org/documentation/modules/contextual">Contextual Links</a>" module turned on, you can simply hover over the shopping cart block and click "Configure block."
 
<ul class="screenshot_breadcrumbs">
    <li class="first">Commerce Kickstart</li>
    <li>Front Page</li>
    <li class="last">Hover over Shopping Cart</li>
</ul>

![Configure Visibility](../images/Cart-Anon2Auth-Hide-Block-2.png)

**Configure Visibility**

Once the block configure screen is up you can scroll down to the Visibility Settings in the Vertical Tabs, Click on "Roles" and select "authenticated user."

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Block</li>
    <li>Manage</li>
    <li>commerce_cart</li>
    <li class="last">Configure</li>
</ul>

<h4>Hide Shopping Cart from Anonymous using Views Permissions</h4>

<p>If you want to hide the /cart page, we have a very simple method that
requires editing a core View. Relax, that's why we use Views instead of our own
half-baked solutions. And think of it this way, if you edit your view and it
doesn't work out, you can always click "Revert" and everything will go back to
the fresh and shiny version that ships with Commerce.</p>

![Click edit to configure the shopping cart View](../images/Cart-Anon2Auth-Views-ClickEdit.png)

**Shopping Cart Config**

If you navigate to the Shopping cart page and hover over the table there, you will see in the upper right a link for "Edit view." Click that.

<ul class="screenshot_breadcrumbs">
    <li class="first">Commerce Kickstart</li>
    <li class="last">Navigate to /cart</li>
</ul>

![Click None under Access](../images/Cart-Anon2Auth-Views-ClickAccess.png)

**Access Controls**

When the view pops up, click on "Access: None"

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>commerce_cart_form</li>
    <li class="last">Click "Edit"</li>
</ul>

![Choose the authenticated role](../images/Cart-Anon2Auth-Views-AuthRole.png)

**Access Options**

First, it will ask you what kind of access. We recommend User Roles. Then it will ask you which roles should be able to access this view, you can choose "Authenticated" to hide the shopping cart from anonymous users.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>commerce_cart_form</li>
    <li class="last">Click "Edit"</li>
</ul>

<p>Be sure to click "Save" on the View and test your shopping cart for what an
anonymous user would experience.</p>

<h4>Hide Shopping Cart if nothing purchased</h4>

<p>Let's say you don't like the default "empty" shopping cart that appears in
the sidebar for Commerce Kickstart. There is a not-so-straightforward way to do this, so hang in there. The Shopping Cart Block that is enabled in the Commerce Kickstart project is actually setup in code. What we need to do is disable that block, create a new Views Block, and enable the Views block instead.</p>

![Shopping Cart Block](../images/Cart-Anon2Auth-Hide-Block.png)

**Shopping Cart Block**

Like all blocks in Drupal 7 with the core "<a href="http://drupal.org/documentation/modules/contextual">Contextual Links</a>" module turned on, you can simply hover over the shopping cart block and click "Configure block."

<ul class="screenshot_breadcrumbs">
    <li class="first">Commerce Kickstart</li>
    <li>Front Page</li>
    <li class="last">Hover over Shopping Cart</li>
</ul>


![Select None to remove the Shopping Cart from your theme.](../images/Cart-Anon2Auth-Block-Disable.png)

**Removing From Theme**

Select None to remove the Shopping Cart from your theme. We will
enable a new block in just a second.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>commerce_cart_form</li>
    <li class="last">Click "Edit"</li>
</ul>


![Click edit to configure the shopping cart View](../images/Cart-Anon2Auth-Views-ClickEdit.png)

**Shopping Cart Config**

If you navigate to the Shopping cart page and hover over the table there, you will see in the upper right a link for "Edit view." Click that.

<ul class="screenshot_breadcrumbs">
    <li class="first">Commerce Kickstart</li>
    <li class="last">Navigate to /cart</li>
</ul>

![Click AddBlock](../images/Cart-Anon2Auth-Views-AddBlock.png)

**Adding Views Block**

Instead of messing about with the View, we are simply going to create a block directly from Views. Don't forget to click "Save."

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>commerce_cart_form</li>
    <li class="last">Click "Edit"</li>
</ul>

![Click Configure to Enable your new block.](../images/Cart-Anon2Auth-Block-Enable.png)

**Enable new block**

Navigate to the Blocks administration screen and click configure next to "View: Shopping Cart Block." Enable it for any section on your site (sidebar-first to mimic Commerce Kickstart). And you're done!

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li class="last">Blocks</li>
</ul>

<h3>Carts Expiration</h3>

<p>Old carts will remain indefinitely until they are checked out or manually
removed by administrators. Old cart orders can contain valuable information that
you may not want to erase before extracting the data for use particularly as
marketing research or contact information for follow-up sales contacts.</p>
<p>This can cause some confusion given the following scenario:</p>

<ol>
    <li>Authenticated user creates a cart, but logs out.</li>
    <li>Anonymous user re-creates their cart a few days later, and logs in.</li>
    <li>After checkout, the user is presented with their most recent cart which
    was the one they created in step 1.</li>
    <li>Worst case scenario is that the user thinks their checkout didn't happen
    and they go through it all again.</li>
</ol>

<p>In this case, you may find some nicely handled features in a contributed
module that can expire carts for you:

<a href="http://drupal.org/project/commerce_cart_expiration">commerce_cart_expiration</a>.</p>

<p>And a screencast on how to implement:</p>

<iframe src="http://player.vimeo.com/video/40541403?portrait=0" width="640" height="360" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>

## Cart Refresh

<h3>Price Recalculation on Load</h3>

![Shopping Cart refreshes prices on every page load.](../images/Cart-Refresh-Price-Rules.png)

**Price Rules Screen**

Pictured here is the page that lists all of the price rules that calculate the sell price per user. This is where you can add discounts, taxes, and many other types of payment calculations.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li class="last">Product Pricing Rules</li>
</ul>

<p>There is a shopping cart refresh that happens when cart orders are loaded. This
refresh involves at least the recalculation of all product prices in the cart.
Other modules may hook into the process to perform additional refreshes as
necessary. This process ensures that the cart will always reflect the most up to
date pricing and availability for products a customer intends to purchase
regardless of how long they’ve been in the customer’s cart.</p>
<p>Even though there is a Shopping cart order state and status, the designation
of a cart order status is not dependent on these. In fact, any order status that
includes the cart Boolean set to TRUE can function as a cart order status,
triggering a refresh of all product prices on load. In core, the Checkout: Review order statuses also function as cart statuses,
because you typically require product prices to be up to date until the time the
customer submits payment.</p>
<p>Prior to purchase, product sell prices are calculated using rule
configurations. Pricing rules can be used for things like discounts, price
lists, currency conversions, and tax application depending on the Rules elements
defined by your enabled modules. To apply the enabled pricing rules on product
display, you must ensure the display formatter settings for your product price
fields are configured to display the Calculated sell price for the current
user.</p>

## Modifying the Shopping Cart using Views


<p>Drupal's strength is that the modules work together. Imagine a world where
you can build the most amazing content lister. It could give you as much or even
more control than raw SQL. Now, imagine a world where an ecommerce platform
completely worked with that amazing content lister. We each have our focus and
leave the "annoying" stuff to the other guy. The end result is everything we can
do is pluggable and has more use cases than we can possibly imagine.</p>
<p><strong>That world exists.</strong> The content lister is called <a
href="http://dev.nodeone.se/en/taming-the-beast-learn-views-with-nodeone">Views</a>
and here we document how you can use Views to do just about anything with your
shopping cart or shopping basket.</p>

<h3>Shopping Cart Block and Page</h3>

<p>You would be tempted to think that the shopping cart block and the cart page
are from the same view with different displays. After all, that is a very common
pattern in Drupal. However for Drupal Commerce 1.x, Views 3.x was very much in
beta and to avoid forgotten bugs, the decision was made to make both the block
and the page separate views that only have a master display. This means, if you
want to edit either the block or the page, know that you must edit both
individually.</p>

<h4>Editing the Shopping Cart Block</h4>

![Click edit to configure the shopping cart block View](../images/Cart-BlockandViews-HoverEditBlock.png)

**Shopping Cart Config**

Navigate to any page and make sure to have an item in your shopping cart block. You can then hover over the table there, you will see in the upper right a link for "Edit view."

<ul class="screenshot_breadcrumbs">
    <li class="first">Commerce Kickstart</li>
    <li>Add item to cart</li>
    <li class="last">Hover over shopping cart, click Edit</li>
</ul>

<h4>Editing the Shopping Cart Page</h4>

![Click edit to configure the shopping cart View](../images/Cart-Anon2Auth-Views-ClickEdit.png)

**Shopping Cart Config**

If you navigate to the Shopping cart page and hover over the table there, you will see in the upper right a link for "Edit view." Click that.

<ul class="screenshot_breadcrumbs">
    <li class="first">Commerce Kickstart</li>
    <li class="last">Navigate to /cart</li>
</ul>

<h4>What views looks like when you click edit.</h4>

![Drupal Commerce Shopping Cart Block Views](../images/Cart-BlockandViews-Overview.png)

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>commerce_cart_form</li>
    <li class="last">Click "Edit"</li>
</ul>
<ul class="screenshot_breadcrumbs">
    <li class="first">Or... Navigate to /cart</li>
    <li class="last">Hover over table and click "Edit"</li>
</ul>

<h3>Creating a Shopping Cart from Scratch</h3>

<p>"Sometimes the best way to learn is to rebuild it." I imagine Tony Stark
would have said that at some point. Let's play the engineer and see how the
Commerce Guys were able to build a shopping cart in Views.</p>

<h4>Create a View</h4>

![Create a View block based on Commerce Order with Display Table](../images/Cart-BlockandViews-CreateView.png)

**Create a View**

Simply navigate to Structure, Views, Create a new View. Make your screen look like the one to the left and then click "Continue and Edit."

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li class="last">Add a New View</li>
</ul>


![Add Commerce Order ID to Contextual Filters](../images/Cart-BlockandViews-Step1.png)

**Contextual Filters**

The first bit of magic includes add a contextual filter in the advanced column. Click add and then search for "Order ID" and add that filter.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>new_shopping_cart</li>
    <li class="last">Edit</li>
</ul>

![VERY IMPORTANT: Supply Default Value and choose current user's order ID](../images/Cart-BlockandViews-Step1.5.png)

**Contextual Filters Config**

This is very important. When setting up the contextual filter, be sure to provide default value and select "Current user's cart order ID."

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>new_shopping_cart</li>
    <li>Edit</li>
    <li class="last">Contextual Filters Edit</li>
</ul>

![Add a relationship to Reference Line Item](../images/Cart-BlockandViews-Step2.png)

**Relationship**

Add a relationship. Choose Referenced Line Item. Also select "require this relationship."

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>new_shopping_cart</li>
    <li>Edit</li>
    <li class="last">Add Relationship</li>
</ul>

![Add a field, search for title](../images/Cart-BlockandViews-Step3.png)

**Fields**

When you add a field, simply start with the title. You could also add a new relationship to the referenced product and add product details. For our example, we added a product photo.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>new_shopping_cart</li>
    <li>Edit</li>
    <li class="last">Add Fields</li>
</ul>

![Add a Footer called the Line Item Summary](../images/Cart-BlockandViews-Step4.png)

**Footer**

Views 3 has a feature called "Area Handlers" and this is what powers the footer. Add a footer and you will see a Line Item Summary near the top. Add that and configure to your liking. We chose to show the Total and Checkout links.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>new_shopping_cart</li>
    <li>Edit</li>
    <li class="last">Add Footer</li>
</ul>

![Add a filter to make sure you are only seeing products.](../images/Cart-BlockandViews-Step4.5.png)

**Filters**

Since line items can be anything from products, to shipping, to discounts, we want to make sure we are only showing products. Though there is a case where you might want to include other kinds of line items.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>new_shopping_cart</li>
    <li>Edit</li>
    <li class="last">Add Filter</li>
</ul>

![Custom Shopping Cart Preview.](../images/Cart-BlockandViews-Step5.png)

**Views Preview**

Note that we have one item in our shopping cart while making this view. If you didn't have anything the preview would show something different.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>new_shopping_cart</li>
    <li>Edit</li>
    <li class="last">Preview at the Bottom.</li>
</ul>

![Drag new block to a region of your choosing](../images/Cart-BlockandViews-Step6.png)

**Blocks**

Save and Exit out of Views. Go to Structure > Blocks and drag your new block to a region of your choosing. For this example, it's going to Sidebar-First.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Blocks</li>
    <li class="last">Drag Block to Region</li>
</ul>

![Default Shopping Cart vs. New Custom Shopping Cart](../images/Cart-BlockandViews-Step7.png)

**Final Result**

The final result is definitely custom. And hopefully this exercise has inspired you to work through and explore other kinds of shopping cart scenarios. For example, it would be possible to do what Amazon does and attach a view to this to show related products, etc.

## Shopping Cart and Multi-Currency

<p>Converting from one currency to another is possible through Rules. We
recommend using only core for this if you want the conversion rate to be input
as a static variable. For example, you simply want to say that 1 US dollar is
equal to .76 Euros, that would be a fine and relatively easy thing to produce
using rules.</p>
<p>Additionally with being able to handle simple conversions, Drupal Commerce
core will properly handle mixed currency orders by converting line items to the
site's default currency if present on the order or using the currency of the
first line item on the order if the default currency isn't represented.</p>
<p>For more complex situations, there is a great contributed module called
Commerce Multi-currency that we have highlighted in a recent Commerce Module
Tuesday Video (seen below).</p>

<iframe src="http://player.vimeo.com/video/38010721?portrait=0" width="640" height="360" frameborder="0" webkitAllowFullScreen mozallowfullscreen
allowFullScreen></iframe>

<h3>Multi-Currency Support in Core</h3>

<p>We have lots of use-cases for handling multiple currencies. As stated above,
Drupal Commerce supports a small subset of currency support.</p>

![Currency Settings Screen](../images/Cart-MultiCurrency-Overview.png)

**Currency Settings**

The currency settings screen under Store &gt; Configuration &gt; Currency is where you can enable additional currencies. If you are going to do the "Static Currency Exchange Rate" exercise below, you should enable a few currencies here.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li class="last">Currency Settings</li>
</ul>

<h3>Static Currency Exchange Rate</h3>

<p>Converting from one currency to another is possible through Rules. We
recommend using only core for this if you want the conversion rate to be input
as a static variable. For example, you simply want to say that 1 US dollar is
equal to .76 Euros, that would be a fine and relatively easy thing to produce
using rules. </p>
<p>Below we work through an entire exercise where we use Rules to create such a
scenario as mentioned above.</p>
![Let's create a static currency conversion for these products!](../images/Cart-MultiCurrency-Step1.png)

**Original Pricing**

The original pricing is shown here for three products. Our goal is to create a rule that affects the prices and changes the currency. Note that you need to enable a few other currencies to make this work.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li class="last">View Products</li>
</ul>

![First, Navigate to Product pricing rules](../images/Cart-MultiCurrency-Step2.png)

**Product Pricing Rules**

The first step is to click on Store and then "Configuration" and, finally, Product Pricing Rules. That is where the magic all happens when dealing with currency exchange.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li class="last">Product Pricing Rules</li>
</ul>

![Click on Add a Pricing Rule and Add event](../images/Cart-MultiCurrency-Step3.png)

**Pricing Rule**

We need to click on add a pricing rule. If this is your first time on this screen, maybe navigate around and look at how some of these other rules are setup. If this is your first time dealing with Rules, we highly recommend <a href="http://dev.nodeone.se/node/984">additional tutorials</a>.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Product Pricing Rules</li>
    <li class="last">Click Add a Price</li>
</ul>

![Name your new rules so that it makes sense to you.](../images/Cart-MultiCurrency-Step4.png)

**Name Rule**

After you have created a name, click "Add Condition."

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Product Pricing Rules</li>
    <li>Name Rule</li>
    <li class="last">Click Add Condition</li>
</ul>

![Write or find the following code: commerce-line-item:commerce-unit-price:currency-code](../images/Cart-MultiCurrency-Step5.png)

**Data Comparison**

After clicking "Add Condition…"

You will want to choose "Data Comparison" and then select "```commerce-line-item:commerce-unit-price:currency-code```" using the object navigator.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Product Pricing Rules</li>
    <li>Edit Rule</li>
    <li>Click Add Condition</li>
    <li class="last">Choose "Data Comparison"</li>
</ul>

![Choose the currency you want to convert. For our example, we are converting US $ to something else.](../images/Cart-MultiCurrency-Step6.png)

**Currency to Filter**

Note that we are creating a filter that will only allow US dollars into our actions. Without this filter, all line items would go through this rule.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Product Pricing Rules</li>
    <li>Click Add Condition</li>
    <li class="last">Choose Which Currency to Act On</li>
</ul>

![Select multiply to change the unit price.](../images/Cart-MultiCurrency-Step7.png)

**Convert Numbers**

Click "Add Action"
In order to actually create a currency conversion we need to do alittle math. This next step is where you add the currency exchange rate.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Product Pricing Rules</li>
    <li>Click Add Action</li>
    <li class="last">Select Multiply</li>
</ul>

![The current currency exchange is 0.76 EUR to 1 US Dollars. So we multiply by 0.76](../images/Cart-MultiCurrency-Step8.png)

**Doing the Exchange**

If you had 1 US dollar, how much would that equal in your other currency? It changes, but for our exercise we're going to assume a static number works for us. (Dynamic currency conversion is possible with <a href="http://drupal.org/project/commerce_multicurrency">Commerce Multicurrency</a>).

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Product Pricing Rules</li>
    <li>Click Add Action</li>
    <li>Select Multiply</li>
    <li class="last">Multiply by 0.76</li>
</ul>

![Create a new action using the convert to new currency rule.](../images/Cart-MultiCurrency-Step9.png)

**Convert Currency Symbol**

Click Add Action.
Select "Convert the unit price to a different currency" so that we can actually convert the currency from US dollars to a new currency. This exchange is only going to change the currency symbol, it will not actually tweak the numbers.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Product Pricing Rules</li>
    <li class="last">Click Add Action</li>
</ul>


![Select the final currency destination. For our example, it will be EUR.](../images/Cart-MultiCurrency-Step10.png)

**Final Currency**

We are simply selecting the final currency symbol. You can safely ignore the data selector.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Product Pricing Rules</li>
    <li>Click Add Action</li>
    <li class="last">Select Currency Symbol</li>
</ul>


![Final rule screen for Static Currency Exchange Rate.](../images/Cart-MultiCurrency-Step11.png)

**Final Screen**

The final screen for the rule. If yours doesn't look like this, go through the steps above carefully.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Product Pricing Rules</li>
    <li class="last">Review Custom Rule</li>
</ul>

![Notice how the cart and the product page show the converted price.](../images/Cart-MultiCurrency-Step12.png)

**Example Cart**

We have not modified any of the products prices, but we have successfully converted all of the currency to Euros and exchanged the prices. For example, Product Three is listed as $32.00 US dollars.

## Shopping Carts, Orders, and Line Items

![Drupal Commerce Shopping Cart](../images/Cart-Order-LineItems-Shopping-Cart.png)

<ul class="screenshot_breadcrumbs">
    <li class="first">Click "Add to Cart"</li>
    <li>Click "View Cart"</li>
    <li class="last">Arrive at /cart</li>
</ul>

<p>Quick! What's the difference between a shopping cart, an order, and a line
item? The answer? Not much. A shopping cart is just a kind of an order and an
order is just a revisioned group of line items. How about we try that again with
less Commerce-jargon?</p>

<ul>
    <li><strong>Shopping Cart</strong> &ndash; Also sometimes called a basket,
    it's the listing of products your customers wants to buy at checkout.</li>
    <li><strong>Order</strong> &ndash; An order is a list of products with a
    status. In our case, a shopping cart is simply an order with a status of
    "Shopping Cart."</li>
    <li><strong>Line Item</strong> &ndash; Every product on the order is
    referenced by a record that includes quantity and a reference to which order
    it belongs. This record is known as a line item. There are other kinds of
    line items (like taxes, or discounts) but those don't really play that much
    of a role in a shopping cart.</li>
</ul>

<h3>Shopping Cart Overview</h3>

![Order Status for Shopping Carts](../images/Cart-Order-LineItems-Order-Status.png)

**Order with Status**

A shopping cart in Drupal Commerce is simply an order with a particular cart order status. In fact, almost everything in Drupal Commerce revolves around changing the order status.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>View Orders</li>
    <li>Add / Edit Order</li>
    <li class="last">Order Status at Bottom</li>
</ul>

<p> As soon as a product is added to the cart, an order is created and
associated with the customer either via user ID if logged in or via an array in
the session if not (commerce_cart_orders).</p>
<p>The shopping cart is totally optional, meaning checkout is implemented in a
separate module and will still function properly for orders created by the
administrator. This allows for sites to do invoicing and payment collection
where users shouldn't be allowed to create and manage their own orders via a
cart.</p>

![Administrative View for Shopping Carts](../images/Cart-Order-LineItems-Admin.png)

**Administrative View**

When you view all of the open shopping carts, it is possible that one
customer has multiple open shopping carts.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>View Orders</li>
    <li class="last">Click "Shopping Carts" in the upper right.</li>
</ul>

<p>Each change to a shopping cart order is saved as a revision. A user can also
have more than one cart, though the default is to present the most recent
shopping cart to the user. hook_commerce_cart_order_id() can be used to introduce alternate logic to the cart load process if necessary.</p>
<p><strong>Note:</strong> As long as the order is still in the shopping cart state, its line
items will be re-validated on load against the latest product prices /
availability, etc.</p>
<h2>Modifying a Line Item in Cart Using Rules</h2>
<p>This is an advanced action that involves Rules and setting values, but we
thought it was worth documenting seeing as how we've gotten lots of issues and
requests regarding this kind of functionality. For example, lets say you can
only buy Product A if Product B is not in the cart. You can make Drupal Commerce
understand this using Rules. We describe the recommended rule below.</p>

![Clone the unset price rule to set up your own rule with your own logic](../images/Cart-Order-LineItems-UnsetLineItem.png)

**Unset Price Rule**

To remove an item from the cart using rules, you must unset the price of the line item during "Calculating the sell price of a product." Deleting a line item just involves leaving the price amount empty.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Produt Pricing Rules</li>
    <li class="last">Click "Clone"</li>
</ul>

<p>To remove an item from the cart using rules, you must unset the price of the
line item during "Calculating the sell price of a product." This is similar to
the rule "Unset the price of disabled products in the cart" that is included
with core. So you can refer to that as an example. Deleting a line item just
involves setting the price to NULL (i.e. no value, not 0).</p>
<p>Thanks to ressa over at Drupal.org, we know we need to further clarify
our advice: "For those trying to remove a line item by entering an empty field,
and setting the price to NULL, you do it under 'Add action' -> 'Data' -> 'Set a
data value'. Under Data Selector, enter
'commerce-line-item:commerce-unit-price:amount' ... and just leave the 'Value'
field empty, don't actually type "NULL" :-)"</p>


<h3>Related Assets</h3>

<p>Are you looking for how to modify the cart block? Checkout our entire article
on <a href="#modifying-the-shopping-cart-using-views">modifying the Shopping Cart block using views</a>.</p>

## Working with Cart Rules events

The Cart modules defines Rules events and related hooks that let you react:

<ul>
<li>Before adding a product to the cart</li>
<li>After adding a product to the cart</li>
<li>After removing a product from the cart</li>
</ul>

The events that are triggered after adding and removing products from the cart receive a line item parameter that contains the full product line item that is being added or removed from the cart.  If you want to update this line item after it is added to the cart, such as to restrict its quantity to a maximum value via a custom Rule, then you are also responsible for saving the shopping order after making your changes so the order total can recalculate with the updated line item data.

The following exported Rule demonstrates restricting the quantity of all purchases on a site to 1 and using the <em>Save entity</em> action to ensure the order is saved with the updated line item data. This is important because initially the order total may be recalculated for a quantity greater than you want to allow if the customer has used the Add to Cart form multiple times for a restricted product.

 ```php
 
  <?php
  { 
    "rules_restrict_quantity_1" : {
      "LABEL" : "Restrict quantity to 1",
      "PLUGIN" : "reaction rule",
      "REQUIRES" : [ "rules", "commerce_cart" ],
      "ON" : [ "commerce_cart_product_add" ],
      "IF" : [
      { "data_is" : {
          "data" : [ "commerce-line-item:quantity" ],
          "op" : "\u003e",
          "value" : "1"
          }
      }
      ],
      "DO" : [
      { "data_set" : { "data" : [ "commerce-line-item:quantity" ], "value" : "1" } },
      { "entity_save" : { "data" : [ "commerce-line-item:order" ] } }
      ]
      }
  }

 ```

!!! note "Note: the same workflow holds true for line item alterations based in modules implementing hook_commerce_cart_product_add()."
