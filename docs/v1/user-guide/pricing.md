---
title: Pricing
taxonomy:
    category: docs
---

<ul>
    <li><a href="#product-pricing-rules-with-screencasts">Product Pricing Rules (with screencasts)</a></li>	
    <li><a href="#currency-conversion">Currency Conversion</a></li>
    <li><a href="#discounts-and-coupons">Discounts and Coupons</a></li>
    <li><a href="#price-components">Price Components</a></li>
    <li><a href="#rules-overview">Rules Overview</a></li>
    <li><a href="#sell-price-calculation">Sell Price Calculation</a></li>
</ul>

## Product Pricing Rules (with screencasts)

<p>Discounts in Drupal Commerce</p>

<h3>Overview</h3>

<ul><li>Create a discount using a rule</li>
<li>Create a weekend (date-based) sale discount using a rule</li>
<li>Create a tag-based weekend sale discount using a rule</li>
</ul>

<h4>Simple Discount</h4>

<p>An example of a simple discount: You want to take 10% off everything in your store until further notice.</p>

<ol>
<li>Go to Store -&gt; Configuration -&gt; Product Pricing Rules</li>
<li>Click "Add a pricing rule"</li>
<li>Give your rule a name</li>
<li>Under "Actions" click "Add Action"</li>
<li>Under "Commmerce Line Item" choose "Multiply the Unit Price by Some Amount"</li>
<li>The data selector should be "line_item"</li>
<li>The amount should be .9 (in other words, multiply the price by .9).</li>
<li>Now all products have 10% taken off of them.</li>
</ol>

<h3>Adding a Date Window to the Discount</h3>

<p>Now we need to take our simple discount and make it effective for just one period of time. We'll use the "Simple Discount" approach above, but now we'll make it only applicable during this upcoming weekend.</p>

<ol>
  <li>Create a discount as in the "Simple Discount" above. </li>
  <li>This time we'll add two conditions to the product pricing rule:
    <ul>
      <li>System Date is Greater than Midnight April 23</li>
      <li>System Date is Less than Midnight April 25.</li>
    </ul>
  </li>
</ol>

<p>That's it. To test in a Linux environment you can temporarily change the server date using</p>
<p><code>sudo date MMDDHHMM</code></p>
<p>for example</p>
<p><code>sudo date 04231237</code></p>
<p>Don't forget to change the time back. Changing it like this makes a big mess of things.<br><code>sudo ntpdate pool.ntp.org</code></p>

<iframe src="https://player.vimeo.com/video/22619046?title=0&amp;byline=0&amp;portrait=0" width="640" height="480" frameborder="0"></iframe><p><a href="https://vimeo.com/22619046">Drupal Commerce Date-Based Discounts</a> from <a href="https://vimeo.com/user5912539">Randy Fay</a> on <a href="https://vimeo.com">Vimeo</a>.</p>

<h3>Adding a Taxonomy Term To Drive Discounts</h3>

<p>Now we're going to go much farther. We'll add a taxonomy term to our product entity to determine which sale events it should be subject to, and <em>also</em> add a date range.</p>
<p>There is a rather major complexity in this one. Rules does not have the ability currently to follow references (like product references and term references) very well, so we have to jump through some hoops to get access to them. It is do-able, but a bit mind-bending. We hope to see <a href="https://drupal.org/node/1053850">the rules issue relating to this</a> land soon, but for now we have to use the tools we have.</p>

<ol>
  <li>Add a taxonomy vocabulary called "Sales Events"</li>
  <li>Add a term to the vocabulary called "April Weekend Madness Sale"</li>
  <li>Add a term reference field to the "Product" product type referencing "Sales Events"</li>
  <li>Set the "Sales Events" value in one or more products to "April Weekend Madness Sale". We're essentially marking them for this sale.</li>
  <li>Create a rules component that determines whether the weekend is here:
    <ul>
      <li>Administer -&gt; Configuration -&gt; Workflow -&gt; Rules -&gt; Components -&gt; Add new component.</li>
      <li>Component plugin is "Condition set (AND)"</li>
      <li>Name is "Weekend of April 23"</li>
      <li>Continue</li>
      <li>Add two conditions which do data comparison against system date to select the weekend timeframe just as we did in the earlier, simpler example.</li>
    </ul>
  </li>
  <li>Create another rules component that accepts a product as a variable which will determine whether the product is marked to be part of the sale. Note that the first three conditions we're adding here are just to force Rules to understand how to traverse the data. They seem quite complex at first, but it's actually just cookbook stuff.
    <ul>
      <li>Administer -&gt; Configuration -&gt; Workflow -&gt; Rules -&gt; Components -&gt; Add new component.</li>
      <li>Condition set (AND)</li>
      <li>Name: "Product is on sale"</li>
      <li>Variables: Data type "Entity - Product", Label "Product", Machine name "product"</li>
      <li>Add a condition</li>
      <li>Entity has field</li>
      <li>Data selector: product</li>
      <li>Field: field_sales_event</li>
      <li>Add another condition</li>
      <li>Data value is empty</li>
      <li>Data selector: product:field-sales-event</li>
      <li>Click negate.</li>
      <li>Add another condition</li>
      <li>Data value is empty</li>
      <li>Data selector: product:field-sales-event:tid</li>
      <li>Click negate.</li>
      <li>Add a final condition</li>
      <li>Data comparison</li>
      <li>Data Selector: product:field-sales-event:tid</li>
      <li>Value equals (the tid of the taxonomy term we created)</li>
    </ul>
  </li>
  <li><p>Now we'll create a product pricing rule at Store -&gt; Configuration -&gt; Product Pricing Rules.</p>
    <ul>
      <li>Add a pricing rule with the name "April weekend sale"</li>
      <li>Under actions, add an action to multiply the price by 0.8 (we'll give them an even bigger discount than we did last time!)</li>
      <li>Under conditions:</li>
      <li>Add condition "Component: April Weekend Sale", which will add the date-based conditions to our rule.</li>
      <li>Add condition "Entity has field" (This is just to bring the product into scope due to the Rules issue mentioned above.)
        <ul>
          <li>Data selector: line-item</li>
          <li>Field: commerce_product</li>
        </ul>
      </li>
      <li>Add condition "Component: Product is on sale."
        <ul>
          <li>Data selector: line-item:commerce-product</li>
        </ul>
      </li>
    </ul>
    <p>Now we have a product pricing rule that does two things. First it checks the conditions in the "Weekend of April 23" to see if the date is correct, and then it uses the "Product is on sale" component (to which we pass the line item's product) to determine whether the item is on this sale.</p>
    <p>We can change the server date to experiment, and should see that the products we marked now have their sale prices.</p>
  </li>
</ol>

<iframe src="https://player.vimeo.com/video/22625018?title=0&amp;byline=0&amp;portrait=0" width="640" height="480" frameborder="0"></iframe><p><a href="https://vimeo.com/22625018">Drupal Commerce Complex Pricing Rules</a> from <a href="https://vimeo.com/user5912539">Randy Fay</a> on <a href="https://vimeo.com">Vimeo</a>.</p>


## Currency Conversion

<p>Let's face it, the internet doesn't just sell to our neighbors down the street, it can literally connect you to the whole world. Last we checked, there are hundreds of currencies and thousands of countries. It's hard to address them all. We've provided the framework for simplified currency conversion in Drupal Commerce core. But we've also built a framework that has already enabled lots of solutions to popup on drupal.org as free currency conversion solutions.</p>
<p>In the next section we outline how one might convert currencies using only Drupal Commerce, but we admit plainly that this has a very limited use-case. The exercise is intended to teach you how to deal with the pricing rules that every major ecommerce shop is going to use. Directly below, we've listed a few other options you have available to meet your currency conversion requirements:</p>

* [Commerce Multicurrency](https://drupal.org/project/commerce_multicurrency)
* [Multicurrency](https://commerceguys.com/blog/commerce-module-tuesday-commerce-multicurrency)
* [Currency Info Hooks](../../developer-guide/core-architecture/#info-hooks)

<h3>Enable Multiple Currencies</h3>

<p>Before you can make a single rule or product into a different currency, you will need to enable a few of the currencies on the backend. Below we show you how to do that.</p>


![Enable Multiple Currencies](../images/Price-Convert-StoreConfig.png)

**Multiple Currencies**

 Just like if you were translating content, you have to enable multiple currencies before you can convert them. To get started, you must navigate to the Store Configuration screen and click Currency Settings.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li class="last">Configure</li>
</ul>

![Enable a couple of currencies for fun](../images/Price-Convert-Enable-Currency.png)

**Currency Settings**

Here on the currency settings page, all you can really do is enable currencies and set the default currency

<ul class="screenshot_breadcrumbs">
  <li class="first">Administration</li>
  <li>Store</li>
  <li>Configure</li>
  <li class="last">Currency Settings</li>
</ul>

![You can then select which currency you want for your product](../images/Price-Convert-EditProduct.png)

**Edit Product**

You can then select which currency you want for your product.

<ul class="screenshot_breadcrumbs">
  <li class="first">Administration</li>
  <li>Store</li>
  <li>Products</li>
  <li class="last">Edit Product</li>
</ul>

![Enable Currency. It's that simple](../images/Price-Convert-Final.png)

**Multiple Currencies**

When you've got currencies enabled you can do all sorts of interesting things with prices.


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

We need to click on add a pricing rule. If this is your first time on this screen, maybe navigate around and look at how some of these other rules are setup. If this is your first time dealing with Rules, we highly recommend <a href="https://www.drupal.org/node/1580776">additional tutorials</a>.

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

In order to actually create a currency conversion we need to do a
little math. This next step is where you add the currency exchange
rate.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Product Pricing Rules</li>
    <li>Click Add Action</li>
    <li class="last">Select Multiply</li>
</ul>

![The current currency exchange is 0.76 EUR to 1 US Dollars. So we multiply by 0.76](../images/Cart-MultiCurrency-Step8.png)
 
**Doing the Exchange**

If you had 1 US dollar, how much would that equal in your other currency? It changes, but for our exercise we're going to assume a static number works for us. (Dynamic currency conversion is possible with <a href="https://drupal.org/project/commerce_multicurrency">Commerce Multicurrency</a>).

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
Select "Convert the unit price to a different currency" so that we can actually convert the currency from US dollars to a new currency. This exchange is only going to change the currency symbol, it will not
actually tweak the numbers.

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

The final screen for the rule. If your screen doesn't look like this, go through the steps above carefully.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Product Pricing Rules</li>
    <li class="last">Review Custom Rule</li>
</ul>

![Notice how the cart and the product page show the converted price.](../images/Cart-MultiCurrency-Step12.png)

**Example Cart**

We have not modified any of the products prices, but we have successfully converted all of the currency to Euros and exchanged the prices. For example, Product Three is listed as $32 US dollars in the database, but is listed here as 7,60 Euros.

## Discounts and Coupons

<p>The core product pricing system is great for flat / percentage discounts of products on the site and for alternate price lists. Special offers, such as "Buy one get one free," are not supported in Drupal Commerce core. They depend on the creation and management of alternate line items (e.g. one line item for the paid product and another for the free product).</p>
<p>Drupal Commerce's sell price pre-calculation mechanism limits what types of data you can access in the conditions and actions of product pricing rules. Very few sites actually make use of this functionality, but the gist of it is your conditions cannot use data specific to the product (i.e. product type or SKU) and your actions can only use data specific to the product (i.e. not the day of the week or user roles).</p>
<p>Even with those limitations, it is still possible to create quite complex pricing scenarios. One Drupal Commerce site currently uses approximately 1500+ Rules!</p>
<p><strong>Note about Coupons.</strong> Using Drupal Commerce Core, it is very possible to allow users to add coupons via line item (when someone clicks an add to cart link) or via Checkout. To do the checkout method, you can follow the same principles as outlined in our "Simple Coupon" exercise, however you will need to add the field to your Order type via code and expose it on the checkout pane using <a href="https://drupal.org/project/commerce_fieldgroup_panes">Commerce Fieldgroup Panes</a>.</p>

<h3>Administrator's Special</h3>

![Administrator's Special](../images/Price-Calc-step10.png)

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Products</li>
    <li>Product Types</li>
    <li>Manage Display</li>
    <li class="last">Edit Price</li>
</ul>

<p>In the <a href="#price-components">Price Calculations article</a>, we went over how to create a conditional discount for a user role.</p>

<h3 id="simplecoupon">Simple Coupon Code per Line Item</h3>

<p>Add a coupon code textfield to the product line item type. Create a Rule that looks for this value and applies a discount based on the code entered.</p>

!["We're going to add a coupon field to the default Line Item Type.](../images/Price-Coupon-01.png)

**Store Configuration**
To start with, we will navigate to the Store Configuration Screen and click "Line Item Types." In order to add a discount to our line items, we need to add a field.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li class="last">Configuration</li>
</ul>



![Add field to Line Item.](../images/Price-Coupon-02.png)

**Line Item Types**

Add field to Line Item

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li class="last">Line Item Types</li>
</ul>

![We're adding a coupon field here.](../images/Price-Coupon-03.png)

**Add Field**

We're adding a text field in the manage fields screen for our custom line type. If you want to be able to create unique line item types, there is a great contributed module for that called: <a href="https://drupal.org/project/commerce_custom_product">Commerce Customizable Products</a>.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Line Item Types</li>
    <li class="last">Manage Fields</li>
</ul>

![Any additional field that you want to be configurable before clicking Add to Cart needs to be enabled here.](../images/Price-Coupon-04.png)

**Field Configuration**

This is the screen you will see after clicking "Continue" when you've "Added" a field. Make sure you configure the "Add to Cart" settings correctly, or you will not be able to edit this field before adding the product to cart.
Any additional field that you want to be configurable before clicking Add to Cart needs to be enabled here.
The idea is that the customer would add a coupon before checkout.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Line Item Types</li>
    <li>Manage Fields</li>
    <li class="last">Field Configuration</li>
</ul>

![This is what the Coupon Code field should look like on an add to cart form.](../images/Price-Coupon-05.png)

**Add to Cart Preview**

This is what the Coupon Code field should look like on an add to cart form.

<ul class="screenshot_breadcrumbs">
    <li class="first">Commerce Kickstart</li>
    <li>Add a Product to your cart</li>
    <li class="last">Add to Cart Preview</li>
</ul>

![Go ahead and add a product to your cart, then click View Cart and then click edit view as shown here.](../images/Price-Coupon-06.png)

**Edit View**

Go ahead and add a product to your cart, then click View Cart and then click edit view as shown here.

<ul class="screenshot_breadcrumbs">
    <li class="first">Commerce Kickstart</li>
    <li>Add a Product to your cart</li>
    <li>Click "View Cart"</li>
    <li class="last">Click "Edit View"</li>
</ul>
![Add our coupon field.](../images/Price-Coupon-07.png)

**Add Field**

In order to view our coupon on the shopping cart, we will add the field. Same could be done with the checkout views and/or Shopping Cart block.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>Edit Shopping Cart</li>
    <li class="last">Click "Add" next to Fields</li>
</ul>

![Add Components](../images/Price-Coupon-08.png)

![Our new cart.](../images/Price-Coupon-09.png)

![Adding a new Product Pricing Rule.](../images/Price-Coupon-10.png)

**Product Price Rule**

Adding a new Product Pricing Rule.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li class="last">Product Pricing Rules</li>
</ul>

![Make up a pricing rule.](../images/Price-Coupon-11.png)

**Add Price Rule**

Make up a pricing rule name on this form. Mostly showing this screen so people don't get too confused with all of this jumping around.


<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Product Pricing Rules</li>
    <li class="last">Add Price Rule</li>
</ul>

![This is where we will be spending the next few minutes. Hang in there, it's pretty easy.](../images/Price-Coupon-12.png)

**Editing Rule**

This is where we will be spending the next few minutes. The plan is to add two conditions, separated by an "And" and finally add an action that applies the discount.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Product Pricing Rules</li>
    <li class="last">Rule Overview</li>
</ul>

![First, add a condition for our new field.](../images/Price-Coupon-13.png)

**Add Condition**

First, add a condition for our new field called "Entity has field." This is a critical step to making that field available in our next condition. Do not skip this step.


<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Product Pricing Rules</li>
    <li>Edit Rule</li>
    <li class="last">Add Condition</li>
</ul>

![For this condition, we need the commerce-line-item data selector and our new field.](../images/Price-Coupon-14.png)

**Configure Condition**

For this condition, we need the commerce-line-item data selector and our new field.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Product Pricing Rules</li>
    <li>Edit Rule</li>
    <li class="last">Configure Condition</li>
</ul>

![Next, add and to require our final data comparison condition.](../images/Price-Coupon-15.png)

**Add And Condition**

Next, add and to require our final data comparison condition. This is required to force both conditions.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Product Pricing Rules</li>
    <li>Edit Rule</li>
    <li class="last">Add "And" Condition</li>
</ul>

![Now lets add data comparison condition.](../images/Price-Coupon-16.png)
**Add Data Comparison**

For our final condition, click "Add condition" and select "Data comparison."

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Product Pricing Rules</li>
    <li>Edit Rule</li>
    <li class="last">Add Data Compairson</li>
</ul>

![Find our new field.](../images/Price-Coupon-17.png)

**Configure Condition**

Find our new field, called something like "commerce-line-item:field-**."

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Product Pricing Rules</li>
    <li>Edit Rule</li>
    <li class="last">Configure Condition</li>
</ul>

![Adding the coupon code here.](../images/Price-Coupon-18.png)

**Configure Condition**

This is part of the magic. I'm setting my coupon code to be an arbitrary four digit number, but you could set it to just about anything that a person could type into their field. You could even potentially be a bit more creative here. This could be a role that a user has or perhaps a previously visited page.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Product Pricing Rules</li>
    <li>Edit Rule</li>
    <li class="last">Configure Condition</li>
</ul>

![Add discount action.](../images/Price-Coupon-19.png)

**Add Action**

Add discount action. For this action, we chose the "Multiply the unit price by some amount" underneath the "Commerce Line Item." Next screen is configuration settings.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Product Pricing Rules</li>
    <li>Edit Rule</li>
    <li class="last">Add Action</li>
</ul>

![Be sure to set multiply value and change component type to discount](../images/Price-Coupon-20.png)

**Configure Action**

Be sure to set multiply value and change component type to discount. There is a lot to learn about <a href="#price-components">Price Component Types</a> if you are interested.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Product Pricing Rules</li>
    <li>Edit Rule</li>
    <li class="last">Configure Action</li>
</ul>

![Final Shopping Cart using our new coupon rule](../images/Price-Coupon-21.png)
            
**Final Cart**
        
Final Shopping Cart using our new coupon rule.

<ul class="screenshot_breadcrumbs">
    <li class="first">Commerce Kickstart</li>
    <li>View /cart</li>
    <li class="last">Final Cart</li>
</ul>

## Price Components

<p>Price Components represent a change record of what happened during price calculation to give you the final price of a line item or order. When building your price calculation rules, you have the ability to choose what type of price component should be used to represent the price change on the order, which is why we provide a couple generic types (like discount / fee).</p>

<ol>
    <li>Price components will keep track of the changes or additions to a price during price calculation</li>
    <li>You can specify which component to use in a price calculation rule</li>
</ol>

<p>Ultimately customizing these further will result in the best customer experience, so instead of just "Discount" a user might have visual feedback that they've received their "Member discount" or "Wholesale discount." The ability to customize the price component label is possible in a custom module or there have been rumors of a contributed module that provides an interface for this to work.</p>

<p>The unit price of line items includes an array of price components that show the breakdown of
how a particular price was calculated. These components will be multiplied by line item quantity
into the line item's total price field and added together into an order's order total price field.
Thus component data at the order level will show all the price components that went into the
order total.</p>
<p>Price component types are defined by modules installed on the site using
hook_commerce_price_component_type_info(). Core price component types include:</p>

<ul>
    <li>Base price &mdash; typically the base price of a product prior to calculation</li>
    <li>Discount &mdash; a simple price component type useful for price reductions</li>
    <li>Fee &mdash; a simple price component type useful for price increases</li>
    <li>Tax rates &mdash; each tax rate gets its own component type so the total tax collected for an
order can be found in its order total price field</li>
</ul>

<h3>Enable Price Components for Display</h3>

<p>In order to show you what price components look like, we've picked up the Sell Price Calculations example towards the end. To see the whole exercise, <a href="#sell-price-calculation">check it out</a>.</p>

![Lets reveal the price components](../images/Price-Calc-step8.png)

**Reveal Price**

To see the discount on your product, you must go to the manage display.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Products</li>
    <li>Product Types</li>
    <li class="last">Manage Display</li>
</ul>

![Set the price field to show formatted with components.](../images/Price-Calc-step9.png)

**Price Field**

Set the price field to show formatted with components.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Products</li>
    <li>Product Types</li>
    <li>Manage Display</li>
    <li class="last">Edit Price</li>
</ul>

![Administrators see the discount](../images/Price-Calc-step10.png)

**Final Discount**

Simply changing the price to show with components, it displays all that is necessary for the discount to be obvious. Also in the screenshot is the same site, not logged in. This is an important step to make sure that your condition is actually working.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Products</li>
    <li>Product Types</li>
    <li>Manage Display</li>
    <li class="last">Edit Price</li>
</ul>

<h3>Enable Price Components via Rules</h3>

<p>In order to show you what price components using rules look like, we've picked up the Sell Price Calculations example in the very middle. To see the whole exercise, <a href="#sell-price-calculation">check it out</a>.</p>

![Set .5 for 50% off, and select Discount for the price component type.](../images/Price-Calc-step6.png)

**Configure Action**

When you are setting the actual math part of the discount, we chose to simply multiply by 0.5 for a 50% discount. You could also divide by 2. Note also that we have changed the value of the price component type. The price component type will show up when you show the price with components. Note that if you want to add your own price component type it will likely need to be done in code.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Product Pricing Rules</li>
    <li class="last">Configure Action</li>
</ul>

## Rules Overview

<p>What is it about the amazing flexibility of Drupal that is so addictive? It's like being a Chef and having all the best ingredients at your finger tips. Or perhaps it's like being a lego-nerd and having an unlimited supply of any brick or any set that was ever made. For free.</p>
<p>Drupal has Views for listing content, Rules for reacting, Flags for collecting, ctools for abstracting, and just about anything else you can imagine built on top of those. But what we want to focus on is Rules and how they affect Pricing in Drupal Commerce.</p>

![Product Price Calculations happen under store configuration product pricing rules](../images/Price-Calc-Overview.png)

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configure</li>
    <li class="last">Product Pricing Rules</li>
</ul>

<p>Rules makes it possible to be an e-commerce site builder or even store manager and be able to dream up complex and cool discounts, sales, and many other things simply using Drupal Commerce Pricing Rules.</p>

<h3>Drupal Commerce Documentation Examples using Rules</h3>

<p>There are a number of topics we handle in the Drupal Commerce documentation that include some or all of the Rule interface to show off a certain aspect. Below we've listed a small accounting of the articles that are written to take advantage and teach you more about the Rules interface.</p>

* [Administrator's Special (50% Discount)](../shopping-cart)
* [Flat Rate Currency Conversion](../shopping-cart/#shopping-cart-and-multi-currency)

<p>There are also a lot of videos regarding Rules and Drupal Commerce that the Commerce Guys have put out there.</p>

<ul>
<li><a href="https://vimeo.com/22625018">Drupal Commerce Complex Pricing Rules</a></li>
<li><a href="https://vimeo.com/35719774">Removing Items From the Cart Using Rules</a></li>
<li><a href="https://vimeo.com/32816741">Shipping Discount for Item in Cart using Calculation Rules</a></li>
<li><a href="https://vimeo.com/26884701">The Power of Rules with Views Bulk Operations (and Commerce)</a></li>
<li><a href="https://vimeo.com/26881784">Commerce, Rules, and Stock: Demonstrating the Power of Rules</a></li>
<li><a href="https://vimeo.com/22625018">Drupal Commerce Complex Pricing Rules</a></li>
</ul>

<h3>Third-Party Tutorials about Rules</h3>

<p>You know something can be pretty complicated (but totally worth the effort) when the whole community comes together to help spread the word on how to work with it. We have below a number of resources that should help you learn Rules better (the core of Drupal Commerce Pricing).</p>

<ul>
  <li><a href="https://drupalize.me/series/coding-rules">Drupalize.me "Coding for Rules"</a></li>
  <li><a href="https://drupal.org/files/tiny-book-of-rules.pdf">The Tiny Book of Rules</a> - a condensed introduction to Rules.</li>
  <li><a href="https://drupal.org/node/1580776">Chapter 12</a> - the online version of <strong>Drupal 7 – The Essentials</strong></li>
</ul>

<h3>Commerce Rules Contributions</h3>

<p>Rules is a very extensible platform. There are a number of user contributed modules that can add additional functionality if needed.</p>

<ul>
<li><a href="https://drupal.org/project/commerce_conditions">Commerce Extra Rules Conditions</a> - lots of extra conditions in this one.</li>
<li><a href="https://drupal.org/project/commerce_cart_expiration">Commerce Cart Expiration</a> - manipulate your cart using rules</li>
<li><a href="https://drupal.org/project/commerce_dispatch">Commerce Dispatch</a> - shipping actions</li>
<li><a href="https://drupal.org/project/commerce_price_components">Commerce Price Components</a> - use price components in conditions</li>
</ul>


## Sell Price Calculation

![Product Price Calculations happen under store configuration product pricing rules](../images/Price-Calc-Overview.png)
<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configure</li>
    <li class="last">Product Pricing Rules</li>
</ul>

<p>For the novice, or perhaps a web developer who is only occasionally asked to create an e-commerce website, it might come as a surprise that the sale or sell price goes through a fair amount of tweaking before being represented on the site. Each step that our sell price goes through is designed to make discounts, taxes, currency conversion and many other possibilities have an impact.</p>
<p>Product sell prices are determined via a Rules based calculation process. If you are not up on your Rules module implementation tasks, you should check out the <a href="https://www.drupal.org/node/1580776">Rules tutorial</a> to get up to speed.</p>

<p><strong>The life of a Price Calculation</strong></p>

<ol>
    <li>A new line item is created representing the product as if it were in the user's current shopping cart order.</li>
    <li>The unit price of the line item is initialized to the product’s base price (commerce_price) value.</li>
    <li>The line item is then passed through Rules via the Calculating the sell price of a product event where its unit price may be manipulated as necessary.</li>
    <li>The final unit price of the line item becomes the sell price of the product displayed on product pages and Views.</li>
</ol>

<p>See a <a href="https://bit.ly/cgprezi-price-calculation">Prezi slideshow visualizing the process</a>.</p>
<p><a title="Sell Price Calculation" href="https://prezi.com/e7mfg8u5qldv/sell-price-calculation/">Sell Price Calculation</a> on <a href="https://prezi.com">Prezi</a></p>

<p>Sell price calculations can include discounts, taxes, currency conversion, and more. Each manipulation of the price is tracked as a price component in the price field’s data array, so you can see exactly what happened to result in a particular sell price at the end of the process. You can even set the Display of any price field to show all components. This is handy for showing a user that you are giving them a discount.</p>
<p>The actions for manipulating unit prices include:</p>

<ul>
    <li>Add an amount to the unit price</li>
    <li>Convert the unit price to a different currency</li>
    <li>Divide the unit price by some amount</li>
    <li>Multiply the unit price by some amount</li>
    <li>Set the unit price to a specific amount</li>
    <li>Set the unit price's currency code</li>
    <li>Subtract an amount from the unit price</li>
</ul>

<p>When configuring each action, you can specify the type of price component to use. If you need additional component types for the site (more than addition/subtract, divide/multiply, etc), you currently have to write them into a module. Not sure how to create your own price component? Look into <a href="https://drupalize.me/series/coding-rules">Drupalize.me's Coding for Rules videos</a>; they are a free and well-produced series of videos!</p>
<h2 id="adminspecial">Administrator's Special</h2>
<p>We are going to learn the sell price calculation process by setting up a conditional discount for our administrators. We will use a condition to apply a 50% discount for any user with the role "Administrator" and show the price with components.</p>
<p>Our base price: $30</p>
<p>What the sell price should be on checkout with 50% discount if I'm an administrator: $15</p>

![Create a product pricing rule](../images/Price-Calc-step1.png)

**Pricing Rule**

Create a Product pricing rule.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Product Pricing Rules</li>
    <li class="last">Add a Pricing Rule</li>
</ul>

![Calculating sale price event should be selected by default. We're going to add a condition for only affecting prices if users are administrators. We're going to reduce the price the by 50%.](../images/Price-Calc-step2.png)
        
**Rule Overview**

Calculating sale price event should be selected by default. We're going to add a condition for only affecting prices if users are administrators. We're going to reduce the price the by 50%.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Product Pricing Rules</li>
    <li class="last">Overview</li>
</ul>

![Select the user has roles condition](../images/Price-Calc-step3.png)

**Add Condition**

When creating a condition, it will ask you what you want to look at. For our discount, we want to look at user's roles.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Product Pricing Rules</li>
    <li class="last">Add Condition</li>
</ul>

![Select the administrator role.](../images/Price-Calc-step4.png)

**Administrator**

Selecting the appropriate role is all you have to do for this screen.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Product Pricing Rules</li>
    <li class="last">Configure Condition</li>
</ul>

![Next, we've already clicked on Add Action and are now selecting the multiply unit price option.](../images/Price-Calc-step5.png)

**Add Action**

Next, we've already clicked on Add Action and are now selecting the multiply unit price option.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Product Pricing Rules</li>
    <li class="last">Add Action</li>
</ul>

![Set .5 for 50% off, and select Discount for the price component type.](../images/Price-Calc-step6.png)

**Configure Action**

<p>When you are setting the actual math part of the discount, we chose to simply multiply by 0.5 for a 50% discount. You could also divide by 2. Note also that we have changed the value of the price component type. The price component type will show up when you show the price with components. Note that if you want to add your own price component type it will likely need to be done in code.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Product Pricing Rules</li>
    <li class="last">Configure Action</li>
</ul>

![Final Rule Screen Overview](../images/Price-Calc-step7.png)

**Rule Overview**

The final screen for the rule that will set all prices to be 50% for administrators.
<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Configuration</li>
    <li>Product Pricing Rules</li>
    <li class="last">Rule Overview</li>
</ul>


![Lets reveal the price components](../images/Price-Calc-step8.png)

**Reveal Price**

We could show you a screenshot of the new product, but that would not show us that the rule is really working. To see the discount on your product, you must go to the manage display.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Products</li>
    <li>Product Types</li>
    <li class="last">Manage Display</li>
</ul>

![Set the price field to show formatted with components.](../images/Price-Calc-step9.png)

**Price Field**

Set the price field to show formatted with components.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Products</li>
    <li>Product Types</li>
    <li>Manage Display</li>
    <li class="last">Edit Price</li>
</ul>


![Administrators see the discount](../images/Price-Calc-step10.png)

**Final Discount**

Simply changing the price to show with components, it displays all that is necessary for the discount to be obvious. Also in the screenshot is the same site, not logged in. This is an important step to make sure that your condition is actually working.

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Products</li>
    <li>Product Types</li>
    <li>Manage Display</li>
    <li class="last">Edit Price</li>
</ul>