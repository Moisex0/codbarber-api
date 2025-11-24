<?php

// Indicamos que la respuesta será JSON :)
header("Content-Type: application/json; charset=utf-8");

// Conexión a BD :)
require_once(__DIR__ . "/bd.php");

// Obtener ID del cliente :)
$id = $_GET["id_cliente"] ?? null;

// Validación de parámetro obligatorio :)
if (!$id) {
    echo json_encode([
        "success" => false,
        "message" => "Falta id_cliente"
    ]);
    exit();
}

// Consultar citas del cliente :)
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

// Si no hay citas, respondemos lista vacía (pero exitoso) :)
echo json_encode([
    "success" => true,
    "citas" => $rows ?: []   // Nunca enviamos NULL :)
], JSON_UNESCAPED_UNICODE);

?>
