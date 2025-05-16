<?php
session_start();

// Verificar si hay un ID de orden
if (!isset($_GET['order_id'])) {
    header("Location: catalogo.php");
    exit();
}

$order_id = $_GET['order_id'];

// Conexión a la base de datos
$servername = "fdb1030.awardspace.net";
$username = "4585639_ohstore";
$password = "eGdCuEzE8g9f";
$dbname = "4585639_ohstore";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener información de la orden
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = :order_id");
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Obtener items de la orden
    $stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>

    <!-- For favicon png -->
		<link rel="shortcut icon" type="image/icon" href="assets/logo/logo.ico"/>
        
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-blue: #719fe5;
        }
        
        body {
            font-family:'Sniglet', sans-serif;
            padding-top: 80px;
            background-color: #f8f9fa;
        }
        
        .confirmation-header {
            background-color: var(--primary-blue);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 20px;
        }
        
        .order-number {
            font-size: 1.5rem;
            font-weight: 800;
        }
        
        .status-badge {
            font-size: 0.9rem;
            padding: 5px 10px;
            border-radius: 20px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="card shadow">
            <div class="confirmation-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">¡Gracias por tu pedido!</h3>
                    <span class="order-number">#<?= $order_id ?></span>
                </div>
            </div>
            
            <div class="card-body">
                <div class="alert alert-success">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                        <div>
                            Tu pedido ha sido recibido y está actualmente 
                            <span class="badge status-badge bg-secondary"><?= ucfirst($order['status'] ?? 'pending') ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Información del Pedido</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></p>
                                <p><strong>Método de Pago:</strong> 
                                    <?= $order['payment_method'] == 'deposit' ? 'Depósito Bancario' : 'Efectivo en Tienda' ?>
                                </p>
                                <p><strong>Estado:</strong> 
                                    <span class="badge 
                                        <?php 
                                        switch(strtolower($order['status'] ?? 'pending')) {
                                            case 'pending': echo 'bg-warning'; break;
                                            case 'processing': echo 'bg-primary'; break;
                                            case 'completed': echo 'bg-success'; break;
                                            case 'cancelled': echo 'bg-danger'; break;
                                            case 'shipped': echo 'bg-info'; break;
                                            default: echo 'bg-secondary';
                                        }
                                        ?>">
                                        <?= ucfirst($order['status'] ?? 'pending') ?>
                                    </span>
                                </p>
                                <p><strong>Total:</strong> $<?= number_format($order['total'], 2) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Información del Cliente</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Nombre:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                                <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
                                <p><strong>Teléfono:</strong> <?= htmlspecialchars($order['phone']) ?></p>
                                <p><strong>Dirección:</strong> <?= htmlspecialchars($order['address']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h5 class="mb-3">Detalles del Pedido</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order_items as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td>$<?= number_format($item['unit_price'], 2) ?></td>
                                <td>$<?= number_format($item['subtotal'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <tr class="table-light">
                                <td colspan="3" class="text-end"><strong>Total</strong></td>
                                <td><strong>$<?= number_format($order['total'], 2) ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($order['payment_method'] == 'deposit'): ?>
                <div class="alert alert-info mt-4">
                    <h5><i class="bi bi-bank me-2"></i>Instrucciones para Pago por Depósito</h5>
                    <p>Por favor realiza el pago a la siguiente cuenta bancaria:</p>
                    <div class="ps-3">
                        <p><strong>Banco:</strong> Banco Ejemplo<br>
                        <strong>Cuenta:</strong> 1234567890<br>
                        <strong>CLABE:</strong> 012180012345678902<br>
                        <strong>Titular:</strong> OH STORE S.A. de C.V.</p>
                    </div>
                    <p class="mb-0">Envía el comprobante de pago a nuestro WhatsApp: 55-1234-5678</p>
                </div>
                <?php endif; ?>
                
                <div class="d-grid gap-2 mt-4">
                    <a href="catalogo.php" class="btn btn-primary">
                        <i class="bi bi-arrow-left"></i> Volver al Catálogo
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>