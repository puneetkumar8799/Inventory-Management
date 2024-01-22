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
    <link href="./assets/assets/theme.css" rel="stylesheet">
    <!-- custom date picker -->
    <link rel="stylesheet" type="text/css" href="./assets/assets/daterangepicker.css" />
    <!-- custom date picker -->
    <link href="./assets/assets/enjoyhint3.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Flag Pickers -->
    <link href="./assets/assets/flags.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" integrity="sha512-uKQ39gEGiyUJl4AI6L+ekBdGKpGw4xJ55+xyJG7YFlJokPNYegn9KwQ3P8A7aFQAUtUsAQHep+d/lrGqrbPIDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
<style>
    body {
        overflow: hidden;
        /* background-image: url(./assets/bg-login-01.png); */
        background-repeat: no-repeat;
        background-size: 120%;
        background-position-x: -50%;
        background-position-y: 40%;

    }

    .float-right {
        float: right;
    }

    .container-fluid {
        width: auto;
    }

    .intro-text h2 {
        font-weight: 800 !important;
    }

    .login-div {
        display: flex;
        justify-content: right;
        height: 100vh;
        margin-right: 10%;
    }

    .login-container {
        background: #f0f1f1;
        border-radius: 35px;
        padding: 15px 25px 25px 25px;
        width: 27em;
    }

    .brand-logo {
        width: 120px;
    }

    .login-form {
        background: #ffffff;
        padding: 20px;
        border-radius: 20px;
    }

    .intro-text {
        font-size: medium;
        padding: 2px 3px 2px 14px;
    }

    .border-grey {
        border: solid 2px gray !important;
    }

    input:focus {
        border: solid 2px gray !important;
        box-shadow: 0 0 0 1px #808080 !important;
    }

    input[type="checkbox"],
    input[type="radio"] {
        position: absolute;
        right: 9000px;
    }

    input[type="checkbox"]+.label-text:before {
        content: "\f096";
        font-family: "FontAwesome";
        font-style: normal;
        font-weight: normal;
        font-variant: normal;
        text-transform: none;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        width: 1em;
        display: inline-block;
        margin-right: 5px;
    }

    .check-label {
        font-size: 16px;
    }

    input[type="checkbox"]:checked+.label-text:before {
        content: "\f14a";
        color: #e20c77;
        animation: effect 250ms ease-in;
    }

    input[type="checkbox"]:disabled+.label-text {
        color: #aaa;
    }

    input[type="checkbox"]:disabled+.label-text:before {
        content: "\f0c8";
        color: #ccc;
    }

    .btn-login {
        font-size: 18px;
        font-weight: 700;
        background-color: #e20c77;
        border-color: #e20c77;
    }

    .btn-info {
        background-color: #e20c77 !important;
        border-color: #e20c77 !important;
    }


    .btn-login:active {
        background: #e20c77 !important;
        border-color: #e20c77 !important;
    }

    .btn-login:focus {
        box-shadow: none !important;
    }

    @media screen and (max-width: 768px) {
        body {
            overflow: hidden;
            background-color: #001c2f;
            background-image: none;
        }

        .login-div {
            justify-content: center;
            height: 100vh;
            margin-right: 0;
        }

        .brand-logo {
            width: 100px;
        }

        .login-container {
            background: #f0f1f1;
            border-radius: 35px;
            padding: 15px 25px 25px 25px;
            width: 100%;
        }
    }
</style>
<div class="container-fluid login-div">
    <div class="vh-100 d-flex justify-content-center align-items-center">
        <div class="rounded-5 border-primary login-container">
            <div class="text-center mb-2 text-primary">
                <img class="brand-logo" src="./assets/bg-login-01.png" alt="Automotive Inventory" />
            </div>

            <form id="loginform">
                <input type="hidden" name="redirecturl" value="" />
                <div class="login-form">
                    <div class="intro-text">
                        <h2 class="">Hello again!</h2>
                        <p>Don't have account?</p>
                    </div>
                    <div class="mb-2">
                        <input placeholder="username" id="username" name="username" value="" type="text" class="form-control border-grey mb-1" aria-describedby="emailHelp" />
                        <input placeholder="Password" id="password" type="password" value="" name="password" class="form-control border-grey mb-1" id="password" />
                    </div>
                    <div class="helper-btns">
                        <div class="d-flex justify-content-between mb-1">
                            <div class="form-check">
                                <label class="check-label">
                                    <input type="checkbox" name="remember_me" value="1" />
                                    <span class="label-text" style="color: white;">Remember me</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <button type="button" class="btn btn-primary btn-block mt-3" onclick="verifyLogin()">Log in</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>

<script src="./assets/assets/jquery.min.js"></script>
<script src="./assets/assets/bootstrap.min.js"></script>
<script>
    function verifyLogin() {
        var username = document.getElementById("username").value;
        var password = document.getElementById("password").value;
        if (username !== "" && password !== "") {
            $.ajax({
                url: 'ajax.php',
                method: 'POST',
                data: {
                    action: 'verifyLogin',
                    email: username,
                    password: password
                },
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.msg == "success") {
                        window.location.href = 'listUser.php';

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Incorrect email or password' + response.message,
                        }).then((result) => {
                            if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                                location.reload();
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('An error occurred during the login request');
                }
            });
        } else {
            console.error('Please enter username and password');
        }
    }
</script>

</html>