<?php

include 'Global/Session.php';
include 'Global/Config.php';

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnAccion'])) {
    switch ($_POST['btnAccion']) {
        case 'Agregar':
            // Decrypt and validate incoming values
            $idDec = openssl_decrypt($_POST['ID'] ?? '', COD, KEY);
            $nombreDec = openssl_decrypt($_POST['Nombre'] ?? '', COD, KEY);
            $precioDec = openssl_decrypt($_POST['Precio'] ?? '', COD, KEY);
            $cantidadDec = openssl_decrypt($_POST['Cantidad'] ?? '', COD, KEY);

            // Obtener cantidad del input de usuario (si existe)
            $cantidadUsuario = isset($_POST['CantidadInput']) ? (int)$_POST['CantidadInput'] : 1;
            if ($cantidadUsuario < 1) {
                $cantidadUsuario = 1;
            }

            if (!is_numeric($idDec) || !is_numeric($precioDec) || !is_numeric($cantidadDec) || !is_string($nombreDec)) {
                $mensaje = 'Datos del producto inválidos.';
                // redirect back to tienda
                header('Location: Tienda.php?error=' . urlencode($mensaje));
                exit;
            }

            $ID = (int)$idDec;
            $NOMBRE = (string)$nombreDec;
            $PRECIO = (float)$precioDec;
            $CANTIDAD = $cantidadUsuario;

            $producto = [
                'ID' => $ID,
                'NOMBRE' => $NOMBRE,
                'CANTIDAD' => $CANTIDAD,
                'PRECIO' => $PRECIO
            ];

            if (!isset($_SESSION['CARRITO'])) {
                $_SESSION['CARRITO'] = [];
            }

            // Si el producto ya existe en el carrito, incrementar cantidad
            $encontrado = false;
            foreach ($_SESSION['CARRITO'] as &$item) {
                if ($item['ID'] === $ID) {
                    $item['CANTIDAD'] += $CANTIDAD;
                    $encontrado = true;
                    break;
                }
            }
            unset($item);

            if (!$encontrado) {
                $_SESSION['CARRITO'][] = $producto;
            }

            // Redirigir al carrito para ver los productos
            header('Location: VerCarrito.php');
            exit;
        break;
    }
}

// Si se accede por GET o sin acción válida, no hacer nada (este archivo puede ser incluido desde Tienda.php)

?>