<?php
$arguments = array_slice($argv, 1);
$arg1 = isset($arguments[0]) ? $arguments[0] : '1';
$servername = "localhost";
$username = "root";
$password = "QWERT!@#$%";
$dbname = "automotive_inventory";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM `tbl_users`";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $userID = $row['id'];
    $emails[$userID] = $row['email'];
}

$sql = "SELECT a.*, b.name as username FROM `tbl_products` AS a JOIN `tbl_users` AS b ON a.added_by = b.id where  a.count<10";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $productID = $row['id'];
    $productName[$productID]['name'] = $row['name'];
    $productName[$productID]['variant'] = $row['variant'];
    $productName[$productID]['color'] = $row['color'];
    $productName[$productID]['mileage'] = $row['mileage'];
    $productName[$productID]['price'] = $row['price'];
    $productName[$productID]['transmission'] = $row['transmission'];
    $productName[$productID]['description'] = $row['description'];
    $productName[$productID]['file'] = $row['file'];
    $productName[$productID]['added_by'] = $row['added_by'];

    $productName[$productID]['username'] = $row['username'];
}
foreach ($productName as $productID => $details) {
    $sendTo = $emails[$details['added_by']];
    $username = $details['username'];
    $productName = $details['name'];
    $userId = $details['added_by'];

    $checkAlertQuery = "SELECT *
        FROM `tbl_alerts`
        WHERE send_to = $userId AND product_id=$productID
        ORDER BY id DESC
        LIMIT 1";

    $existingAlertResult = $conn->query($checkAlertQuery);

    if ($existingAlertResult && $existingAlertResult->num_rows > 0) {
        while ($existingRow = $existingAlertResult->fetch_assoc()) {
            $id = $existingRow['id'];
            $date = $existingRow['alert_send'];

            // Assuming alert_send is in the format Ymd H
            $alertSend = DateTime::createFromFormat('Ymd H', $date);
            $currentDate = new DateTime();

            // Compare the difference in hours
            $hoursDifference = $currentDate->diff($alertSend)->h;

            if ($hoursDifference >= 2) {
                // Perform your actions when the current date and time is 2 or more hours greater than alert_send

                $node = "node C:\\xampp\\htdocs\inventory_management\\web\\sendingEmail\\index.js $sendTo $productName $username > log.txt ";
                echo "\n" . $node . "\n";
                exec($node);

                // Update the alert_send date to the current date
                $updateAlertQuery = "UPDATE tbl_alerts SET alert_send = NOW() WHERE id = $id";

                try {
                    $updateResult = $conn->query($updateAlertQuery);
                } catch (Exception $e) {
                    // Handle the update error if needed
                }
            } else {
                echo "Going to next iteration\n";
            }
        }
    } else {
        $node = "node C:\\xampp\\htdocs\inventory_management\\web\\sendingEmail\\index.js $sendTo $productName $username > log.txt ";
        echo "\n" . $node . "\n";
        exec($node);

        // Insert a new alert record with the current date
        $insertAlertQuery = "INSERT INTO tbl_alerts (alert_send, product_id, send_to) VALUES (NOW(), '$productID', '$userId')";

        try {
            $insertResult = $conn->query($insertAlertQuery);
        } catch (Exception $e) {
        }
    }
}
