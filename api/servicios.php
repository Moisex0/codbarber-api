<?php

header("Content-Type: application/json; charset=utf-8");

// Cargar la BD desde la misma carpeta /api :)
require_once(__DIR__ . "/bd.php");

// Obtener ID de la barbería :)
$id = $_GET["id_barberia"] ?? null;

// Validar parámetro obligatorio :)
if (!$id) {
    echo json_encode([
        "success" => false,
        "message" => "Falta id_barberia"
    ]);
    exit();
}

// Consultar servicios de esa barbería :)
$rows = seleccionar("
    SELECT id_servicio, nombre, descripcion, precio
    FROM servicio
    WHERE id_barberia = $1
", [$id]);

// Respuesta en JSON :)
echo json_encode([
    "success"    => true,
    "servicios"  => $rows ?: []   // si no hay servicios, devolver arreglo vacío :)
], JSON_UNESCAPED_UNICODE);

?>
