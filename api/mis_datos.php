<?php
// Indicamos que la respuesta será JSON :)
header("Content-Type: application/json; charset=utf-8");

// Conexión segura :)
require_once(__DIR__ . "/bd.php");

// Obtener id_cliente enviado por GET :)
$id = $_GET["id_cliente"] ?? null;

// Validar parámetro :)
if (!$id) {
    echo json_encode([
        "success" => false,
        "message" => "Falta id_cliente"
    ]);
    exit();
}

// Buscar datos del cliente :)
$usuario = seleccionar("
    SELECT nombre, correo, telefono
    FROM cliente
    WHERE id_cliente = $1
", [$id]);

// Si no existe :)
if (!$usuario) {
    echo json_encode([
        "success" => false,
        "message" => "Cliente no encontrado"
    ]);
    exit();
}

// Respuesta correcta :)
echo json_encode([
    "success" => true,
    "usuario" => $usuario[0]
], JSON_UNESCAPED_UNICODE);

?>
