<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

// Check if ID is set
if (!isset($_GET['id'])) {
    header("Location: manage_eventos.php");
    exit();
}

$evento_id = $_GET['id'];

// Fetch evento data
$stmt = $conn->prepare("SELECT * FROM proximos_eventos WHERE id = ?");
$stmt->bind_param("i", $evento_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: manage_eventos.php");
    exit();
}

$evento = $result->fetch_assoc();

// Handle Update Evento
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_evento'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $tipo = $_POST['tipo'];
    $lugar = $_POST['lugar'];
    $fecha_estimada = $_POST['fecha_estimada'];
    
    $stmt = $conn->prepare("UPDATE proximos_eventos SET nombre = ?, descripcion = ?, tipo = ?, lugar = ?, fecha_estimada = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $nombre, $descripcion, $tipo, $lugar, $fecha_estimada, $evento_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: manage_eventos.php");
    exit();
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento - Panel de Administración</title>
    
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
            --evento: #6f42c1;
            --lugar: #20c997;
        }

        body {
            font-family: 'Sniglet', cursive;
            background-color: #f8f9fa;
            background-image: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            padding: 20px;
        }

        .edit-evento-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            transition: all 0.3s ease;
        }

        .edit-evento-container:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .edit-evento-title {
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

        .form-label {
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
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

        .btn-outline-secondary {
            border-radius: 10px;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            transform: translateY(-2px);
        }

        .preview-image {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border-radius: 8px;
            border: 2px solid #e1e5ee;
            transition: all 0.3s ease;
        }

        .preview-image:hover {
            transform: scale(1.05);
        }

        .current-image-container {
            margin-top: 15px;
            text-align: center;
        }

        .current-image {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            border: 2px solid #e1e5ee;
        }

        .admin-footer {
            text-align: center;
            margin-top: 2rem;
            color: #7f8c8d;
            font-size: 0.9rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="edit-evento-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="edit-evento-title"><i class="bi bi-calendar-event"></i> Editar Evento</h1>
            <a href="manage_eventos.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver a Eventos
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Editando: <?= htmlspecialchars($evento['nombre']) ?></h5>
            </div>
            <div class="card-body">
                <form action="edit_evento.php?id=<?= $evento_id ?>" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($evento['nombre']) ?>" required placeholder="Nombre del evento">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo</label>
                            <select class="form-select" name="tipo" required>
                                <option value="producto" <?= $evento['tipo'] == 'producto' ? 'selected' : '' ?>>Llegada de Producto</option>
                                <option value="evento" <?= $evento['tipo'] == 'evento' ? 'selected' : '' ?>>Evento Especial</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3" required placeholder="Descripción detallada del evento"><?= htmlspecialchars($evento['descripcion']) ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lugar</label>
                            <select class="form-select" name="lugar" required>
                                <option value="tienda" <?= $evento['lugar'] == 'tienda' ? 'selected' : '' ?>>Tienda</option>
                                <option value="cafeteria" <?= $evento['lugar'] == 'cafeteria' ? 'selected' : '' ?>>Cafetería</option>
                                <option value="ambos" <?= $evento['lugar'] == 'ambos' ? 'selected' : '' ?>>Ambos</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha Estimada</label>
                            <input type="date" class="form-control" name="fecha_estimada" value="<?= $evento['fecha_estimada'] ?>" required>
                        </div>
                    </div>
                    
                    
                    <div class="action-buttons">
                        <button type="submit" name="update_evento" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Cambios
                        </button>
                        <a href="manage_eventos.php" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="admin-footer">
            OH STORE &copy; <?php echo date('Y'); ?> - Panel de Administración
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image preview functionality
        document.getElementById('imageUpload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    
                    // Hide current image if exists
                    const currentImage = document.getElementById('currentImage');
                    if (currentImage) {
                        currentImage.style.display = 'none';
                    }
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Handle remove image checkbox
        const removeImageCheckbox = document.getElementById('removeImage');
        if (removeImageCheckbox) {
            removeImageCheckbox.addEventListener('change', function() {
                const currentImage = document.getElementById('currentImage');
                if (this.checked) {
                    currentImage.style.opacity = '0.5';
                } else {
                    currentImage.style.opacity = '1';
                }
            });
        }
    </script>
</body>
</html>