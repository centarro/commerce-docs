---
title: Adding images to variations and dealing with image styles
taxonomy:
    category: docs
---

<p>Adding images to your products sounds like an easy scenario. In fact. you might be surprised to see this in a documentation user guide. But there are a lot of questions you will need to answer before you can even begin to understand how you might want your website to handle images. For example, do you want each "size" of t-shirt to have multiple photos? What about each size and each gender? What about just changing the photos on gender, regardless of size? Or perhaps you want a single gallery for all the variations per page. We don't answer every one of the possibilities below, but perhaps you can learn how we're doing this and come up with your own ways to treat images on products.</p>
<h3>Per Variation (Demo Store Setup)</h3>
<p>The Kickstart 2 Demo Store chooses a relatively complicated setup to show you how that complexity works through the entire site: Each variation has its own set of images. Below are the two steps you'll need to take to add an image using the Kickstart 2 Demo Store</p>

![Product Images - Add a Product](../../images/CK-Product-Images-01.png)

**Step 1: Add Product**

<p>To add a product with an image, first you mouseover "Products" and choose "Add a Product."</p>

![Product Images - Add a Product](../../images/CK-Product-Images-02.png)

**Step 2: Add Variation Image**

<p>In Drupal, image sizes are only limited by the amount you can safely upload and how big of an image your server can process. It usually gives you an idea of how big of an image you can upload based on maximum filesize. If unsure, try not to upload images greater than 5MB.</p>

<p>To set this up on the "No Demo" kickstarter, you can follow these steps:</p>

![Product Images - Add a Product](../../images/CK-Product-Images-03.png)

**Step 1**

<p>Let's go to the Product Variations so we can add a new one. If you don't need to add a new variation, skip to step 4 below.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Products</li>
    <li class="last">Variations</li>
</ul>

![Product Images - Click Create a Variation](../../images/CK-Product-Images-04.png)

**Step 2**

<p>If you need a new Variation, let's go ahead and click on "Add a New Variation"</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Products</li>
    <li>Variations</li>
    <li class="last">Add a Variation</li>
</ul>

![Product Images - Create a Variation](../../images/CK-Product-Images-05.png)

**Step 3**

<p>This is what the form looks like to create a new Product Variation. Simply give it a name, make sure both checkmarks are checked and click "save product variation type."</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Products</li>
    <li>Variations</li>
    <li class="last">Add a Variation</li>
</ul>

![Product Images - Manage Fields](../../images/CK-Product-Images-06.png)

**Step 4**

<p>Now you want to locate your new Product Variation and click "Manage Fields" next to it.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Products</li>
    <li>Variations</li>
    <li class="last">Manage Fields</li>
</ul>

![Product Images - Manage Fields](../../images/CK-Product-Images-07.png)

**Step 5**

<p>Let's go ahead and add an existing field. It's already got a database table and everything defaults to the one that comes with Kickstart 2 No Demo store. This is potentially more efficient and clean than creating a brand-new field.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Products</li>
    <li>Variations</li>
    <li>Manage Fields</li>
    <li class="last">Add Existing Field</li>
</ul>

![Product Images - Manage Fields](../../images/CK-Product-Images-08.png)

**Step 6**

<p>This is what the product variation type manage fields section should look like to create a gallery product field.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Products</li>
    <li>Variations</li>
    <li class="last">Manage Fields</li>
</ul>

![Product Images - Add Photo](../../images/CK-Product-Images-09.png)

**Example Product Form**

<p>This is what adding a new product type should look like for you to add new images.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Products</li>
    <li>Add a Product</li>
    <li class="last">Add a Widget</li>
</ul>

<h3>Per Product Display</h3>

<p>You can follow the same procedure above to add an image gallery to your product displays. The advantage or difference is that you are adding the image field to the Content Type or the "group" of products. So, if you were selling t-shirts and just had one image, this is how you would set that up.</p>

![Product Images - Edit Content Type](../../images/CK-Product-Images-10.png)

**Step 1**

<p>Mouseover "Content" and select "Content Types." This will take you to the Product Displays.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Content</li>
    <li class="last">Content Types</li>
</ul>

![Product Images - Manage Fields](../../images/CK-Product-Images-11.png)

**Step 2**

<p>Select "Manage Fields" next to your Product Display ... for me, this is the one next to "Widgets."</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Content</li>
    <li>Content Types</li>
    <li class="last">Manage Fields</li>
</ul>

![Product Images - Add Existing Field](../../images/CK-Product-Images-12.png)

**Step 3**

<p>Let's add the same existing field as we did in the last step.</p>

<ul class="screenshot_breadcrumbs">
    <li class="first">Content</li>
    <li>Content Types</li>
    <li>Manage Fields</li>
    <li class="last">Add Field</li>
</ul>

![Product Images - Example Form](../../images/CK-Product-Images-13.png)

**Example Form**

<p>This is the example form we have for adding images to the product display vs. product variations. As you can see, the image field is independent of the Product variations.</p>