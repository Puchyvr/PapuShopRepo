<?php

// Archivo centralizado para iniciar la sesión una sola vez
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
