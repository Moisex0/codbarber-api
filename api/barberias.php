<?php

header("Content-Type: application/json; charset=utf-8");

// Cargar bd.php desde la MISMA carpeta /api :)
require_once(__DIR__ . "/bd.php");

// Consultar todas las barberías registradas :)
$rows = seleccionar("
    SELECT id_barberia, nombre, direccion
    FROM barberia
    ORDER BY nombre
", []);

// Validación por si falla o está vacío :)
if ($rows === false) {
    echo json_encode([
        "success" => false,
        "message" => "Error al consultar barberías"
    ]);
    exit();
}

// Respuesta en formato JSON :)
echo json_encode([
    "success" => true,
    "barberias" => $rows
]);

?>
