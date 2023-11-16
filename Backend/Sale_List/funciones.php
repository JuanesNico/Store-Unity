<?php

define("HOY", date("Y-m-d"));

function agregarProductosVendidos($ventas){
    foreach($ventas as $venta){
        $venta->productos = obtenerProductosVendidos($venta->id);
    }
    return $ventas;
}

function obtenerVentas($fechaInicio, $fechaFin){
    $parametros = [];
    $sentencia  = "SELECT ventas.*
    FROM ventas";

    if(empty($fechaInicio) && empty($fechaFin)){
        $sentencia .= " WHERE DATE(ventas.fecha) = ? ";
        array_push($parametros, HOY);
        $ventas = select($sentencia, $parametros);
        return agregarProductosVendidos($ventas);
    }

    if(isset($fechaInicio) && isset($fechaFin)){
        $sentencia .= " WHERE DATE(ventas.fecha) >= ? AND DATE(ventas.fecha) <= ?";
        array_push($parametros, $fechaInicio, $fechaFin);
    }

    $ventas = select($sentencia, $parametros);
   
    return agregarProductosVendidos($ventas);
}

function obtenerProductosVendidos($idVenta){
    $sentencia = "SELECT productos_ventas.cantidad, productos_ventas.precio, productos.nombre,
    productos.compra
    FROM productos_ventas
    INNER JOIN productos ON productos.id = productos_ventas.idProducto
    WHERE idVenta  = ? ";
    return select($sentencia, [$idVenta]);
}

function registrarVenta($productos, $idUsuario, $idCliente, $total){
    $sentencia =  "INSERT INTO ventas (fecha, total, idUsuario, idCliente) VALUES (?,?,?,?)";
    $parametros = [date("Y-m-d H:i:s"), $total, $idUsuario, $idCliente];

    $resultadoVenta = insertar($sentencia, $parametros);
    if($resultadoVenta){
        $idVenta = obtenerUltimoIdVenta();
        $productosRegistrados = registrarProductosVenta($productos, $idVenta);
        return $resultadoVenta && $productosRegistrados;
    }
}

function registrarProductosVenta($productos, $idVenta){
    $sentencia = "INSERT INTO productos_ventas (cantidad, precio, idProducto, idVenta) VALUES (?,?,?,?)";
    foreach ($productos as $producto ) {
        $parametros = [$producto->cantidad, $producto->venta, $producto->id, $idVenta];
        insertar($sentencia, $parametros);
        descontarProductos($producto->id, $producto->cantidad);
    }
    return true;
}

function descontarProductos($idProducto, $cantidad){
    $sentencia =  "UPDATE productos SET existencia  = existencia - ? WHERE id = ?";
    $parametros = [$cantidad, $idProducto];
    return editar($sentencia, $parametros);
}

function obtenerUltimoIdVenta(){
    $sentencia  = "SELECT id FROM ventas ORDER BY id DESC LIMIT 1";
    return select($sentencia)[0]->id;
}

function calcularTotalLista($lista){
    $total = 0;
    foreach($lista as $producto){
        $total += floatval($producto->venta * $producto->cantidad);
    }
    return $total;
}

function agregarProductoALista($producto, $listaProductos){
    if($producto->existencia < 1) return $listaProductos;
    $producto->cantidad = 1;
    
    $existe = verificarSiEstaEnLista($producto->id, $listaProductos);

    if(!$existe){
        array_push($listaProductos, $producto);
    } else{
        $existenciaAlcanzada = verificarExistencia($producto->id, $listaProductos, $producto->existencia);
        
        if($existenciaAlcanzada)return $listaProductos;

        $listaProductos = agregarCantidad($producto->id, $listaProductos);
        }
        
    return $listaProductos;
    
}

function verificarExistencia($idProducto, $listaProductos, $existencia){
    foreach($listaProductos as $producto){
        if($producto->id == $idProducto){
           if($existencia <= $producto->cantidad) return true; 
        }
    }
    return false;
}

function verificarSiEstaEnLista($idProducto, $listaProductos){
    foreach($listaProductos as $producto){
        if($producto->id == $idProducto){
            return true;
        }
    }
    return false;
}

function agregarCantidad($idProducto, $listaProductos){
    foreach($listaProductos as $producto){
        if($producto->id == $idProducto){
            $producto->cantidad++;
        }
    }
    return $listaProductos;
}

function obtenerProductoPorCodigo($codigo){
    $sentencia = "SELECT * FROM productos WHERE codigo = ?";
    $producto = select($sentencia, [$codigo]);
    if($producto) return $producto[0];
    return [];
}

function obtenerNumeroProductos(){
    $sentencia = "SELECT IFNULL(SUM(existencia),0) AS total FROM productos";
    $fila = select($sentencia);
    if($fila) return $fila[0]->total;
}

function obtenerTotalInventario(){
    $sentencia = "SELECT IFNULL(SUM(existencia * venta),0) AS total FROM productos";
    $fila = select($sentencia);
    if($fila) return $fila[0]->total;
}

function calcularGananciaProductos(){
    $sentencia = "SELECT IFNULL(SUM(existencia*venta) - SUM(existencia*compra),0) AS total FROM productos";
    $fila = select($sentencia);
    if($fila) return $fila[0]->total;
}

function eliminarProducto($id){
    $sentencia = "DELETE FROM productos WHERE id = ?";
    return eliminar($sentencia, $id);
}

function editarProducto($codigo, $nombre, $compra, $venta, $existencia, $id){
    $sentencia = "UPDATE productos SET codigo = ?, nombre = ?, compra = ?, venta = ?, existencia = ? WHERE id = ?";
    $parametros = [$codigo, $nombre, $compra, $venta, $existencia, $id];
    return editar($sentencia, $parametros);
}

function obtenerProductoPorId($id){
    $sentencia = "SELECT * FROM productos WHERE id = ?";
    return select($sentencia, [$id])[0];
}

function obtenerProductos($busqueda = null){
    $parametros = [];
    $sentencia = "SELECT * FROM productos ";
    if(isset($busqueda)){
        $sentencia .= " WHERE nombre LIKE ? OR codigo LIKE ?";
        array_push($parametros, "%".$busqueda."%", "%".$busqueda."%"); 
    } 
    return select($sentencia, $parametros);
}

function registrarProducto($codigo, $nombre, $compra, $venta, $existencia){
    $sentencia = "INSERT INTO productos(codigo, nombre, compra, venta, existencia) VALUES (?,?,?,?,?)";
    $parametros = [$codigo, $nombre, $compra, $venta, $existencia];
    return insertar($sentencia, $parametros);
}

function select($sentencia, $parametros = []){
    $bd = conectarBaseDatos();
    $respuesta = $bd->prepare($sentencia);
    $respuesta->execute($parametros);
    return $respuesta->fetchAll();
}


function conectarBaseDatos() {
	$host = "localhost";
	$db   = "store_unity";
	$user = "root";
	$pass = "";
	$charset = 'utf8mb4';

	$options = [
	    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
	    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
	    \PDO::ATTR_EMULATE_PREPARES   => false,
	];
	$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
	try {
	     $pdo = new \PDO($dsn, $user, $pass, $options);
	     return $pdo;
	} catch (\PDOException $e) {
	     throw new \PDOException($e->getMessage(), (int)$e->getCode());
	}
}