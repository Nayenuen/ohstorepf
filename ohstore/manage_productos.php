<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

// Incluir conexión a la base de datos
include 'db.php';

// Manejar agregar producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_producto'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $tipo = $_POST['tipo'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Preparar y ejecutar
    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, tipo, precio, stock) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssds", $nombre, $descripcion, $tipo, $precio, $stock);
    $stmt->execute();
    $stmt->close();
}

// Manejar eliminar producto
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Preparar y ejecutar
    $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

// Obtener datos de la tabla productos
$result = $conn->query("SELECT id, nombre, descripcion, tipo, precio, stock FROM productos");
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - Panel de Administración</title>
    
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

        .products-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            transition: all 0.3s ease;
        }

        .products-container:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .products-title {
            color: var(--primary);
            font-weight: 800;
            margin-bottom: 1.5rem;
            font-size: 2.2rem;
            border-bottom: 3px solid var(--primary);
            padding-bottom: 10px;
            display: inline-block;
        }

        .section-title {
            color: var(--primary);
            font-weight: 700;
            margin: 25px 0 15px;
            font-size: 1.8rem;
        }

        .add-product-form {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
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
            min-height: 100px;
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
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
        }

        .btn-warning {
            background-color: var(--warning);
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            background-color: #e67e22;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--danger);
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            margin-top: 20px;
        }

        .table thead {
            background-color: var(--primary);
            color: white;
        }

        .table th {
            font-weight: 600;
            padding: 15px;
        }

        .table td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(113, 159, 229, 0.1);
            transform: scale(1.005);
        }

        .badge-tipo {
            padding: 6px 10px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .badge-normal {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-limitado {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-especial {
            background-color: #cce5ff;
            color: #004085;
        }

        .badge-disponible {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-no-disponible {
            background-color: #f8d7da;
            color: #721c24;
        }

        .admin-footer {
            text-align: center;
            margin-top: 2rem;
            color: #7f8c8d;
            font-size: 0.9rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .action-buttons .btn {
            margin-right: 5px;
            padding: 8px 12px;
        }
    </style>
</head>
<body>
    <div class="products-container">
        <h1 class="products-title"><i class="bi bi-box-seam"></i> Gestión de Productos</h1>
        
        <!-- Formulario para agregar productos -->
        <div class="add-product-form">
            <h2 class="section-title"><i class="bi bi-plus-circle"></i> Agregar Nuevo Producto</h2>
            <form action="manage_productos.php" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" name="nombre" required placeholder="Nombre del producto">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="tipo">Tipo:</label>
                            <select class="form-select" name="tipo" required>
                                <option value="normal">Normal</option>
                                <option value="limitado">Limitado</option>
                                <option value="especial">Especial</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group mb-3">
                    <label for="descripcion">Descripción:</label>
                    <textarea class="form-control" name="descripcion" required placeholder="Descripción detallada del producto"></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="precio">Precio:</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" name="precio" required placeholder="0.00">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="stock">Disponibilidad:</label>
                            <select class="form-select" name="stock" required>
                                <option value="tienda">Disponible en tienda</option>
                                <option value="cafeteria">Disponible en cafetería</option>
                                <option value="no disponible">No disponible</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <button type="submit" name="add_producto" class="btn btn-primary">
                    <i class="bi bi-save"></i> Agregar Producto
                </button>
            </form>
        </div>

        <!-- Tabla de productos -->
        <h2 class="section-title"><i class="bi bi-list-check"></i> Productos Registrados</h2>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Tipo</th>
                        <th>Precio</th>
                        <th>Disponibilidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['descripcion']; ?></td>
                        <td>
                            <span class="badge badge-tipo badge-<?php echo $row['tipo']; ?>">
                                <?php echo ucfirst($row['tipo']); ?>
                            </span>
                        </td>
                        <td>$<?php echo number_format($row['precio'], 2); ?></td>
                        <td>
                            <?php if($row['stock'] == 'no disponible'): ?>
                                <span class="badge badge-no-disponible">No disponible</span>
                            <?php else: ?>
                                <span class="badge badge-disponible">
                                    <?php echo ($row['stock'] == 'tienda') ? 'Tienda' : 'Cafetería'; ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="action-buttons">
                            <a href="edit_producto.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <a href="manage_productos.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este producto?');">
                                <i class="bi bi-trash"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <a href="admin_dashboard.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Panel
        </a>
        
        <div class="admin-footer">
            OH STORE &copy; <?php echo date('Y'); ?> - Panel de Administración
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>