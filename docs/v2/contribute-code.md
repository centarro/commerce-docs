---
title: Contributing
taxonomy:
    category: docs
---

## Preparing the local environment

We recommend [DDEV]{target=_blank} for local development. You will also need [Git]{target=_blank}.
DDEV ships with Composer and Drush.

### Getting Commerce

The following commands create a Drupal site with Commerce cloned from git, in the `mystore` folder:

```bash
mkdir mystore && cd mystore
ddev config --project-type=drupal11 --docroot=web
ddev start
ddev composer create-project drupal/recommended-project
ddev composer require drupal/commerce:3.x-dev --prefer-source
```

The `--prefer-source` option tells Composer to use `git clone` as the download method,
so `web/modules/contrib/commerce` is a git repository you can work in.

Tips:

-  The `web` folder represents the document root.
-  Composer commands are always run from the site root (`mystore` in this case).
-  Downloading additional modules: `ddev composer require drupal/devel`
-  Updating an existing module: `ddev composer update drupal/address --with-dependencies`

!!! tip
    Check out our [Quick start](./installation.md#quick-start-with-ddev) documentation to get things running easily.

### Running tests

All the Drupal Commerce tests are based on the PHPUnit framework. Merge requests are tested automatically
by GitLab CI, but you'll want to run the relevant tests locally while working. Add the testing dependencies
and copy the default PHPUnit configuration:

```bash
ddev composer require --dev drupal/core-dev
cp web/core/phpunit.xml.dist web/core/phpunit.xml
```

Then point the environment settings in `web/core/phpunit.xml` at your DDEV containers:

```xml
<php>
  <env name="SIMPLETEST_BASE_URL" value="http://web"/>
  <env name="SIMPLETEST_DB" value="mysql://db:db@db/db"/>
  <env name="BROWSERTEST_OUTPUT_DIRECTORY" value="/var/www/html/web/sites/simpletest/browser_output"/>
</php>
```

Run the tests from inside the container:

```bash
ddev exec vendor/bin/phpunit -c web/core web/modules/contrib/commerce/modules/order
```

## Choosing an issue

Commerce uses [merge requests] for contributing code changes and drupal.org for tracking issues. To choose an
issue, go through [the open issues], pick one you like and **assign it to you**.

If you need help choosing an issue or working on one, ask in the *#commerce* [Drupal Slack channel].

## Creating a merge request

Code changes are made in an *issue fork*: a copy of the Commerce repository on [git.drupalcode.org] dedicated to
a single issue. You need a drupal.org account and to have agreed to the Git access terms
in your [drupal.org account settings](https://www.drupal.org/user) (*Git access* tab), with an SSH key added.

1. On the issue page, click **Create issue fork**. If the issue already has a fork, click **Get push access** instead.
2. Click **Show commands** next to the fork. It lists the exact commands for your issue; they look like this:

    ```bash
    cd web/modules/contrib/commerce
    git remote add commerce-2276369 git@git.drupal.org:issue/commerce-2276369.git
    git fetch commerce-2276369
    git checkout -b '2276369-fix-product-form-notice' --track commerce-2276369/'2276369-fix-product-form-notice'
    ```

3. Commit your changes and push them to the issue fork:

    ```bash
    git push commerce-2276369 2276369-fix-product-form-notice
    ```

4. Back on the issue page, click **Open merge request** (once per issue). The merge request should target the
   `3.x` branch. Later pushes to the same branch update the merge request automatically.

See the drupal.org documentation on [Creating issue forks and merge requests] for more details.

## Update the issue and its status

GitLab CI runs the test suite on every push to the merge request. Follow the pipeline link on the merge request to see the results.

If there are any test failures, leave the issue status set to "Needs work" until the problems are resolved. If all tests pass, update the issue status to "Needs review" to get feedback from other members of the community and reviews by the module maintainers. Leave a comment on the issue explaining what you changed.

Do *not* set the status to "Fixed" even though you've provided a "fix" in the form of your merge request. The Fixed status is used by the module maintainers to indicate that either the merge request has been merged or the question asked in the issue has been sufficiently answered in the comments.

Typically, an issue will need several cycles of reviews and revisions before it is ready to be merged into the Drupal Commerce project.

## Reviewing merge requests

Reviewing merge requests created by other members of the community is another valuable way to contribute to Drupal Commerce.

You can comment on specific lines directly in the merge request on GitLab. To test a merge request locally, check out the
issue fork branch with the commands from **Show commands** on the issue page, or apply the merge request to a
Composer-managed site as a patch (see [Patching](./installation.md#patching)). Download the diff to a file in your
project rather than referencing the merge request URL directly, as its content changes with every push:

```bash
mkdir -p patches
curl -o patches/commerce-2276369.diff https://git.drupalcode.org/project/commerce/-/merge_requests/123.diff
```

```json
"extra": {
    "patches": {
        "drupal/commerce": {
            "2276369: Fix product form notice": "patches/commerce-2276369.diff"
        }
    }
}
```

Once you've tested a merge request, leave a comment on the issue. If it worked for you, you can change the issue status to "Reviewed & tested by the community". If it didn't, change the issue status to "Needs work". Provide as many details as possible to help developers reproduce your results and figure out how to fix the code.

!!! tip "How to get a Commerce issue committed"
    * Up-to-date issue summary
    * Complete tests
        * If it is a bug report, tests are more important than the fix, they guarantee both that the bug exists and that the fix actually fixes it.
        * Make sure to have full test coverage, lazy test writing will stall your issue.
    * Reports of the merge request working for others
        * Explain what you’re doing and what the merge request fixes for you
        * This is helpful even if one person has already RTBC’d
    * Small single focus for each merge request
        * Don’t try to fix 8 things at once. If needed, do the most common use case and can then cover additional use cases with follow-up issues.
        * A small merge request means the reviewer can easily grasp the whole change quickly. Large and mixed changes require a lot more review and can’t be done quickly as the reviewer has to spend a lot of time understanding the whole change. 4 little merge requests are faster than 1 big one.
    * Change Record entry
        * Write a change record entry if the change requires ANY manual work from the site owner or changes existing functionality that might be breaking or confusing.

That’s it! Happy contributing!

[the open issues]: https://www.drupal.org/project/issues/commerce?status=Open
[Drupal Slack channel]: https://www.drupal.org/slack
[merge requests]: https://www.drupal.org/docs/develop/git/using-gitlab-to-contribute-to-drupal
[git.drupalcode.org]: https://git.drupalcode.org/project/commerce
[Creating issue forks and merge requests]: https://www.drupal.org/docs/develop/git/using-gitlab-to-contribute-to-drupal/creating-issue-forks
[DDEV]: https://ddev.com/
[Git]: https://git-scm.com/
