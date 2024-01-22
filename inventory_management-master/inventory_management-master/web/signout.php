<?php 

session_start(); 
unset($_SESSION['userRecords']);
session_destroy();
header("Location: listProducts.php");
exit();
?>