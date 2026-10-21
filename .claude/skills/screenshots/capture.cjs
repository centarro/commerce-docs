// Usage: NODE_PATH=<dir>/node_modules node capture.cjs <login-url> <shots.json> <out-dir>
//
// shots.json is a list of:
//   { "file": "store-add.png", "url": "/store/add/online",
//     "selector": "#edit-address-wrapper",        // optional: capture one element, else the viewport
//     "padding": 24,                              // optional, with selector: include surrounding px
//     "maxHeight": 300,                           // optional, with selector: cut the capture at this height
//     "fullPage": true,                           // optional, without selector
//     "viewport": { "width": 900, "height": 600 },   // optional: viewport for this shot (default 1440x900)
//     "actions": [{ "click": "#edit-customer-type-new" }, { "fill": "#edit-mail", "value": "x@y.z" },
//                 { "select": "#edit-size", "label": "Large" }, { "type": "text" },  // select by value or label; type into the focused element
//                 { "hover": "..." }, { "press": "ArrowDown" }, { "wait": 500 }] }
//   Autocomplete: fill, wait, then press ArrowDown and Enter to pick the first suggestion.
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

(async () => {
  const [loginUrl, shotsFile, outDir] = process.argv.slice(2);
  const shots = JSON.parse(fs.readFileSync(shotsFile, 'utf8'));
  const base = new URL(loginUrl).origin;
  const browser = await chromium.launch();
  const page = await browser.newPage({
    viewport: { width: 1440, height: 900 },
    deviceScaleFactor: 2,
    ignoreHTTPSErrors: true,
  });
  await page.goto(loginUrl);
  fs.mkdirSync(outDir, { recursive: true });

  for (const shot of shots) {
    await page.setViewportSize({ width: 1440, height: 900, ...shot.viewport });
    await page.goto(base + shot.url, { waitUntil: 'networkidle' });
    for (const a of shot.actions || []) {
      if (a.click) await page.click(a.click);
      if (a.fill) await page.fill(a.fill, a.value);
      if (a.select) await page.selectOption(a.select, a.label ? { label: a.label } : a.value);
      if (a.type) await page.keyboard.type(a.type);
      if (a.hover) await page.hover(a.hover);
      if (a.press) await page.keyboard.press(a.press);
      if (a.wait) await page.waitForTimeout(a.wait);
    }
    // Hide Drupal status messages (e.g. "Saved" from a previous shot).
    await page.addStyleTag({ content: '[data-drupal-messages] { display: none !important; }' });
    const file = path.join(outDir, shot.file);
    const opts = { path: file, animations: 'disabled', type: file.endsWith('.jpg') ? 'jpeg' : 'png' };
    if (shot.selector) {
      const p = shot.padding || 0;
      const el = page.locator(shot.selector).first();
      await el.scrollIntoViewIfNeeded();
      // boundingBox() is relative to the viewport: clip in the viewport when the
      // element fits (dialogs are position: fixed), else on the full page.
      const box = await el.boundingBox();
      const height = Math.min(box.height + 2 * p, shot.maxHeight || Infinity);
      const fits = box.y - p >= 0 && box.y + height <= page.viewportSize().height;
      const y = fits ? box.y - p : box.y - p + await page.evaluate(() => window.scrollY);
      await page.screenshot({ ...opts, fullPage: !fits, clip: { x: Math.max(box.x - p, 0), y: Math.max(y, 0), width: box.width + 2 * p, height } });
    }
    else await page.screenshot({ ...opts, fullPage: !!shot.fullPage });
    console.log('saved', file);
  }
  await browser.close();
})().catch((e) => { console.error(e); process.exit(1); });
