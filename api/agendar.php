<?php

header("Content-Type: application/json; charset=utf-8");
require_once(__DIR__ . "/bd.php");

// Leer JSON enviado por la app móvil :)
$data = json_decode(file_get_contents("php://input"), true);

// Validar que se recibió un JSON válido
if (!is_array($data)) {
    echo json_encode([
        "success" => false,
        "message" => "No se recibió información válida"
    ]);
    exit();
}

// Guardar valores recibidos :)
$id_cliente  = $data["id_cliente"] ?? null;
$id_barbero  = $data["id_barbero"] ?? null; // opcional :)
$id_servicio = $data["id_servicio"] ?? null;
$fecha       = $data["fecha"] ?? null;
$hora        = $data["hora"] ?? null;

// Validar campos obligatorios :)
if (!$id_cliente || !$id_servicio || !$fecha || !$hora) {
    echo json_encode([
        "success" => false,
        "message" => "Faltan datos obligatorios"
    ]);
    exit();
}

// Obtener precio del servicio :)
$precio = seleccionar(
    "SELECT precio FROM servicio WHERE id_servicio=$1",
    [$id_servicio]
);

if (!$precio) {
    echo json_encode([
        "success" => false,
        "message" => "Servicio inválido"
    ]);
    exit();
}

$precio_final = $precio[0]["precio"];

// Insertar la cita en la BD :)
$sql = "
INSERT INTO cita (id_cliente, id_barbero, id_servicio, fecha, hora, precio, estado)
VALUES ($1, $2, $3, $4, $5, $6, 'pendiente')
RETURNING id_cita
";

$cita = seleccionar($sql, [
    $id_cliente,
    $id_barbero ?: null, // si viene vacío se guarda NULL :)
    $id_servicio,
    $fecha,
    $hora,
    $precio_final
]);

// Validación por si la inserción falla :)
if (!$cita) {
    echo json_encode([
        "success" => false,
        "message" => "Error al crear la cita"
    ]);
    exit();
}

// Respuesta final :)
echo json_encode([
    "success" => true,
    "message" => "Cita creada correctamente :)",
    "id_cita" => $cita[0]["id_cita"]
], JSON_UNESCAPED_UNICODE);

?>
