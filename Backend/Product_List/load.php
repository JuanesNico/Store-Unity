<?php

@include 'config.php';

session_start();

if (!isset($_SESSION['store_name'])) {
   header('location:../Store/login_form.php');
}

?>

<?php

require 'config.php';

$columns = ['id', 'store','codigo','nombre', 'supplier', 'existencia', 'venta'];

$table = "productos";

$id = 'id';

$search = isset($_POST['search']) ? $conn->real_escape_string($_POST['search']) : null;

$where = '';

if ($search != null) {
    $where = "WHERE (";

    $cont = count($columns);
    for ($i = 0; $i < $cont; $i++) {
        $where .= $columns[$i] . " LIKE '%" . $search . "%' OR ";
    }
    $where = substr_replace($where, "", -3);
    $where .= ")";
} 

$sql = "SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . "
FROM $table
$where";
$resultado = $conn->query($sql);
$num_rows = $resultado->num_rows;

$sqlTotal = "SELECT count($id) FROM $table ";
$resTotal = $conn->query($sqlTotal);
$row_total = $resTotal->fetch_array();
$totalRegistros = $row_total[0];

$output = [];
$output['totalRegistros'] = $totalRegistros;
$output['data'] = '';

$filtro = $_SESSION['store_name'];
$hayResultados = false;

while ($row = $resultado->fetch_assoc()) {
    if ($row['store'] == $filtro) {
        $hayResultados = true;
        $output['data'] .= '<tr>';
        $output['data'] .= '<td>' . $row['codigo'] . '</td>';
        $output['data'] .= '<td>' . $row['nombre'] . '</td>';
        $output['data'] .= '<td>' . $row['supplier'] . '</td>';
        $output['data'] .= '<td>' . $row['existencia'] . '</td>';
        $output['data'] .= '<td>' . $row['venta'] . '</td>';

        $output['data'] .= '<td><a class="btn btn-warning btn-sm" href="edit.php?id=' . $row['id'] . '">Editar</a></td>';
        $output['data'] .= "<td><a class='btn btn-danger btn-sm' href='delete.php?id=" . $row['id'] . "'>Eliminar</a></td>";
        $output['data'] .= '</tr>';
    }
}

if (!$hayResultados) {
    $output['data'] .= '<tr>';
    $output['data'] .= '<td colspan="7">Sin resultados</td>';
    $output['data'] .= '</tr>';
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);

?>