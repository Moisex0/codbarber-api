<?php

header("Content-Type: application/json; charset=utf-8");
require_once(__DIR__ . "/bd.php");

// Consultar barberías
$rows = seleccionar("
    SELECT id_barberia, nombre, direccion
    FROM barberia
    ORDER BY nombre
");

// Validar si hubo error en BD
if ($rows === false) {
    echo json_encode([
        "success" => false,
        "message" => "Error al consultar barberías"
    ]);
    exit();
}

// Respuesta correcta (si no hay barberías, lista vacía)
echo json_encode([
    "success"   => true,
    "barberias" => $rows ?: []
], JSON_UNESCAPED_UNICODE);

?>
