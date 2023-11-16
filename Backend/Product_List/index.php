<?php

@include 'config.php';

session_start();

if (!isset($_SESSION['store_name'])) {
   header('location:../Store/login_form.php');
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>

    <main>
        <div class="container py-4 text-center">
            <h2><span><?php echo $_SESSION['store_name'] ?></span></h2>

            <hr>

            <div class="row g-4">

                <div class="col-1">
                    <label for="search" class="col-form-label">Buscar: </label>
                </div>

                <div class="col-2">
                    <input type="text" name="search" id="search" class="form-control">
                </div>

                <div class="col-2"></div>

                <div class="col-2">
                    <a href="../Sale_List/reporte_ventas.php" class="form-control" style="text-decoration:none">Ver Ventas</a>
                </div>

                <div class="col-2">
                    <a href="../Sale/vender.php" class="form-control" style="text-decoration:none">Registrar Venta</a>
                </div>

                <div class="col-2">
                    <a href="../Product/register_form.php" class="form-control" style="text-decoration:none">Registrar Producto</a>
                </div>

                <div class="col-auto">
                    <a href="logout.php" class="form-control" style="text-decoration:none">Salir</a>
                </div>
                
            </div>

            <div class="row py-4">
                <div class="col">
                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                            <th class="sort asc">Código</th>
                            <th class="sort asc">Producto</th>
                            <th class="sort asc">Proveedor</th>
                            <th class="sort asc">Existencia</th>
                            <th class="sort asc">Precio</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </thead>

                        <tbody id="content">

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <label id="lbl-total"></label>
                </div>
            </div>
            
        </div>
    </main>

    <script>
        
        getData()

        document.getElementById("search").addEventListener("keyup", function() {
            getData()
        }, false)

        function getData() {
            let input = document.getElementById("search").value
            let url = "load.php"
            let formaData = new FormData()
            formaData.append('search', input)

            fetch(url, {
                    method: "POST",
                    body: formaData
                }).then(response => response.json())
                .then(data => {
                    content.innerHTML = data.data
                }).catch(err => console.log(err))
        }

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>