const args = process.argv;
const url = args[2];
const puppeteer = require('puppeteer');

async function run() {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();
  
  await page.setCookie({
  "value": "|_with_go0gle_s0n9_k13m_h0p_b1ch_>|<}",
  "domain": "35.231.54.0",
  "name": "Secret"
})
  await page.goto(url);
  await page.screenshot({ path: 'capture.png' });
  browser.close();
}

run();
