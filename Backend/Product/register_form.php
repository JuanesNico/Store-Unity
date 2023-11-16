<?php

@include 'config.php';

session_start();

if (!isset($_SESSION['store_name'])) {
   header('location:../Store/login_form.php');
}

?>

<?php

@include 'config.php';

if (isset($_POST['submit'])) {

   $store = mysqli_real_escape_string($conn, $_POST['store']);
   $codigo = mysqli_real_escape_string($conn, $_POST['codigo']);
   $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
   $supplier = mysqli_real_escape_string($conn, $_POST['supplier']);
   $existencia = mysqli_real_escape_string($conn, $_POST['existencia']);
   $venta = mysqli_real_escape_string($conn, $_POST['venta']);

   $select = " SELECT * FROM productos WHERE supplier = '$supplier' && nombre = '$nombre' ";

   $result = mysqli_query($conn, $select);

   if (mysqli_num_rows($result) > 0) {

      $error[] = 'Este producto ya existe';
   } else {

      $insert = "INSERT INTO productos(store, codigo, nombre, supplier, existencia, venta) VALUES('$store','$codigo','$nombre','$supplier','$existencia', '$venta')";
      mysqli_query($conn, $insert);
      header('location:product_page.php');
   }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registro-Producto</title>
   <link rel="stylesheet" href="../css/style.css">
</head>

<body>

   <div class="form-container">

      <form action="" method="post">
         <h3>Registro-Producto</h3>

         <?php
         if (isset($error)) {
            foreach ($error as $error) {
               echo '<span class="error-msg">' . $error . '</span>';
            };
         }
         ?>
         <input type="hidden" name="store" value="<?php echo $_SESSION['store_name'] ?>" readonly>
         <input type="text" name="codigo" required placeholder="Ingrese el código de su producto">
         <input type="text" name="nombre" required placeholder="Ingrese el nombre de su producto">
         <input type="text" name="supplier" required placeholder="Ingrese el nombre del proveedor">
         <input type="text" name="existencia" required placeholder="Ingrese las existencias de su producto">
         <input type="text" name="venta" required placeholder="Ingrese el precio de su producto">
         <input type="submit" name="submit" value="Registre Su Producto" class="form-btn">
      </form>

   </div>

</body>

</html>