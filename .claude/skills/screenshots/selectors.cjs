// Usage: NODE_PATH=<dir>/node_modules node selectors.cjs <login-url> <path> [<path>...]
// Lists the forms, fieldsets, panels and field wrappers on each admin page, with sizes,
// to pick selectors for shots.json.
const { chromium } = require('playwright');
(async () => {
  const [login, ...urls] = process.argv.slice(2);
  const base = new URL(login).origin;
  const b = await chromium.launch(); const p = await b.newPage({ ignoreHTTPSErrors: true, viewport: { width: 1440, height: 900 } });
  await p.goto(login);
  for (const u of urls) {
    await p.goto(base + u, { waitUntil: 'networkidle' });
    console.log('\n## ' + u + '  [' + (await p.title()) + ']');
    console.log((await p.$$eval('main form, main fieldset, main details, main .panel, main .admin-list, main table, main [id^=edit-][id$=-wrapper], .gin-sticky-form-actions, main .form-actions, main .layout-region', els =>
      els.map(e => `${e.tagName.toLowerCase()}${e.id ? '#' + e.id : ''}.${[...e.classList].slice(0,3).join('.')}  "${(e.querySelector('legend,summary,h2,h3,label')||{}).textContent?.trim().slice(0,40) || ''}"  ${Math.round(e.getBoundingClientRect().width)}x${Math.round(e.getBoundingClientRect().height)}`))).join('\n'));
  }
  await b.close();
})();
