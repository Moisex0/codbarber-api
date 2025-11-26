<?php

// Indicamos que la respuesta será JSON :)
header("Content-Type: application/json; charset=utf-8");

// Leer JSON crudo (Render a veces lo manda vacío si no viene bien)
$raw = file_get_contents("php://input");
echo $raw;
$data = json_decode($raw, true);

// Si viene vacío → evitar warnings
if (!is_array($data)) {
    echo json_encode([
        "success" => false,
        "message" => "No se recibió información válida."
    ]);
    exit();
}

// Conexión a la base de datos :)
require_once(__DIR__ . "/bd.php");

// Extraigo valores con validación básica :)
$correo      = trim($data["correo"] ?? "");
$contrasena  = $data["contrasena"] ?? "";

// Si falta correo o contraseña → error :)
if ($correo === "" || $contrasena === "") {
    echo json_encode([
        "success" => false,
        "message" => "Faltan datos"
    ]);
    exit();
}

// Busco al cliente por correo :)
$res = seleccionar("SELECT * FROM cliente WHERE correo = $1 LIMIT 1", [$correo]);

// Si no existe → correo incorrecto :)
if (!$res) {
    echo json_encode([
        "success" => false,
        "message" => "Correo incorrecto"
    ]);
    exit();
}

$user = $res[0]; // Cliente encontrado :)

// Validar que la columna contrasena exista (por seguridad)
if (!isset($user["contrasena"])) {
    echo json_encode([
        "success" => false,
        "message" => "El usuario no tiene contraseña registrada."
    ]);
    exit();
}

// Comparo contraseña usando password_verify :)
if (!password_verify($contrasena, $user["contrasena"])) {
    echo json_encode([
        "success" => false,
        "message" => "Contraseña incorrecta"
    ]);
    exit();
}

// Login correcto :)
echo json_encode([
    "success" => true,
    "message" => "Login correcto",
    "cliente" => [
        "id_cliente" => $user["id_cliente"],
        "nombre"     => $user["nombre"],
        "telefono"   => $user["telefono"],
        "correo"     => $user["correo"],
    ]
]);

?>
