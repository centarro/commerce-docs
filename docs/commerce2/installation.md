---
title: Installation
taxonomy:
    category: docs
---

## Before you begin

If you are not familiar with using [Composer to manage Drupal dependencies](https://www.drupal.org/docs/develop/using-composer/using-composer-with-drupal){target=_blank}, read [Using Composer with Drupal](./getting-started.md#using-composer) before continuing. Since you are already using the command line, we recommend you use [Drush](http://www.drush.org/){target=_blank} or [Drupal console](https://drupalconsole.com/){target=_blank} for various site management operations.

If you want to avoid using Composer for site management, the [Ludwig module](https://www.drupal.org/project/ludwig){target=_blank} offers a manual alternative to Composer. In the [Installing section](#installation), we document Ludwig installation; however, most of the documentation assumes that you will be using Composer to manage your site. If you do decide to use Ludwig instead of Composer, please be aware that updating and maintaining your site will be more difficult. Site administrators using Ludwig need to be careful when combining modules that depend on external libraries, since there are no safeguards against incompatible library versions or overlapping requirements.

## Requirements

 - Commerce 2.x currently requires Drupal 8.5.0 or newer. Generally we require each minor release, as it contains improvements that we use, or to reduce our code base.

 - If you already have a web server, make sure it satisfies [Drupal 8’s requirements]{target=_blank}.
 The recommended memory limit is 256MB or more.

 - To properly take advantage of [Drupal's configuration management system], you should always develop locally. For local development we recommend
 [DDEV]{target=_blank} (Docker-based) or [Drupal VM]{target=_blank} (Vagrant-based).

 - You will also need [Composer]{target=_blank}. We recommend that you use the newest version of composer, as older versions may or may not work. Check that your version matches the version listed on [getcomposer.org](https://getcomposer.org/){target=_blank}.


!!! info "PHP requirements"

    - Drupal Commerce requires that you have the [bcmath](http://php.net/manual/en/intro.bc.php){target=_blank} extension installed.

    - If you are using Drupal VM, add the following to your configuration (change PHP version number if needed).

    ```
    php_packages_extra:
    - php7.1-bcmath
    ```
    - If you are having issues related to the bcmath extension, the [Drupal VM documentation] provides useful information.

## Links and resources
* [Best Practice Drupal Development](https://drupalize.me/tutorial/best-practice-drupal-development)
* [Why must we use Composer?](https://glamanate.com/blog/managing-your-drupal-project-composer)

## Installation

**Installing Commerce to contribute back?** Check out our [Getting ready for development](./getting-started.md#preparing-the-local-environment) guide.

Be sure to [review requirements](#requirements) before starting the installation process.


=== "New site"

    The following command will download Drupal 8 + Commerce 2.x with all
    dependencies to the `mystore` folder:

    ```bash
    composer create-project drupalcommerce/project-base mystore --stability dev
    ```

    Install it just like a regular Drupal site. Commerce will be
    automatically enabled for you.

    !!! tip "Tips"
        - The `bin` folder contains any library binaries, such as [Drupal Console]{target=_blank}, [PHPUnit]{target=_blank}, [Behat]{target=_blank}, etc.
        - The `web` folder represents the document root.
            - If you host your site on Acquia Cloud or another service that requires Drupal in a subdirectory other than `web`, [these instructions]{target=_blank} describe how you can relocate the docroot.
        - Composer commands are always run from the site root (`mystore` in this case).
        - See the [Composer template for Drupal projects README]{target=_blank} for more details.


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
    !!! warning
        Composer is the recommended way to install and maintain a site. Site administrators using Ludwig need to be careful when combining modules that depend on external libraries, since there are no safeguards against incompatible library versions or overlapping requirements.

    These instructions assume you are working with an existing site. See the Drupal.org documentation on [Installing Drupal 8]{target=_blank} if you do not have an existing site.

    1. Download and install the [Ludwig module]{target=_blank}.

    2. Download [Commerce]{target=_blank} and the following 6 required modules. Do not install the modules yet.
        - [Address]{target=_blank}
        - [Entity API]{target=_blank}
        - [Entity Reference Revisions]{target=_blank}
        - [Inline Entity Form]{target=_blank}
        - [Profile]{target=_blank}
        - [State Machine]{target=_blank}

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

### Quick start with ddev

#### Requirements

 - [Drupal 8 system requirements]
 - Install [Composer]{target=_blank}.
 - Install [ddev]{target=_blank}.


#### Create your Drupal Commerce project.

 The following command will download Drupal 8 + Commerce 2.x with all
 dependencies to the `mystore` folder:

 ```bash
 composer create-project drupalcommerce/project-base mystore --stability dev
 ```

#### Configure ddev for local development.
 Navigate to the project directory (which is 'mystore', unless you changed it
 in the above command).

 Configure ddev:
 ```bash
 cd mystore
 ddev config
 ```

 When prompted:
 - Leave the project name set to the name of your project directory (mystore).
 - Leave the docroot location set to 'web'.
 - Leave the project type set to 'drupal8'.

 At this point, you can start up your project environment. However, you may
 want to first change the http and https ports for your site, which are set to
 80 and 443 by default. If these ports are already in use on your machine, you
 will get the following error message:

>  Failed to start commerce-docs: Unable to listen on required ports, localhost
>  port 80 is in use, Troubleshooting suggestions at
>  https://ddev.readthedocs.io/en/latest/users/troubleshooting/#unable-listen

 To change the ports used by ddev, open the file `.ddev/config.yaml` in your
 project. Edit the `router_http_port` and `router_https_port` values and save
 the file.

 Start running your project:
 ```bash
 ddev start
 ```

 When the project successfully starts, you will be given the address for your
 new site. For example: `http://mystore.ddev.local:8080`. Copy and paste your
 site address into a browser.

#### Install Drupal 8
 The first time you access your site, you will be asked for some basic
 configuration information. For the database configuration, use `ddev describe`
 to view your MySQL Credentials:

 ```bash
 MySQL Credentials
 -----------------
 Username:     	db
 Password:     	db
 Database name:	db
 Host:         	db
 Port:         	3306
 ```
 Note that you need to open the 'Advanced options' section of the form to
 enter the `Host` value.

#### Getting started
 The very first thing you'll want to do to get started with Drupal
 Commerce is create a store. Under the Commerce menu in the toolbar,
 navigate to Configuration > Store > Stores and click the `Add store` button.
 Once you've created a store, you'll be able to create products and start
 developing the rest of your site.

![create_new_store](./images/05.create-new-store.jpg)

## Updating

!!! warning "Important: You must use Composer to update Drupal Core, Drupal Commerce, and all contributed modules. Otherwise, your site will break. Also, you should always back up your site before starting an update procedure. See [Concept: Data Backups documentation] in the Drupal 8 User Guide for more information."

It is **critically important** that you keep up-to-date with [Drupal security advisories].
You can subscribe to the "Security announcements" newsletter in your [Drupal.org]{target=_blank} user
profile settings.

In order to keep Drupal Commerce and your other installed modules up-to-date,
you can use Composer to produce a list of outdated modules with the Composer
`outdated` command:

```bash
composer outdated --direct
```

Then for each outdated project, you can use the Composer `update` command:

```bash
composer update drupal/token --with-dependencies
```

To update to the newest version of Drupal Commerce, you will need to update with Composer.

Due to the way Drupal.org manages package information, you need to run one of the following commands. Until [Change submodule metadata to use '*' instead of 'self.version'](https://www.drupal.org/project/project_composer/issues/2948861){target=_blank} is fixed, this is needed.

To update Drupal Commerce and all contributed projects extending Drupal Commerce:

```bash
composer update --with-dependencies "drupal/commerce*"
```

If you want to *only* upgrade Drupal Commerce, run this command:

```bash
composer update --with-dependencies drupal/commerce drupal/commerce_price drupal/commerce_product drupal/commerce_order drupal/commerce_payment drupal/commerce_payment_example drupal/commerce_checkout drupal/commerce_tax drupal/commerce_cart drupal/commerce_log drupal/commerce_store drupal/commerce_promotion drupal/commerce_number_pattern
```

Once the Drupal.org infrastructure issue is resolved, the command will be

```bash
composer update drupal/commerce --with-dependencies
```

Please note the `--with-dependencies` option. Without this option
specified, any needed, contributed projects and libraries will not
update. Only the Drupal Commerce module will be updated.

Run your Drupal updates once all of the dependencies are updated. We
recommend running them on the command line rather than the
`update.php` script. See the example below.

```bash
drupal debug:update
drupal update:execute
```

!!! tip "Composer update tips"
    If your `composer update` command isn't working, you can try:

    - Run `composer why-not` to check dependency issues.
    - Run `composer remove` then `composer require` to reinstall the project.
    - Delete the `composer.lock` file and entire /vendor directory from
        your project and then run `composer install`.
    - Run `composer clear-cache`.
    - Run `composer self-update`.

Drupal uses a patch system to provide solutions to issues in between version
releases. If you are unfamiliar with the concept of patching, you can learn
about [Patches]{target=_blank} at drupal.org and check our [patches documentation](./contribute.md#patches)

## Extending

One of the primary strengths of both Commerce and Drupal is that they are open
source projects. There is a large, active community of developers who
contribute to these and related projects. As a result, you will find that it's
easy to extend Commerce with freely available, "contributed" modules. And if
you can't find something to meet your needs, you can always create your own
modules as well. Contributed modules as well as extensive documentation about
extending your Drupal site can be found on [drupal.org]{target=_blank}. A good place to start
is [Extending Drupal 8].

For Commerce, you can find everything from modules that provide major types of
functionality to smaller modules that provide unique, specialized functionality.
For example:

* [Commerce Shipping]{target=_blank} makes it possible to ship physical products to your customers.
* [Commerce Recurring]{target=_blank} makes it possible to provide subscriptions and recurring billing.

Extending your composer-managed site with additional modules is a
straightforward process. Each Drupal module has its own project page on
drupal.org with information and instructions. To add a module using composer,
the command is:

```bash
composer require drupal/commerce_shipping --prefer-dist
```

Where `commerce_shipping` is the name of the module you want to add to your
project. Composer will add the module as well as all of its dependencies.

Then you can install the module using the Admin UI (`/admin/modules`) or Drupal console:

The instructions below use [Drupal Console]{target=_blank}

```bash
drupal module:install commerce_shipping
```

### Adding custom modules from GitHub

Many Drupal developers use [GitHub]{target=_blank} for their initial module development work. So you may find that a module you'd like to use to extend your site is available on GitHub but not on Drupal.org. So how do you add that custom module to your composer-managed site? You can use [Composer Installers]{target=_blank}, a multi-framework composer library installer. Please see [How to download a module hosted on GitHub via composer.json?]{target=_blank} on Drupal Answers for a good example of how to do this.

If you are developing custom modules locally yourself, see [Managing dependencies for a custom product]{target=_blank} for an explanation of using Composer to manage your dependencies.

## Uninstalling

In order to completely remove Commerce from your Drupal site, you will first
need to delete all existing data. You can do this through the Admin UI using
the `Extend` toolbar item and then clicking on the `Uninstall` tab.

Type 'commerce' into the text box at the top of the page to filter the list by
Commerce modules.

Scan through the list for items that say:
```bash
There is content for the entity type:
```

Click the provided link to permanently remove the content for each entity type.


For example, if you have created a Store for your site, you will see:

![uninstall_store](./images/04.uninstall-store.jpg)

Click the `Remove stores` link.


Once all content has been removed, you can start uninstalling the commerce
modules. You will only be able to uninstall a few at a time because of
dependencies. For example, you will not be able to uninstall the base Commerce
module until all other commerce modules are uninstalled. Note: if you have
extended your Drupal Commerce site with any modules that are dependent upon
Commerce, those modules will also need to be uninstalled before you are able
to uninstall Commerce.

Here you can see boxes checked for 5 of the Commerce modules which can be
uninstalled. After clicking the `Uninstall` button, it will be possible to
uninstall additional modules.

![uninstall_commerce](./images/04.uninstall-commerce.jpg)

Since the Commerce base module provides a field type, it cannot be uninstalled together with the other modules. This is a known Drupal limitation. You will be blocked from uninstalling Commerce and see a "Fields pending deletion" message. You can delete the field in question by running Cron. You can run Cron through the Admin UI by navigating to Configuration > System > Cron.

Once all Commerce modules have been uninstalled, use Composer to remove the
Commerce dependency from your project.

```bash
composer remove drupal/commerce
```

At this point, Commerce and all its data have been uninstalled from your Drupal
site.

## Patching

Drupal uses a patch system to provide solutions to issues in between version
releases. If you are unfamiliar with the concept of patching, you can learn
about [Patches] at drupal.org.

- If you used `composer create-project` to create your site, you can use Composer to apply patches by modifying the `composer.json` file that's at the root of your project.

- If you did not use `composer create-project` to create your site, you can use the [Applying patches documentation] provided by drupal.org to learn about patching options.

The following documentation describes the procedure for applying patches for a composer-managed site.

In the "extra" section of your `composer.json` file, you will see "installer-types" and "installer-paths". To add patches to your project, add a
new "patches" group to "extra".

For example, your "extra" section looks something like this without any patches:

```bash
    "extra": {
        "installer-types": [
            "bower-asset",
            "npm-asset"
        ],
        "installer-paths": {
            "web/core": [
                "type:drupal-core"
            ],
            "web/libraries/{$name}": [
                "type:drupal-library",
                "type:bower-asset",
                "type:npm-asset"
            ],
            "web/modules/contrib/{$name}": [
                "type:drupal-module"
            ],
            "web/profiles/contrib/{$name}": [
                "type:drupal-profile"
            ],
            "web/themes/contrib/{$name}": [
                "type:drupal-theme"
            ],
            "drush/contrib/{$name}": [
                "type:drupal-drush"
            ]
        }
    }
```

Then suppose you want to apply a patch for a Drupal Commerce Issue:
[Issue #2901939: Move variations form to its own tab]

1. Copy the link address for the patch you want to apply. In this case, the
link address for the patch is `https://www.drupal.org/files/issues/2018-05-18/commerce-product-variations-tab-2901939-40.patch`

2. Make the following addition to the "extra" section of your composer.json file:

```bash
"patches": {
	  "drupal/commerce": {
	  	  "2901939: Move variations form to its own tab": "https://www.drupal.org/files/issues/2018-05-18/commerce-product-variations-tab-2901939-40.patch"
    }
}
```

Note that "drupal/commerce" is the project name. Then for that project, you
provide the specific patch information. If you have multiple patches, the
"extra" section of your composer.json file should look something like this:

```bash
    "extra": {
        "installer-types": [
            "bower-asset",
            "npm-asset"
        ],
        "installer-paths": {
            "web/core": [
                "type:drupal-core"
            ],
            "web/libraries/{$name}": [
                "type:drupal-library",
                "type:bower-asset",
                "type:npm-asset"
            ],
            "web/modules/contrib/{$name}": [
                "type:drupal-module"
            ],
            "web/profiles/contrib/{$name}": [
                "type:drupal-profile"
            ],
            "web/themes/contrib/{$name}": [
                "type:drupal-theme"
            ],
            "drush/contrib/{$name}": [
                "type:drupal-drush"
            ]
        },
        "patches": {
            "drupal/commerce": {
                "2901939: Move variations form to its own tab": "https://www.drupal.org/files/issues/2018-05-18/commerce-product-variations-tab-2901939-40.patch"
            },
            "drupal/core": {
                "2904908: Fetch User ID from route context views' contextual filter for any entity": "https://www.drupal.org/files/issues/fetch_user_id_from_route_for_all-2904908-13.patch"
            },
            "drupal/serial": {
                "2946075: Support migrating existing values": "https://www.drupal.org/files/issues/2946075-2.serial.Support-migrating-existing-values.patch"
            }
        }
    }
```

Once you've made the changes to `composer.json`, you can apply the patch by running:

```bash
composer update drupal/commerce
```

 [Patches]: https://www.drupal.org/patch
 [Issue #2901939: Move variations form to its own tab]: https://www.drupal.org/project/commerce/issues/2901939
 [Applying patches documentation]: https://www.drupal.org/patch/apply
 [Concept: Data Backups documentation]: https://www.drupal.org/docs/user_guide/en/prevent-backups.html
 [Drupal 8’s requirements]: https://www.drupal.org/requirements
 [DDEV]: https://ddev.readthedocs.io/
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
 [Drupal security advisories]: https://www.drupal.org/security
 [Concept: Data Backups documentation]: https://www.drupal.org/docs/user_guide/en/prevent-backups.html
 [Patches]: https://www.drupal.org/patch
[Issue #2901939: Move variations form to its own tab]: https://www.drupal.org/project/commerce/issues/2901939
[Applying patches documentation]: https://www.drupal.org/patch/apply

[drupal.org]: https://www.drupal.org
[Extending Drupal 8]: https://www.drupal.org/docs/8/extending-drupal-8
[Commerce Shipping]: https://www.drupal.org/project/commerce_shipping
[Commerce Recurring]: https://www.drupal.org/project/commerce_recurring
[Commerce Variation Cart Form]: https://www.drupal.org/project/commerce_variation_cart_form
[GitHub]: https://github.com/
[Composer Installers]: https://github.com/composer/installers
[How to download a module hosted on GitHub via composer.json?]: https://drupal.stackexchange.com/questions/243581/how-to-download-a-module-hosted-on-github-via-composer-json
[Managing dependencies for a custom product]: https://www.drupal.org/docs/develop/using-composer/managing-dependencies-for-a-custom-project

