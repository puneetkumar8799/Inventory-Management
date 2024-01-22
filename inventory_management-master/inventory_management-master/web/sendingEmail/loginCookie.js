const puppeteerCore = require("puppeteer-extra");
const execSync = require("child_process").execSync;
const jsonfile = require("jsonfile");
const pluginStealth = require("puppeteer-extra-plugin-stealth");

// const settings = require('./settings');
// const os = require('os');

const moment = require("moment");
const path = require("path");
const filesystem = require("fs");
puppeteerCore.use(pluginStealth());
let login = true;
// let array = {
//     0: {
//         email: "testmalik396@gmail.com",
//         password: "QWERT!@#$%",
//     },
//     1: {
//         email: "josbuttler71@gmail.com",
//         password: "321KKK654",
//     },
// };
email="inventorym96@gmail.com";
password="ptcldsl100";
const url = "https://mail.google.com/mail/u/0/?ogbl#inbox";
if (!url) {
    throw new Error("Please provide URL as the first argument");
}
async function run(email, password) {
    var cookie_path = process.cwd();
    console.log(`cookie is save at : ${cookie_path}`);
    var cookiesFilePath = cookie_path + "/gmailInbox/cookies/" + email + ".txt";

    const browser = await puppeteerCore.launch({
        args: [
            "--disable-web-security",
            "--allow-http-screen-capture",
            "--allow-running-insecure-content",
            "--disable-features=site-per-process",
            "--no-sandbox",
            //'--proxy-server=socks5://37.209.208.253:43836'
        ],
        ignoreHTTPSErrors: true,
        headless: false,
        timeout: 60000,
        executablePath: "C:\\Program Files\\Google\\Chrome\\Application\\chrome",
        //executablePath: settings.allowedHosts.indexOf(os.hostname()) !== -1 ? '' : '/usr/bin/chromium-browser',

        //userDataDir: `1292`,
        // slowMo: 250, // slow down by 250ms
        //devtools: false
    });

    const page = await browser.newPage();
    await page.goto(url, {
        waitUntil: 'networkidle2',
        timeout: 100000000
    });

    await page.waitForTimeout(3000);
    const emailInput = await page.$('input[type="email"]');
    await emailInput.type(email);
    console.log('Waiting for any manual function to complete')
    await page.waitForTimeout(5000);
    const identifierNextButton = await page.$("#identifierNext");
    await identifierNextButton.click();
    console.log('Waiting for any manual function to complete')
    await page.waitForTimeout(4000);
    await page.waitForSelector("#password");
    await page.$eval(
        "#password input[data-initial-value]",
        (el, password) => (el.value = password),
        password
    );
    console.log('Waiting for manual function to complete')
    await page.waitForTimeout(5000);

    const identifierSubmitButton = await page.$("#passwordNext");


    await Promise.race([
        await identifierSubmitButton.click(),
        page.waitForNavigation(),
        new Promise((resolve) => setTimeout(resolve, 3000)),
    ]);
    console.log("waiting 3 minutes to make cookies and manual actions");
    let cookiesObject;
    await page.waitForTimeout(70000);
    // Save Session Cookies
    try{

         cookiesObject = await page.cookies();
    }catch(e){
        console.log(e)
    }
    if(cookiesObject){

        // Write cookies to temp file to be used in other profile pages
        jsonfile.writeFile(
            cookiesFilePath,
            cookiesObject,
            { spaces: 2 },
            function (err) {
                if (err) {
                    console.log("The file could not be written.", err);
                }
                
                console.log("Session has been successfully saved");
            }
        );
    }
    await browser.close();

}

async function runAll() {
    var stdout = execSync(`php ${__dirname}/getMails.php `, {
        stdio: "pipe",
    });
    var data1 = stdout.toString();
    var data2 = JSON.parse(data1);
    if (data2.status == "OK" && JSON.parse(data2.data).length > 0) {
        array_data = JSON.parse(data2.data);

       
        // for (const element of array_data) {
        //     const email = element.username;
        //     const password = element.password;
        //     console.log(element.username);
        //     console.log(element.password);
        //     await run(email, password);
        // }

        for (let key in array) {
            console.log("Key:", key);
            console.log("Email:", array[key]["email"]);
            console.log("Password:", array[key]["password"]);
            email = array[key]["email"];
            password = array[key]["password"];
            // id = array[key]["id"];
            await run(email, password);
        }
    } else {
        console.log("Database connection issue");
    }
}
run(email, password)
//runAll();
