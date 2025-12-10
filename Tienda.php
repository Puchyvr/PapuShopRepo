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

        <!-- Banner Carrusel Auto-desplegable -->
        <section class="banner-carousel">
            <div class="carousel-container">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="Assets/Img/carrusel1.webp" alt="Colección 1" class="carousel-image">
                    </div>
                    <div class="carousel-item">
                        <img src="Assets/Img/carrusel2.webp" alt="Colección 2" class="carousel-image">
                    </div>
                    <div class="carousel-item">
                        <img src="Assets/Img/carrusel3.webp" alt="Colección 3" class="carousel-image">
                    </div>
                    <div class="carousel-item">
                        <img src="Assets/Img/carrusel4.webp" alt="Colección 4" class="carousel-image">
                    </div>
                </div>
                
                <!-- Controles -->
                <button class="carousel-control prev" aria-label="Imagen anterior">❮</button>
                <button class="carousel-control next" aria-label="Imagen siguiente">❯</button>
                
                <!-- Indicadores -->
                <div class="carousel-indicators">
                    <span class="indicator active" data-slide="0"></span>
                    <span class="indicator" data-slide="1"></span>
                    <span class="indicator" data-slide="2"></span>
                    <span class="indicator" data-slide="3"></span>
                </div>
            </div>
        </section>

        <!-- Sección de Productos -->
        <section class="productos-seccion">
            <h2 class="productos-titulo">Últimos Productos</h2>
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
                        <div class="producto-body">
                            <div class="producto-info">
                                <h3 class="producto-titulo"><?php echo ($producto['NombreProducto']); ?></h3>
                                <p class="producto-precio"><?php echo '$' . number_format($producto['Precio'], 2, ',', '.'); ?></p>
                                <p class="producto-texto"><?php echo ($producto['DescripcionProducto']); ?></p>
                            </div>

                            <div class="producto-actions">
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
                    </div>
            <?php } ?>
            </div>
        </section>
    </main>

    <!-- Script para Carrusel Auto-desplegable -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = {
                currentIndex: 0,
                items: document.querySelectorAll('.carousel-item'),
                indicators: document.querySelectorAll('.indicator'),
                nextBtn: document.querySelector('.carousel-control.next'),
                prevBtn: document.querySelector('.carousel-control.prev'),
                autoPlayInterval: null,
                autoPlayDelay: 4000, // 4 segundos
                
                init() {
                    if (this.items.length === 0) return;
                    this.nextBtn?.addEventListener('click', () => this.next());
                    this.prevBtn?.addEventListener('click', () => this.prev());
                    this.indicators.forEach((indicator, index) => {
                        indicator.addEventListener('click', () => this.goTo(index));
                    });
                    this.autoPlay();
                },
                
                show(index) {
                    if (index >= this.items.length) this.currentIndex = 0;
                    if (index < 0) this.currentIndex = this.items.length - 1;
                    
                    this.items.forEach(item => item.classList.remove('active'));
                    this.indicators.forEach(ind => ind.classList.remove('active'));
                    
                    this.items[this.currentIndex].classList.add('active');
                    this.indicators[this.currentIndex].classList.add('active');
                },
                
                next() {
                    this.currentIndex++;
                    this.show(this.currentIndex);
                    this.resetAutoPlay();
                },
                
                prev() {
                    this.currentIndex--;
                    this.show(this.currentIndex);
                    this.resetAutoPlay();
                },
                
                goTo(index) {
                    this.currentIndex = index;
                    this.show(this.currentIndex);
                    this.resetAutoPlay();
                },
                
                autoPlay() {
                    this.autoPlayInterval = setInterval(() => {
                        this.currentIndex++;
                        this.show(this.currentIndex);
                    }, this.autoPlayDelay);
                },
                
                resetAutoPlay() {
                    clearInterval(this.autoPlayInterval);
                    this.autoPlay();
                }
            };
            
            carousel.init();
        });
    </script>
 
<?php

include 'Templates/Footer.php'

?>