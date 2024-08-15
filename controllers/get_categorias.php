<?php
include_once 'db.php';

class Categoria {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCategorias() {
        $query = "SELECT cat_id, cat_nombre FROM categorias";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$database = new Database();
$db = $database->getConnection();

$categoria = new Categoria($db);
$categorias = $categoria->getCategorias();

echo json_encode($categorias);
?>