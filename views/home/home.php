<?php
global $pdo;
include "views/templates/header.php";
include "views/templates/navbar.php";
include "controllers/conexion.php";
include "controllers/cmostrar.php";

$mostrar= new mostrar($pdo);
$productos= $mostrar->getproducts($pdo);
//echo "<br>"; // Salto de línea en HTML
//print_r($productos); // Imprime el contenido de $productos
//echo "<br>"; // Otro salto de línea en HTML
?>
<h2>Productos destacados</h2>
<!-- <a href="<?php echo url_base;?>views/admin/admin.php">admin</a><br>
<a href="<?php echo url_base;?>views/registro/registro.php">Registro</a> -->
<div class="product-container">
    <?php foreach ($productos as $producto): ?>
        <div class="product-card">
            <img src="<?php echo url_base."controllers/uploads/".$producto['prod_imagen_url']; ?>" alt="<?php echo htmlspecialchars($producto['prod_nombre']); ?>" class="product-image">
            <div class="product-info">
                <h2 class="product-title"><?php echo htmlspecialchars($producto['prod_nombre']); ?></h2>
                <p class="product-description"><?php echo htmlspecialchars($producto['prod_descripcion']); ?></p>
                <p class="product-price">L.<?php echo number_format($producto['prod_precio'], 2); ?></p>
                <a href="#"><?php echo $producto['cat_nombre']; ?></a>
                <button class="product-button">Añadir al carrito</button>

            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include "views/templates/footer.php";?>
