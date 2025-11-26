<?php
header("Content-Type: application/json; charset=utf-8");

echo json_encode([
    "success"   => true,
    "message"   => "API CodBarber funcionando correctamente",
    "version"   => "1.0",
    "timestamp" => date("Y-m-d H:i:s"),
    "endpoints" => [
        "login"         => "/login.php",
        "register"      => "/register.php",
        "barberias"     => "/barberias.php",
        "barberos"      => "/barberos.php?id_barberia={id}",
        "servicios"     => "/servicios.php?id_barberia={id}",
        "agendar"       => "/agendar.php",
        "mis_datos"     => "/mis_datos.php?id_cliente={id}",
        "mis_citas"     => "/mis_citas.php?id_cliente={id}",
    ]
]);
?>
