<?php include_once "header.php";

if (empty($_SESSION)) {
    echo '<meta http-equiv="refresh" content="0;url=listProducts.php">';
    exit();
}

function getAllProducts()
{
    global $conn;
    $user_id = $_SESSION['userdata'][0]['id'];
    $role = $_SESSION['userdata'][0]['role'];
    if ($role == 'superAdmin') {

        $sql = "SELECT * FROM tbl_products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $products = array();
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            return $products;
        } else {
            return array();
        }
    } else if ($role == 'admin') {
        $sql = "SELECT * FROM tbl_products where added_by =$user_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $products = array();
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            return $products;
        } else {
            return array();
        }
    }
}

$products = getAllProducts();
?>
<style>
    body {
        overflow-x: hidden;
    }
    .toast-container {
        position: fixed;
        top: 10%;
        right: 60%;
        /* Change from bottom to top */
        max-width: 300px;
        z-index: 9999;
    }

    .toast {
        background-color: lightcoral;
        color: whitesmoke;
        padding: 100px;
        border-radius: 5px;
        margin-bottom: 10px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .toast.show {
        opacity: 1;
    }
</style>
<div class="toast-container" id="toastContainer"></div>

<div class="card p-1 m-2 ml-5 mr-5 mb-2">
    <div class="block-area" id="responsiveTable">
        <div class="card-header header-elements-inline">
            <div class="row">
                <div class="col-md-8">

                    <h3 class="card-title">Manage Products</h3>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-primary btn-block mt-3 text-white" href="listProducts.php" type="button"> <i class="fa fa-th-list custom-text-color mr-2"> </i> Market</a>

                </div>
                <div class="col-md-2">
                    <a class="btn btn-primary btn-block mt-3 text-white" href="addProducts.php" type="button"> <i class="fa fa-plus-circle custom-text-color mr-2"> </i> Add New Product</a>

                </div>
            </div>
        </div>
        <div class='card-body '>
            <div class="row">
                <div class="col-md-12">

                    <table class="table" id='tag_data'>
                        <thead>
                            <tr>
                                <th><strong>Id</strong></th>

                                <th><strong>Name </strong></th>
                                <!-- <th><strong>Mileage </strong></th> -->

                                <th><strong>Variant </strong></th>
                                <th><strong>Color </strong></th>
                                <th><strong>Price</strong></th>
                                <th><strong>Car</strong></th>
                                <th><strong>Count</strong></th>
                                <th colspan="2"> Action </th>
                            </tr>
                        </thead>
                        <tbody
                        <?php if(!empty($products)): ?>
                        <?php foreach ($products as $d) { ?> <tr>
                            <td><?= $d['id'] ?></td>
                            <td><?= $d['name'] ?></td>
                            <td><?= $d['variant'] ?></td>
                            <td><?= $d['color'] ?></td>
                            <td>$<?= $d['price'] ?></td>
                            <td><?= $d['transmission'] ?></td>
                            <td><?= $d['count'] ?></td>
                            <td><a href="editUserProduct.php?productID=<?= urlencode($d['id']) ?>" target='_blank'><i class='fa fa-edit mr-2 cursor-pointer'></i> </a></td>
                            <td><a href="#" onclick="deleteProduct(<?= urlencode($d['id']) ?>)"> <i class='fa fa-trash mr-2 cursor-pointer'></i> </a></td>
                            </tr>
                        <?php } ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9">
                                    <?php echo "No product found"; ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // $(document).ready(function() {
    //     // Add show class when clicking on the nav-link
    //     $('.nav-item.dropdown .nav-link').click(function() {
    //         $('#myDropdown').toggleClass('show');
    //         $('.dropdown-menu').toggleClass('show');
    //     });

    //     // Remove show class when clicking outside the dropdown
    //     $(document).on('click', function(event) {
    //         if (!$(event.target).closest('.nav-item.dropdown').length) {
    //             $('#myDropdown').removeClass('show');
    //             $('.dropdown-menu').removeClass('show');
    //         }
    //     });
    // });
    
    function deleteProduct(id) {
        $('.loader-container').css('display', 'flex')

        $.ajax({
            url: 'ajax.php',
            method: 'POST',
            data: {
                action: 'deleteProduct',
                id: id,

            },
            success: function(data) {
                $('.loader-container').css('display', 'none')

                var response = JSON.parse(data);

                if (response.msg == "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Product deleted successfully!',
                    }).then((result) => {
                        if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete user. ' + response.message,
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing the request.',
                });
            }
        });
    }

    function displayToast(message) {
        const toastContainer = document.getElementById('toastContainer');

        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.innerText = message;

        toastContainer.appendChild(toast);
        toast.offsetWidth;
        toast.classList.add('show');
        // setTimeout(() => {
        //     toast.classList.remove('show');
        //     setTimeout(() => {
        //         toast.remove();
        //     }, 300);
        // }, 1000);
    }

    </script>