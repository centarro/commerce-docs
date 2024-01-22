---
title: Adding a taxonomy field to a Product for Attributes
taxonomy:
    category: docs
---

<p>There is a great explanation of <a hreg="/user-guide/product-attributes-variations">Product Attributes</a> in the general User Guide. </p>

<p>The following will help you understand how to use taxonomy on Product Variations to get a simple drop down to show up on your Add to Cart pages.</p>

<p>Prior to continuing with this example, it assumed you have already created a vocabulary of taxonomy terms to be used for your products. For our example below, we will be using a taxonomy term list for size of tshirt.</p>

![If you haven't already done so, look at Adding a New Product over here: http://www.drupalcommerce.org/commerce-kickstart-2/adding-new-product](../../images/CK-Product-Variations-01.png)

**Create a Variation Type**

<p>There is already a guide describing <a href="/commerce-kickstart-2/adding-new-product">how to add a New Product variation in Commerce Kickstart 2</a>. The recommended route to take is to add a variation type and click "Save and add fields."</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store Settings</li>
    <li>Product Settings</li>
    <li>Variation Types</li>
    <li class="last">Add Variation Type</li>
</ul>

![Simply add a taxonomy field.](../../images/CK-Product-Variations-02.png)

**Add Taxonomy Field**

<p>If you are following along with this tutorial, you should be at the "Manage Fields" for your product variation type. If not, you can get there by click on "Variation Types" in the Products menu and selecting "Manage Fields" next to the Variation type you want to have a drop down.</p>
<p>Next you'll want to add a "Term reference" field. Our example term reference field only has a few options, so a select list here makes sense. This does not affect how the drop down will be listed on the product page.</p>


<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store Settings</li>
    <li>Product Settings</li>
    <li>Variation Types</li>
    <li class="last">Manage fields</li>
</ul>

![Simply configure a taxonomy field.](../../images/CK-Product-Variations-03.png)

**Configure Taxonomy Field**

<p>After choosing the appropriate vocabulary, you'll want to make sure you select the Attribute Field Settings option for "Enable this field to function as an attribute field on Add to Cart forms."</p>


<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store Settings</li>
    <li>Product Settings</li>
    <li>Variation Types</li>
    <li class="last">Manage fields</li>
</ul>

![Demonstrating the Taxonomy field.](../../images/CK-Product-Variations-04.png)

**Taxonomy Field Demo**

<p>When adding a new "tshirt" that we created in step 1, we now have a "tshirt size" taxonomy field that we can choose <strong>PER VARIATION</strong>. That is an important differentiation from the Product display, which includes the "Title" and the vertical tabs area.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Products</li>
    <li class="last">Add a Product</li>
</ul>

![The final page shows us a drop down that affects the price.](../../images/CK-Product-Variations-05.png)

**Final Product Demo**

<p>The final page shows us a drop down that affects the price.</p>