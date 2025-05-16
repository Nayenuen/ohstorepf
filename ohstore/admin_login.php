<?php
session_start();
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM administradores WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Admin found, set session variable
        $_SESSION['admin_email'] = $email;
        header("Location: admin_dashboard.php"); 
        exit();
    } else {
        $error_message = "Invalid email or password.";
    }

    $stmt->close(); // Close the statement
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    
    <!-- Google Fonts - Sniglet -->
    <link href="https://fonts.googleapis.com/css2?family=Sniglet&display=swap" rel="stylesheet">
    
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

        .login-container {
            max-width: 500px;
            width: 100%;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .login-container:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }

        .login-title {
            color: var(--primary);
            font-weight: 800;
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 2.2rem;
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
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(113, 159, 229, 0.25);
        }

        .btn-login {
            background-color: var(--primary);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            background-color: var(--navbar-blue-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(113, 159, 229, 0.3);
        }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            color: var(--navbar-blue-hover);
            transform: translateX(-3px);
        }

        .error-message {
            color: #e74c3c;
            background-color: #fadbd8;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: 600;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-footer {
            text-align: center;
            margin-top: 2rem;
            color: #7f8c8d;
            font-size: 0.9rem;
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
    <div class="login-container">
        <h1 class="login-title">Admin Login</h1>
        
        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <form action="admin_login.php" method="POST">
            <div class="form-group mb-4">
                <label for="email">Email:</label>
                <div class="input-icon">
                    <i class="bi bi-envelope-fill"></i>
                    <input type="email" class="form-control" name="email" required placeholder="tu@email.com">
                </div>
            </div>
            
            <div class="form-group mb-4">
                <label for="password">Password:</label>
                <div class="input-icon">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" class="form-control" name="password" required placeholder="••••••••">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-login">
                Login
            </button>
            
            <a href="index.php" class="btn-back">
                <i class="bi bi-arrow-left"></i> Volver al Inicio
            </a>
        </form>
        
        <div class="login-footer">
            OH STORE &copy; <?php echo date('Y'); ?> - Panel de Administración
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    
    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>