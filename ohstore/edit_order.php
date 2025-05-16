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
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows == 0) {
    header("Location: manage_orders.php");
    exit();
}

$order = $order_result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_order'])) {
    $customer_name = $_POST['customer_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $payment_method = $_POST['payment_method'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];
    
    $stmt = $conn->prepare("
        UPDATE orders 
        SET customer_name = ?, email = ?, phone = ?, address = ?, 
            payment_method = ?, status = ?, notes = ?
        WHERE id = ?
    ");
    $stmt->bind_param("sssssssi", $customer_name, $email, $phone, $address, 
                     $payment_method, $status, $notes, $order_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: view_order.php?id=$order_id");
    exit();
}

// Fetch order items for display (not editing)
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
    <title>Editar Pedido #<?php echo $order_id; ?> - Panel de Administración</title>
    
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

        .edit-order-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            transition: all 0.3s ease;
        }

        .edit-order-container:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .edit-order-title {
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
            height: 100%;
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

        .form-label {
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 10px 15px;
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
            width: 100%;
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

        .list-group-item {
            border-left: none;
            border-right: none;
            padding: 12px 0;
        }

        .list-group-item:first-child {
            border-top: none;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }

        .admin-footer {
            text-align: center;
            margin-top: 2rem;
            color: #7f8c8d;
            font-size: 0.9rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .info-label {
            font-weight: 600;
            color: var(--secondary);
            margin-right: 5px;
        }

        .summary-value {
            color: var(--primary);
        }
    </style>
</head>
<body>
    <div class="edit-order-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="edit-order-title"><i class="bi bi-pencil-square"></i> Editar Pedido #<?php echo $order_id; ?></h1>
            <a href="view_order.php?id=<?php echo $order_id; ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver a Detalles
            </a>
        </div>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-person-lines-fill"></i> Información del Cliente</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" name="customer_name" 
                                       value="<?php echo htmlspecialchars($order['customer_name']); ?>" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" 
                                           value="<?php echo htmlspecialchars($order['email']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" name="phone" 
                                           value="<?php echo htmlspecialchars($order['phone']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Dirección</label>
                                <textarea class="form-control" name="address" rows="3" required><?php echo htmlspecialchars($order['address']); ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Método de Pago</label>
                                    <select class="form-select" name="payment_method" required>
                                        <option value="deposit" <?php echo $order['payment_method'] == 'deposit' ? 'selected' : ''; ?>>Depósito Bancario</option>
                                        <option value="cash" <?php echo $order['payment_method'] == 'cash' ? 'selected' : ''; ?>>Efectivo en Tienda</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Estado</label>
                                    <select class="form-select" name="status" required>
                                        <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pendiente</option>
                                        <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Procesando</option>
                                        <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>Completado</option>
                                        <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelado</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Notas Adicionales</label>
                                <textarea class="form-control" name="notes" rows="3"><?php echo htmlspecialchars($order['notes']); ?></textarea>
                            </div>
                            
                            <button type="submit" name="update_order" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Actualizar Pedido
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-receipt"></i> Resumen de Pedido</h5>
                    </div>
                    <div class="card-body">
                        <p><span class="info-label">Fecha:</span> 
                            <span class="summary-value"><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></span>
                        </p>
                        <p><span class="info-label">Total:</span> 
                            <span class="summary-value">$<?php echo number_format($order['total'], 2); ?></span>
                        </p>
                        <p><span class="info-label">Productos:</span> 
                            <span class="summary-value"><?php echo $items_result->num_rows; ?></span>
                        </p>
                        
                        <hr>
                        
                        <h6 class="fw-bold"><i class="bi bi-cart-check"></i> Productos:</h6>
                        <ul class="list-group list-group-flush">
                            <?php while ($item = $items_result->fetch_assoc()): ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><?php echo $item['product_name']; ?> (x<?php echo $item['quantity']; ?>)</span>
                                <span>$<?php echo number_format($item['subtotal'], 2); ?></span>
                            </li>
                            <?php endwhile; ?>
                            <li class="list-group-item d-flex justify-content-between fw-bold">
                                <span>Total</span>
                                <span>$<?php echo number_format($order['total'], 2); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="admin-footer">
            OH STORE &copy; <?php echo date('Y'); ?> - Panel de Administración
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>