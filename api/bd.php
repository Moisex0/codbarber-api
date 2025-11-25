<?php

// Detectar si estamos en Render (producción)
if (getenv("RENDER")) {

    // Variables estándar de Render PostgreSQL
    $host = getenv("PGHOST");
    $port = getenv("PGPORT");
    $db   = getenv("PGDATABASE");
    $user = getenv("PGUSER");
    $pass = getenv("PGPASSWORD");

    // Conexión en Render
    $conexion = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass");

} else {

    // -------------------------------------------
    // MODO LOCAL (XAMPP o Docker local)
    // -------------------------------------------
    $conexion = pg_connect("host=localhost port=5432 dbname=codbarber user=postgres password=msh79000");
}

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
