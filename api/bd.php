<?php

// PRODUCCIÓN RENDER — MISMA BD QUE TU WEB
$host = "dpg-d4idlvf5r7bs73eg0h2g-a.oregon-postgres.render.com";
$port = "5432";
$dbname = "codbarber";
$user = "codbarber";
$password = "BNQhm48yvi0w6WzWuahl9H7e0tHJDVWh";

$conexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password sslmode=require");

// Verificar conexión
if (!$conexion) {
    echo "Error al conectar con la base de datos: " . pg_last_error();
    exit();
}

// Insertar
function insertar($query, $datos = []) {
    global $conexion;
    return pg_query_params($conexion, $query, $datos);
}

// Eliminar
function eliminar($query, $datos = []) {
    global $conexion;
    return pg_query_params($conexion, $query, $datos);
}

// Modificar
function modificar($query, $datos = []) {
    global $conexion;
    return pg_query_params($conexion, $query, $datos);
}

// Seleccionar
function seleccionar($query, $datos = []) {
    global $conexion;
    $result = pg_query_params($conexion, $query, $datos);
    if (!$result) {
        return [];
    }
    $data = pg_fetch_all($result);
    return $data ?: [];
}

?>
