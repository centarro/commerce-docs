"""Replaces {{ commerce_latest_release }} with the latest Commerce Core release.

The release is read from the drupal.org update feed at build time, so the docs
only need to be rebuilt (not edited) when a new version ships.
"""
import logging
import re
import urllib.request
import xml.etree.ElementTree as ET
from datetime import datetime, timezone

FEED = "https://updates.drupal.org/release-history/commerce/current"
PLACEHOLDER = "{{ commerce_latest_release }}"
FALLBACK = "[Commerce Core releases](https://www.drupal.org/project/commerce/releases){target=_blank}"

log = logging.getLogger("mkdocs.hooks.latest_release")
release = FALLBACK


def on_config(config):
    global release
    try:
        with urllib.request.urlopen(FEED, timeout=10) as response:
            feed = ET.fromstring(response.read())
        # Newest first. Only plain X.Y.Z counts: that skips dev/alpha/beta/rc
        # and the legacy 8.x-2.x branch (the feed has no version_extra element).
        latest = next(r for r in feed.iter("release")
                      if r.findtext("status") == "published"
                      and re.fullmatch(r"\d+\.\d+\.\d+", r.findtext("version")))
        date = datetime.fromtimestamp(int(latest.findtext("date")), timezone.utc)
        release = "[Commerce Core {}]({}){{target=_blank}}, released on {:%B} {}, {}".format(
            latest.findtext("version"), latest.findtext("release_link"), date, date.day, date.year)
    except Exception as e:
        log.warning("Could not fetch the latest Commerce release: %s", e)
    return config


def on_page_markdown(markdown, **kwargs):
    return markdown.replace(PLACEHOLDER, release)
