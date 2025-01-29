<?php
require_once 'config.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;
$equipo = [
    'nombre' => '',
    'descripcion' => ''
];
$error = '';

// Si es edición, cargar datos del equipo
if ($id) {
    $equipo = callAPI('GET', EQUIPOS_API . '/' . $id);
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipo = [
        'nombre' => $_POST['nombre'],
        'descripcion' => $_POST['descripcion']
    ];

    // Validaciones
    if (empty($equipo['nombre'])) {
        $error = 'El nombre es requerido';
    } elseif (strlen($equipo['nombre']) < 3) {
        $error = 'El nombre debe tener al menos 3 caracteres';
    } elseif (!empty($equipo['descripcion']) && strlen($equipo['descripcion']) > 500) {
        $error = 'La descripción no puede exceder los 500 caracteres';
    }

    if (empty($error)) {
        if ($id) {
            callAPI('PUT', EQUIPOS_API . '/' . $id, $equipo);
        } else {
            callAPI('POST', EQUIPOS_API, $equipo);
        }
        header('Location: equipos.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Editar' : 'Nuevo' ?> Equipo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Sistema de Gestión</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="miembros.php">Miembros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="equipos.php">Equipos</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <?php echo $id ? 'Editar' : 'Nuevo' ?> Equipo
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                       value="<?php echo htmlspecialchars($equipo['nombre']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="4"
                                          maxlength="500"><?php echo htmlspecialchars($equipo['descripcion']); ?></textarea>
                                <div class="form-text">Máximo 500 caracteres</div>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="equipos.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">
                                    <?php echo $id ? 'Actualizar' : 'Crear' ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 