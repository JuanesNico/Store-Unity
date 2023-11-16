<?php

@include 'config.php';

session_start();

if (!isset($_SESSION['store_name'])) {
   header('location:../Store/login_form.php');
}

?>

<?php

include("conexion.php");
$con = conectar();

$id = $_GET['id'];

$sql = "SELECT * FROM productos WHERE id='$id'";
$query = mysqli_query($con, $sql);

$row = mysqli_fetch_array($query);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/style.css" rel="stylesheet">
    <title>Editar-Producto</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="form-container">

        <form action="update.php" method="POST">
        <h3>Editar-Producto</h3>
            <input type="hidden" name="id" value="<?php echo $row['id']  ?>">

            <input type="hidden" name="store" value="<?php echo $_SESSION['store_name'] ?>">

            <input type="text" name="codigo" placeholder="Código" value="<?php echo $row['codigo']  ?>">

            <input type="text" name="nombre" placeholder="Producto" value="<?php echo $row['nombre']  ?>">

            <input type="text" name="supplier" placeholder="Proveedor" value="<?php echo $row['supplier']  ?>">

            <input type="text" name="existencia" placeholder="Existencias" value="<?php echo $row['existencia']  ?>">

            <input type="text" name="venta" placeholder="Precio" value="<?php echo $row['venta']  ?>">

            <input type="submit" class="form-btn" value="Editar Producto">
        </form>

    </div>

</body>

</html>