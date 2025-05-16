<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php"); // Redirect to login if not logged in
    exit();
}

// Include database connection
include 'db.php';

// Check if ID is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the client data
    $stmt = $conn->prepare("SELECT IdCliente, nombre, email, telefono, direccion FROM clientes WHERE IdCliente = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the client exists
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Client not found.";
        exit();
    }
} else {
    echo "No ID provided.";
    exit();
}

// Handle Update Client
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_cliente'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE clientes SET nombre = ?, email = ?, telefono = ?, direccion = ? WHERE IdCliente = ?");
    $stmt->bind_param("ssssi", $nombre, $email, $telefono, $direccion, $id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to manage_clientes.php after update
    header("Location: manage_clientes.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente - Panel de Administración</title>
    
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
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        }

        .edit-container {
            max-width: 600px;
            width: 100%;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .edit-container:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }

        .edit-title {
            color: var(--primary);
            font-weight: 800;
            margin-bottom: 1.5rem;
            font-size: 2.2rem;
            text-align: center;
            border-bottom: 3px solid var(--primary);
            padding-bottom: 10px;
            display: inline-block;
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
            width: 100%;
            margin-top: 1rem;
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
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            width: 100%;
        }

        .btn-secondary:hover {
            color: var(--navbar-blue-hover);
            transform: translateY(-2px);
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

        .edit-form {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
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
    <div class="edit-container">
        <h1 class="edit-title">Editar Cliente</h1>
        
        <div class="edit-form">
            <form action="edit_cliente.php?id=<?php echo $id; ?>" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <div class="input-icon">
                                <i class="bi bi-person-fill"></i>
                                <input type="text" class="form-control" name="nombre" value="<?php echo $row['nombre']; ?>" required placeholder="Nombre completo">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <div class="input-icon">
                                <i class="bi bi-envelope-fill"></i>
                                <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>" required placeholder="correo@ejemplo.com">
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
                                <input type="text" class="form-control" name="telefono" value="<?php echo $row['telefono']; ?>" required placeholder="Número de teléfono">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="direccion">Dirección:</label>
                            <div class="input-icon">
                                <i class="bi bi-house-door-fill"></i>
                                <input type="text" class="form-control" name="direccion" value="<?php echo $row['direccion']; ?>" required placeholder="Dirección completa">
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" name="update_cliente" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Actualizar Cliente
                </button>
            </form>
        </div>
        
        <a href="manage_clientes.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver a Clientes
        </a>
        
        <div class="admin-footer">
            OH STORE &copy; <?php echo date('Y'); ?> - Panel de Administración
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>