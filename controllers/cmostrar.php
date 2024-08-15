<?php  
require_once './controllers/conexion.php';

class mostrar {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getProducts() {
        try {
            $sql = "SELECT * FROM productos
                    inner join categorias on productos.cat_id = categorias.cat_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("ERROR: No se pudo ejecutar la consulta. " . $e->getMessage());
        }
    }
}

// Crear una instancia de la clase y obtener los productos
//$mostrar = new Mostrar($pdo);
//$productos = $mostrar->getProducts();

// Para depuración: Imprimir los productos
/*foreach ($productos as $producto) {
    echo "ID: " . $producto['prod_id'] . " - Nombre: " . $producto['prod_nombre'] . "<br>";
}*/
?>