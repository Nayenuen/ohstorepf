<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    $stmt->close();
}

// Handle delete order
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // First delete order items to maintain referential integrity
    $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    
    // Then delete the order
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: manage_orders.php");
    exit();
}

// Fetch all orders with customer info
$result = $conn->query("
    SELECT o.id, o.customer_name, o.email, o.order_date, o.total, 
           COALESCE(o.status, 'pending') as status 
    FROM orders o
    ORDER BY o.order_date DESC
");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos - Panel de Administración</title>
    
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
            --info: #17a2b8;
        }

        body {
            font-family: 'Sniglet', cursive;
            background-color: #f8f9fa;
            background-image: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            padding: 20px;
        }

        .orders-container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            transition: all 0.3s ease;
        }

        .orders-container:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .orders-title {
            color: var(--primary);
            font-weight: 800;
            margin-bottom: 1.5rem;
            font-size: 2.2rem;
            border-bottom: 3px solid var(--primary);
            padding-bottom: 10px;
            display: inline-block;
        }

        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            margin-top: 20px;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background-color: var(--primary);
            color: white;
        }

        .table th {
            font-weight: 600;
            padding: 15px;
            border-bottom: none;
        }

        .table td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            transform: scale(1.005);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .status-pending { 
            background-color: rgba(224, 230, 248, 0.5);
            border-left: 4px solid #ffc107;
        }
        .status-processing { 
            background-color: rgba(113, 159, 229, 0.1);
            border-left: 4px solid var(--primary);
        }
        .status-completed { 
            background-color: rgba(46, 204, 113, 0.1);
            border-left: 4px solid var(--success);
        }
        .status-cancelled { 
            background-color: rgba(231, 76, 60, 0.1);
            border-left: 4px solid var(--danger);
        }
        .status-shipped { 
            background-color: rgba(23, 162, 184, 0.1);
            border-left: 4px solid var(--info);
        }

        .btn-primary {
            background-color: var(--primary);
            border: none;
            border-radius: 8px;
            padding: 8px 15px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--navbar-blue-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(113, 159, 229, 0.3);
        }

        .btn-outline-primary {
            border-radius: 8px;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            transform: translateY(-2px);
        }

        .btn-info {
            background-color: var(--info);
            border: none;
            border-radius: 8px;
        }

        .btn-warning {
            background-color: var(--warning);
            border: none;
            border-radius: 8px;
        }

        .btn-danger {
            background-color: var(--danger);
            border: none;
            border-radius: 8px;
        }

        .form-select {
            border-radius: 8px;
            padding: 6px 12px;
            border: 2px solid #e1e5ee;
            transition: all 0.3s ease;
            font-family: 'Sniglet', cursive;
        }

        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(113, 159, 229, 0.25);
        }

        .alert {
            border-radius: 10px;
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
        }

        .btn-group .btn {
            padding: 6px 10px;
        }
    </style>
</head>
<body>
    <div class="orders-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="orders-title"><i class="bi bi-receipt"></i> Gestión de Pedidos</h1>
            <a href="admin_dashboard.php" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Volver al Panel
            </a>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> Orden actualizada correctamente!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <!-- Orders Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Email</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="status-<?php echo strtolower($row['status']); ?>">
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['order_date'])); ?></td>
                        <td>$<?php echo number_format($row['total'], 2); ?></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="pending" <?php echo $row['status'] == 'pending' ? 'selected' : ''; ?>>Pendiente</option>
                                    <option value="processing" <?php echo $row['status'] == 'processing' ? 'selected' : ''; ?>>Procesando</option>
                                    <option value="completed" <?php echo $row['status'] == 'completed' ? 'selected' : ''; ?>>Completado</option>
                                    <option value="cancelled" <?php echo $row['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelado</option>
                                    <option value="shipped" <?php echo $row['status'] == 'shipped' ? 'selected' : ''; ?>>Enviado</option>
                                </select>
                                <noscript><button type="submit" name="update_status" class="btn btn-sm btn-primary mt-1">Actualizar</button></noscript>
                            </form>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="view_order.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="edit_order.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="manage_orders.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este Pedido?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <div class="admin-footer">
            OH STORE &copy; <?php echo date('Y'); ?> - Panel de Administración
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>