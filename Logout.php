<?php

include 'Global/Session.php';

// Destruir la sesión
session_destroy();

// Redirigir al login
header('Location: Login.php');
exit;

?>
