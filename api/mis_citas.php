<?php

header("Content-Type: application/json; charset=utf-8");
require_once(__DIR__ . "/bd.php");

// Obtener y validar id_cliente
$id = isset($_GET["id_cliente"]) ? intval($_GET["id_cliente"]) : 0;

if ($id <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "id_cliente inválido o faltante"
    ]);
    exit();
}

// Ejecutar la consulta
$rows = seleccionar("
    SELECT 
        c.id_cita,
        s.nombre AS servicio,
        c.fecha,
        c.hora,
        c.precio,
        c.estado
    FROM cita c
    LEFT JOIN servicio s ON c.id_servicio = s.id_servicio
    WHERE c.id_cliente = $1
    ORDER BY c.fecha DESC, c.hora DESC
", [$id]);

// Validar si hubo ERROR en la consulta
if ($rows === false) {
    echo json_encode([
        "success" => false,
        "message" => "Error al consultar citas"
    ]);
    exit();
}

// Respuesta exitosa (aunque la lista esté vacía)
echo json_encode([
    "success" => true,
    "citas" => $rows ?: []
], JSON_UNESCAPED_UNICODE);

?>
