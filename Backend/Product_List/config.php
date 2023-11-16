<?php

$conn = mysqli_connect('localhost', 'root', '', 'store_unity');

if ($conn->connect_error) {
    die('Error De Conexión' . $conn->connect_error);
}

?>