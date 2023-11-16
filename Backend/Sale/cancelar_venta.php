<?php
session_start();
$_SESSION['lista'] = [];
header("location:../Product_List/index.php");
?>