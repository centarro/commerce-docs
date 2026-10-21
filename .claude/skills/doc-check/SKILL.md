---
name: doc-check
description: Verify a Commerce docs page (or section) against the latest released Commerce source and fix what's wrong. Use when asked to check, verify, update or fill in a docs page, e.g. "/doc-check docs/v2/developer-guide/orders".
argument-hint: <page or directory under docs/>
---

# Check docs against the released Commerce source

The docs are a set of claims. Check every claim against the code of the latest
Commerce release, never against memory: many "well-known" facts turned out wrong.

Target: $ARGUMENTS (a `.md` file or a directory under `docs/`). Only touch `docs/v2/`
unless told otherwise; `docs/v1/` documents Commerce 1.x and is frozen.

## 1. Get the source (same version on every machine)

Never use a local Commerce checkout: it may be another branch or have local changes.
Clone the latest release tag into a temp directory (`SCRATCH=$(mktemp -d)`, or the session scratchpad):

```bash
project=commerce   # or commerce_shipping, commerce_stripe, ... for contrib pages
tag=$(curl -s "https://updates.drupal.org/release-history/$project/current" \
  | python3 -c "import re,sys,xml.etree.ElementTree as ET; print(next(r.findtext('version') for r in ET.parse(sys.stdin).iter('release') if r.findtext('status')=='published' and re.fullmatch(r'(\d+\.x-)?\d+\.\d+(\.\d+)?', r.findtext('version'))))")
git clone -q --depth 1 --branch "$tag" "https://git.drupalcode.org/project/$project.git" "$SCRATCH/$project-$tag"
```

Clone each contrib module the page covers the same way. Note every project and tag:
they go in the PR description.

## 2. List the claims

Read the target page(s) and list every checkable claim: class, interface, trait and
method names, service IDs, hooks and events, plugin types and attributes, config and
permission names, routes and UI paths (menu labels, form fields), Drush commands,
module names, version numbers, code examples.

## 3. Verify each claim

Grep the clones for each one and mark it **confirmed**, **wrong** (with the correct
value and the file:line proving it) or **not found**. For UI paths, check the
`*.links.menu.yml`, `*.routing.yml` and form classes. For code examples, check
signatures, namespaces and that plugins use PHP attributes, not annotations.

## 4. Fix

- Correct what's wrong; write empty or "TODO" sections from the source.
- Remove what no longer exists rather than guessing a replacement.
- Keep the existing tone, headings and anchors (other pages link to them).
- Keep the `v2/` URL paths: Commerce core links to them.
- Internal links point to `.md` files so the build validates them.
- Anything you can't settle from the source: leave it, and list it in the PR.

## 5. Build

```bash
python3 -m venv "$SCRATCH/venv" && "$SCRATCH/venv/bin/pip" install -q -r docs/requirements.txt
"$SCRATCH/venv/bin/mkdocs" build --strict --site-dir "$SCRATCH/site"
```

The build must pass with no warnings (`strict: true` fails on broken links and anchors).

## 6. Pull request

One branch and PR per page or theme. The PR description says:
- which pages were checked, against which projects and tags ("verified against Commerce 3.3.12");
- what was wrong and what was fixed, with source references;
- what is still open (claims not found or not verifiable).
