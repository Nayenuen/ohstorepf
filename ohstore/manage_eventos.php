<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

// Handle Add Evento
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_evento'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $tipo = $_POST['tipo'];
    $lugar = $_POST['lugar'];
    $fecha_estimada = $_POST['fecha_estimada'];
    
    
    $stmt = $conn->prepare("INSERT INTO proximos_eventos (nombre, descripcion, tipo, lugar, fecha_estimada) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nombre, $descripcion, $tipo, $lugar, $fecha_estimada);
    $stmt->execute();
    $stmt->close();
}

// Handle Delete Evento
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    
    // Then delete the record
    $stmt = $conn->prepare("DELETE FROM proximos_eventos WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: manage_eventos.php");
    exit();
}

// Fetch all eventos
$result = $conn->query("SELECT * FROM proximos_eventos ORDER BY fecha_estimada ASC");
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Próximos Eventos - Panel de Administración</title>
    
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

        .events-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            transition: all 0.3s ease;
        }

        .events-container:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .events-title {
            color: var(--primary);
            font-weight: 800;
            margin-bottom: 1.5rem;
            font-size: 2.2rem;
            border-bottom: 3px solid var(--primary);
            padding-bottom: 10px;
            display: inline-block;
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

        .btn-outline-primary {
            border-radius: 10px;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            transform: translateY(-2px);
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            margin-top: 20px;
        }

        .table thead {
            background-color: var(--primary);
            color: white;
        }

        .table th {
            font-weight: 600;
            padding: 15px;
        }

        .table td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(113, 159, 229, 0.1);
            transform: scale(1.005);
        }

        .badge-producto {
            background-color: var(--primary);
            color: white;
        }

        .badge-evento {
            background-color: var(--evento);
            color: white;
        }

        .badge-lugar {
            background-color: var(--lugar);
            color: white;
        }

        .badge-pasado {
            background-color: var(--danger);
            color: white;
        }

        .badge-proximo {
            background-color: var(--success);
            color: white;
        }

        .evento-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .evento-img:hover {
            transform: scale(1.1);
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
            padding: 8px 12px;
        }
    </style>
</head>
<body>
    <div class="events-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="events-title"><i class="bi bi-calendar-event"></i> Gestión de Eventos</h1>
            <a href="admin_dashboard.php" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Volver al Panel
            </a>
        </div>

        <!-- Add Evento Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Agregar Nuevo Evento/Llegada</h5>
            </div>
            <div class="card-body">
                <form action="manage_eventos.php" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required placeholder="Nombre del evento o producto">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo</label>
                            <select class="form-select" name="tipo" required>
                                <option value="producto">Llegada de Producto</option>
                                <option value="evento">Evento Especial</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3" required placeholder="Descripción detallada del evento o producto"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Lugar</label>
                            <select class="form-select" name="lugar" required>
                                <option value="tienda">Tienda</option>
                                <option value="cafeteria">Cafetería</option>
                                <option value="ambos">Ambos</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fecha Estimada</label>
                            <input type="date" class="form-control" name="fecha_estimada" required>
                        </div>
    
                    </div>
                    
                    <button type="submit" name="add_evento" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar Evento
                    </button>
                </form>
            </div>
        </div>

        <!-- Eventos Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list-check"></i> Lista de Eventos y Llegadas</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Lugar</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>

                                <td>
                                    <strong><?= htmlspecialchars($row['nombre']) ?></strong><br>
                                    <small class="text-muted"><?= substr(htmlspecialchars($row['descripcion']), 0, 50) ?>...</small>
                                </td>
                                <td>
                                    <span class="badge <?= $row['tipo'] == 'producto' ? 'badge-producto' : 'badge-evento' ?>">
                                        <?= ucfirst($row['tipo']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-lugar">
                                        <?= ucfirst($row['lugar']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= date('d/m/Y', strtotime($row['fecha_estimada'])) ?>
                                    <br>
                                    <span class="badge <?= $row['fecha_estimada'] > date('Y-m-d') ? 'badge-proximo' : 'badge-pasado' ?>">
                                        <?= $row['fecha_estimada'] > date('Y-m-d') ? 'Próximo' : 'Pasado' ?>
                                    </span>
                                </td>
                                <td class="action-buttons">
                                    <a href="edit_evento.php?id=<?= $row['id'] ?>" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    <a href="manage_eventos.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este evento/llegada?');">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
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