---
title: Installation
taxonomy:
    category: docs
---

## Before you begin

If you are not familiar with using [Composer to manage Drupal dependencies](https://www.drupal.org/docs/develop/using-composer/using-composer-with-drupal), read [Using Composer with Drupal](../../getting-started/#using-composer) before continuing. Since you are already using the command line, we recommend you use [Drush](http://www.drush.org/) or [Drupal console](https://drupalconsole.com/) for various site management operations.

If you want to avoid using Composer for site management, the [Ludwig module](https://www.drupal.org/project/ludwig) offers a manual alternative to Composer. In the [Installing section](#installation), we document Ludwig installation; however, most of the documentation assumes that you will be using Composer to manage your site. If you do decide to use Ludwig instead of Composer, please be aware that updating and maintaining your site will be more difficult. Site administrators using Ludwig need to be careful when combining modules that depend on external libraries, since there are no safeguards against incompatible library versions or overlapping requirements.

## Requirements

 - Commerce 2.x currently requires Drupal 8.5.0 or newer. Generally we require each minor release, as it contains improvements that we use, or to reduce our code base.

 - If you already have a web server, make sure it satisfies [Drupal 8’s requirements].
 The recommended memory limit is 256MB or more.

 - To properly take advantage of [Drupal's configuration management system], you should always develop locally. For local development we recommend
 [DDEV] (Docker-based) or [Drupal VM] (Vagrant-based).

 - You will also need [Composer]. We recommend that you use the newest version of composer, as older versions may or may not work. Check that your version matches the version listed on [getcomposer.org](https://getcomposer.org/).


!!! info "PHP requirements"

    - Drupal Commerce requires that you have the [bcmath](http://php.net/manual/en/intro.bc.php) extension installed.

    - If you are using Drupal VM, add the following to your configuration (change PHP version number if needed).

    ```
    php_packages_extra:
    - php7.1-bcmath
    ```
    - If you are having issues related to the bcmath extension, the [Drupal VM documentation] provides useful information.
## Installation

**Installing Commerce to contribute back?** Check out our [Getting ready for development](../../getting-started/#preparing-the-local-environment) guide.

Be sure to [review requirements](#requirements) before starting the installation process.


=== "New site"

    The following command will download Drupal 8 + Commerce 2.x with all
    dependencies to the `mystore` folder:

    ```bash
    composer create-project drupalcommerce/project-base mystore --stability dev
    ```

    Install it just like a regular Drupal site. Commerce will be
    automatically enabled for you.

    Tips:

    - The `bin` folder contains any library binaries, such as [Drupal Console], [PHPUnit], [Behat], etc.
    - The `web` folder represents the document root.
        - If you host your site on Acquia Cloud or another service that requires Drupal in a subdirectory other than `web`, [these instructions] describe how you can relocate the docroot.
    - Composer commands are always run from the site root (`mystore` in this case).
    - See the [Composer template for Drupal projects README] for more details.


=== "Existing site"

    Run these commands in the root of your website:

    **Download Commerce**

    This will also download the required libraries and modules (Address, Entity, State Machine, Inline Entity Form, Profile).

    ```bash
    cd /path/to/drupal8
    composer require "drupal/commerce"
    ```

    **Enable Commerce**

    The instructions below use [Drupal Console]

    ```bash
    drupal module:install commerce_product commerce_checkout commerce_cart
    ```

=== "Alternative (Ludwig)"
    > Composer is the recommended way to install and maintain a site. Site administrators using Ludwig need to be careful when combining modules that depend on external libraries, since there are no safeguards against incompatible library versions or overlapping requirements.

    These instructions assume you are working with an existing site. See the Drupal.org documentation on [Installing Drupal 8] if you do not have an existing site.

    1. Download and install the [Ludwig module].

    2. Download [Commerce] and the following 6 required modules. Do not install the modules yet.
        - [Address]
        - [Entity API]
        - [Entity Reference Revisions]
        - [Inline Entity Form]
        - [Profile]
        - [State Machine]

    3. Ludwig generates a listing of libraries required by those modules. The Packages page at `admin/reports/packages` provides a download link for each missing library along with the paths where they should be placed.
    ![Ludwig user interface](images/ludwig-ui.jpg)

    4. Download the libraries, then clear the cache to make them available. For example, download commerceguys/addressing and place it in `modules/contrib/address/lib/commerceguys-addressing/v1.0.0`. You should see the STATUS for each required package change from "Missing" to "Installed". Alternatively, if you are comfortable with the command line, you can use Drupal Console or Drush commands.

        **Drupal Console**
        - ludwig:list: List all managed packages.
        - ludwig:download: Download missing packages.

        **Drush**
        - ludwig-download: Download missing packages.


    5. Install Commerce and the 6 required modules.

    > Whenever Commerce needs to be updated, all 7 modules need to be downloaded again, and then all of their libraries need to be downloaded again as well.

In subsequent sections it is assumed that you are using Composer to manage your site.

## Links and resources
* [Best Practice Drupal Development](https://drupalize.me/tutorial/best-practice-drupal-development)
* [Why must we use Composer?](https://glamanate.com/blog/managing-your-drupal-project-composer)


 [Drupal 8’s requirements]: https://www.drupal.org/requirements
 [DDEV]: https://www.drud.com/what-is-ddev/
 [Drupal VM]: http://www.drupalvm.com/
 [Composer]: https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx
 [Drupal's configuration management system]: https://www.drupal.org/docs/8/configuration-management/managing-your-sites-configuration
 [Drupal VM documentation]: https://github.com/geerlingguy/drupal-vm/search?q=bcmath&type=Issues
  [Drupal Console]: https://drupalconsole.com
 [project-base README]: https://github.com/drupalcommerce/project-base/blob/8.x/README.md
 [PHPUnit]: https://www.drupal.org/docs/8/phpunit/running-phpunit-tests
 [Behat]: http://docs.behat.org/en/latest/
 [these instructions]: https://github.com/drupal-composer/drupal-project/issues/64#issuecomment-206455356
 [Composer template for Drupal projects README]: https://github.com/drupal-composer/drupal-project/blob/8.x/README.md
 [Ludwig module]: https://www.drupal.org/project/ludwig
 [Commerce]: https://www.drupal.org/project/commerce
 [Address]: https://www.drupal.org/project/address
 [Entity API]: https://www.drupal.org/project/entity
 [Entity Reference Revisions]: https://www.drupal.org/project/entity_reference_revisions
 [Inline Entity Form]: https://www.drupal.org/project/inline_entity_form
 [Profile]: https://www.drupal.org/project/profile
 [State Machine]: https://www.drupal.org/project/state_machine
 [Installing Drupal 8]: https://www.drupal.org/docs/8/install
