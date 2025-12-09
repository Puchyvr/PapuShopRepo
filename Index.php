<?php

include 'Global/Session.php';

// Si el usuario ya está logueado, redirigir según su rol
if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['rol'] === 'Administrador') {
        header('Location: Dashboard.php');
    } else {
        header('Location: Tienda.php');
    }
    exit;
}

// Si no está logueado, redirigir al login
header('Location: Login.php');
exit;

?>
