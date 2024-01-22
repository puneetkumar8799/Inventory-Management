const puppeteer = require('puppeteer-extra');
// const execSync = require("child_process").execSync;
// const crypto = require('crypto');
// const sendEmail = require('./mail');

const pluginStealth = require("puppeteer-extra-plugin-stealth");

// const settings = require('./settings');
// const os = require('os');

const moment = require("moment");
const path = require("path");

const filesystem = require("fs");
const { exit } = require("process");
puppeteer.use(pluginStealth());
// const emailAddress = 'naveed@neoc.com';
// const emailAddress2 = 'naveed.ahmed6453@gmail.com';
const arguments = process.argv.slice(2); // The first two elements are node and the script file path
console.log('Command-line arguments:', arguments);

const emailAddress2 = arguments[0] || 'naveed.ahmed6453@gmail.com';
const productName = arguments[1] || 'Gear Box';
const username = arguments[2] || 'User';

const cookiesPath = "C:/xampp/htdocs/inventory_management/web/sendingEmail/gmailInbox/cookies/inventorym96@gmail.com.txt";
// const encodedEmailAddress = encodeURIComponent(emailAddress + emailAddress2);
const composeURL = `https://mail.google.com/mail/u/0/#inbox`;

(async () => {

  try {
    console.log('Step 1 - Opening Ad URL', 1);

    //puppeteer.use(pluginStealth());

    const browser = await puppeteer.launch({
      'args': ['--disable-web-security', '--allow-http-screen-capture', '--allow-running-insecure-content', '--disable-features=site-per-process', '--no-sandbox'],
      ignoreHTTPSErrors: true,
      'headless': true,
      timeout: 10000,
      slowMo: 250, // slow down by 250ms
      devtools: false,
      executablePath: "C:\\Program Files\\Google\\Chrome\\Application\\chrome",

    });

    const page = await browser.newPage();
    await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36');
    await page.setViewport({ width: 1360, height: 700 });

    console.log("Opening url =" + composeURL);
    const previousSession = filesystem.existsSync(cookiesPath)
    console.log(cookiesPath);
    if (previousSession) {
      const content = filesystem.readFileSync(cookiesPath);
      const cookiesArr = JSON.parse(content);
      if (cookiesArr.length !== 0) {
        for (let cookie of cookiesArr) {
          await page.setCookie(cookie)
        }
        console.log('Cookies has been loaded in the browser')
      }
    }
    else {
      console.log("Cookies not found");
      // await browser.close();
    }
    await page.goto(composeURL, {
      waitUntil: 'networkidle2',
      timeout: 100000000
    });
    //await page.screenshot({path: '/opt/fb_puppeteer/scanning.png'});
    console.log("current url is " + page.url());
    await page.waitForTimeout(2000);

    page.goto("https://mail.google.com/mail/u/0/#inbox?compose=new", {
      waitUntil: 'networkidle2',
      timeout: 100000000
    });
    await page.waitForTimeout(2000);
    // var composeInput = await page.$$('table[role="presentation"] input')[2];
    // await composeInput.type(emailAddress2);

    await page.evaluate((selector) => {
      const composeInput = document.querySelectorAll(selector)[2];
      if (composeInput) {
        composeInput.click();
      }
    }, 'table[role="presentation"] input');

    await page.keyboard.type(emailAddress2);
    await page.type('form [name="subjectbox"]', "Refill your Stock");
    await page.evaluate((selector) => {
      const composeInput = document.querySelectorAll(selector)[0];
      if (composeInput) {
        composeInput.click();
      }
    }, 'div[aria-label="Message Body"]');
    await page.type('div[aria-label="Message Body"]', `Dear ${username}`);
    await page.keyboard.press('Enter');
    await page.type('div[aria-label="Message Body"]', 'We hope this message finds you well.')


    await page.keyboard.press('Enter');

    await page.type('div[aria-label="Message Body"]', `We would like to inform you that your product ${productName} is about to get out of stock`);
    await page.keyboard.press('Enter');
    await page.type('div[aria-label="Message Body"]', 'Please consider restocking these items to ensure availability for our customers.');
    await page.keyboard.press('Enter');
    await page.type('div[aria-label="Message Body"]', 'Thank you for your attention to this matter.');

    // Simulate pressing Ctrl + Enter to send the email
    await page.keyboard.down('Control');
    await page.keyboard.press('Enter');
    await page.keyboard.up('Control');
    await browser.close();
    process.exit(1);

  }
  catch (e) {
    console.log(e)
    await browser.close();
    process.exit(1);
  }
})();