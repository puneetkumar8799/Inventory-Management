<?php include_once 'config.php'; ?>

<?php

if (isset($_POST['action'])) {
    $uploadsDirectory = 'assets/productImage/';
    if ($_POST['action'] == "addUser") {
        if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['role']) && isset($_POST['password'])) {
            global $conn;

            $name = $_POST['name'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $password = $_POST['password'];
            $currentDateTime = date("Y-m-d H:i:s");

            $sql = "INSERT INTO tbl_users (name, email, role, date_added,password) VALUES ('$name', '$email', '$role', '$currentDateTime','$password')";

            if ($conn->query($sql) === TRUE) {
                $conn->close();
                echo json_encode(["msg" => "success"]);
            } else {
                $conn->close();

                echo json_encode(["msg" => "error", "error" => $conn->error]);
            }
        }
    }
    if ($_POST['action'] == "editUser") {
        if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['role']) && isset($_POST['password'])) {
            global $conn;
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $password = $_POST['password'];
            $currentDateTime = date("Y-m-d H:i:s");

            $stmt = $conn->prepare("UPDATE tbl_users SET name=?, email=?, role=?, password=? WHERE id=?");
            $stmt->bind_param("ssssi", $name, $email, $role, $password, $id);

            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                echo json_encode(["msg" => "success"]);
            } else {
                $stmt->close();
                $conn->close();
                echo json_encode(["msg" => "error", "error" => $conn->error]);
            }
        }
    }
    if ($_POST['action'] == "deleteUser") {
        if (isset($_POST['id'])) {
            global $conn;
            $id = $_POST['id'];
            $stmt = $conn->prepare("DELETE FROM tbl_users WHERE id=?");
            $stmt->bind_param("i", $id); // Assuming 'id' is an integer

            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                echo json_encode(["msg" => "success"]);
            } else {
                $stmt->close();
                $conn->close();
                echo json_encode(["msg" => "error", "error" => $conn->error]);
            }
        }
    }
    if ($_POST['action'] == "verifyLogin") {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $stmt = $conn->prepare("SELECT id, name, email, role FROM tbl_users WHERE email=? AND password=?");
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $name, $email, $role);
                $userRecords = [];
                while ($stmt->fetch()) {
                    $userRecords[] = ['id' => $id, 'name' => $name, 'email' => $email, 'role' => $role];
                }

                $_SESSION['userdata'] = $userRecords;
                echo json_encode(["msg" => "success"]);
            } else {
                echo json_encode(["msg" => "error", "message" => "Invalid username or password"]);
            }

            $stmt->close();
        } else {
            echo json_encode(["msg" => "error", "message" => "Missing username or password"]);
        }
    }


    if ($_POST['action'] == 'addProduct') {
        global $conn;

        $name = $_POST['name'];
        $variant = $_POST['variant'];
        $color = $_POST['color'];
        $mileage = $_POST['mileage'];
        $price = $_POST['price'];
        $transmission = $_POST['transmission'];
        $count = $_POST['count'];
        $description = $_POST['description'];
        $added_by = $_SESSION['userdata'][0]['id'];
        $currentDateTime = date("Y-m-d H:i:s");

        // Handle file upload
        $file = $_FILES['file'];
        $fileTempPath = $file['tmp_name'];
        $fileName = $file['name'];
        $fileUploadPath = $uploadsDirectory . $fileName;

        move_uploaded_file($fileTempPath, $fileUploadPath);

        $fileUrl = 'http://localhost/inventory_management/web/' . $fileUploadPath;
        if ($conn->connect_error) {
            echo json_encode(['msg' => 'error', 'message' => $conn->error]);
            exit;
        }

        $sql = "INSERT INTO tbl_products (name, variant, color, mileage, price, transmission, count, file,description,added_by,date_added)
            VALUES ('$name', '$variant', '$color', '$mileage', '$price', '$transmission', '$count', '$fileUrl','$description','$added_by','$currentDateTime')";
        if ($conn->query($sql) === TRUE) {
            $conn->close();

            echo json_encode(['msg' => 'success']);
        } else {
            echo json_encode(['msg' => 'error', 'message' => $conn->error]);
            exit;
        }
    }
    if ($_POST['action'] == 'updateProduct') {
        global $conn;

        $productId = $_POST['id'];
        $name = $_POST['name'];
        $variant = $_POST['variant'];
        $color = $_POST['color'];
        $mileage = $_POST['mileage'];
        $price = $_POST['price'];
        $transmission = $_POST['transmission'];
        $count = $_POST['count'];
        $description = $_POST['description'];
        $added_by = $_SESSION['userdata'][0]['id'];
        $currentDateTime = date("Y-m-d H:i:s");

        // Handle file upload if a new file is selected
        if (!empty($_FILES['file']['name'])) {
            $file = $_FILES['file'];
            $fileTempPath = $file['tmp_name'];
            $fileName = $file['name'];
            $fileUploadPath = $uploadsDirectory . $fileName;

            move_uploaded_file($fileTempPath, $fileUploadPath);

            $fileUrl = 'http://localhost/inventory_management/web/' . $fileUploadPath;

            // Update the product with the new file
            $sql = "UPDATE tbl_products SET
                name = '$name',
                variant = '$variant',
                color = '$color',
                mileage = '$mileage',
                price = '$price',
                transmission = '$transmission',
                count = '$count',
                file = '$fileUrl',
                description = '$description',
                added_by = '$added_by',
                date_added = '$currentDateTime'
                WHERE id = '$productId'";
        } else {
            // Update the product without changing the file
            $sql = "UPDATE tbl_products SET
                name = '$name',
                variant = '$variant',
                color = '$color',
                mileage = '$mileage',
                price = '$price',
                transmission = '$transmission',
                count = '$count',
                description = '$description',
                added_by = '$added_by',
                date_added = '$currentDateTime'
                WHERE id = '$productId'";
        }

        if ($conn->query($sql) === TRUE) {
            $conn->close();
            echo json_encode(['msg' => 'success']);
        } else {
            echo json_encode(['msg' => 'error', 'message' => $conn->error]);
            exit;
        }
    }
    if ($_POST['action'] == 'getProductName') {
        $productId = $_POST['productId'];

        $stmt = $conn->prepare("SELECT name FROM tbl_products WHERE id=?");
        $stmt->bind_param("s", $productId); // Use "s" for a single string parameter
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($name);

            while ($stmt->fetch()) {
                $productName = $name;
            }

            echo json_encode(["msg" => "success", "productName" => $productName]);
        } else {
            echo json_encode(["msg" => "error", "message" => "Invalid product ID"]);
        }

        $stmt->close();
    }
    if ($_POST['action'] == 'getProductDetails') {
        $productId = $_POST['productId'];

        $stmt = $conn->prepare("SELECT name, file, price FROM tbl_products WHERE id=?");
        $stmt->bind_param("s", $productId); // Use "s" for a single string parameter
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($name, $file, $price);

            while ($stmt->fetch()) {
                $productName = $name;
                $productImage = $file;
                $productPrice = $price;
            }

            // Get count from the request
            $productCount = isset($_POST['productCount']) ? $_POST['productCount'] : 1;

            // Calculate total price for the current product
            $totalPrice = $productCount * $productPrice;

            echo json_encode([
                "msg" => "success",
                "productName" => $productName,
                "productImage" => $productImage,
                "productPrice" => $productPrice,
                "totalPrice" => $totalPrice,
            ]);
        } else {
            echo json_encode(["msg" => "error", "message" => "Invalid product ID"]);
        }

        $stmt->close();
    }
    if ($_POST['action'] == 'updateProductCounts') {
        //    echo '<pre>', print_r( $_POST['orderDetails']);exit;
        $orderDetails = $_POST['orderDetails'];
        $orderDetails = json_decode($orderDetails, true);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        try {

            foreach ($orderDetails as $orderDetail) {
                $productId = $orderDetail['productId'];
                $count = $orderDetail['count'];
                $sql = "UPDATE tbl_products SET count = count - $count WHERE id = $productId";
                $conn->query($sql);
            }
          
            // if ($returnCode == 0) {
            //     echo json_encode(['msg' => 'success']);
            // } else {
            //     echo json_encode(['msg' => 'fail']);
            // }
             echo json_encode(['msg' => 'success']);
        } catch (Exception $e) {
            echo json_encode(['msg' => 'error', 'message' => $conn->error]);
            exit;
        }
    }
    if ($_POST['action'] == "deleteProduct") {
        if (isset($_POST['id'])) {
            global $conn;
            $id = $_POST['id'];
            $stmt = $conn->prepare("DELETE FROM tbl_products WHERE id=?");
            $stmt->bind_param("i", $id); // Assuming 'id' is an integer

            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                echo json_encode(["msg" => "success"]);
            } else {
                $stmt->close();
                $conn->close();
                echo json_encode(["msg" => "error", "error" => $conn->error]);
            }
        }
    }
}
