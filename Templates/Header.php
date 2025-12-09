<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PapuShop</title>
    <link rel="stylesheet" href="Assets/Css/Style.css">
</head>
<body>
    <header>
        <nav class="navegador">
            <h1>Logo</h1>
            <ul class="menu">
                <li class="menu-item"><a href="Index.php">Inicio</a></li>
                <li class="menu-item">
                    <a href="VerCarrito.php">Carrito(<?php
                        echo (empty($_SESSION['CARRITO']))?0:count($_SESSION['CARRITO']);    
                        ?>)</a>
                </li>
                <li class="menu-item"><a href="Logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>