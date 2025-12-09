<?php 

include 'Global/Session.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: Login.php');
    exit;
}

include 'Global/Config.php';
include 'Global/Conexion.php';
include 'Carrito.php';
include 'Templates/Header.php';

?>


        <div class="pantalla-mensaje">
            Pantalla de Mensaje...
            <a href="" class="ver-carrito">Ver el carrito</a>
        </div>
        <div class="contenedor-productos">
            <?php

                $sql = "SELECT * FROM productos ORDER BY ID DESC LIMIT 8";

                $query = $pdo->prepare($sql);
                $query->execute();
                $listaProductos = $query->fetchAll(PDO::FETCH_ASSOC);

            ?>

            <?php foreach($listaProductos as $producto){ ?>
                <div class="producto">
                    <?php
                        // Normalizar la ruta de la imagen: quitar prefijo "../" si existe
                        $img = preg_replace('#^\.\./#', '', $producto['Imagen']);
                    ?>
                    <img src="<?php echo ($img); ?>"
                    alt="Imagen"
                    class="producto-img">
                    <div class="producto-info">
                        <h2 class="producto-titulo"><?php echo ($producto['NombreProducto']); ?></h2>
                        <p class="producto-precio"><?php echo '$' . number_format($producto['Precio'], 2, ',', '.'); ?></p>
                        <p class="producto-texto"><?php echo ($producto['DescripcionProducto']); ?></p>

                        <form action="Carrito.php" method="post">
                            <input type="hidden" name="ID" value="<?php echo openssl_encrypt($producto['ID'], COD, KEY); ?>">
                            <input type="hidden" name="Nombre" value="<?php echo openssl_encrypt($producto['NombreProducto'], COD, KEY); ?>">
                            <input type="hidden" name="Precio" value="<?php echo openssl_encrypt($producto['Precio'], COD, KEY); ?>">
                            <div class="producto-cantidad-grupo">
                                <label for="cantidad_<?php echo $producto['ID']; ?>" class="cantidad-label">Cantidad:</label>
                                <input type="number" id="cantidad_<?php echo $producto['ID']; ?>" name="CantidadInput" value="1" min="1" class="cantidad-input">
                            </div>
                            <input type="hidden" name="Cantidad" value="<?php echo openssl_encrypt(1, COD, KEY); ?>">
                            <button class="btn-agregar"
                                name="btnAccion"
                                value="Agregar"
                                type="submit"
                            >
                                Agregar al Carrito
                            </button>
                        </form>
                    </div>
                </div>
            <?php } ?>

        </div>
    </main>
 
<?php

include 'Templates/Footer.php'

?>