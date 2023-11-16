<?php
include_once "funciones.php";
session_start();

$fechaInicio = (isset($_POST['inicio'])) ? $_POST['inicio'] : null;
$fechaFin = (isset($_POST['fin'])) ? $_POST['fin'] : null;

$ventas = obtenerVentas($fechaInicio, $fechaFin)

?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<div class="container">
    <br>
    <h2>Reporte de ventas : 
    </h2>
    <?php if(count($ventas) > 0){?>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Productos</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($ventas as $venta) {?>
                <tr>
                    <td><?= $venta->id;?></td>
                    <td><?= $venta->fecha;?></td>
                    <td>$<?= $venta->total;?></td>
                    <td>
                        <table class="table">
                            <?php foreach($venta->productos as $producto) {?>
                                <tr>
                                    <td><?= $producto->nombre;?></td>
                                    <td><?= $producto->cantidad;?></td>
                                    <td> X </td>
                                    <td>$<?=  $producto->precio ;?></td>
                                    <th>$<?= $producto->cantidad * $producto->precio ;?></th>
                                </tr>
                                <?php }?>
                        </table>
                    </td>
                </tr>
            <?php }?>
        </tbody>
    </table>
    <?php }?>
    <?php if(count($ventas) < 1){?>
        <div class="alert alert-warning mt-3" role="alert">
            <h1>No se han encontrado ventas</h1>
        </div>
    <?php }?>
</div>