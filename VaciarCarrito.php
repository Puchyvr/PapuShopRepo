<?php
include 'Global/Session.php';

// Vaciar el carrito
if (isset($_SESSION['CARRITO'])) {
    unset($_SESSION['CARRITO']);
}

header('Location: VerCarrito.php');
exit;

?>
