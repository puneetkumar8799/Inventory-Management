<?php include_once "header.php" ?>

<style>
   body {
        background-color: #f5f5f5;
        overflow-x: hidden;
    }
</style>

<?php if (empty($_SESSION['userdata'])) {
    echo '<meta http-equiv="refresh" content="0;url=listProducts.php">';
    exit();
} 
if ($_SESSION['userdata'][0]['role']!='superAdmin') {
    echo '<meta http-equiv="refresh" content="0;url=listProducts.php">';
    exit();
}


?>
<div class="card p-1 mr-5 ml-5 mt-2 mb-2">
    <div class="card-header">
        <div class="row">
            <div class="col-md-10">

                <h3 class="card-title">Add User</h3>
            </div>
            <div class="col-md-2">
                <a class="btn btn-primary btn-block mt-3 text-white" href="listUser.php" type="button"> <i class="fa fa-th-list custom-text-color mr-2"> </i> List User</a>

            </div>
        </div>

    </div>
    <div class="card-body">
        <div class="form-group mb-5">
            <label for="name">Name</label>
            <input class="form-control" type="text" id="name" placeholder="Enter your name" />
        </div>
        <div class="form-group mb-5">
            <label for="password">Password</label>
            <input class="form-control" type="password" id="password" placeholder="Enter your password" />

        </div>
        <div class="form-group mb-5">
            <label for="email">Email</label>
            <input class="form-control" type="text" id="email" placeholder="Enter your email" />

        </div>
        <div class="form-group mb-5">
            <label for="role">Select the role</label>
            <select id="role" class="form-control">
                <option value="">Select the role</option>
                <option value="superAdmin">Super Admin</option>
                <option value="admin">Admin</option>

            </select>
        </div>
        <div class="form-group">
            <button class="btn btn-primary btn-block mt-3" onclick="save()" type="button" name="submit">Save</button>
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
                url: 'ajax.php',
                method: 'POST',
                data: {
                    action: 'addUser',
                    name: name,
                    email: email,
                    role: role,
                    password: password
                },
                success: function(data) {
                    $('.loader-container').css('display', 'none')

                    var response = JSON.parse(data);

                    if (response.msg == "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'User added successfully!',
                        }).then((result) => {
                            if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to add user. ' + response.message,
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