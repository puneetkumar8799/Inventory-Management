<?php include_once "header.php";

?>

<style>
   body {
        background-color: #f5f5f5;
        overflow-x: hidden;
    }

    .custom-text-color {
        color: white !important;
    }
</style>
<?php
if (empty($_SESSION)) {
    echo '<meta http-equiv="refresh" content="0;url=listProducts.php">';
    exit();
}
if ($_SESSION['userdata'][0]['role'] != 'superAdmin') {
    echo '<meta http-equiv="refresh" content="0;url=listProducts.php">';
    exit();
}
global $conn;
$sql = 'SELECT * FROM `tbl_users`';
$result = $conn->query($sql);
$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

?>

<div class="card p-1 m-2 ml-5 mr-5 mb-2">
    <div class="block-area" id="responsiveTable">
        <div class="card-header header-elements-inline">
            <div class="row">
                <div class="col-md-10">

                    <h3 class="card-title">Manage Users</h3>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-primary btn-block mt-3 text-white" href="addUser.php" type="button"> <i class="fa fa-plus-circle custom-text-color mr-2"> </i> Add New User</a>

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
                                <th><strong>Password </strong></th>

                                <th><strong>Email </strong></th>
                                <th><strong>Role </strong></th>
                                <th><strong>Date Added </strong></th>

                                <th colspan="2"> Action </th>
                            </tr>
                        </thead>
                        <tbody <?php foreach ($data as $d) { ?> <tr>
                            <td><?= $d['id'] ?></td>
                            <td><?= $d['name'] ?></td>
                            <td><?= $d['password'] ?></td>
                            <td><?= $d['email'] ?></td>

                            <td><?= $d['role'] ?></td>
                            <td><?= $d['date_added'] ?></td>
                            <td><a href="editUser.php?id=<?= urlencode($d['id']) ?>" target='_blank'><i class='fa fa-edit mr-2 cursor-pointer'></i> </a></td>
                            <td><a href="#" onclick="deleteUser(<?= urlencode($d['id']) ?>)"> <i class='fa fa-trash mr-2 cursor-pointer'></i> </a></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="./assets/assets/jquery.min.js"></script>
<script src="./assets/assets/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    $(document).ready(function() {
        // Add show class when clicking on the nav-link
        $('.nav-item.dropdown .nav-link').click(function() {
            $('#myDropdown').toggleClass('show');
            $('.dropdown-menu').toggleClass('show');
        });

        // Remove show class when clicking outside the dropdown
        $(document).on('click', function(event) {
            if (!$(event.target).closest('.nav-item.dropdown').length) {
                $('#myDropdown').removeClass('show');
                $('.dropdown-menu').removeClass('show');
            }
        });
    });

    function deleteUser(id) {
        $('.loader-container').css('display', 'flex')

        $.ajax({
            url: 'ajax.php',
            method: 'POST',
            data: {
                action: 'deleteUser',
                id: id,

            },
            success: function(data) {
                $('.loader-container').css('display', 'none')

                var response = JSON.parse(data);

                if (response.msg == "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'User deleted successfully!',
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
</script>