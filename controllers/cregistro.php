<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $usu_nombre_usuario = $_POST['usu_nombre_usuario'] ?? null;
    $usu_correo = $_POST['usu_correo'] ?? null;
    $usu_contraseña = $_POST['usu_contraseña'] ?? null;
    $usu_nombre = $_POST['usu_nombre'] ?? null;
    $usu_apellido = $_POST['usu_apellido'] ?? null;
    $usu_direccion = $_POST['usu_direccion'] ?? null;
    $usu_telefono = $_POST['usu_telefono'] ?? null;
    
    // Validar los datos
    if (empty($usu_nombre_usuario) || empty($usu_correo) || empty($usu_contraseña)) {
        echo json_encode(['status' => 'error', 'message' => 'Nombre de usuario, correo electrónico y contraseña son obligatorios.']);
        exit;
    }

    try {
        // Insertar un nuevo usuario con permisos predeterminados (tipo_id = 1)
        $stmt = $pdo->prepare("INSERT INTO usuarios (usu_nombre_usuario, usu_correo, usu_contraseña, usu_nombre, usu_apellido, usu_direccion, usu_telefono, tipo_id) VALUES (:usu_nombre_usuario, :usu_correo, :usu_contraseña, :usu_nombre, :usu_apellido, :usu_direccion, :usu_telefono, 1)");
        $stmt->execute([
            ':usu_nombre_usuario' => $usu_nombre_usuario,
            ':usu_correo' => $usu_correo,
            ':usu_contraseña' => password_hash($usu_contraseña, PASSWORD_BCRYPT),
            ':usu_nombre' => $usu_nombre,
            ':usu_apellido' => $usu_apellido,
            ':usu_direccion' => $usu_direccion,
            ':usu_telefono' => $usu_telefono
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Registro exitoso.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la consulta: ' . $e->getMessage()]);
    }
}
?>
