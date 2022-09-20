---
title: Getting started
taxonomy:
    category: docs
---

# Commerce 2.x

At its core, Commerce is a set of Drupal 8 modules, which in turn depend
on other best-of-breed modules and libraries.

## Using Drupal Commerce

To learn how to use Drupal Commerce, see the [User Guide](./user-guide/index.md)

## Working with Drupal Commerce

Ready to start developing and working with Drupal Commerce?

* [Using Composer](#using-composer): If you are new to Composer or new to managing Drupal with Composer. Drupal Commerce requires using Composer with Drupal.
* [Getting Help](#best-practices): General guidelines for how and where to get help for Drupal Commerce.
* [Installation guide](./installation.md#installation): To get Drupal Commerce installed.
* [Updating guide](./updating.md) guide: For keeping Drupal Commerce up to date.

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

>  Dependency management is not a new concept and not unique to PHP. NPM for NodeJS,
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

Commerce utilizes various [libraries and dependencies](../../02.developer-guide/03.core/00.libraries-and-dependencies). Without Composer and the
generated class autoloader you cannot use Commerce. The libraries we depend on
will not be available, even if manually installed.

Composer also enables version constraints and prevents dependency conflicts.
Without Composer there is no way to tell our users “make sure you also update
this dependency as well.”

This also means less work for you.

### How to install Composer

Composer offers a convenient installer that you can execute directly from the commandline.
Follow instructions on [how to install Composer here](https://www.getcomposer.org/doc/00-intro.md){target=_blank}.
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
  "homepage": "http://drupal.org/project/commerce_authnet",
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
>  This link is for `drupal/core: ^8.3` [https://semver.mwl.be/#?package=drupal%2Fdrupal&version=%5E8.3&minimum-stability=dev](https://semver.mwl.be/#?package=drupal%2Fdrupal&version=%5E8.3&minimum-stability=dev){target=_blank}

**[composer.lock]{target=_blank}**

The `composer.lock` file is auto generated by Composer. It has information about
all the dependencies in the project, including ones your modules or themes depend
on.

The lock file contains information on how to fetch the dependency and its
dependency version constraints.

[composer install]

The `composer install` command will download and install dependencies. The install command will install off of lock file. However, if no lock file is available it will act as the update command.

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
[PSR-4]: http://www.php-fig.org/psr/psr-4/
[Drush]: http://www.drush.org
[composer.json]: https://getcomposer.org/doc/04-schema.md
[composer.lock]: https://getcomposer.org/doc/01-basic-usage.md#composer-lock-the-lock-file
[composer install]: https://getcomposer.org/doc/03-cli.md#install
[composer update]: https://getcomposer.org/doc/03-cli.md#update
[composer require]: https://getcomposer.org/doc/03-cli.md#require
[composer remove]: https://getcomposer.org/doc/03-cli.md#remove

## Getting help

### Best Practices
It's important to keep in mind that this is open source software. While some
contributors are paid for their time (usually for specific projects/tasks), a
great many more are donating their time to the community. When asking for help,
you'll find that people can be very friendly and giving but only if they are
treated respectfully. Also, ultimately, it's up to you to solve your own
problems: nobody in the community "works for you". That being said, here are
some general tips on how to get help in a way that's both respectful and
constructive:

 - Start with a search in the project's [issue queue](#drupal-commerce-issue-queue). Try to avoid posting
   duplicate issues!
 - If you can't find anything that's applicable to your problem, searching
   [Drupal Answers on StackExchange](#search-drupal-answers-on-stackexchange) is a good next step. Post a question if none of the existing questions apply to your issue.
 - For more immediate responses, you can try the [Commerce Slack channel](#chat-with-us-on-slack). Post a general message about the problem you're having, to start. You can get
   into the details in a separate thread. If your problem is a "known issue",
   you'll probably be directed to its Issue page.
 - Chatting with people in the Slack channel may lead to a suggestion to post
   a new Issue.
 - If you're posting an issue for the first time, review [How to create a good issue].
 - Whenever you get responses to your Issue (either in the form of requests for
   additional information or patches), it's helpful if you can respond quickly.
 - Become an active, "giving" member of the Drupal Commerce community yourself.
   Review patches, provide feedback, and help others in the Slack channel
   whenever you can.

### Drupal Commerce Issue Queue
If you are new to Drupal, you will want to learn more about [Drupal issue queues].

Drupal Commerce is an actively maintained project, and oftentimes you'll be
able to search its [Issue Queue] to find an answer to a question or a solution to
a problem/bug. Solutions are usually presented in the form of a patch. See the
[Patching section](../../02.developer-guide/02.install-update/07.patching) of this documentation
guide for information about applying patches.

### Search Drupal Answers on StackExchange
[Drupal Answers] is a question and answer site for Drupal developers and administrators. To search for information specific to Drupal Commerce, tag your queries with `drupal-commerce`. There are thousands of posted [Drupal Commerce questions], so it's a great place to look for help.

### Chat with us on Slack
Drupal Commerce benefits from an active community of developers who participate
in the #commerce [Slack]{target=_blank} channel. There is also a general Drupal #support channel
and the Drupal #general channel for light support questions. To join, visit:
[http://drupalslack.herokuapp.com/]{target=_blank}

For more information about Drupal Slack groups, see: [https://www.drupal.org/slack]{target=_blank}


[Slack]: https://slack.com/
[http://drupalslack.herokuapp.com/]: http://drupalslack.herokuapp.com/
[https://www.drupal.org/slack]: https://www.drupal.org/slack
[Drupal issue queues]: https://www.drupal.org/issue-queue
[Issue Queue]: https://www.drupal.org/project/issues/commerce
[How to create a good issue]: https://www.drupal.org/issue-queue/how-to
[Drupal Answers]: https://drupal.stackexchange.com/
[Drupal Commerce questions]: https://drupal.stackexchange.com/questions/tagged/drupal-commerce
