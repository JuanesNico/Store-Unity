<?php

@include 'config.php';

session_start();

if (isset($_POST['submit'])) {

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = md5($_POST['password']);

   $select = " SELECT * FROM store_form WHERE email = '$email' && password = '$pass' ";

   $result = mysqli_query($conn, $select);

   if (mysqli_num_rows($result) > 0) {

      $row = mysqli_fetch_array($result);

      $_SESSION['store_name'] = $row['store'];
      header('location:store_page.php');
   } else {
      $error[] = 'Correo o contraseña incorrectos';
   }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Ingreso-Tienda</title>
   <link rel="stylesheet" href="../css/style.css">
</head>

<body>

   <div class="form-container">

      <form action="" method="post">
         <h3>Ingreso-Tienda</h3>

         <?php
         if (isset($error)) {
            foreach ($error as $error) {
               echo '<span class="error-msg">' . $error . '</span>';
            };
         };
         ?>

         <input type="email" name="email" required placeholder="Ingrese su correo">
         <input type="password" name="password" required placeholder="Ingrese su contraseña">
         <input type="submit" name="submit" value="Ingrese A Su Tienda" class="form-btn">
         <p>¿No tiene una tienda? <a href="register_form.php">Registre Su Tienda</a></p>
      </form>

   </div>

</body>

</html>