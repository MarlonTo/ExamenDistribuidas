<?php
require_once 'config.php';

// Obtener todos los equipos
$equipos = callAPI('GET', EQUIPOS_API);

// Manejar eliminación
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    callAPI('DELETE', EQUIPOS_API . '/' . $id);
    header('Location: equipos.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Equipos</title>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Lista de Equipos</h2>
            <a href="equipo_form.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Equipo
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Fecha Creación</th>
                        <th>Miembros</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($equipos): ?>
                        <?php foreach ($equipos as $equipo): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($equipo['id']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['descripcion']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($equipo['fechaCreacion'])); ?></td>
                                <td>
                                    <span class="badge bg-primary">
                                        <?php echo count($equipo['equipoMiembros']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="equipo_form.php?id=<?php echo $equipo['id']; ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="equipo_miembros.php?id=<?php echo $equipo['id']; ?>" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    <a href="javascript:void(0);" 
                                       onclick="if(confirm('¿Está seguro de eliminar este equipo?')) window.location.href='equipos.php?delete=<?php echo $equipo['id']; ?>'"
                                       class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay equipos registrados</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 