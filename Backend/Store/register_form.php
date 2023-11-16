<?php

@include 'config.php';

if (isset($_POST['submit'])) {

   $store = mysqli_real_escape_string($conn, $_POST['store']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = md5($_POST['password']);
   $cpass = md5($_POST['cpassword']);

   $select = " SELECT * FROM store_form WHERE email = '$email' && password = '$pass' ";

   $result = mysqli_query($conn, $select);

   if (mysqli_num_rows($result) > 0) {

      $error[] = 'Esta tienda ya existe';
   } else {

      if ($pass != $cpass) {
         $error[] = 'La contraseña no coincide';
      } else {
         $insert = "INSERT INTO store_form(store, email, password) VALUES('$store','$email','$pass')";
         mysqli_query($conn, $insert);
         header('location:login_form.php');
      }
   }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registro-Tienda</title>
   <link rel="stylesheet" href="../css/style.css">
</head>

<body>

   <div class="form-container">

      <form action="" method="post">
         <h3>Registro-Tienda</h3>

         <?php
         if (isset($error)) {
            foreach ($error as $error) {
               echo '<span class="error-msg">' . $error . '</span>';
            };
         };
         ?>

         <input type="text" name="store" required placeholder="Ingrese el nombre de su tienda">
         <input type="email" name="email" required placeholder="Ingrese su correo">
         <input type="password" name="password" required placeholder="Ingrese su contraseña">
         <input type="password" name="cpassword" required placeholder="Confirme su contraseña">
         <input type="submit" name="submit" value="Registre Su Tienda" class="form-btn">
         <p>¿Ya tiene una tienda? <a href="login_form.php">Ingrese A Su Tienda</a></p>
      </form>

   </div>

</body>

</html>