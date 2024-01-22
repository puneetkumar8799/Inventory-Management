<?php include_once "header.php" ?>

<style>
    body {
        background-color: #f5f5f5;
        overflow-x: hidden;
    }
</style>
<?php
if (empty($_SESSION['userdata'])) {
    echo '<meta http-equiv="refresh" content="0;url=listProducts.php">';
    exit();
}
if ($_SESSION['userdata'][0]['role']!='superAdmin') {
    echo '<meta http-equiv="refresh" content="0;url=listProducts.php">';
    exit();
}


$id = $_GET['id'];
global $conn;
$sql = "SELECT * FROM `tbl_users` where id=$id";
$result = $conn->query($sql);
$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
?>

<div class="card p-1 mr-5 ml-5 mt-2 mb-2">
    <div class="card-header">
        <h4>
            Edit User
        </h4>

    </div>
    <div class="card-body">
        <div class="form-group mb-5">
            <label for="name">Name</label>
            <input class="form-control" type="text" id="name" placeholder="Enter your name" value="<?= $data[0]["name"] ?>" />
        </div>
        <div class="form-group mb-5">
            <label for="password">Password</label>
            <input class="form-control" type="password" id="password" placeholder="Enter your password" value="<?= $data[0]["password"] ?>" />

        </div>
        <div class="form-group mb-5">
            <label for="email">Email</label>
            <input class="form-control" type="text" id="email" placeholder="Enter your email" value="<?= $data[0]["email"] ?>" />

        </div>
        <div class="form-group mb-5">
            <label for="role">Select the role</label>
            <select id="role" class="form-control">
                <option value="">Select the role</option>
                <option value="superAdmin" <?php if ($data[0]["role"] == "superAdmin") {
                                            echo "selected";
                                        } ?>>Super Admin</option>
                <option value="admin" <?php if ($data[0]["role"] == "admin") {
                                            echo "selected";
                                        } ?>>Admin</option>

            </select>
        </div>
        <div class="form-group">
            <button class="btn btn-primary btn-block mt-3" onclick="save()" type="button" name="submit">Update</button>
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
    function save() {
        var name = $('#name').val();
        var email = $('#email').val();
        var role = $('#role').val();
        var password = $('#password').val();
        $('.loader-container').css('display', 'flex')
        if (name !== "" && name != undefined && email != "" && email != undefined && role != "" && role != undefined && password != "" && password != undefined) {

            $.ajax({
                url: 'ajax.php', // Change the URL to the actual server-side script
                method: 'POST',
                data: {
                    action: 'editUser',
                    id: "<?= $id ?>",
                    name: name,
                    email: email,
                    role: role,
                    password: password
                },
                success: function(data) {
                    // Parse the JSON response
                    $('.loader-container').css('display', 'none')

                    var response = JSON.parse(data);

                    if (response.msg == "success") {
                        // Success toast
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'User added successfully!',
                        }).then((result) => {
                            // Reload the page after the user presses "OK"
                            if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                                location.reload();
                            }
                        });
                    } else {
                        // Failure toast
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to add user. ' + response.message,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Error toast
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while processing the request.',
                    });
                }

            });

        } else if (name == "" || name == undefined) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter user name',
            });
        } else if (email == "" || email == undefined) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter user email',
            });
        } else if (password == "" || password == undefined) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter user password',
            });
        } else if (role == "" || role == undefined) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please select user role',
            });
        }
    }
</script>
</body>

</html>