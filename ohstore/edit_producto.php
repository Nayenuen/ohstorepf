<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

// Incluir conexión a la base de datos
include 'db.php';

// Verificar si el ID está en la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos del producto
    $stmt = $conn->prepare("SELECT id, nombre, descripcion, tipo, precio, stock FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si el producto existe
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Producto no encontrado.";
        exit();
    }
} else {
    echo "No se proporcionó ID.";
    exit();
}

// Manejar actualización del producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_producto'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $tipo = $_POST['tipo'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Preparar y ejecutar
    $stmt = $conn->prepare("UPDATE productos SET nombre = ?, descripcion = ?, tipo = ?, precio = ?, stock = ? WHERE id = ?");
    $stmt->bind_param("sssdsi", $nombre, $descripcion, $tipo, $precio, $stock, $id);
    $stmt->execute();
    $stmt->close();

    // Redirigir a manage_productos.php después de actualizar
    header("Location: manage_productos.php");
    exit();
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - Panel de Administración</title>
    
    <!-- Google Fonts - Sniglet -->
    <link href="https://fonts.googleapis.com/css2?family=Sniglet&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    
    <!-- For favicon png -->
    <link rel="shortcut icon" type="image/icon" href="assets/logo/logo.ico"/>
    
    <!-- Estilos personalizados -->
    <style>
        :root {
            --primary: #719fe5;
            --secondary: #5f5b57;
            --light: #f8f9fd;
            --navbar-blue: #719fe5;
            --navbar-blue-hover: #5a8ad8;
            --danger: #e74c3c;
            --warning: #f39c12;
            --success: #2ecc71;
        }

        body {
            font-family: 'Sniglet', cursive;
            background-color: #f8f9fa;
            background-image: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            padding: 20px;
        }

        .edit-product-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            transition: all 0.3s ease;
        }

        .edit-product-container:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .edit-product-title {
            color: var(--primary);
            font-weight: 800;
            margin-bottom: 1.5rem;
            font-size: 2.2rem;
            border-bottom: 3px solid var(--primary);
            padding-bottom: 10px;
        }

        .form-group label {
            color: var(--secondary);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e1e5ee;
            transition: all 0.3s ease;
            font-family: 'Sniglet', cursive;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(113, 159, 229, 0.25);
        }

        textarea.form-control {
            min-height: 120px;
        }

        .btn-primary {
            background-color: var(--primary);
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .btn-primary:hover {
            background-color: var(--navbar-blue-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(113, 159, 229, 0.3);
        }

        .btn-secondary {
            border-radius: 10px;
            padding: 10px 20px;
            transition: all 0.3s ease;
            display: inline-block;
            margin-top: 15px;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-radius: 10px 0 0 10px;
            border-right: none;
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
            border-left: none;
        }

        .admin-footer {
            text-align: center;
            margin-top: 2rem;
            color: #7f8c8d;
            font-size: 0.9rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="edit-product-container">
        <h1 class="edit-product-title"><i class="bi bi-box-seam"></i> Editar Producto</h1>
        
        <form action="edit_producto.php?id=<?php echo $id; ?>" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($row['nombre']); ?>" required placeholder="Nombre del producto">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="tipo">Tipo:</label>
                        <select class="form-select" name="tipo" required>
                            <option value="normal" <?php echo ($row['tipo'] == 'normal') ? 'selected' : ''; ?>>Normal</option>
                            <option value="limitado" <?php echo ($row['tipo'] == 'limitado') ? 'selected' : ''; ?>>Limitado</option>
                            <option value="especial" <?php echo ($row['tipo'] == 'especial') ? 'selected' : ''; ?>>Especial</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-group mb-3">
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control" name="descripcion" required placeholder="Descripción detallada del producto"><?php echo htmlspecialchars($row['descripcion']); ?></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="precio">Precio:</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control" name="precio" value="<?php echo $row['precio']; ?>" required placeholder="0.00">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="stock">Disponibilidad:</label>
                        <select class="form-select" name="stock" required>
                            <option value="tienda" <?php echo ($row['stock'] == 'tienda') ? 'selected' : ''; ?>>Disponible en tienda</option>
                            <option value="cafeteria" <?php echo ($row['stock'] == 'cafeteria') ? 'selected' : ''; ?>>Disponible en cafetería</option>
                            <option value="no disponible" <?php echo ($row['stock'] == 'no disponible') ? 'selected' : ''; ?>>No disponible</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <button type="submit" name="update_producto" class="btn btn-primary">
                <i class="bi bi-save"></i> Actualizar Producto
            </button>
            
            <a href="manage_productos.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver a Productos
            </a>
        </form>
        
        <div class="admin-footer">
            OH STORE &copy; <?php echo date('Y'); ?> - Panel de Administración
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>