<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Panel de Control</title>

       <!-- For favicon png -->
		<link rel="shortcut icon" type="image/icon" href="assets/logo/logo.ico"/>
        
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Google Font: Averia Sans Libre -->
    <link href="https://fonts.googleapis.com/css2?family=Sniglet:wght@400;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #719fe5;
            --hover-blue: #5a8bd8;
        }
        
        body {
            font-family: 'Sniglet', sans-serif;
            font-weight: 400;
             font-style: normal;
            background-color: #f8f9fa;
        }
        
        .admin-header {
            color: var(--primary-blue);
            margin-bottom: 2rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .dashboard-card {
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            height: 100%;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(113, 159, 229, 0.2);
        }
        
        .card-icon {
            font-size: 2.5rem;
            color: var(--primary-blue);
            margin-bottom: 1rem;
        }
        
        .card-title {
            color: var(--primary-blue);
        
        }
        
        .btn-dashboard {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            color: white;
            font-weight: 400;
            border-radius: 50px;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        
        .btn-dashboard:hover {
            background-color: var(--hover-blue);
            border-color: var(--hover-blue);
            transform: translateY(-2px);
        }
        
        .user-info {
            color: #6c757d;
            margin-bottom: 2.5rem;
            font-size: 1.5rem;
        }
        
        .logout-section {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #eee;
        }
        
        .btn-logout {
            background-color: transparent;
            border: 1px solid #dc3545;
            color: #dc3545;
            transition: all 0.3s ease;
        }
        
        .btn-logout:hover {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="text-center">
            <h1 class="admin-header">Panel de administracion</h1>
            <p class="user-info">
                <i class="bi bi-person-circle"></i> Sesión iniciada como: 
                <span class="fw-bold"><?php echo $_SESSION['admin_email']; ?></span>
            </p>
        </div>
        
        <!-- Dashboard Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card dashboard-card text-center p-4">
                    <i class="bi bi-people card-icon"></i>
                    <h5 class="card-title">Clientes</h5>
                    <p class="card-text text-muted mb-3">Gestión de usuarios registrados</p>
                    <a href="manage_clientes.php" class="btn btn-dashboard">Gestionar</a>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card dashboard-card text-center p-4">
                    <i class="bi bi-receipt card-icon"></i>
                    <h5 class="card-title">Órdenes</h5>
                    <p class="card-text text-muted mb-3">Ver y administrar pedidos</p>
                    <a href="manage_orders.php" class="btn btn-dashboard">Gestionar</a>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card dashboard-card text-center p-4">
                    <i class="bi bi-box-seam card-icon"></i>
                    <h5 class="card-title">Productos</h5>
                    <p class="card-text text-muted mb-3">Administrar catálogo</p>
                    <a href="manage_productos.php" class="btn btn-dashboard">Gestionar</a>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card dashboard-card text-center p-4">
                    <i class="bi bi-list-check card-icon"></i>
                    <h5 class="card-title">Proximos</h5>
                    <p class="card-text text-muted mb-3">Productos y Eventos</p>
                    <a href="manage_eventos.php" class="btn btn-dashboard">Gestionar</a>
                </div>
            </div>
        </div>
        
        <!-- Logout Section -->
        <div class="logout-section text-center">
            <a href="logout.php" class="btn btn-logout">
                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
            </a>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>