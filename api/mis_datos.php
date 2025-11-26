<?php

header("Content-Type: application/json; charset=utf-8");
require_once(__DIR__ . "/bd.php");

// Obtener id_cliente y convertir a entero
$id = isset($_GET["id_cliente"]) ? intval($_GET["id_cliente"]) : 0;

// Validación
if ($id <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "id_cliente inválido o faltante"
    ]);
    exit();
}

// Consultar datos del cliente
$usuario = seleccionar("
    SELECT nombre, correo, telefono
    FROM cliente
    WHERE id_cliente = $1
", [$id]);

// Validar existencia
if ($usuario === false) {
    echo json_encode([
        "success" => false,
        "message" => "Error al consultar la base de datos"
    ]);
    exit();
}

if (empty($usuario)) {
    echo json_encode([
        "success" => false,
        "message" => "Cliente no encontrado"
    ]);
    exit();
}

// Respuesta correcta
echo json_encode([
    "success" => true,
    "usuario" => $usuario[0]
], JSON_UNESCAPED_UNICODE);

?>
