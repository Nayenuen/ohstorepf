<?php
session_start();
ob_start(); // Inicia buffer de salida

// Cargar productos
$products = json_decode(file_get_contents('products.json'), true);

// Función para sanitizar
function sanitizeString($input) {
    return htmlspecialchars(strip_tags($input ?? ''), ENT_QUOTES, 'UTF-8');
}

// Procesar el carrito
$cartData = [];
if (isset($_POST['cartData'])) {
    $cartData = json_decode($_POST['cartData'], true);
} elseif (isset($_SESSION['cart'])) {
    $cartData = $_SESSION['cart'];
}

// Procesar checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_checkout'])) {
    // Validar y sanitizar (forma moderna)
    $name = sanitizeString($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $address = sanitizeString($_POST['address']);
    $phone = sanitizeString($_POST['phone']);
    $payment_method = sanitizeString($_POST['payment_method']);
    $notes = sanitizeString($_POST['notes']);
    
    // Calcular total
    $total = 0;
    foreach ($cartData as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    
    // Conexión a la base de datos (XAMPP)
 $servername = "fdb1030.awardspace.net";
$username = "4585639_ohstore";
$password = "eGdCuEzE8g9f";
$dbname = "4585639_ohstore";
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // 1. Insertar orden
        $stmt = $conn->prepare("INSERT INTO orders (customer_name, email, address, phone, payment_method, notes, total, order_date, status) 
                               VALUES (:name, :email, :address, :phone, :payment_method, :notes, :total, NOW(), 'pending')");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->bindParam(':notes', $notes);
        $stmt->bindParam(':total', $total);
        $stmt->execute();
        
        $order_id = $conn->lastInsertId();
            }
        catch (PDOException $e) {
    // Manejo del error
    $error_message = "Error de conexión: " . $e->getMessage();
    // Puedes redirigir o mostrar el error
    die("Error al procesar la orden. Por favor, inténtalo de nuevo.");
}
        
        // 2. Insertar items de la orden
        foreach ($cartData as $item) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, subtotal) 
                                   VALUES (:order_id, :product_id, :product_name, :quantity, :unit_price, :subtotal)");
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':product_id', $item['id']);
            $stmt->bindParam(':product_name', $item['name']);
            $stmt->bindParam(':quantity', $item['quantity']);
            $stmt->bindParam(':unit_price', $item['price']);
            $subtotal = $item['price'] * $item['quantity'];
            $stmt->bindParam(':subtotal', $subtotal);
            $stmt->execute();
        }
        
        // Limpiar carrito
        unset($_SESSION['cart']);
        $cartData = [];
        
     header("Location: order_confirmation.php?order_id=$order_id");
    ob_end_flush(); // Envía el buffer y desactiva
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - OH STORE</title>

    <!-- For favicon png -->
    <link rel="shortcut icon" type="image/icon" href="assets/logo/logo.ico"/>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts - Sniglet -->
    <link href="https://fonts.googleapis.com/css2?family=Sniglet&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary: #719fe5;
            --secondary: #5f5b57;
            --light: #f8f9fd;
            --navbar-blue: #719fe5;
            --navbar-blue-hover: #5a8ad8;
            --success: #2ecc71;
            --danger: #e74c3c;
            --warning: #f39c12;
        }

        body {
            font-family: 'Sniglet', cursive;
            background-color: #f8f9fa;
            background-image: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            padding-top: 80px;
        }

        .checkout-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 15px;
        }

        .checkout-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .checkout-card:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background-color: var(--primary);
            color: white;
            padding: 20px;
            font-weight: 700;
            font-size: 1.3rem;
            border-bottom: none;
        }

        .order-summary {
            background-color: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .form-label {
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select, .form-check-input {
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
            padding: 15px 25px;
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

        .product-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
            transition: all 0.2s ease;
        }

        .product-item:hover {
            background-color: rgba(113, 159, 229, 0.05);
        }

        .total-row {
            font-weight: 700;
            font-size: 1.2rem;
            border-top: 2px solid var(--primary);
            padding-top: 15px;
            margin-top: 15px;
        }

        .navbar {
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 800;
            color: var(--primary) !important;
        }

        .nav-link {
            color: var(--secondary) !important;
            font-weight: 600;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        .cart-badge {
            top: -8px;
            right: -8px;
            font-size: 10px;
            padding: 5px 8px;
        }

        .payment-method {
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 10px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .payment-method:hover {
            background-color: rgba(113, 159, 229, 0.1);
        }

        .payment-method.active {
            background-color: rgba(113, 159, 229, 0.2);
            border: 1px solid var(--primary);
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">OH STORE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#new-arrivals">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="catalogo.php">Catálogo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#blog">Noticias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#newsletter">Contacto</a>
                    </li>
                </ul>
                <ul class="navbar-nav d-flex align-items-center">
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="admin_login.php">
                            <i class="bi bi-person fs-4"></i>
                        </a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link position-relative p-0" href="catalogo.php#shoppingCart">
                            <i class="bi bi-cart fs-4"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">
                                <?= array_reduce($cartData, function($carry, $item) { return $carry + $item['quantity']; }, 0) ?>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Checkout -->
    <div class="checkout-container">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="checkout-card">
                        <div class="card-header">
                            <i class="bi bi-person-lines-fill"></i> Información del Cliente
                        </div>
                        <div class="card-body p-4">
                            <?php if (isset($error_message)): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= $error_message ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="checkout.php">
                                <input type="hidden" name="cartData" value='<?= json_encode($cartData) ?>'>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nombre Completo</label>
                                        <input type="text" class="form-control" id="name" name="name" required placeholder="Tu nombre completo">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="email" name="email" required placeholder="tucorreo@ejemplo.com">
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="address" class="form-label">Dirección</label>
                                    <textarea class="form-control" id="address" name="address" rows="2" required placeholder="Dirección completa para envío"></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required placeholder="Número de contacto">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label">Método de Pago</label>
                                    
                                    <div class="payment-method mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="deposit" value="deposit" checked>
                                            <label class="form-check-label" for="deposit">
                                                <i class="bi bi-bank"></i> Depósito Bancario
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="payment-method">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash">
                                            <label class="form-check-label" for="cash">
                                                <i class="bi bi-cash"></i> Efectivo en Tienda
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="notes" class="form-label">Notas Adicionales</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Instrucciones especiales para tu pedido"></textarea>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" name="submit_checkout" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Completar Pedido
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="order-summary">
                        <h4 class="mb-4"><i class="bi bi-cart-check"></i> Resumen del Pedido</h4>
                        
                        <?php if (empty($cartData)): ?>
                            <div class="alert alert-warning">No hay productos en tu carrito</div>
                        <?php else: ?>
                            <?php foreach ($cartData as $item): ?>
                                <div class="product-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?= $item['name'] ?></h6>
                                        <small class="text-muted"><?= $item['quantity'] ?> x $<?= number_format($item['price'], 2) ?></small>
                                    </div>
                                    <div class="fw-bold">
                                        $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <div class="total-row d-flex justify-content-between">
                                <span>Total</span>
                                <span>$<?= number_format(array_reduce($cartData, function($carry, $item) { 
                                    return $carry + ($item['price'] * $item['quantity']); 
                                }, 0), 2) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        OH STORE &copy; <?= date('Y') ?> - Todos los derechos reservados
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Activar estilos para métodos de pago seleccionados
        document.querySelectorAll('.form-check-input').forEach(input => {
            input.addEventListener('change', function() {
                document.querySelectorAll('.payment-method').forEach(method => {
                    method.classList.remove('active');
                });
                
                if (this.checked) {
                    this.closest('.payment-method').classList.add('active');
                }
            });
            
            // Activar el método por defecto
            if (input.checked) {
                input.closest('.payment-method').classList.add('active');
            }
        });
    </script>
</body>
</html>