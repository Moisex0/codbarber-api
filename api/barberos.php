<?php

header("Content-Type: application/json; charset=utf-8");

// Cargar BD
require_once(__DIR__ . "/bd.php");

// Obtener id_barberia y convertirlo a entero
$id_barberia = isset($_GET["id_barberia"]) ? intval($_GET["id_barberia"]) : 0;

// Validación
if ($id_barberia <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "id_barberia inválido o faltante"
    ]);
    exit();
}

// Consultar barberos
$rows = seleccionar("
    SELECT 
        id_barbero,
        nombre,
        telefono,
        correo
    FROM barbero
    WHERE id_barberia = $1
", [$id_barberia]);

// Detectar error real en la consulta
if ($rows === false) {
    echo json_encode([
        "success" => false,
        "message" => "Error al consultar barberos"
    ]);
    exit();
}

// Respuesta correcta SIEMPRE con array
echo json_encode([
    "success" => true,
    "barberos" => $rows ?: []
], JSON_UNESCAPED_UNICODE);

?>
