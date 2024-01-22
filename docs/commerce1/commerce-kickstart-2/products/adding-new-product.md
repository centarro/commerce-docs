---
title: Adding a New Product
taxonomy:
    category: docs
---

<p>There are two topics at play here. </p>
<ol>
  <li>First, we have the simple "How do I add another product to my site?" question that is easy to answer (see below). <a href="#howaddproducts">Read More</a>.</li>
  <li>Second, we have the much more common "How do I add a new type of product variation?" This is typically asked at the beginning of creating a site. And we have worked hard in Commerce Kickstart 2 to make the interface for creating your first Product Variation Type very simple. <a href="#">Read More</a>.</li>
</ol>

<h3 id="howaddproducts">How do I add another product to my site?</h3>

![Mouseover the word Products in the menu and select Add a Product.](../../images/CK-Product-New-01_1.png)

**Add a Product**

<p>Mouseover the word "Products" in the menu and select "Add a Product." That will give  you a nice listing of the available Products that you can add to your site. If you want to create a new kind of product, definitely checkout the next section, "How do I add a new type of product variation?"</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Products</li>
    <li>Actions</li>
    <li class="last">Add a Product</li>
</ul>

![The Product Creation Form has three sections: Title/Description, Product Variations, and Catalog Management.](../../images/CK-Product-New-02.png)

**Product Form**

<p>The Product Creation Form has three sections: Title/Description, Product Variations, and Catalog Management. The Title/Description area is the Product Display and should be filled out in a way that describes the entire product, not a specific variation. For products that have a price and other attributes, you can create variations. This could one product variation or many. Finally, at the end we have our Catalog Options. These are really just a set of taxonomy vocabularies that we use to create Facets and organize our Catalog on the Demo store.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Products</li>
    <li>Actions</li>
    <li class="last">Add a Product</li>
</ul>

<h3>How do I add a new type of product variation?</h3>

![Mouseover the word Products in the menu and select Variation types.](../../images/CK-Product-New-03.png)

**Navigate**

<p>Mouseover the word "Products" in the menu and select "Variation types."</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store Settings</li>
    <li>Product Settings</li>
    <li class="last">Variation Types</li>
</ul>

![First, you should read the disclaimer at the top of the page. Second, go ahead and click on the button.](../../images/CK-Product-New-04.png)

**Click Add Product Variation**

<p>First, you should read the disclaimer at the top of the page. Second, go ahead and click on the button titled "Add product variation type."</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store Settings</li>
    <li>Product Settings</li>
    <li class="last">Variation Types</li>
</ul>

![Just like any entity type creation form (think Content Types) you can name the type of product variation.](../../images/CK-Product-New-05.png)

**Variation Type Form**
        
<p>Just like any entity type creation form (think Content Types) you can name the type of product variation. Additionally, there are three checkboxes here that will save you gobs of time: Revisions, Automatic SKU, and Create matching product display type. That third option will save you an amazing amount of time that you can quickly get up and running.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store Settings</li>
    <li>Product Settings</li>
    <li class="last">Variation Types</li>
</ul>

<p>Now, we will stop the screenshots here for a second to pause and think on what we have created here. When you click "Save product variation type" there will be a content type created as well named the exact same thing with a product reference field added to it and the inline entity reference settings set up for you.</p>

<p>But you now have two things: A Product Variation Type and a Content type that displays Products of the new variation. Put another way, you now have your Product Display and Product Variation set up for you. The trick is now determining whether you want your fields to be on the Product Variations (things that change the price) or if  you want to add fields to the Product Display (things that singularly describe the group of products for each URL).</p> 

<p>So this means you have a set of fields for both the Product Display (the node that keeps track of product variations and gives us a viewable path, among other things) and the Product Variation (the actual product entity that can have more than one added to each display).</p>

## Choosing between Page or Variation

![Drupal Commerce Entity Graph](../../images/CK-Entity-or-Node.jpg)

**Page or Variation?**

<p>This illustration explains the differences between product pages (nodes) and product variations (products).</p>

<p>There are lots of questions you might be asking yourself about how to put your store together. This article will be tackling the question of choosing whether you should put a field on your Product Data or your Product Node. The difference is simple, products are more there for inventory (sizes and colors of one kind of shirt) and product nodes are more there for display (here's all the Commerce Guys t-shirts).</p>
<p>This question comes in lots of forms:</p>

<ul>
<li>Should I add the picture to the product page (node) or the product variation?</li>
<li>Should I add the product category to the node or the product variation?</li>
<li>Why is there a difference between products and nodes?</li>
<li>etc.</li>
</ul>

<p>The fact is that there are two different places you can add categories and pictures:</p>
<p>1. Nodes or "Product Pages" - Product pages are great for images and/or categories that represent all of that one kind of product. For example, if we had an online shoe website, we would add an "athletic" category to the Nike Jordan product page, but we wouldn't want to add a "size" or "color" to the product page, because those belong in Product variations.</p>
<p>2. Product Variations - These are the specific product listings that include price and "variation categories" like size, color, weight, etc.</p>
<p>In the graphic above we have created a visual of the difference between product pages and product variations. Typically, Product variations include the photo, but if a product's photo won't change based on size or color, then it makes more sense to add that directly to a page.</p>
<p>The whole point of deciding between a product page and product variation is that every field on a product variation will be loaded in javascript every time a user selects a small variation. So product variations that have photos will be replaced if the user changes the size or the color. </p>

## Product Attributes & Variations

<p>Product attributes are the descriptors we use to define kinds of products. For example, we could describe a tshirt by the color and size. These attributes mean that in the real physical world your store may only carry one red shirt, but you have three sizes or three "variations." Commerce software must deal with product variations in a flexible way. Here's how Drupal Commerce abstracts it:</p>
<ol>
    <li><strong>Product Types.</strong> A product type is a specific bundle based on a custom product entity. Each bundle can have fields attached to it, including pictures and other kinds of information.</li>
    <li><strong>Product "Informational" Fields.</strong> Any field on a product can be a simple "informational" field. Typical informational fields include an image of that specific configuration of a product. For example, if you sell a tshirt with a cool print in multiple colors, each product with a different color could have an image field that, when displayed on the product display node, will change when you select different attributes (like color).</li>
    <li><strong>Product "Attribute" Fields.</strong> Any field on a product type with a defined list of options (list of text options, list of taxonomy terms, list of colors). The attribute fields are special because of the way they turn into selection widgets on a product display. Often you will pair an attribute field (like a color dropdown) with an information field (like an image field) to let the user "select" the blue tshirt and the Drupal Commerce system will quickly load the picture associated with the blue product.</li>
    <li><strong>Product Displays.</strong> If needed at all, these are groupings of products. You can reference any type and number of products on a product display. In order to make use of product displays, we recommend only showing one type of product per product display. The product display is where you typically see the product with drop downs to "configure" or "order" the right kind of product.</li>
</ol>

<h3>Example attribute configuration</h3>

<p>Lets walk through setting up an attribute field on a product type and see how it interacts with the product fields injected on the product display. For this example, we are using Commerce Kickstart 1.x</p>

![Manage your Product Fields](../../images/Prod-Attr-Step1.png)

**Product Types**

<p>Navigate to your Store page and click on "Products" and then "Product
Types" and then "Manage Fields." This is the standard way to find,
create, and edit different types of product. You can add as many types
of products as you want, but make sure you understand the difference
between product types and product displays.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Products</li>
    <li class="last">Product Types</li>
</ul>

![Add a List (text) field and use select list.](../../images/Prod-Attr-Step2.png)

**Add Product Field*
<p>When you navigate to the manage field screen, if you are a Drupal
native, you'll notice that the field screen is just like a manage field
screen for content types. We'll need a List (text) using a select list.
This could also be a taxonomy term reference, or any kind of simple list
field.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Products</li>
    <li>Product Type</li>
    <li class="last">Manage Fields</li>
</ul>

![Add you field choices. This is editable later :)](../../images/Prod-Attr-Step3.png)

**Add Field Choices**

<p>This can be any simple list, one for each new line. Let your imagination run wild!</p>
<p>Click "Save."</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Products</li>
    <li>Product Type</li>
    <li>Manage Fields</li>
    <li class="last">Add Field</li>
</ul>

![After clicking save, you will want to enabled the attribute field settings.](../../images/Prod-Attr-Step4.png)

**Enable Attribute Field**

<p>This is the most important step as it is what turns a normal field
into an attribute field that can change the Add to Cart form on
update.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Products</li>
    <li>Product Type</li>
    <li>Manage Fields</li>
    <li class="last">Configure Field</li>
</ul>

![Let's modify the three products with a unique image and color option.](../../images/Prod-Attr-Step5.png">

**Modify Products**

<p>Here is the edit screen of PROD-01 that comes with the standard
Commerce Kickstart 1.x. Notice we have a new "Color" field that you have
just added to the Product Type.</p>
<p>Repeat this for all three products. Add a picture and choose a
color.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Store</li>
    <li>Products</li>
    <li class="last">Edit PROD-01</li>
</ul>

![Now lets create a new Product Display for our three new colors.](../../images/Prod-Attr-Step6.png)

**Add Product Display**

<p>Navigate to the Content List screen and click "Add Content." This
will then list all of the content types on your site. Our Product
Display content type is the third one after a Commerce Kickstart
install. Select it to create a new Product Display to attach our three
products on.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Content</li>
    <li class="last">Add Content</li>
</ul>

![Attach the updated  products to the new product display.](../../images/Prod-Attr-Step7.png)

**Attach Products**

<p>This is the Product Reference Field that is included on Commerce
Kickstart's Product Display Content Type. Any content type that has this
kind of field is able to connect products and display an "Add to Cart"
button. </p>
<p>This field will take the three products that we have updated with our
new information and create an interactive drop down list of our color
field options.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Content</li>
    <li>Add Content</li>
    <li class="last">Add Product Display</li>
</ul>


![The color drop down now updates the price and picture.](../../images/Prod-Attr-Step8.png)

**Final Result**

<p>The color drop down now updates the price and picture.</p>

## Product Displays

<p>Think of nodes as a way of grouping like products together. The most obvious grouping is one product that has variations in size, color, etc. In Drupal Commerce every variation is a product and to group them properly, we use Product Displays.</p>
<p>"Product Display" is the default content type that Commerce Kickstart 1.x creates and it has a product reference field. Technically, any entity that has a product reference field could be considered a product display. Nodes are the obvious product display entity type, and they're privileged because they're the default and standard entity type to use for the front-end display of content. But you could use taxonomy vocabularies, or even users as product displays.</p>

![Drupal Commerce Entity Graph](../../images/CK-Entity-or-Node.jpg)

**Page or Variation?**

<p>This illustration explains the differences between product pages (nodes) and product variations (products).</p>
<h2>Advantages of Separating Product & Product Display</h2>
<p>A product in Drupal Commerce can represent one of several things:</p>
<ul>
  <li>A single item for sale on the site</li>
  <li>A variation in a group of items for sale on the site (e.g. one size of a t-shirt)</li>
  <li>A non-tangible product reserved through the site (e.g. event registration)</li>
  <li>An item that is not purchased but which represents the purpose of payment for a particular order (e.g. donation account or campaign)</li>
</ul>
<p>Depending on the product and the site’s needs, products may be displayed on unique pages, on pages grouping multiple products together (e.g. all the sizes of a t-shirt), on multiple different pages or Views, or not at all. For this reason, Drupal Commerce enforces a separation between the definition of a product and the product display.</p>
<p>This separation allows you to:</p>
<ul>
  <li>Track stock for a single product regardless of what display it’s purchased through (especially important for multilingual / multi-domain sites).</li>
  <li>Use a different display strategy to fit the site (e.g. a node per product, a single View listing all available products, etc.).</li>
  <li>Easily manage the import and update of particular product data without worrying about updating related display settings.</li>
</ul>

<h3>Attribute Fields</h3>

![Changing an attribute should update the price and picture.](../../images/Add-to-Cart-form-goal_0.png)

**Attribute Fields**

<p>Fields attached to products can be exposed on Add to Cart forms as
attribute fields. These are common fields among a group of products
whose allowed values may be used to select a particular product from the
group to add to the cart.</p>
<p>The settings for attribute fields are built into the field edit form. These are typically List fields and Taxonomy term reference fields, because they have a discreet number of options. Attribute fields can use select list or radio button widgets on the Add to Cart form.</p>

## Administering Products

<p>There are only about 20 administrative screens that come with Drupal
Commerce. Most of those screens are either Views or Settings Forms. Of particular
interest to store managers are the screens available for Product management.</p>


![Drupal Commerce Store Administration Screen.](../../images/Prod-Admin-Store.png)

**Store Admin Screen**

<p>This is the screen you will see when you click on "Store" up in the top toolbar.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Drupal Commerce</li>
    <li>Administration</li>
    <li class="last">Store</li>
</ul>


![Product Listing](../../images/Prod-Admin-Store-Product.png)

**Product Listing**

<p>The Product Listing is pretty bare-bones to start with. Below we've
made a simple exercise to add a few more filters and an image field.
Since this is built with Views, the possibilities are literally
endless.</p>

<ul class="screenshot_breadcrumbs">
  <li class="first">Drupal Commerce</li>
  <li>Administration</li>
  <li>Store</li>
  <li class="last">Products</li>
</ul>


![Product Type Listing Screen](../../images/Prod-Admin-Store-Product-Types.png)

**Product Types**
        
<p>The product types are documented in great detail in the <a href="Prod-Entity.html">Products as an Entity</a>.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Drupal Commerce</li>
    <li>Administration</li>
    <li>Store</li>
    <li>Products</li>
    <li class="last">Product Types</li>
</ul>

![Product Type, Manage Fields](../../images/Prod-Admin-Store-Product-Fields.png)

**Manage Fields**

<p>If you click on "Manage Fields" from the standard Product Type this is what you will see. You will note that it is very similar to editing a content type, using the same interface to add fields to product types.See <a href="/user-guide/product-attributes-variations">Product Attributes &amp; Variations</a> for more information.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Drupal Commerce</li>
    <li>Administration</li>
    <li>Store</li>
    <li>Products</li>
    <li>Product Types</li>
    <li class="last">Manage Fields</li>
</ul>

![This is where you manage the display of the fields for default values.](../../images/Prod-Admin-Store-Product-Display.png)

**Manage Product Display**
        
<p>This is where you manage how product fields will display in a variety of contexts, such as in the different view modes for product display nodes.</p>
    
<ul class="screenshot_breadcrumbs">
    <li class="first">Drupal Commerce</li>
    <li>Administration</li>
    <li>Store</li>
    <li>Products</li>
    <li>Product Types</li>
    <li class="last">Manage Display</li>
</ul>

![Product Edit Screen](../../images/Prod-Admin-Store-Product-Edit.png)

**Product Edit Screen**

<p>We've included the product edit screen for your benefit to see the
Product Entity form in all of it's glory. Pretty simple, we've got the
Product SKU and Product Title that are required. Additionally, Commerce
Kickstart has added the Image field. <a
href="../cart/Cart-MultiCurrency.html">The Price can be changed to allow
for multiple currencies</a>.</p>
    
<ul class="screenshot_breadcrumbs">
    <li class="first">Drupal Commerce</li>
    <li>Administration</li>
    <li>Store</li>
    <li>Products</li>
    <li class="last">Edit Product</li>
</ul>

<h3>Customizing On-site Product Administration</h3>

<p>Because all the back-end interfaces in Drupal Commerce are default Views, you
have the ability to tailor each page to your needs. The product list includes a
simple SKU filter by default but can do much more for you through customization.
Below we include a simple exercise where we add an image field, hide the "type"
field, and extend a product title keyword filter.</p>

![Hover over the product listing view and click Edit](../../images/Prod-Admin-ViewEdit-step1.png)

**Edit Product View**

<p>To start with we are going to use a shortcut to edit the product
listing view. This is a very simple way to tweak nearly all of the
Drupal Commerce administrative screens.</p>
    
<ul class="screenshot_breadcrumbs">
    <li class="first">Drupal Commerce</li>
    <li>Administration</li>
    <li>Store</li>
    <li class="last">Products</li>
</ul>

![Rearrange the fields to easily move and remove fields quickly.](../../images/Prod-Admin-ViewEdit-step2.png)

**Remove Product Type**

<p>Our first step is to remove the product type. This will be replaced
later on with an exposed filter.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li class="last">Edit View</li>
</ul>

![Lets remove the Product Type Field](../../images/Prod-Admin-ViewEdit-step3.png)

**Remove Product Type**

<p>This is a great way to remove and re-order the fields on the product
listing table. It's got a nice drag and drop feature as well as a very
simple "Remove" capability.</p>
    
<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>Edit View</li>
    <li class="last">Rearrange Fields</li>
</ul>


![We're adding an image field.](../../images/Prod-Admin-ViewEdit-step4.png)

**Add Image Field**

<p>Go ahead and click "Add" next to the fields list and select "Commerce
Product: Image" which is about halfway down in the list. Imagine for a
second how many fields are available here for your use. The most useful
are probably the "Commerce Product" fields, but there are many more to
choose from.</p>
    
<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>Edit View</li>
    <li class="last">Add Image Field</li>
</ul>

![We want to remove the default colon and change the image to display as a thumbnail.](../../images/Prod-Admin-ViewEdit-step5.png)

**Edit Field Image**

<p>We want to remove the default colon and change the image to display
as a thumbnail. You may want to go back and re-arrange the fields so
that the image field appears closer to the front of the table. <em>See
Remove Product Type</em> up above.</p>
    
<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>Edit View</li>
    <li class="last">Edit Field Image</li>
</ul>

![Let's go ahead and add a couple new Exposed Filters](../../images/Prod-Admin-ViewEdit-step6.png)

**Add Exposed Filters**

<p>Exposed filters are one of the coolest features of Views. They create
a simple form at the top of the administrative interface and let you
setup simple keyword searches and drop downs that are
automatically generated.</p>
    
<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>Edit View</li>
    <li class="last">Add Exposed Filters</li>
</ul>

![Here we're adding two exposed filters. We're going to enable keyword search for Titles and drop down selection for Product Types.](../../images/Prod-Admin-ViewEdit-step7.png)

**Add Filter Criteria**

<p>Here we're adding two exposed filters. We're going to enable keyword search for Titles and drop down selection for Product Types.</p>
    
<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>Edit View</li>
    <li class="last">Add Filter Criteria</li>
</ul>


![For the title, we will expose the filter and change the operator to contains any word.](../../images/Prod-Admin-ViewEdit-step8.png)

**Configure Title Filter**

<p>For the title, we will expose the filter and change the operator to
"Contains any word."</p>
    
<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>Edit View</li>
    <li class="last">Configure Title Filter</li>
</ul>

![The Product Type Exposed Filter automatically knows it can be a drop down of all available product types.](../../images/Prod-Admin-ViewEdit-step9.png)

**Configure Type Filter**

<p>The Product Type Exposed Filter automatically knows it can be a drop down of all available product types.</p>
    
<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>Edit View</li>
    <li class="last">Configure Type Filter</li>
</ul>

![Lets change the exposed filter submit button from Apply to Search.](../../images/Prod-Admin-ViewEdit-step10.png)

**Submit Button Change**

<p>Lets change the exposed filter submit button from "Apply" to "Search."</p>
    
<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>Edit View</li>
    <li class="last">Submit Button Change</li>
</ul>

![It's really that simple.](../../images/Prod-Admin-ViewEdit-step11.png)

**Change Submit Text**

<p>It's really that simple. Next we will view our product administration
screen. Don't forget to save your View!</p>
    
<ul class="screenshot_breadcrumbs">
    <li class="first">Administration</li>
    <li>Structure</li>
    <li>Views</li>
    <li>Edit View</li>
    <li class="last">Change Submit Text</li>
</ul>

![Final Customized Drupal Commerce Product Administration Screen](../../images/Prod-Admin-ViewEdit-step12.png)

**Product Admin Screen**

<p>Final Customized Drupal Commerce Product Administration Screen.</p>
    
<ul class="screenshot_breadcrumbs">
    <li class="first">Drupal Commerce</li>
    <li>Administration</li>
    <li>Store</li>
    <li class="last">Products</li>
</ul>

<h4>Contrib Product Administration</h4>

<p>If you don't want to tweak Views on your own, we recommend using one or more
of the modules listed below to tweak your Drupal Commerce Product Administration
experience:</p>

<ul>
    <li>Editable Fields - http://drupal.org/project/editablefields</li>
    <li>Commerce VBO Views - http://drupal.org/project/commerce_vbo_views</li>
    <li>Admin VBO Views - http://drupal.org/project/admin_vbo_views</li>
</ul>

<p>On the drupalcommerce.org community site, there is a community-fueled list of
all available <a href=""http://www.drupalcommerce.org/contrib/admin>Drupal
Commerce Administrative Modules</a>.</p>

<h3>Bulk Product Management with VBO</h3>

<p>Using Views Bulk Operations will let you perform batch processes across your
whole product list or a subset of the list using specific operations or Rules
based operations. The Commerce VBO Views module provides a replacement default
product View with a VBO version you can use as an example. Note that it does not
overwrite the existing View but disables the default View and enables the VBO
View at the same URL.</p>
<p>Further integrating Editable Fields with the VBO powered View will also let
you edit individual field values within the View itself even without a batch
process.</p>