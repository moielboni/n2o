<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=n2o", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Conexión fallida: ' . $e->getMessage()]);
    exit;
}
?>
