<?php
session_start();
$products = json_decode(file_get_contents('products.json'), true);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo</title>

    <!-- For favicon png -->
    <link rel="shortcut icon" type="image/icon" href="assets/logo/logo.ico"/>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts - Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">
    
    <!-- Estilos personalizados -->
    <style>
        :root {
            --primary: #A4C1ED;
            --secondary: #5f5b57;
            --light: #f8f9fd;
            --navbar-blue: rgb(113, 159, 229);
            --navbar-blue-hover: rgb(88, 144, 228);
        }

        body {
            font-family: 'Roboto', sans-serif;
            padding-top: 80px;
            background-color: #fff;
            color: #333;
        }

        /* Títulos con color azul */
        h1, h2, h3, h4, h5, h6 {
            color: var(--navbar-blue);
            font-weight: 700;
        }

        /* Navbar estilizado */
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar .navbar-brand,
        .navbar .nav-link {
            color: var(--navbar-blue) !important;
            font-weight: 500;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link:focus {
            color: var(--navbar-blue-hover) !important;
        }

        .navbar .nav-link.active {
            font-weight: 600;
        }

        /* Secciones */
        section {
            padding: 60px 0;
        }

        .section-header {
            margin-bottom: 40px;
            text-align: center;
        }

        .section-header h2 {
            font-size: 2.2rem;
            margin-bottom: 15px;
        }

        .section-header p {
            color: #666;
            font-size: 1.1rem;
        }

        /* Productos */
        .product-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .product-img-container {
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9f9f9;
            padding: 20px;
        }

        .product-img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-img {
            transform: scale(1.05);
        }

        .card-body {
            padding: 20px;
            text-align: center;
        }

        .product-title {
            font-weight: 500;
            color: var(--navbar-blue);
            margin-bottom: 10px;
        }

        .product-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }

        /* Botones */
        .btn-add-to-cart {
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-add-to-cart:hover {
            background-color: var(--navbar-blue);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(113, 159, 229, 0.3);
        }

        /* Carrito */
        #shoppingCart {
            background-color: #f8f9fa;
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead th {
            background-color: var(--primary);
            color: white;
            font-weight: 500;
        }

        /* Badge del carrito */
        #cartBadge {
            top: -8px;
            right: -8px;
            font-size: 10px;
            padding: 3px 6px;
        }

        /* Dropdown del carrito */
        .dropdown-cart {
            width: 350px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 0;
        }

        .dropdown-items {
            max-height: 300px;
            overflow-y: auto;
        }

        .cart-item {
            border-bottom: 1px solid #eee;
            padding: 10px;
        }

        .cart-item-img {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        /* Animaciones */
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .bounce {
            animation: bounce 0.5s;
        }

        /* Responsive */
        @media (max-width: 768px) {
            section {
                padding: 40px 0;
            }
            
            .section-header h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
          
        <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-N5GD1VJ7K7"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-N5GD1VJ7K7');
</script>
        
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="">OH STORE</a>
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
                        <a class="nav-link active" href="catalogo.php">Catálogo</a>
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
                    <li class="nav-item dropdown mx-2">
                        <a class="nav-link dropdown-toggle position-relative p-0" href="#" id="cartDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-cart fs-4"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartBadge" style="display: none;">
                                0
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-cart" aria-labelledby="cartDropdown">
                            <div class="dropdown-header p-3">
                                <h6 class="m-0">Tu Carrito</h6>
                            </div>
                            <div id="cartDropdownItems" class="dropdown-items">
                                <div class="text-center p-3">
                                    <span>Carrito vacío</span>
                                </div>
                            </div>
                            <div class="dropdown-footer p-3 bg-light">
                                <div class="d-grid gap-2">
                                    <a href="#shoppingCart" class="btn btn-sm btn-outline-dark">Ver Carrito</a>
                                    <button class="btn btn-sm btn-primary" id="dropdownCheckoutBtn">Checkout</button>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Catálogo -->
    <section class="py-5">
        <div class="container">
            <div class="section-header">
                <h2 class="fw-bold">Catálogo</h2>
                <p class="text-muted">Explora nuestra selección de productos</p>
            </div>
            
            <div class="row" id="productsGrid">
                <?php foreach ($products as $product): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card product-card h-100">
                        <div class="product-img-container">
                            <img src="<?= $product['image'] ?>" class="product-img" alt="<?= $product['name'] ?>">
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title product-title"><?= $product['name'] ?></h5>
                            <p class="card-text product-price">$<?= number_format($product['price'], 2) ?></p>
                            <button class="btn btn-add-to-cart" data-id="<?= $product['id'] ?>">
                                Agregar al carrito
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Carrito -->
    <section id="shoppingCart" class="py-5 bg-light">
        <div class="container">
            <div class="section-header">
                <h2 class="fw-bold">Tu Carrito</h2>
                <p class="text-muted">Revisa tus productos seleccionados</p>
            </div>
            
            <div class="table-responsive bg-white rounded shadow-sm">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Acciones</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="cartTableBody">
                        <tr>
                            <td colspan="5" class="text-center py-4">Carrito vacío</td>
                        </tr>
                    </tbody>
                    <tfoot id="cartTableFooter"></tfoot>
                </table>
            </div>
        </div>
    </section>

    <!-- Templates -->
    <template id="cartItemTemplate">
        <tr>
            <td class="product-id"></td>
            <td class="product-name"></td>
            <td class="product-quantity"></td>
            <td>
                <button class="btn btn-sm btn-success increase-btn">+</button>
                <button class="btn btn-sm btn-danger decrease-btn">-</button>
            </td>
            <td class="product-total">$<span class="amount"></span></td>
        </tr>
    </template>
    
    <template id="cartFooterTemplate">
        <tr class="table-light">
            <td colspan="2">Total</td>
            <td id="totalQuantity"></td>
            <td>
                <button id="clearCartBtn" class="btn btn-sm btn-outline-danger">Vaciar todo</button>
                <form action="checkout.php" method="post" style="display: inline;">
                    <input type="hidden" name="cartData" id="cartDataInput">
                    <button type="submit" id="checkoutBtn" class="btn btn-sm btn-success">Checkout</button>
                </form>
            </td>
            <td class="fw-bold">$<span id="totalPrice"></span></td>
        </tr>
    </template>
    
    <template id="dropdownItemTemplate">
        <div class="cart-item d-flex align-items-center p-3">
            <img src="" class="cart-item-img me-3" width="60">
            <div class="flex-grow-1">
                <h6 class="mb-1 product-name"></h6>
                <div class="d-flex justify-content-between">
                    <span>$<span class="product-price"></span> x <span class="product-quantity"></span></span>
                    <button class="btn btn-sm btn-outline-danger remove-item-btn" data-id="">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript del Carrito -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables globales
            let cart = JSON.parse(localStorage.getItem('cart')) || {};
            const products = <?php echo json_encode($products); ?>;
            
            // Elementos del DOM
            const cartBadge = document.getElementById('cartBadge');
            const cartTableBody = document.getElementById('cartTableBody');
            const cartTableFooter = document.getElementById('cartTableFooter');
            const cartDropdownItems = document.getElementById('cartDropdownItems');
            const dropdownCheckoutBtn = document.getElementById('dropdownCheckoutBtn');
            
            // Inicializar el carrito
            updateCart();
            
            // Eventos
            document.addEventListener('click', function(e) {
                // Agregar al carrito
                if (e.target.classList.contains('btn-add-to-cart')) {
                    const productId = e.target.dataset.id;
                    addToCart(productId);
                    animateCartIcon();
                }
                
                // Incrementar cantidad
                if (e.target.classList.contains('increase-btn')) {
                    const productId = e.target.closest('tr').querySelector('.product-id').textContent;
                    updateQuantity(productId, 1);
                }
                
                // Decrementar cantidad
                if (e.target.classList.contains('decrease-btn')) {
                    const productId = e.target.closest('tr').querySelector('.product-id').textContent;
                    updateQuantity(productId, -1);
                }
                
                // Eliminar item
                if (e.target.classList.contains('remove-item-btn') || e.target.closest('.remove-item-btn')) {
                    const button = e.target.classList.contains('remove-item-btn') ? e.target : e.target.closest('.remove-item-btn');
                    const productId = button.dataset.id;
                    removeFromCart(productId);
                }
                
                // Vaciar carrito
                if (e.target.id === 'clearCartBtn') {
                    if (confirm('¿Estás seguro de vaciar el carrito?')) {
                        clearCart();
                    }
                }
                
                // Checkout desde dropdown
                if (e.target.id === 'dropdownCheckoutBtn') {
                    document.getElementById('checkoutBtn').click();
                }
            });
            
            // Funciones del carrito
            function addToCart(productId) {
                const product = products.find(p => p.id == productId);
                if (!product) return;
                
                if (cart[productId]) {
                    cart[productId].quantity += 1;
                } else {
                    cart[productId] = {
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        image: product.image,
                        quantity: 1
                    };
                }
                
                saveCart();
            }
            
            function updateQuantity(productId, change) {
                if (cart[productId]) {
                    cart[productId].quantity += change;
                    
                    if (cart[productId].quantity <= 0) {
                        delete cart[productId];
                    }
                    
                    saveCart();
                }
            }
            
            function removeFromCart(productId) {
                delete cart[productId];
                saveCart();
            }
            
            function clearCart() {
                cart = {};
                saveCart();
            }
            
            function saveCart() {
                localStorage.setItem('cart', JSON.stringify(cart));
                updateCart();
            }
            
            function updateCart() {
                updateCartBadge();
                renderCartTable();
                renderDropdownCart();
                updateCheckoutData();
            }
            
            function updateCartBadge() {
                const totalItems = Object.values(cart).reduce((total, item) => total + item.quantity, 0);
                cartBadge.textContent = totalItems;
                cartBadge.style.display = totalItems > 0 ? 'block' : 'none';
            }
            
            function renderCartTable() {
                cartTableBody.innerHTML = '';
                
                if (Object.keys(cart).length === 0) {
                    cartTableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-4">Carrito vacío</td>
                        </tr>
                    `;
                    cartTableFooter.innerHTML = '';
                    return;
                }
                
                Object.values(cart).forEach(item => {
                    const template = document.getElementById('cartItemTemplate').content.cloneNode(true);
                    template.querySelector('.product-id').textContent = item.id;
                    template.querySelector('.product-name').textContent = item.name;
                    template.querySelector('.product-quantity').textContent = item.quantity;
                    template.querySelector('.amount').textContent = (item.price * item.quantity).toFixed(2);
                    
                    cartTableBody.appendChild(template);
                });
                
                renderCartFooter();
            }
            
            function renderCartFooter() {
                const template = document.getElementById('cartFooterTemplate').content.cloneNode(true);
                const totalQuantity = Object.values(cart).reduce((total, item) => total + item.quantity, 0);
                const totalPrice = Object.values(cart).reduce((total, item) => total + (item.price * item.quantity), 0);
                
                template.querySelector('#totalQuantity').textContent = totalQuantity;
                template.querySelector('#totalPrice').textContent = totalPrice.toFixed(2);
                
                // Configurar datos del carrito para enviar
                const cartDataInput = template.querySelector('#cartDataInput');
                cartDataInput.value = JSON.stringify(cart);
                
                // Habilitar/deshabilitar botón de checkout
                const checkoutBtn = template.querySelector('#checkoutBtn');
                checkoutBtn.disabled = totalQuantity <= 0;
                
                cartTableFooter.innerHTML = '';
                cartTableFooter.appendChild(template);
            }
            
            function renderDropdownCart() {
                cartDropdownItems.innerHTML = '';
                
                if (Object.keys(cart).length === 0) {
                    cartDropdownItems.innerHTML = `
                        <div class="text-center p-3">
                            <span>Carrito vacío</span>
                        </div>
                    `;
                    return;
                }
                
                Object.values(cart).forEach(item => {
                    const template = document.getElementById('dropdownItemTemplate').content.cloneNode(true);
                    template.querySelector('img').src = item.image;
                    template.querySelector('.product-name').textContent = item.name;
                    template.querySelector('.product-price').textContent = item.price.toFixed(2);
                    template.querySelector('.product-quantity').textContent = item.quantity;
                    template.querySelector('.remove-item-btn').dataset.id = item.id;
                    
                    cartDropdownItems.appendChild(template);
                });
            }
            
            function updateCheckoutData() {
                const input = document.getElementById('cartDataInput');
                if (input) {
                    input.value = JSON.stringify(cart);
                }
            }
            
            function animateCartIcon() {
                const cartIcon = document.querySelector('.bi-cart');
                cartIcon.classList.add('bounce');
                setTimeout(() => cartIcon.classList.remove('bounce'), 500);
            }
        });
    </script>
</body>
</html>