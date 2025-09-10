---
title: Getting started
taxonomy:
    category: docs
---

# Getting started with Drupal Commerce

Drupal Commerce natively extends Drupal, the open source content management system (CMS). If you have no experience building Drupal sites, please note that it requires intermediate to advanced web development skills such as:

* Using command line tools to download code
* Tracking changes via a version control system
* Configuring a web host to use the same tools
* Managing configuration via YAML code files
* Running a web server on your own computer for development and testing prior to deployment

There are resources for learning all of the above, but the purpose of this user guide is *not* to explain in detail how to begin working with Drupal with no prior experience. If you *are* completely new, you might benefit from working through [Drupal's "Getting started" guide](https://www.drupal.org/docs/getting-started) first.

## Working with Drupal Commerce

Ready to start developing and working with Drupal Commerce?

* [Using Composer](#using-composer): If you are new to Composer or new to managing Drupal with Composer. Drupal Commerce requires using Composer with Drupal.
* [Getting Help](#community-support): General guidelines for how and where to get help for Drupal Commerce.
* [Installation guide](./installation.md#installation): To get Drupal Commerce installed.
* [Updating guide](./installation.md#updating) guide: For keeping Drupal Commerce up to date.

## Looking for a demo?

There are several Drupal Commerce 2 demo sites available.

* [PayPal demo](https://commerce.demo.centarro.io/){target=_blank}, using PayPal Express Checkout for payment.
* [Braintree demo](https://braintree.demo.centarro.io/){target=_blank}, using Braintree Hosted Fields for payment.
* [Square demo](https://square.demo.centarro.io){target=_blank}, using Square Payment Form for payment.
* [Authorize.net demo](https://authnet.demo.centarro.io){target=_blank}, using Authorize.net for payment.

Additionally, you can use the Drupal Commerce Demo button on the homepage of [Simplytest](https://simplytest.me){target=_blank} to build the full demo store in a temporary web environment. Log in as the administrative user using admin / admin to test all that Commerce Core has to offer out of the box.

## Using Composer

[Composer]{target=_blank} is a tool for managing dependencies on the project level, a
project being your site or web application. It has become the de facto dependency
management tool for PHP. Composer allows PHP developers to easily build standalone
distributable libraries that can be shared and integrated by others. This is
possible in part by the PHP Framework Interoperability Group (FIG) and [PSR-4]{target=_blank}
for autoloading of class files.

>  Dependency management is not a new concept and not unique to PHP. NPM for Node.js,
>  Bower for front end libraries, Bundler/Gems for Ruby, PIP for Python, Maven for
>  Java and so forth.

If you’ve ever used [Drush]{target=_blank} Make to download Drupal modules and themes, then
all of this sounds familiar. You can think of Composer as the more advanced
Drush Make that works for all PHP projects and packages. Compared to Drush Make,
Composer has the benefit of being able to recursively resolve dependencies
(downloading the dependencies of each dependency) and being able to detect conflicts.

### Why does Commerce need it?

Modern applications such as Drupal 8 consist of many classes, so it would be
impractical and costly to manually include each one. Instead, the application
includes one special class, called the autoloader which then automatically
includes other classes when they are first needed. When Composer runs, it
regenerates the autoloader, giving it the locations of the newly downloaded
dependencies.

Commerce utilizes various [libraries and dependencies](../developer-guide/core/core/#php-libraries). Without Composer and the
generated class autoloader you cannot use Commerce. The libraries we depend on
will not be available, even if manually installed.

Composer also enables version constraints and prevents dependency conflicts.
Without Composer there is no way to tell our users “make sure you also update
this dependency as well.”

This also means less work for you.

### How to install Composer

Composer offers a convenient installer that you can execute directly from the commandline.
Follow instructions on [how to install Composer here](https://getcomposer.org/doc/00-intro.md){target=_blank}.
We recommend that you use the newest version of composer, as older versions may or may not work.
Check that your version matches the version listed on [getcomposer.org](https://getcomposer.org/){target=_blank}.



### How to use it

**[composer.json]{target=_blank}**

The ``composer.json`` file defines required libraries, modules, themes, and
Drupal core to download. It allows you to run commands when Composer finishes
an operation, and more.

Here is an example from the `commerce_authnet` module.

```json
{
  "name": "drupal/commerce_authnet",
  "type": "drupal-module",
  "description": "Provides Commerce integration for Authorize.net.",
  "homepage": "https://drupal.org/project/commerce_authnet",
  "license": "GPL-2.0+",
  "require": {
    "drupal/commerce": "^2",
    "commerceguys/authnet": "dev-master"
  }
}
```

This specifies the project as being a Drupal module available at ``drupal/commerce_authnet``
with information about its license and homepage. It requires `commerce` of any
satisfiable version 2 release, and the development version of the `commerceguys/authnet` library.

>  Composer relies on semantic versioning, using `~` and `^` operators, or direct
>  release names (`2.0-beta3`.)
>
>  Check out the Packagist Semver Checker to explore how version constraints work.
>  This link is for `drupal/core: ^8.3` [https://semver.madewithlove.com/#?package=drupal%2Fdrupal&version=%5E8.3&minimum-stability=dev](https://semver.madewithlove.com/#?package=drupal%2Fdrupal&version=%5E8.3&minimum-stability=dev){target=_blank}

**[composer.lock]{target=_blank}**

The `composer.lock` file is auto generated by Composer. It has information about
all the dependencies in the project, including ones your modules or themes depend
on.

The lock file contains information on how to fetch the dependency and its
dependency version constraints.

[composer install]

The `composer install` command will download and install dependencies. The "install" command will install off of lock file. However, if no lock file is available it will act as the update command.

The command will regenerate the class autoloader.

**[composer update]{target=_blank}**

The `composer update` command resolves dependencies and generates the `composer.lock` file. The update command will update dependencies to their latest versions.

The command will regenerate the class autoloader.

**[composer require]{target=_blank}**

The `composer require` command adds a new dependency to your project. This will update the `composer.json` and `composer.lock` files and regenerate the class autoloader.

If the new dependency has any conflicts with other dependencies, such as incompatible shared dependencies, it will not install.

**[composer remove]{target=_blank}**

The `composer remove` command removes a dependency from your project. This will update the `composer.json` and `composer.lock` files and regenerate the class autoloader.

If the dependency is required by another package, it will not be removed.

### Links and resources

* [Managing Your Drupal Project with Composer](https://glamanate.com/blog/managing-your-drupal-project-composer){target=_blank}, or the [slides version](https://docs.google.com/presentation/d/1PK9q2dBkGHfyEO76bEVpqS61wTgA0LGbru2PECiwUnk/edit?usp=sharing){target=_blank}
* [Drupal Commerce Composer project template](https://github.com/drupalcommerce/project-base){target=_blank}
* [Drupal Composer project template](https://github.com/drupal-composer/drupal-project){target=_blank}
* [Platform.sh Drupal 8 + Composer template example](https://github.com/platformsh/template-drupal8){target=_blank}
* [Amazee Labs Composer recipes](https://www.amazeelabs.com/en/journal/drupal-composer-recipes){target=_blank}
* [Using Drupal + Composer project templates with Pantheon sites](https://pantheon.io/blog/using-composer-relocated-document-root-pantheon){target=_blank}
* [Using Composer - Drupal.org](https://www.drupal.org/docs/develop/using-composer){target=_blank}

[Composer]: https://getcomposer.org/
[PSR-4]: https://www.php-fig.org/psr/psr-4/
[Drush]: https://www.drush.org
[composer.json]: https://getcomposer.org/doc/04-schema.md
[composer.lock]: https://getcomposer.org/doc/01-basic-usage.md#composer-lock-the-lock-file
[composer install]: https://getcomposer.org/doc/03-cli.md#install
[composer update]: https://getcomposer.org/doc/03-cli.md#update
[composer require]: https://getcomposer.org/doc/03-cli.md#require
[composer remove]: https://getcomposer.org/doc/03-cli.md#remove

## Community support

Open source projects are typically supported by a community of contributors donating their own time. When asking for help, please respect their time by searching existing resources first and communicating clearly, concisely, and politely when asking for help. A typical support journey looks like:

1. Search the project's [issue queue](#drupal-commerce-issue-queue) for existing issues about the bug you're encountering or feature you're looking for.
2. If you cannot find an answer there, search [Drupal Answers](#search-drupal-answers-on-stackexchange) next, as the project maintainers monitor the `commerce` tag.
3. For questions that require some dialogue or extended discussion, consider joining the [#commerce channel](#chat-with-us-on-slack) in the Drupal Slack community. Please try to keep all discussion to replies in a thread. If your problem is a known issue, you may be directed to its issue page to try to keep all discussion about a subject in one place.
4. Ultimately, you may need to open an issue in the issue queue, in which case you can read more on [How to create a good issue].

As you find help and grow in your experience and ability, it would be awesome for you to give back by supporting other members of the Drupal Commerce community yourself. Don't be shy about reviewing patches, providing feedback, and helping others in the Slack channel when you can!

### Drupal Commerce Issue Queue
If you are new to Drupal, you will want to learn more about [Drupal issue queues].

Drupal Commerce is an actively maintained project, and oftentimes you'll be
able to search its [Issue Queue] to find an answer to a question or a solution to
a problem/bug. Solutions are usually presented in the form of a patch. See the
[Patching section](../installation/#patching) of this documentation
guide for information about applying patches.

### Search Drupal Answers on StackExchange
[Drupal Answers] is a question and answer site for Drupal developers and administrators. To search for information specific to Drupal Commerce, tag your queries with `drupal-commerce`. There are thousands of posted [Drupal Commerce questions], so it's a great place to look for help.

### Chat with us on Slack
Drupal Commerce benefits from an active community of developers who participate
in the #commerce [Slack]{target=_blank} channel. There is also a general Drupal #support channel
and the Drupal #general channel for light support questions.

For more information about Drupal Slack groups, see: [https://www.drupal.org/slack]{target=_blank}


[Slack]: https://slack.com/
[https://drupalslack.herokuapp.com/]: https://drupalslack.herokuapp.com/
[https://www.drupal.org/slack]: https://www.drupal.org/slack
[Drupal issue queues]: https://www.drupal.org/issue-queue
[Issue Queue]: https://www.drupal.org/project/issues/commerce
[How to create a good issue]: https://www.drupal.org/issue-queue/how-to
[Drupal Answers]: https://drupal.stackexchange.com/
[Drupal Commerce questions]: https://drupal.stackexchange.com/questions/tagged/drupal-commerce
