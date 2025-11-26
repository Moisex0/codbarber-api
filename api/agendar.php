<?php

header("Content-Type: application/json; charset=utf-8");
require_once(__DIR__ . "/bd.php");

// Leer JSON
$data = json_decode(file_get_contents("php://input"), true);

// Validar que sea arreglo
if (!is_array($data)) {
    echo json_encode([
        "success" => false,
        "message" => "No se recibió información válida"
    ]);
    exit();
}

// Sanitizar y convertir
$id_cliente  = intval($data["id_cliente"] ?? 0);
$id_barbero  = intval($data["id_barbero"] ?? 0); // opcional
$id_servicio = intval($data["id_servicio"] ?? 0);
$fecha       = trim($data["fecha"] ?? "");
$hora        = trim($data["hora"] ?? "");

// Validación obligatoria
if ($id_cliente <= 0 || $id_servicio <= 0 || $fecha === "" || $hora === "") {
    echo json_encode([
        "success" => false,
        "message" => "Faltan datos obligatorios"
    ]);
    exit();
}

// Validar formato de fecha
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $fecha)) {
    echo json_encode([
        "success" => false,
        "message" => "Fecha inválida"
    ]);
    exit();
}

// Validar formato de hora
if (!preg_match("/^\d{2}:\d{2}$/", $hora)) {
    echo json_encode([
        "success" => false,
        "message" => "Hora inválida"
    ]);
    exit();
}

// Obtener precio del servicio
$precio = seleccionar(
    "SELECT precio FROM servicio WHERE id_servicio = $1",
    [$id_servicio]
);

// Detectar error real en BD
if ($precio === false) {
    echo json_encode([
        "success" => false,
        "message" => "Error al consultar el servicio"
    ]);
    exit();
}

if (empty($precio)) {
    echo json_encode([
        "success" => false,
        "message" => "Servicio inválido"
    ]);
    exit();
}

$precio_final = $precio[0]["precio"];

// Insertar cita
$sql = "
INSERT INTO cita (id_cliente, id_barbero, id_servicio, fecha, hora, precio, estado)
VALUES ($1, $2, $3, $4, $5, $6, 'pendiente')
RETURNING id_cita
";

$cita = seleccionar($sql, [
    $id_cliente,
    $id_barbero > 0 ? $id_barbero : null, 
    $id_servicio,
    $fecha,
    $hora,
    $precio_final
]);

// VALIDACIÓN de error real
if ($cita === false) {
    echo json_encode([
        "success" => false,
        "message" => "Error al crear la cita"
    ]);
    exit();
}

// Respuesta OK
echo json_encode([
    "success" => true,
    "message" => "Cita creada correctamente :)",
    "id_cita" => $cita[0]["id_cita"]
], JSON_UNESCAPED_UNICODE);

?>
