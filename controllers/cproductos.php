<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? null;

    switch ($accion) {
        case 'cargar_categorias':
            cargarCategorias();
            break;
        case 'guardar':
            guardarProducto();
            break;
        case 'actualizar':
            actualizarProducto();
            break;
        case 'eliminar':
            eliminarProducto();
            break;
        case 'buscar':
            buscarProducto();
            break;
        case 'buscar_coincidencias':
            buscarCoincidencias();
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Acción no válida.']);
            break;
    }
}

function cargarCategorias() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT cat_id, cat_nombre FROM categorias");
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $categorias]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error al cargar categorías: ' . $e->getMessage()]);
    }
}

function guardarProducto() {
    global $pdo;
    
    $prod_nombre = $_POST['prod_nombre'] ?? null;
    $prod_descripcion = $_POST['prod_descripcion'] ?? null;
    $prod_precio = $_POST['prod_precio'] ?? null;
    $prod_stock = $_POST['prod_stock'] ?? null;
    $cat_id = $_POST['cat_id'] ?? null;

    // Manejo de la imagen
    $imagen_url = null;
    if (isset($_FILES['prod_imagen_url']) && $_FILES['prod_imagen_url']['error'] === UPLOAD_ERR_OK) {
        $imagenTmpName = $_FILES['prod_imagen_url']['tmp_name'];
        $imagenExtension = pathinfo($_FILES['prod_imagen_url']['name'], PATHINFO_EXTENSION);
        $imagenNombre = uniqid() . '.' . $imagenExtension;
        $imagenRuta = 'uploads/' . $imagenNombre;

        if (move_uploaded_file($imagenTmpName, $imagenRuta)) {
            $imagen_url =$imagenNombre;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al subir la imagen.']);
            return;
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO productos (prod_nombre, prod_descripcion, prod_precio, prod_stock, cat_id, prod_imagen_url) VALUES (:prod_nombre, :prod_descripcion, :prod_precio, :prod_stock, :cat_id, :prod_imagen_url)");
        $stmt->execute([
            ':prod_nombre' => $prod_nombre,
            ':prod_descripcion' => $prod_descripcion,
            ':prod_precio' => $prod_precio,
            ':prod_stock' => $prod_stock,
            ':cat_id' => $cat_id,
            ':prod_imagen_url' => $imagen_url
        ]);
        echo json_encode(['status' => 'success', 'message' => 'Producto guardado exitosamente.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error al guardar producto: ' . $e->getMessage()]);
    }
}

function actualizarProducto() {
    global $pdo;

    $prod_id = $_POST['prod_id'] ?? null;
    $prod_nombre = $_POST['prod_nombre'] ?? null;
    $prod_descripcion = $_POST['prod_descripcion'] ?? null;
    $prod_precio = $_POST['prod_precio'] ?? null;
    $prod_stock = $_POST['prod_stock'] ?? null;
    $cat_id = $_POST['cat_id'] ?? null;

    // Manejo de la imagen
    $imagen_url = null;
    if (isset($_FILES['prod_imagen_url']) && $_FILES['prod_imagen_url']['error'] === UPLOAD_ERR_OK) {
        $imagenTmpName = $_FILES['prod_imagen_url']['tmp_name'];
        $imagenExtension = pathinfo($_FILES['prod_imagen_url']['name'], PATHINFO_EXTENSION);
        $imagenNombre = uniqid() . '.' . $imagenExtension;
        $imagenRuta = 'uploads/' . $imagenNombre;

        if (move_uploaded_file($imagenTmpName, $imagenRuta)) {
            $imagen_url = $imagenRuta;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al subir la imagen.']);
            return;
        }
    }

    if ($prod_id === null) {
        echo json_encode(['status' => 'error', 'message' => 'ID del producto no definido.']);
        return;
    }

    try {
        $query = "UPDATE productos SET prod_nombre = :prod_nombre, prod_descripcion = :prod_descripcion, prod_precio = :prod_precio, prod_stock = :prod_stock, cat_id = :cat_id";
        
        if ($imagen_url) {
            $query .= ", prod_imagen_url = :prod_imagen_url";
        }
        
        $query .= " WHERE prod_id = :prod_id";

        $stmt = $pdo->prepare($query);

        $params = [
            ':prod_nombre' => $prod_nombre,
            ':prod_descripcion' => $prod_descripcion,
            ':prod_precio' => $prod_precio,
            ':prod_stock' => $prod_stock,
            ':cat_id' => $cat_id,
            ':prod_id' => $prod_id
        ];

        if ($imagen_url) {
            $params[':prod_imagen_url'] = $imagen_url;
        }

        $stmt->execute($params);
        echo json_encode(['status' => 'success', 'message' => 'Producto actualizado exitosamente.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar producto: ' . $e->getMessage()]);
    }
}

function eliminarProducto() {
    global $pdo;

    $prod_id = $_POST['prod_id'] ?? null;

    if ($prod_id === null) {
        echo json_encode(['status' => 'error', 'message' => 'ID del producto no definido.']);
        return;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM productos WHERE prod_id = :prod_id");
        $stmt->execute([':prod_id' => $prod_id]);
        echo json_encode(['status' => 'success', 'message' => 'Producto eliminado exitosamente.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar producto: ' . $e->getMessage()]);
    }
}

function buscarProducto() {
    global $pdo;

    $prod_nombre = $_POST['prod_nombre'] ?? null;

    if ($prod_nombre === null) {
        echo json_encode(['status' => 'error', 'message' => 'Nombre del producto no definido.']);
        return;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE prod_nombre = :prod_nombre LIMIT 1");
        $stmt->execute([':prod_nombre' => $prod_nombre]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($producto) {
            echo json_encode(['status' => 'success', 'data' => $producto]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se encontró el producto.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error al buscar producto: ' . $e->getMessage()]);
    }
}

function buscarCoincidencias() {
    global $pdo;

    $prod_nombre = $_POST['prod_nombre'] ?? null;

    if ($prod_nombre === null) {
        echo json_encode(['status' => 'error', 'message' => 'Nombre del producto no definido.']);
        return;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM productos WHERE prod_nombre LIKE :prod_nombre LIMIT 10");
        $stmt->execute([':prod_nombre' => '%' . $prod_nombre . '%']);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $productos]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error al buscar coincidencias: ' . $e->getMessage()]);
    }
}
