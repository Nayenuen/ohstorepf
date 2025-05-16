<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

// Check if ID is set
if (!isset($_GET['id'])) {
    header("Location: manage_orders.php");
    exit();
}

$order_id = $_GET['id'];

// Fetch order details
$stmt = $conn->prepare("
    SELECT o.*, COUNT(oi.id) as item_count 
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    WHERE o.id = ?
    GROUP BY o.id
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows == 0) {
    header("Location: manage_orders.php");
    exit();
}

$order = $order_result->fetch_assoc();

// Fetch order items
$stmt = $conn->prepare("
    SELECT oi.* 
    FROM order_items oi
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Pedido #<?php echo $order_id; ?> - Panel de Administración</title>
    
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

        .order-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            transition: all 0.3s ease;
        }

        .order-container:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .order-title {
            color: var(--primary);
            font-weight: 800;
            margin-bottom: 1.5rem;
            font-size: 2.2rem;
            border-bottom: 3px solid var(--primary);
            padding-bottom: 10px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: var(--primary);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .card-body {
            padding: 20px;
        }

        .info-label {
            font-weight: 600;
            color: var(--secondary);
            min-width: 120px;
            display: inline-block;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .table thead {
            background-color: var(--primary);
            color: white;
        }

        .table th {
            font-weight: 600;
            padding: 12px 15px;
        }

        .table td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        .badge {
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .bg-warning {
            background-color: var(--warning) !important;
        }

        .bg-primary {
            background-color: var(--primary) !important;
        }

        .bg-success {
            background-color: var(--success) !important;
        }

        .bg-danger {
            background-color: var(--danger) !important;
        }

        .btn-primary {
            background-color: var(--primary);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
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

        .action-buttons {
            margin-top: 25px;
            display: flex;
            gap: 10px;
        }

        .admin-footer {
            text-align: center;
            margin-top: 2rem;
            color: #7f8c8d;
            font-size: 0.9rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .total-row {
            font-weight: 700;
            background-color: rgba(113, 159, 229, 0.1) !important;
        }
    </style>
</head>
<body>
    <div class="order-container">
        <h1 class="order-title">Detalles de Pedido #<?php echo $order_id; ?></h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-person-circle"></i> Información del Cliente</h5>
                    </div>
                    <div class="card-body">
                        <p><span class="info-label">Nombre:</span> <?php echo $order['customer_name']; ?></p>
                        <p><span class="info-label">Email:</span> <?php echo $order['email']; ?></p>
                        <p><span class="info-label">Teléfono:</span> <?php echo $order['phone']; ?></p>
                        <p><span class="info-label">Dirección:</span> <?php echo $order['address']; ?></p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-receipt"></i> Información del Pedido</h5>
                    </div>
                    <div class="card-body">
                        <p><span class="info-label">Fecha:</span> <?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></p>
                        <p><span class="info-label">Método de Pago:</span> 
                            <?php 
                            if ($order['payment_method'] == 'deposit') {
                                echo '<span class="badge bg-primary">Depósito Bancario</span>';
                            } elseif ($order['payment_method'] == 'cash') {
                                echo '<span class="badge bg-success">Efectivo en Tienda</span>';
                            } else {
                                echo $order['payment_method'];
                            }
                            ?>
                        </p>
                        <p><span class="info-label">Estado:</span> 
                            <span class="badge 
                                <?php 
                                switch(strtolower($order['status'])) {
                                    case 'pending': echo 'bg-warning'; break;
                                    case 'processing': echo 'bg-primary'; break;
                                    case 'completed': echo 'bg-success'; break;
                                    case 'cancelled': echo 'bg-danger'; break;
                                    default: echo 'bg-secondary';
                                }
                                ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </p>
                        <p><span class="info-label">Notas:</span> <?php echo $order['notes'] ? $order['notes'] : 'Ninguna'; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-cart-check"></i> Productos</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($item = $items_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $item['product_name']; ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>$<?php echo number_format($item['unit_price'], 2); ?></td>
                                        <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <tr class="total-row">
                                        <td colspan="3" class="text-end"><strong>Total</strong></td>
                                        <td><strong>$<?php echo number_format($order['total'], 2); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="edit_order.php?id=<?php echo $order_id; ?>" class="btn btn-primary">
                <i class="bi bi-pencil-square"></i> Editar Pedido
            </a>
            <a href="manage_orders.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver a Pedidos
            </a>
        </div>
        
        <div class="admin-footer">
            OH STORE &copy; <?php echo date('Y'); ?> - Panel de Administración
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>