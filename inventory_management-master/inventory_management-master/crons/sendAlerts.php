<?php
// require_once "./vendor/autoload.php";
require_once "C:/xampp/htdocs/inventory_management/vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer;

$mail->From = "inventorym96@gmail.com"; //enter your email 
$mail->FromName = "Inventory Manaagement";
$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->Username = 'inventorym96@gmail.com';
$mail->Password = 'vcrpyhoppbakshnk';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
$mail->Host = 'smtp.gmail.com';
 //$mail->Port = 587;
$mail->Port = 465;
// $mail->SMTPDebug =2;
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

$sql = "SELECT a.*, b.name as username FROM `tbl_products` AS a JOIN `tbl_users` AS b ON a.added_by = b.id where a.count<10";
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
    print_r($sendTo);
    $mail->addAddress($sendTo, $details['username']);

    $mail->isHTML(true);
    $mail->Body = "<p><strong>Dear {$details['username']},</strong></p>
    <br><br><br>
                   <p>We hope this message finds you well.</p>
                   <p>We would like to inform you that the following car product is currently out of stock:</p>
                   <br><br>
                   <ul>
                   ";
    $mail->Body .= "<li>
                      <strong>Product Name:</strong> {$details['name']}<br>
                      <strong>Variant:</strong> {$details['variant']}<br>
                      <strong>Color:</strong> {$details['color']}<br>
                      <strong>Mileage:</strong> {$details['mileage']}<br>
                      <strong>Price:</strong> {$details['price']}<br>
                      <strong>Transmission:</strong> {$details['transmission']}<br>
                      <strong>Description:</strong> {$details['description']}<br>
                      <strong>Image:</strong> <img src='{$details['file']}' alt='Product Image' style='max-width: 200px;'><br>
                  </li>";
    $mail->Body .= "</ul>
                                 <p>Please consider restocking these items to ensure availability for our customers.</p>
                                 <p>Thank you for your attention to this matter.</p>
                                 <p>Best regards,<br>Your Company Name</p>";
    $productId = $productID;
    $userId = $details['added_by'];
    $alertDatetime = date('Y-m-d H:i:s');

    // Check if an alert already exists for the specified conditions
    $checkAlertQuery = "SELECT * FROM `tbl_alerts` WHERE send_to = $userId AND product_id = $productId AND DATE(alert_send) = CURDATE() AND HOUR(alert_send) = HOUR(NOW())";

    $existingAlertResult = $conn->query($checkAlertQuery);

    if ($existingAlertResult && $existingAlertResult->num_rows > 0) {
        // Alert already exists, no need to send a new one
        echo "Alert already sent for today or this hour. No new alert sent.";
    } else {
        // Insert a new alert
        $insertAlertQuery = "INSERT INTO tbl_alerts (alert_send, product_id, send_to) VALUES ('$alertDatetime', '$productId', '$userId')";

        try {
            $insertResult = $conn->query($insertAlertQuery);

            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                echo "Message has been sent successfully";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
