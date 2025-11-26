<?php

header("Content-Type: application/json; charset=utf-8");
require_once(__DIR__ . "/bd.php");

// Recibir JSON
$data = json_decode(file_get_contents("php://input"), true);

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

// Validar
if ($nombre === "" || $correo === "" || $contrasena === "") {
    echo json_encode([
        "success" => false,
        "message" => "Faltan datos para registrar al usuario"
    ]);
    exit();
}

// Validar formato de correo
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "success" => false,
        "message" => "Correo inválido"
    ]);
    exit();
}

// Buscar si existe el correo
$existe = seleccionar(
    "SELECT id_cliente FROM cliente WHERE correo = $1 LIMIT 1",
    [$correo]
);

// Si existe → detener registro
if ($existe && count($existe) > 0) {
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

// Validación
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
