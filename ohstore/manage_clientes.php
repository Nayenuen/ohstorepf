<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php"); // Redirect to login if not logged in
    exit();
}

// Include database connection
include 'db.php';

// Handle Add Client
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_cliente'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO clientes (nombre, email, telefono, direccion) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $email, $telefono, $direccion);
    $stmt->execute();
    $stmt->close();
}

// Handle Delete Client
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Prepare and bind
    $stmt = $conn->prepare("DELETE FROM clientes WHERE IdCliente = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch data from the "clientes" table
$result = $conn->query("SELECT IdCliente, nombre, email, telefono, direccion FROM clientes");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Panel de Administración</title>
    
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
        }

        body {
            font-family: 'Sniglet', cursive;
            background-color: #f8f9fa;
            padding: 20px;
            background-image: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        }

        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            transition: all 0.3s ease;
        }

        .admin-container:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .admin-title {
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

        .form-group label {
            color: var(--secondary);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e1e5ee;
            transition: all 0.3s ease;
            font-family: 'Sniglet', cursive;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(113, 159, 229, 0.25);
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

        .action-buttons .btn {
            margin-right: 5px;
            padding: 8px 12px;
        }

        .admin-footer {
            text-align: center;
            margin-top: 2rem;
            color: #7f8c8d;
            font-size: 0.9rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #bdc3c7;
        }

        .input-icon input {
            padding-left: 40px;
        }

        .add-client-form {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .success-message {
            color: #27ae60;
            background-color: #d5f5e3;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: 600;
            animation: fadeIn 0.5s ease;
            display: none;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1 class="admin-title">Gestión de Clientes</h1>
        
        <!-- Add Client Form -->
        <div class="add-client-form">
            <h2 class="section-title">Agregar Nuevo Cliente</h2>
            <form action="manage_clientes.php" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <div class="input-icon">
                                <i class="bi bi-person-fill"></i>
                                <input type="text" class="form-control" name="nombre" required placeholder="Nombre completo">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <div class="input-icon">
                                <i class="bi bi-envelope-fill"></i>
                                <input type="email" class="form-control" name="email" required placeholder="correo@ejemplo.com">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="telefono">Teléfono:</label>
                            <div class="input-icon">
                                <i class="bi bi-telephone-fill"></i>
                                <input type="text" class="form-control" name="telefono" required placeholder="Número de teléfono">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="direccion">Dirección:</label>
                            <div class="input-icon">
                                <i class="bi bi-house-door-fill"></i>
                                <input type="text" class="form-control" name="direccion" required placeholder="Dirección completa">
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" name="add_cliente" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Agregar Cliente
                </button>
            </form>
        </div>

        <!-- Clients Table -->
        <h2 class="section-title">Clientes Registrados</h2>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['IdCliente']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['telefono']; ?></td>
                        <td><?php echo $row['direccion']; ?></td>
                        <td class="action-buttons">
                            <a href="edit_cliente.php?id=<?php echo $row['IdCliente']; ?>" class="btn btn-warning">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
                            <a href="manage_clientes.php?delete_id=<?php echo $row['IdCliente']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este cliente?');">
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
    
    <!-- Script para mostrar mensaje de éxito -->
    <script>
        // Mostrar mensaje de éxito si se agregó un cliente
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success')) {
                const successMessage = document.createElement('div');
                successMessage.className = 'success-message';
                successMessage.textContent = 'Cliente agregado exitosamente';
                successMessage.style.display = 'block';
                
                const container = document.querySelector('.admin-container');
                container.insertBefore(successMessage, container.firstChild);
                
                // Ocultar el mensaje después de 3 segundos
                setTimeout(() => {
                    successMessage.style.opacity = '0';
                    setTimeout(() => successMessage.remove(), 500);
                }, 3000);
            }
        });
    </script>
</body>
</html>