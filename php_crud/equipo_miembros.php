<?php
require_once 'config.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id) {
    header('Location: equipos.php');
    exit;
}

$error = '';
$success = '';

// Obtener datos del equipo
$equipo = callAPI('GET', EQUIPOS_API . '/' . $id);
if (!$equipo) {
    header('Location: equipos.php');
    exit;
}

// Obtener miembros actuales del equipo
$miembros = [];
if (!empty($equipo['equipoMiembros'])) {
    foreach ($equipo['equipoMiembros'] as $em) {
        $miembro = callAPI('GET', MIEMBROS_API . '/' . $em['miembroId']);
        if ($miembro) {
            $miembros[] = $miembro;
        }
    }
}

// Obtener todos los miembros disponibles
$todosLosMiembros = callAPI('GET', MIEMBROS_API);

// Filtrar miembros que no están en el equipo
$miembrosDisponibles = array_filter($todosLosMiembros, function($m) use ($miembros) {
    foreach ($miembros as $miembroEquipo) {
        if ($m['idMiembro'] == $miembroEquipo['idMiembro']) {
            return false;
        }
    }
    return true;
});

// Manejar agregar miembro
if (isset($_POST['agregar_miembro'])) {
    $miembroId = $_POST['miembro_id'];
    $result = callAPI('POST', EQUIPOS_API . '/' . $id . '/miembros', ['idMiembro' => $miembroId]);
    if ($result) {
        $success = 'Miembro agregado exitosamente';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . $id);
        exit;
    } else {
        $error = 'Error al agregar el miembro';
    }
}

// Manejar eliminar miembro
if (isset($_GET['remove'])) {
    $miembroId = $_GET['remove'];
    $result = callAPI('DELETE', EQUIPOS_API . '/' . $id . '/miembros/' . $miembroId);
    if ($result !== false) {
        $success = 'Miembro eliminado exitosamente';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . $id);
        exit;
    } else {
        $error = 'Error al eliminar el miembro';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miembros del Equipo</title>
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
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Miembros del Equipo: <?php echo htmlspecialchars($equipo['nombre']); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <?php echo $success; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Formulario para agregar miembro -->
                        <?php if (!empty($miembrosDisponibles)): ?>
                            <form method="post" class="mb-4">
                                <div class="row align-items-end">
                                    <div class="col-md-8">
                                        <label for="miembro_id" class="form-label">Agregar Miembro</label>
                                        <select class="form-select" name="miembro_id" required>
                                            <option value="">Seleccione un miembro</option>
                                            <?php foreach ($miembrosDisponibles as $m): ?>
                                                <option value="<?php echo $m['idMiembro']; ?>">
                                                    <?php echo htmlspecialchars($m['nombreMiembro']); ?> 
                                                    (<?php echo htmlspecialchars($m['correoMiembro']); ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" name="agregar_miembro" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Agregar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        <?php endif; ?>

                        <!-- Lista de miembros actuales -->
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Teléfono</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($miembros)): ?>
                                        <?php foreach ($miembros as $miembro): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($miembro['nombreMiembro']); ?></td>
                                                <td><?php echo htmlspecialchars($miembro['correoMiembro']); ?></td>
                                                <td><?php echo htmlspecialchars($miembro['telefono']); ?></td>
                                                <td>
                                                    <a href="javascript:void(0);" 
                                                       onclick="if(confirm('¿Está seguro de eliminar este miembro del equipo?')) window.location.href='equipo_miembros.php?id=<?php echo $id; ?>&remove=<?php echo $miembro['idMiembro']; ?>'"
                                                       class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No hay miembros en este equipo</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <a href="equipos.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver a Equipos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 