<?php

$servidor = "mysql:dbname=" . DB . ";host=" . SERVIDOR;

try {
    $pdo = new PDO($servidor, USUARIO, PASSWORD, [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    ]);
    // Configurar modo de errores a excepción para facilitar el manejo
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Registrar el error para desarrollo/diagnóstico, no enviar salida a la página
    error_log('Error de conexión a la base de datos: ' . $e->getMessage());
    // Dejar $pdo definido en null para que los scripts puedan comprobarlo
    $pdo = null;
}
