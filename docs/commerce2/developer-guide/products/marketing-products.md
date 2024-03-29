---
title: Marketing products
taxonomy:
    category: docs
---

**[Product search](#product-search)**

- Use the Search API module and views to create a product search page.

**[Product catalog with facets](#product-catalog-facets)**

- Build a product catalog with facets and add-to-cart functionality.

**Possible future sections/pages**

- upselling / cross-selling / featured products (commerce_pado module)
- grouped / related products
- product categories / tags (menus for categories?)
- includes SEO (links to relevant modules: pathauto, facets_pretty_paths, etc.)
- product reviews/ratings (fivestar?)
- product stories (blog articles, applications, user stories, etc.)

## Product Search

In this section we'll walk through the process of using the [Search API module] and the Drupal 8 core [Views module] to create a basic product search page like this:

![Product search page](../../images/product-search.jpg)

### Configure Search API module for product search
Search API is a contributed module that provides a framework for creating searches on Drupal entities. The general steps for setting up search functionality for products (or any type of data) with Search API are:

**Step 1:** [Install the *Search API module* and uninstall the *Search* module provided by Drupal 8 Core.](#step-1-install-search-api-modules)
 - Typically, you will want to uninstall the Core Search module for performance reasons.

**Step 2:** [Add a search server with Drupal's own database as the search backend.](#step-2-add-a-server)
 - Typically, most sites will want to use a more powerful backend like Solr or Elasticsearch instead of Drupal's own database.

**Step 3:** [Add an index for products.](#step-3-add-an-index)
 - The index's settings determine what data is indexed and how it is indexed.

**Step 4:** [Specify which product fields should be indexed and set their data types and weights.](#step-4-select-the-indexed-fields)
 - The data type of a field determines how it can be used for searching and filtering.
 - A *boost* value is used to give additional weight to fields to affect the ordering of the search results.

**Step 5:** [Enable some basic processors for our index.](#step-5-configure-processors)
 - The [Processors page] in the Search API documentation guide provides a good overview of processor options.

##### Step 1: Install Search API modules
!!! example 
    1. Add the [Search API module] to your site. (*See the [documentation on extending Drupal Commerce](../../../installation/#extending).*)
    2. Navigate to the *Extend* page at `/admin/modules`.
    3. Install the *Database Search* and *Search API* modules.
    4. Also, it is recommended that you *uninstall* the Core *Search* module whenever you are using Search API.

    ![Install search api modules](../../images/product-search-1.jpg)

##### Step 2: Add a server
!!!example
    1. Navigate to the Search API configuration page at `/admin/config/search/search-api`.
    2. Click the *Add server* button to add a server.
    3. Enter "Database" for the server name.
    4. Click the *Save* button.

    ![Add server](../../images/product-search-2.jpg)

##### Step 3: Add an index
!!! example
    1. Return to the Search API configuration page at `/admin/config/search/search-api`.
    2. Click the *Add index* button to add an index.
    3. Enter "Products" for the index name.
    4. Scroll down the list of *Data sources* until you see *Product*.
    5. Select *Product* as the type of data you want to index and search with this index.
    6. Select *Database* (the server we just created) for the *Server*.
    7. Click the *Save and add fields* button.

    ![Add search index](../../images/product-search-3.jpg)

##### Step 4: Select the indexed fields
!!! example
    1. Navigate to the Search API field management administration page at `/admin/config/search/search-api/index/products/fields`.
    2. Use the *Add fields* button to select all the field properties you want indexed. Search API will store data on the search server for each of these fields. In some cases, you will need to drill down into the options to locate the exact property you want. For example, for the Product Body field, it's the *Processed text* property that we actually want to index and search by:
   
        ![Add index fields](../../images/product-search-10.jpg)

    3. Specify the Type for each of the added fields. We've added Title, Product variation SKU, and Product variation Title fields to this search index. Since we want to be able to find individual words contained in our search fields, not just the whole field value, select *Fulltext* for each of the fields. (You can use non-Fulltext field types when you only want to use a field for filtering or sorting.)
    4. Set the Boost value for each of the added fields. Fields with higher boost scores will be *boosted* towards the top of the search listings. For example, if the Product Title has a boost score of 5, and other fields only have a boost score of 1, then products with the search term in their title will appear higher in the search results than products that only have the term in other fields.

        ![Add index fields](../../images/product-search-4.jpg)

##### Step 5: Configure processors
!!! example 
    1. Navigate to the Search API processors administration page at `/admin/config/search/search-api/index/products/processors`.
    2. Enable the processors you want to use. We'll select *Entity status*, *HTML filter*, and *Ignore case*.
    3. You can use the default settings for the *Processor order* settings.
    4. Then, for each of the selected processors, you may have some additional settings to configure:
     - For the *HTML filter* processor settings, we only need the processor to be enabled for the product Body field. So deselect the other options. Leave the *Tag boosts* as is.
     - For the *Ignore case* processor settings, just use the default settings.

    ![Configure processors](../../images/product-search-5.jpg)

### Create a basic product search page
Next, we'll set up a basic search page for our indexed data using Views and the Drupal core Block layout functionality.

##### Step 1: Create a products search view
!!! example 
    1. Navigate to the Views administration page at `/admin/structure/views` and click the *Add view* button.
    2. Enter "Product search" for the view name.
    3. Select *Index Products* for the *Show* setting, under *View settings*. This is the index we created previously and named *Products*.
    4. Select *Create a page*, under *Page settings*.
    5. Enter "Products" for the page title and "products" for the path.
    6. For the *Page display settings*, select *Unformatted list* of *Fields*.
    7. Click the *Save and edit* button.

    ![Create product search view](../../images/product-search-8.jpg)

##### Step 2: Configure the products search view
!!! example
    1. In the Fields section, remove the *Product datasource: Body >> Processed text* field.
    2. In the Fields section, add and configure the *Product datasource: Title* field.
      * Select the *Plain Text* formatter.
      * Select the *Link to the Product* option.
    3. In the Fields section, add and configure the *Product datasource: Body* field.
      * Select the *Summary or trimmed* formatter.
      * Enter "300" for the *Trimmed limit*
    4. In the Filter criteria section, add and configure the *Fulltext search* item:
     - Select *Expose this filter to visitors, and allow them to change it*.
     - Enter "Search products" for the *Label*.
     - Select *Contains any of these words* for the *Operator*.

    ![Add fulltext search filter](../../images/product-search-9.jpg)

    5. In the Sort criteria section, add and configure the *Relevance* option.
     - Select *Sort descending* for the *Order*.
    6. In the Exposed form section of the *Advanced* settings, click the *No* link next to *Exposed form in block* to change the setting to *Yes*.
    7. In the Exposed form section of the *Advanced* settings, click the *Settings* link next to *Exposed form style* to change the *Submit button text* to "Search".
    8. Click the *Save* button to save your changes.

##### Step 3: Add the search block to your pages
!!! example
    1. Navigate to the *Block layout* administration page at `/admin/structure/block`.
    2. Click the *Place block* button for the *Content* region.
    3. Click the *Place block* button for the *Exposed form: product_search-page_1* block.
    4. Set the Visibility settings so that the block only appears on the `/products` page.
    ![Search form block visibility settings](../../images/product-search-11.jpg)
    5. Rearrange the *Content* blocks so that the *Exposed form: product_search-page_1* block appears right below the *Page title* block.
    6. Click the *Save blocks* button at the bottom of the page.
    7. Navigate to your Products search page at `\products` to try your new search form.

    Optionally, you may want to add a menu link to the `\products` page or add the search block to all pages, perhaps in the Header region.

## Product Catalog Facets

In this section, we'll transform the [Basic product search](#product-search) page we already created into a Product catalog page with faceted search functionality. We'll use the Brands and Product categories taxonomies we created in the [Product categories documentation](../product-architecture/#product-categories). We'll also incorporate the [Add to cart form](../displaying-products/#add-to-cart-form) into our product catalog listing to encourage browsing users to become buying customers.

![Product catalog page](../../images/product-catalog.jpg)

#### Configure Search API
For the [Basic product search](#product-search), we installed Search API modules, added a server and an index, and selected the fields to be indexed for the search. For a faceted product catalog, we can use the same server and index. We'll just add a few additional fields and configure an additional processor.

!!! example "Add fields to the Products search index"
    1. Navigate to the Products search index fields administration page at `/admin/config/search/search-api/index/products/fields`.
    2. Click the *Add fields* button.
    3. For the facets, add the *Brand* and *Categories* fields.
    4. For the Add to cart form, add the *Variations* field.
    5. Click the *Done* button to add the fields.
    6. Click the *Save changes* button.
    ![Add fields to index](../../images/product-catalog-1.jpg)

    **Enable the *Index hierarchy* processor**

    Since Categories is a hierarchical taxonomy, we'll enable the hierarchy processor.
    7. Navigate to the Products search index processors administration page at `/admin/config/search/search-api/index/products/processors`.
    8. Select *Index hierarchy* as an enabled processor.
    9. In the Processor settings section, select *Categories* as the field to which hierarchical data should be added.
    10. Click the *Save* button.
    ![Products search index processors](../../images/product-catalog-11.jpg)

#### Configure the product catalog view
We're going to build upon the Product search view we created for the [Basic product search documentation](#product-search), to save a few steps here. The following steps assume that you have already created a view and will add an additional display. Alternatively, you could create the view and then make changes directly to the original *Page* view for your faceted product catalog page.

!!! example "Add a Product catalog display to the product search view"
    1. Navigate to the administration page for the Product search view at `/admin/structure/views/view/product_search`.
    2. Click the *Add* button under *Displays* to add a new *Page* display.
   
        ![Add new views page display](../../images/product-catalog-2.jpg)
    3. Click the *Page 2* link next to *Display name* to change the display name to "Product catalog".
    4. In the Page settings section, click the *No path is set* link next to *Path* to set the path to "product-catalog".
    5. In the Pager section, change the Items per page to "12".
    6. In the *Advanced* section, under *Other*, change the *Caching* setting to "Search API (tag-based)". Otherwise, facets break:
   
        ![Add new views page display](../../images/product-catalog-14.png)


        **Customize the product catalog display**

        >For these steps, make sure that you select *This page (override)* before applying changes. Also, see [Configure the Product Brand View fields](../products/displaying-products.md#configure-the-product-brand-view-fields) in the Multi-product displays documentation for a more detailed description, with screenshots, of the configuration options we're applying here.

    7. In the Format section, click the *Unformatted list* link next to *Format* to select *Grid* for the view style. Then click the *Settings* link next to Grid to change the number of columns to 3.
    8. Remove the *Product datasource: Body* field.
    9. Add a *Variations (Product datasource)* field.
     - Select *Use entity field rendering*.
     - Select *Rendered entity* for the Formatter.
     - Select *Single image* for the View mode.
     - Enter "1" for the number of values to display.
    10. Add a second *Variations (Product datasource)* field.
     - Select *Use entity field rendering*.
     - Select *Add to cart form* for the Formatter.
     - Enter an Administrative title to distinguish this variations field from the other.

    If you navigate to the product catalog page, at `/product-catalog`, we now have a searchable grid-style product catalog, with add-to-cart buttons. Only the facets are missing.
    ![Product catalog without facets](../../images/product-catalog-3.jpg)

#### Adding facets to the view

!!! example "Create facets"

    **Install and administer facets**

    1. Add and install the [Facets module]. (*See the [documentation on extending Drupal Commerce](../../installation.md#extending).*)
    2. Navigate to the Facets administration page at `/admin/config/search/facets`, where you will see all existing views of type DB index.

    ![Facets administration page](../../images/product-catalog-4.jpg)

    **Add the Brand facet**

    1. Click the *Add facet* button on the Facets administration page.
    2. For the Facet source, select the view and display for which you want to create facets.
    3. Select the field to be used as the data source for the facet. In this case, we'll select the Brand field.
    4. Enter a name for the facet, like "Brand".
    5. Cick the *Save* button.

    ![Add brand facet](../../images/product-catalog-5.jpg)

    **Configure the Brand facet**

    1. In the Facet Settings section, select *Transform entity ID to label* so that the Brand names will be displayed instead of their numeric IDs.
    2. In the Facet Sorting section, de-select *Sort by active state* and *Sort by count* so that products will appear alphabetically.
    3. Click the *Save* button to apply the changes.

    ![Facet settings](../../images/product-catalog-6.jpg)
    ![Facet sorting](../../images/product-catalog-7.jpg)

    **Add the Categories facet**

    1. Return to the Facets administration page at `/admin/config/search/facets`.
    2. Repeat the *Add the Brand facet* steps to add a facet for the *Categories* field.

    **Configure the Categories facet**

    1. In the Facet Settings section:
    - Select *Transform entity ID to label*.
    - Select *Use hierarchy*, since Categories is a hierarchical taxonomy.
    2. In the Facet Sorting section, select only *Sort by taxonomy term weight* to use the ordering previously set for the Categories taxonomy.
    3. Click the *Save* button to apply the changes.

    ![Facet settings](../../images/product-catalog-6.jpg)
    ![Categories facet settings](../../images/product-catalog-12.jpg)
    ![Categories facet sorting](../../images/product-catalog-10.jpg)

    We have now created two facets, which can be viewed on the Facets administration page and are available as blocks.

    ![Facet adminstration page with facets](../../images/product-catalog-8.jpg)

    **Place facets block on the product catalog page**

    1. Navigate to the Block layout administration page at `/admin/structure/block`.
    2. Click the *Place block* button for the *Sidebar first* region.
    3. Click the *Place block* button for the Facets *Brand* block.

    ![Place Brand block](../../images/product-catalog-9.jpg)

    4. For the *Configure block* settings, the default values are fine. Click *Save block* to add the Brand block.
    5. Repeat steps 2-4 for the Facet *Categories* block.
    6. Rebuild the cache and navigate to the Product catalog page at `/product-catalog` to view the results.

    For the screenshot below, I selected the *Urban Living* Category. Then its five sub-categories appeared. Selecting *Toys/Novelties* reduced the number of matching products to just two. Notice that only the single *Pool Toys Inc.* Brand is listed, since that is the brand for both toys.
    ![Product catalog page in action](../../images/product-catalog-13.jpg)


## Links and resources
* [Search API module documentation]
* Drupal 8 User Guide documentation on [Creating Listings with Views]
* Drupal 8 User Guide documentation on [Concept: Blocks]

[Search API module documentation]: https://www.drupal.org/docs/8/modules/search-api
[Creating Listings with Views]: https://www.drupal.org/docs/user_guide/en/views-chapter.html
[Facets module]: https://www.drupal.org/project/facets
[Concept: Blocks]: https://www.drupal.org/docs/user_guide/en/block-concept.html
[Search API module]: https://www.drupal.org/project/search_api
[Views module]: https://www.drupal.org/docs/user_guide/en/views-chapter.html
[Creating Listings with Views]: https://www.drupal.org/docs/user_guide/en/views-chapter.html
[Concept: Blocks]: https://www.drupal.org/docs/user_guide/en/block-concept.html
[Search API module documentation]: https://www.drupal.org/docs/8/modules/search-api
[Processors page]: https://www.drupal.org/docs/8/modules/search-api/getting-started/processors