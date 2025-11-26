<?php

// ============================
//        CORS PARA RENDER
// ============================
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 86400");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

// ============================
//  CONTENIDO REAL DEL SCRIPT
// ============================
header("Content-Type: application/json; charset=utf-8");
require_once(__DIR__ . "/bd.php");

// Recibir JSON
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

// Debug opcional:
// file_put_contents("debug_register.txt", $raw);

// Validar estructura JSON
if (!is_array($data)) {
    echo json_encode([
        "success" => false,
        "message" => "No se recibió información válida"
    ]);
    exit();
}

// Sanitizar campos
$nombre     = trim($data["nombre"] ?? "");
$telefono   = trim($data["telefono"] ?? "");
$correo     = trim($data["correo"] ?? "");
$contrasena = trim($data["contrasena"] ?? "");

// Validar obligatorios
if ($nombre === "" || $correo === "" || $contrasena === "") {
    echo json_encode([
        "success" => false,
        "message" => "Faltan datos para registrar al usuario"
    ]);
    exit();
}

// Validar correo
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "success" => false,
        "message" => "Correo inválido"
    ]);
    exit();
}

// Buscar si ya existe
$existe = seleccionar(
    "SELECT id_cliente FROM cliente WHERE correo = $1 LIMIT 1",
    [$correo]
);

if ($existe) {
    echo json_encode([
        "success" => false,
        "message" => "El correo ya está registrado"
    ]);
    exit();
}

// Encriptar contraseña
$hash = password_hash($contrasena, PASSWORD_DEFAULT);

// Insertar usuario
$sql = "INSERT INTO cliente (nombre, telefono, correo, contrasena)
        VALUES ($1, $2, $3, $4)
        RETURNING id_cliente";

$insert = seleccionar($sql, [$nombre, $telefono, $correo, $hash]);

if (!$insert || !isset($insert[0]["id_cliente"])) {
    echo json_encode([
        "success" => false,
        "message" => "Error al registrar usuario"
    ]);
    exit();
}

// Respuesta final
echo json_encode([
    "success"    => true,
    "message"    => "Usuario registrado correctamente",
    "id_cliente" => $insert[0]["id_cliente"]
], JSON_UNESCAPED_UNICODE);

?>
