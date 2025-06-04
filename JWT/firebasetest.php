<?php

require "config/index.php";

$deviceToken = $_POST["token"];

# enviarCorreo("dfraga547@gmail.com", "Firebase Ventas App Log-" . date("Y-m-d"), date("H:i:s") . "<hr><p><b>Token:</b> $deviceToken</p>");
# $fo = fopen("log.txt", "w");
# fwrite($fo, $deviceToken);
# fclose($fo);
# exit;

$message = array(
    "token" => $deviceToken,
    "notification" => array(
        "title" => "🚀 Notificación de Prueba",
        "body" => "Hola Mundo!"
    )
);

$firebase->send($message);

?>
