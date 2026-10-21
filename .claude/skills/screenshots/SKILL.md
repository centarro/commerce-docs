---
name: screenshots
description: Retake the screenshots of a docs page from a fresh Commerce Kickstart site with the Gin admin theme. Use when asked to update, refresh or retake screenshots, e.g. "/screenshots docs/v2/user-guide/store-setup.md".
argument-hint: <page under docs/v2/>
---

# Retake a page's screenshots with Gin

Target: $ARGUMENTS. One page per run and per PR.

Files next to this skill:
- `capture.cjs`: logs in and captures a list of shots (format at the top of the file).
- `selectors.cjs`: lists the forms, fieldsets, panels and field wrappers of admin pages.
- `seed.php`: extra demo data the docs rely on (four "Centorium" stores, US/New York defaults).
- `shots/<section>--<page>.json`: the shot list of each page already done. If the target has
  one, start from it: rerunning it is usually all a refresh needs.

## 1. The site

A throwaway DDEV site with Commerce Kickstart and its demo content, outside this repo.
Reuse it if it exists (`ddev describe -j` in its folder); otherwise:

```bash
SITE=~/projects/commerce-screenshots
mkdir -p $SITE && cd $SITE
ddev config --project-type=drupal11 --docroot=web --php-version=8.3 --project-name=commerce-screenshots
ddev start -y
ddev composer create-project -n centarro/commerce-kickstart-project
# Install from the demo recipe directly. The installer applies only this recipe when
# "demo" is chosen; applying it on top of an existing install fails on config conflicts.
ddev drush si ../recipes/commerce_kickstart_demo -y --account-name=admin --account-pass=admin --site-name="Commerce Kickstart"
```

Then, every time (it's safe to rerun), copy `seed.php` into `$SITE` (the container can't
see this repo) and run `ddev drush php:script seed.php`. If the page needs more data
(orders, promotions, ...), add it to `seed.php` rather than clicking it together.

Check `ddev drush cget system.theme admin` says `gin`, and note the Commerce version
(`ddev composer show drupal/commerce`): it goes in the PR.

## 2. Playwright

```bash
SCRATCH=$(mktemp -d)   # or the session scratchpad
npm i --prefix "$SCRATCH/pw" playwright@1.48.2 && (cd "$SCRATCH/pw" && npx playwright install chromium)
export NODE_PATH="$SCRATCH/pw/node_modules"
LOGIN=$(cd $SITE && ddev drush uli --uri=https://commerce-screenshots.ddev.site)
```

The login link is single-use: get a fresh one for each run.

## 3. The shot list

For each image on the page:
1. Look at the old image (Read it) and the text around it: which screen, what framing
   (one panel or field, a whole form, the page), what state (a hovered button, a selected radio).
2. Find the admin URL (`*.routing.yml`, `*.links.*.yml`, or browse) and run `selectors.cjs`
   on it to pick a selector.
3. Write the shot to `shots/<section>--<page>.json`, keeping the old file name.

Lessons from the pages already done:
- Gin's toolbar and sidebar are sticky: a `fullPage` capture cuts them at the viewport.
  Use a taller `viewport` instead.
- Gin's forms are full width: capture form-only shots at a narrower `viewport` width (900),
  or the image is mostly empty space.
- Crop to what the text talks about (`selector` + `padding`, `maxHeight` to stop early).
- Gin turns boolean checkboxes into toggles, and moves some fields to the sidebar
  (e.g. "Make this the default store."). Fix the text when it says "check the box" or
  "at the bottom of the form".

## 4. Capture and review

```bash
node .claude/skills/screenshots/capture.cjs "$LOGIN" .claude/skills/screenshots/shots/<file>.json "$SCRATCH/out"
```

Read every new image and compare it with the old one before using it. Fix the shot list
and recapture until each one shows what the text describes. Then copy them over the old
files in the page's `images/` directory (same names, so the page needs no edit).

If a screen no longer exists or works differently, don't force a matching screenshot:
the text is outdated too. Say so, and suggest `/doc-check` for the page.

## 5. Pull request

One branch and PR per page, with the shot list. The description says the Commerce and
Kickstart versions, which images changed, and any text fixed for Gin.
