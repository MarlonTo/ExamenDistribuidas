<?php
require_once 'config.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;
$miembro = [
    'nombreMiembro' => '',
    'correoMiembro' => '',
    'telefono' => ''
];
$error = '';

// Si es edición, cargar datos del miembro
if ($id) {
    $miembro = callAPI('GET', MIEMBROS_API . '/' . $id);
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $miembro = [
        'nombreMiembro' => $_POST['nombreMiembro'],
        'correoMiembro' => $_POST['correoMiembro'],
        'telefono' => $_POST['telefono']
    ];

    // Validaciones
    if (empty($miembro['nombreMiembro'])) {
        $error = 'El nombre es requerido';
    } elseif (strlen($miembro['nombreMiembro']) < 3) {
        $error = 'El nombre debe tener al menos 3 caracteres';
    } elseif (empty($miembro['correoMiembro'])) {
        $error = 'El correo es requerido';
    } elseif (!filter_var($miembro['correoMiembro'], FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo no es válido';
    } elseif (empty($miembro['telefono'])) {
        $error = 'El teléfono es requerido';
    } elseif (!preg_match('/^\d{10}$/', $miembro['telefono'])) {
        $error = 'El teléfono debe tener 10 dígitos';
    }

    if (empty($error)) {
        if ($id) {
            callAPI('PUT', MIEMBROS_API . '/' . $id, $miembro);
        } else {
            callAPI('POST', MIEMBROS_API, $miembro);
        }
        header('Location: miembros.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Editar' : 'Nuevo' ?> Miembro</title>
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
                        <a class="nav-link active" href="miembros.php">Miembros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="equipos.php">Equipos</a>
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
                            <?php echo $id ? 'Editar' : 'Nuevo' ?> Miembro
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
                                <label for="nombreMiembro" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombreMiembro" name="nombreMiembro"
                                       value="<?php echo htmlspecialchars($miembro['nombreMiembro']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="correoMiembro" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="correoMiembro" name="correoMiembro"
                                       value="<?php echo htmlspecialchars($miembro['correoMiembro']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono"
                                       value="<?php echo htmlspecialchars($miembro['telefono']); ?>" 
                                       pattern="\d{10}" maxlength="10" required>
                                <div class="form-text">El teléfono debe tener 10 dígitos</div>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="miembros.php" class="btn btn-secondary">Cancelar</a>
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