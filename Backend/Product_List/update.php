<?php

include("conexion.php");
$con=conectar();

$id=$_POST['id'];
$store=$_POST['store'];
$codigo=$_POST['codigo'];
$nombre=$_POST['nombre'];
$supplier=$_POST['supplier'];
$existencia=$_POST['existencia'];
$venta=$_POST['venta'];

$sql="UPDATE productos SET store='$store',codigo='$codigo',nombre='$nombre',supplier='$supplier',existencia='$existencia',venta='$venta' WHERE id='$id'";

$query=mysqli_query($con,$sql);

    if($query){
        Header("Location: index.php");
    }

?>