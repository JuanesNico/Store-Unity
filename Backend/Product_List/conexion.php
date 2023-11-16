<?php

function conectar(){
    $host="localhost";
    $user="root";
    $pass="";
    $bd="store_unity";

    $con=mysqli_connect($host, $user, $pass);

    mysqli_select_db($con, $bd);

    return $con;
}

?>