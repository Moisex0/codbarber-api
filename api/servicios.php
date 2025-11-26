<?php

header("Content-Type: application/json; charset=utf-8");

// Cargar BD
require_once(__DIR__ . "/bd.php");

// Obtener ID de barbería
$id = isset($_GET["id_barberia"]) ? intval($_GET["id_barberia"]) : 0;

// Validación
if ($id <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "Falta id_barberia o es inválido"
    ]);
    exit();
}

// Ejecutar consulta
$rows = seleccionar("
    SELECT id_servicio, nombre, descripcion, precio
    FROM servicio
    WHERE id_barberia = $1
", [$id]);

// Validar resultado
if ($rows === false) {
    echo json_encode([
        "success" => false,
        "message" => "Error al consultar la base de datos"
    ]);
    exit();
}

// Devolver JSON
echo json_encode([
    "success"   => true,
    "servicios" => $rows ?: []
], JSON_UNESCAPED_UNICODE);

?>
