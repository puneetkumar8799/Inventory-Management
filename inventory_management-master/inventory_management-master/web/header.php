<?php include_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Automotive Inventory</title>
    <meta name="theme-color" content="#ffffff">
    <script src="./assets/assets/config.navbar-vertical.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
    <link href="./assets/assets/perfect-scrollbar.css" rel="stylesheet">
    <link href="./assets/assets/flatpickr.min.css" rel="stylesheet">
    <link href="./assets/assets/theme.css" rel="stylesheet">



    <link href="./assets/assets/jquery.fancybox.min.css" rel="stylesheet">
    <link rel="icon" href="./assets/bg-white-01-01.png" type="image/png" />
    <!-- Custom CSS Skeleton-->
    <link href="./assets/assets/custom-mcp.css" rel="stylesheet">
    <script src="./assets/assets/jquery.min.js"></script>
    <script src="./assets/assets/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- custom date picker -->
    <link rel="stylesheet" type="text/css" href="./assets/assets/daterangepicker.css" />
    <!-- custom date picker -->
    <link href="./assets/assets/enjoyhint3.css" rel="stylesheet">
    <!-- Flag Pickers -->
    <link href="./assets/assets/flags.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" integrity="sha512-uKQ39gEGiyUJl4AI6L+ekBdGKpGw4xJ55+xyJG7YFlJokPNYegn9KwQ3P8A7aFQAUtUsAQHep+d/lrGqrbPIDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="./assets/assets/main/form_select2.js"></script>
    <script src="./assets/assets/main/select2.min.js"></script>
    <script src="./assets/assets/main/interactions.min.js"></script>
    <script src="./assets/assets/plugins/fancybox.min.js"></script>
    <script src="./assets/assets/plugins/uniform.min.js"></script>

    <style>
        .text-danger>p {
            color: #dc3545 !important;
        }

        * {
            font-family: "Nunito" !important;
            /* color: #001c2f; */
        }

        table thead tr th {
            color: #001c2f;
        }

        table tbody tr td {
            color: #001c2f;
        }

        table tbody tr td a {
            color: #001c2f;
        }

        label {
            color: #001c2f;
            font-size: medium;
        }

        .loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.5);
            /* Transparent white background */
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            /* Ensure it's on top of other elements */
        }

        .loader {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            /* Slate blue color */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<?php
function getEmptyProduct()
{
    global $conn;
    $userID = $_SESSION['userdata'][0]['id'];
    $query = "SELECT * FROM tbl_products WHERE added_by = $userID AND count < 5";
    $result = mysqli_query($conn, $query);
    ob_start();

    if ($result) {
        // Check if there are any products
        $rowCount = mysqli_num_rows($result);

        echo '<div class="nav-item dropdown">';
        echo '<a class="nav-link dropdown-toggle" href="#" id="productDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        echo '<i class="fa fa-cube fa-lg"></i>';
        echo '<span class="badge badge-danger badge-counter" style="position: relative; top: -8px;">' . $rowCount . '</span>';
        echo '</a>';

        if ($rowCount > 0) {
            echo '<div class="dropdown-menu alerts-dropdown" id="productDropdownMenu" aria-labelledby="productDropdown">';
            echo '<h6 class="dropdown-header">Need More</h6>';

            // Loop through the products and display them
            while ($row = mysqli_fetch_assoc($result)) {
                $productName = $row['name'];

                echo '<a href="#" class="dropdown-item"><i class="fa fa-cube mr-2"></i>' . $productName . '</a>';
            }
            echo '</div>';
        } else {
            // No products with count 0 found
            echo '<div class="dropdown-menu alerts-dropdown" id="productDropdownMenu" aria-labelledby="productDropdown">';
            echo '<span class="dropdown-item">No products with count 0</span>';
            echo '</div>';
        }

        echo '</div>';

        // Free the result set
        mysqli_free_result($result);
    } else {
        // Query execution failed
        echo '<div class="dropdown-menu alerts-dropdown" id="productDropdownMenu" aria-labelledby="productDropdown">';
        echo '<span class="dropdown-item">Error fetching products</span>';
        echo '</div>';
    }

    // Close the database connection

    return ob_get_clean();
}
if(isset($_SESSION['userdata'])){

    $productHTML = getEmptyProduct();
}
?>

<body>
    <nav class="navbar navbar-light navbar-glass navbar-top sticky-kit navbar-expand navbar-glass-shadow">
        <!-- Brand Logo on the Left -->
        <a class="navbar-brand mr-1 mr-sm-3" href="#" style="margin-left: 2%;">

            <img class="ml-2" src="./assets/bg-white-01-01.png" alt="Logo" width="80">
        </a>

        <!-- Centered Search Bar -->
        <form class="form-inline mx-auto">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        </form>

        <?php if (isset($_SESSION['userdata'])) { ?>
            <ul class="navbar-nav ml-auto" style="margin-right: 2%;">
                <?php if ($_SESSION['userdata'][0]['role'] == 'admin') {
                    echo $productHTML;
                } ?>

                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="./assets/placeholder.jpg" class="rounded-circle mr-2" height="34" alt="">
                        <?= $_SESSION['userdata'][0]['name'] ?>
                    </a>
                    <div class="dropdown-menu" id="userDropdownMenu" aria-labelledby="userDropdown">
                        <a href="#" class="dropdown-item"><i class="fa fa-user-plus mr-2"></i> My profile</a>
                        <?php if ($_SESSION['userdata'][0]['role'] == 'superAdmin') { ?>
                            <a href="listUser.php" class="dropdown-item"><i class="fa fa-users mr-2"></i> Users</a>
                        <?php } ?>
                        <a href="listUserProducts.php" class="dropdown-item"><i class="fa fa-bars mr-2"></i> Products</a>
                        <a href="signout.php" class="dropdown-item"><i class='fa fa-power-off mr-2'> </i> Sign Out</a>
                    </div>
                </li>
            </ul>
        <?php  } else { ?>
            <a href="login.php" class="btn btn-danger rounded-round"><i class="fa fa-sign-in mr-2"> </i> Login</a>
        <?php } ?>
    </nav>
    <div class="loader-container">
        <div class="loader"></div>
    </div>
    <script>

    </script>