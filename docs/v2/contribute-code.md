---
title: Contributing
taxonomy:
    category: docs
---

## Preparing the local environment

Start by setting up a web server, PHP and MySQL. We recommend [Drupal VM] for
advanced users, [Acquia Dev Desktop] for beginners.

You will also need [Git]{target=_blank} and [Composer]{target=_blank}. Note that Drupal VM comes with Composer included.

### Getting Commerce

The following command will download Drupal 8 + Commerce 2.x with all
dependencies to the `mystore` folder:

```bash
composer create-project drupalcommerce/project-base mystore --prefer-source --stability dev
```

The –prefer-source option tells Composer to use Git clone as the download method.

When prompted, answer **n** to:

```text
Do you want to remove the existing VCS (.git, .svn..) history? [Y,n]?
```

This will keep the downloaded git repositories inside their parent folders (such
as `web/modules/contrib/commerce`).

Tips:

-  The `bin` folder contains [Drupal Console] and [PHPUnit]{target=_blank}.
-  The `web` folder represents the document root.
-  Composer commands are always run from the site root (`mystore` in this case).
-  Downloading additional modules: `composer require "drupal/devel"`
-  Updating an existing module: `composer update drupal/address –with-dependencies`

See the [project-base README] for more details.

!!! tip
    Check out our [Quick start](./installation.md#quick-start-with-ddev) documentation to get things running easily.

### Preparing your fork

**Note:** You will need a GitHub account for contributing.

Visit the Commerce [repository on GitHub] and click the **fork** button. That
will create a copy of the repository on your GitHub account.

Now go to the Commerce folder and add your fork:

```bash
cd /path/to/mystore
cd web/modules/contrib/commerce
git remote add fork git@github.com:YOUR_USER/commerce.git
```

Replace YOUR\_USER with your username (the full url is shown on your
fork’s GitHub page).

You will now be able to push new branches to your fork and create [pull requests]
against the main repository.

### Running tests

All of the Drupal Commerce tests are based on the PHPUnit framework. In
order to run the tests you will need to copy the `phpunit.xml.dist`
from the core directory and modify it for your environment. An in depth
article on getting ready to run the tests can be found here:
<https://drupalcommerce.org/blog/45322/commerce-2x-unit-kernel-and-functional-tests-oh-my>

```bash
cd /path/to/mystore/web
# Run PHPUnit tests
../bin/phpunit -c core/phpunit.xml modules/contrib/commerce
```

#### Example **phpunit.xml** environment settings.

This `phpunit.xml` assumes you will be using SQLite (requires PHP SQLite extension) and
the Drupal site accessible at `https://localhost:8080`.

```xml
<!-- Snippet of environment settings. -->
  <php>
    <!-- Set error reporting to E_ALL. -->
    <ini name="error_reporting" value="32767"/>
    <!-- Do not limit the amount of memory tests take to run. -->
    <ini name="memory_limit" value="-1"/>
    <!-- Example SIMPLETEST_BASE_URL value: https://localhost -->
    <env name="SIMPLETEST_BASE_URL" value="https://localhost:8080"/>
    <!-- Example SIMPLETEST_DB value: mysql://username:password@localhost/databasename#table_prefix -->
    <env name="SIMPLETEST_DB" value="sqlite://localhost/sites/default/files/.ht.sqlite"/>
    <!-- Example BROWSERTEST_OUTPUT_DIRECTORY value: /path/to/webroot/sites/simpletest/browser_output -->
    <!--<env name="BROWSERTEST_OUTPUT_DIRECTORY" value="/path/to/drupal8/sites/simpletest/browser_output"/>-->
    <env name="BROWSERTEST_OUTPUT_DIRECTORY" value="/path/to/drupal8/sites/simpletest/browser_output"/>
    <ini name="display_errors" value="On" />
    <ini name="display_startup_errors" value="On" />
  </php>
```

You can run `php -S localhost:8080` from within your Drupal site to allow the tests to run.

## Choosing an issue

Commerce uses patches for contributing code changes and drupal.org for tracking issues. To choose an
issue, go through [the open issues], pick one you like and **assign it to you**.

If you need help choosing an issue or working on one, join the Commerce 2.x office hours.
They are held every Wednesday at 5PM GMT+1 on the *#commerce* [Drupal Slack channel].

**Tip:** You can also view the issue queue as a [kanban board].

## Creating a patch

Start by creating a branch for your work.
The branch name should contain a brief summary of its id and the issue, e.g **2276369-fix-product-form-notice**:

```bash
cd web/modules/contrib/commerce
git checkout -b 2276369-fix-product-form-notice
```

Once you’re done with writing your code and you've committed all changes to your branch, use `diff` to create a patch. For example, if the current version of Drupal Commerce is `8.x-2.x`, create a patch like this:

```bash
git diff 8.x-2.x > fix-product-form-notice-2276369-13.patch
```

Name your patch with this pattern: `[short-description]-[issue-number]-[comment-number].patch`

Note that the `[comment-number]` should be the number of the comment you *will post* to the issue; if the issue currently has 22 comments, then your comment number will be 23. If you have just created a new issue, then the comment number for your patch will be 2.

## Creating an interdiff

If your patch is not the initial patch for the issue, you should also create an interdiff. For an introduction to interdiffs and how to create them, see the Drupal.org documentation on [Creating an interdiff]{target=_blank}. For example, you can use [patchutils]{target=_blank} to create an interdiff for a new patch like this:

```bash
interdiff fix-product-form-notice-2276369-7.patch fix-product-form-notice-2276369-13.patch > interdiff-2276369-7-13.txt
```

The naming convention for interdiff files is: `interdiff-[issue-number]-[comment-number-for-previous-patch]-[new-comment-number].txt`

Using `.txt` as the extension ensures that the testbot will ignore your interdiff file.

## Update the issue and its status

Now that you've created the patch and interdiff locally, create a new comment on the issue and add your files. Typically, your patch will get automatically queued for testing. If not, you can click the `Add test / retest` link that appears below your uploaded patch to trigger the testbot.

If there are any test failures, you should leave the issue status set to "Needs work" until the problems are resolved. If all tests pass, update the issue status to "Needs review" to get feedback from other members of the community and reviews by the module maintainers.

Do *not* set the status to "Fixed" even though you've provided a "fix" in the form of your patch. The Fixed status is used by the module maintainers to indicate that either the latest patch has been committed to the development repository or the question asked in the issue has been sufficiently answered in the comments.

Typically, an issue will need several cycles of reviews and revisions before it is ready to be committed to the Drupal Commerce project.

## Patches

Reviewing patches created by other members of the community is another valuable way to contribute to Drupal Commerce.

### Applying Patches

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
### Reviewing patches

Once you've tested a patch, leave a comment on the issue. If the patch worked for you, you can change the issue status to "Reviewed & tested by the community". If the patch didn't work for you, you can change the issue status to "Needs work". Provide as many details as possible to help developers reproduce your results and figure out how to fix the patch.

!!! tip "How to get a Commerce issue committed"
    * Up to date issue summary
    * Complete tests
        * If it is a bug report, tests are more important than the fix, they guarantee both that the bug exists and that the fix actually fixes it.
        * Make sure to have full test coverage, lazy test writing will stall your issue.
    * Reports of the patch working for others
        * Explain what you’re doing and what the patch fixes for you
        * This is helpful even if one person has already RTBC’d
    * Small single focus for each patch
        * Don’t try to fix 8 things at once. If needed, do the most common use case and can then cover additional use cases with additional patches.
        * A small patch means the reviewer can easily grasp the whole change quickly. Large and mixed patches require a lot more review and can’t be done quickly as the reviewer has to spend a lot of time understanding the whole patch. 4 little patches are faster than 1 big one.
    * Change Record entry
        * Write a change record entry if the patch requires ANY manual work from the site owner or changes existing functionality that might be breaking or confusing.

That’s it! Happy contributing!

[the open issues]: https://www.drupal.org/project/issues/search/commerce?assigned=&submitted=&project_issue_followers=&status%5B0%5D=Open&version%5B0%5D=8.x&issue_tags_op=%3D&issue_tags=&text=&&&&order=field_issue_priority&sort=desc
[Drupal Slack channel]: https://www.drupal.org/slack
[kanban board]: https://contribkanban.com/board/commerce2x
[Version control page]: https://www.drupal.org/project/commerce/git-instructions
[Creating an interdiff]: https://www.drupal.org/documentation/git/interdiff
[patchutils]: https://freshmeat.sourceforge.net/projects/patchutils
[Drupal VM]: https://www.drupalvm.com/
[Acquia Dev Desktop]: https://www.acquia.com/products-services/dev-desktop
[Git]: https://git-scm.com/
[Composer]: https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos
[Drupal Console]: https://drupalconsole.com
[PHPUnit]: https://phpunit.de/
[project-base README]: https://github.com/drupalcommerce/project-base/blob/8.x/README.md
[repository on GitHub]: https://github.com/drupalcommerce/commerce
[pull requests]: https://help.github.com/articles/using-pull-requests
[Patches]: https://www.drupal.org/patch
[Issue #2901939: Move variations form to its own tab]: https://www.drupal.org/project/commerce/issues/2901939
[Applying patches documentation]: https://www.drupal.org/patch/apply