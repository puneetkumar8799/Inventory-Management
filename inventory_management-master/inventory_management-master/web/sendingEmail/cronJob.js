// import http from 'http';
// import cron from 'node-cron';
// import { execSync } from 'child_process';
const { execSync } = require('child_process');
const cron = require('node-cron');
const http = require('http');

const hostname = '127.0.0.1';
const port = 3002;

const server = http.createServer((req, res) => {
    res.statusCode = 200;
    res.setHeader('Content-type', 'text/plain');
    res.end('Hello world');
});

server.listen(port, hostname, () => {
    console.log(`Server running at http://${hostname}:${port}`);

    // Run every 30 minutes
    cron.schedule('* * * * *', () => {
        try {
            // Replace 'php' with the full path to your PHP executable if needed
            const stdout_ISB = execSync('php sendAlerts_scrap.php > logs.txt');
            console.log(`PHP script executed successfully. Output: ${stdout_ISB.toString()}`);
        } catch (error) {
            console.error(`Error: ${error.message}`);
        }
    });
});
