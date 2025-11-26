<?php

// PRODUCCIÓN RENDER — MISMA BD QUE TU WEB
$host = "dpg-d4idlvf5r7bs73eg0h2g-a.oregon-postgres.render.com";
$port = "5432";
$dbname = "codbarber";
$user = "codbarber";
$password = "BNQhm48yvi0w6WzWuahl9H7e0tHJDVWh";

// Conexión con SSL obligatorio
$conexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password sslmode=require");

// Verificar conexión
if (!$conexion) {
    echo json_encode([
        "success" => false,
        "message" => "Error al conectar con la base de datos",
        "error" => pg_last_error()
    ]);
    exit();
}

// Insertar
function insertar($query, $datos = []) {
    global $conexion;
    $result = pg_query_params($conexion, $query, $datos);
    return $result !== false;
}

// Eliminar
function eliminar($query, $datos = []) {
    global $conexion;
    $result = pg_query_params($conexion, $query, $datos);
    return $result !== false;
}

// Modificar
function modificar($query, $datos = []) {
    global $conexion;
    $result = pg_query_params($conexion, $query, $datos);
    return $result !== false;
}

// Seleccionar
function seleccionar($query, $datos = []) {
    global $conexion;

    $result = pg_query_params($conexion, $query, $datos);

    if ($result === false) {
        return false; // ERROR REAL
    }

    $data = pg_fetch_all($result);
    return $data ?: []; // Sin resultados
}

?>
