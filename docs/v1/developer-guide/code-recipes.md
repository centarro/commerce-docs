---
title: Code Recipes
taxonomy:
    category: docs
---

!!! example "Change "Delete" on your Cart to X"

    ```php
    <?php
    /**
    * Implements hook_form_alter().
    * Designed to be added to your template.php in your custom theme.
    */
    function themename_form_alter(&$form, $form_state, $form_id) {
            
      switch ($form_id)  {
        case 'views_form_commerce_cart_form_default':
          foreach ($form['edit_delete'] as $row_id => $row) {
            if(isset($form['edit_delete'][$row_id]['#value'])){
              $form['edit_delete'][$row_id]['#value'] = 'X';
            }
          }
          break;
      }
    }
    ```

!!! example "Customize Checkout Look and Feel"

    There was a <a href="https://drupal.org/node/1098028">recent commit that made the checkout process table-less in the Commerce Issue Que</a>. It was reverted in favor of making such a large change to the codebase on a 2.x release. That code still exists and it's all possible using theme_ functions in your template.php...

    <ol>
    <li><strong>Step 1</strong> - Go here <code>/modules/checkout/includes/commerce_checkout.pages.inc</code> and look for functions that start with theme_</li>
    <li><strong>Step 2</strong> - Add those functions that you want to your template.php in your theme or a custom module</li>
    <li><strong>Step 3</strong> - change the function word "theme" with your theme name or module name.</li>
    <li><strong>Step 4</strong> - Make an obvious change or two</li>
    <li><strong>Step 5</strong> - Clear all caches and then go through the checkout process.</li>
    </ol>

    Let us know in the comments if this process worked for you! Also, post any tweaks to the Commerce Issue Que and they might be considered for inclusion for the 2.x release.

!!! example "Function that tells us if the items in Shopping Cart / Basket"

    ```php
    <?php
    // Provided by stevetook in the forums.
    function items_on_cart() {
      global $user;
      $cart = commerce_cart_order_load($user->uid); 
      $line_items = count($cart->commerce_line_items) ? TRUE : FALSE;
      return $line_items;
    }
    ```