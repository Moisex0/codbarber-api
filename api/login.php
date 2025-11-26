<?php

header("Content-Type: application/json; charset=utf-8");

// Leer JSON de entrada
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

// Validar JSON recibido
if (!is_array($data)) {
    echo json_encode([
        "success" => false,
        "message" => "No se recibió información válida."
    ]);
    exit();
}

require_once(__DIR__ . "/bd.php");

// Sanitizar datos
$correo     = trim($data["correo"] ?? "");
$contrasena = trim($data["contrasena"] ?? "");

// Validar campos
if ($correo === "" || $contrasena === "") {
    echo json_encode([
        "success" => false,
        "message" => "Faltan datos"
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

// Buscar usuario
$res = seleccionar("SELECT * FROM cliente WHERE correo = $1 LIMIT 1", [$correo]);

// Si hubo error en BD
if ($res === false) {
    echo json_encode([
        "success" => false,
        "message" => "Error al consultar la base de datos"
    ]);
    exit();
}

if (empty($res)) {
    echo json_encode([
        "success" => false,
        "message" => "Correo incorrecto"
    ]);
    exit();
}

$user = $res[0];

// Validar existencia de contraseña
if (!isset($user["contrasena"])) {
    echo json_encode([
        "success" => false,
        "message" => "El usuario no tiene contraseña registrada."
    ]);
    exit();
}

// Validar contraseña
if (!password_verify($contrasena, $user["contrasena"])) {
    echo json_encode([
        "success" => false,
        "message" => "Contraseña incorrecta"
    ]);
    exit();
}

// Login exitoso
echo json_encode([
    "success" => true,
    "message" => "Login correcto",
    "cliente" => [
        "id_cliente" => intval($user["id_cliente"]),
        "nombre"     => $user["nombre"],
        "telefono"   => $user["telefono"],
        "correo"     => $user["correo"]
    ]
], JSON_UNESCAPED_UNICODE);

?>
