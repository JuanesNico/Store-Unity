<?php
include_once "funciones.php";


session_start();
$productos = $_SESSION['lista'];
$total = calcularTotalLista($productos);

if(count($productos) === 0) {
    header("location:../Product_List/index.php");
    return;
};
$resultado = registrarVenta($productos, $idUsuario, $idCliente, $total);

if(!$resultado) {
    echo "Error al registrar la venta";
    return;
}

$_SESSION['lista'] = [];
$_SESSION['clienteVenta'] = "";

echo "
<script type='text/javascript'>
    window.location.href='../Product_List/index.php'
    alert('Venta realizada con éxito')
</script>";
//header("location: vender.php");

?>