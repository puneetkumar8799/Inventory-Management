import http from 'http';
import cron from 'node-cron';
import { execSync } from 'child_process';

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
    cron.schedule('*/30 * * * *', () => {
        try {
            // Replace 'php' with the full path to your PHP executable if needed
            const stdout_ISB = execSync('php sendAlerts.php > logs.txt');
            console.log(`PHP script executed successfully. Output: ${stdout_ISB.toString()}`);
        } catch (error) {
            console.error(`Error: ${error.message}`);
        }
    });
});
