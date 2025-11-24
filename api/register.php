<?php

// Indicamos que la respuesta será JSON :)
header("Content-Type: application/json; charset=utf-8");
require_once(__DIR__ . "/bd.php");

// Recibir datos JSON desde la app :)
$data = json_decode(file_get_contents("php://input"), true);

// Si la petición no trae JSON válido → evitar fallos
if (!is_array($data)) {
    echo json_encode([
        "success" => false,
        "message" => "No se recibió información válida"
    ]);
    exit();
}

// Asignación segura con validación básica :)
$nombre      = trim($data["nombre"] ?? "");
$telefono    = trim($data["telefono"] ?? "");
$correo      = trim($data["correo"] ?? "");
$contrasena  = $data["contrasena"] ?? "";

// Validar campos obligatorios :)
if ($nombre === "" || $correo === "" || $contrasena === "") {
    echo json_encode([
        "success" => false,
        "message" => "Faltan datos para registrar al usuario"
    ]);
    exit();
}

// Verificar si el correo ya existe :)
$existe = seleccionar(
    "SELECT id_cliente FROM cliente WHERE correo = $1 LIMIT 1",
    [$correo]
);

// Si ya está registrado → no permitir duplicado :)
if ($existe) {
    echo json_encode([
        "success" => false,
        "message" => "El correo ya está registrado"
    ]);
    exit();
}

// Encriptar contraseña :)
$hash = password_hash($contrasena, PASSWORD_DEFAULT);

// Insertar nuevo cliente :)
$sql = "INSERT INTO cliente (nombre, telefono, correo, contrasena)
        VALUES ($1, $2, $3, $4)
        RETURNING id_cliente";

$insert = seleccionar($sql, [$nombre, $telefono, $correo, $hash]);

// Validación extra por si falla la inserción
if (!$insert) {
    echo json_encode([
        "success" => false,
        "message" => "Error al registrar usuario"
    ]);
    exit();
}

// Respuesta final :)
echo json_encode([
    "success"     => true,
    "message"     => "Usuario registrado correctamente",
    "id_cliente"  => $insert[0]["id_cliente"]
], JSON_UNESCAPED_UNICODE);

?>
