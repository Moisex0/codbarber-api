<?php

header("Content-Type: application/json; charset=utf-8");

// Cargar bd.php desde la misma carpeta /api :)
require_once(__DIR__ . "/bd.php");

// Validar parámetro :)
$id = $_GET["id_barberia"] ?? null;

if (!$id) {
    echo json_encode([
        "success" => false,
        "message" => "Falta id_barberia"
    ]);
    exit();
}

// Consulta de barberos :)
$rows = seleccionar("
    SELECT 
        id_barbero,
        nombre,
        telefono,
        correo
    FROM barbero
    WHERE id_barberia = $1
", [$id]);

// Validación por si falla :)
if ($rows === false) {
    echo json_encode([
        "success" => false,
        "message" => "Error al consultar barberos"
    ]);
    exit();
}

// Respuesta JSON :)
echo json_encode([
    "success" => true,
    "barberos" => $rows
]);

?>
