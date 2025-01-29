<?php
require_once 'config.php';

// Obtener todos los miembros
$miembros = callAPI('GET', MIEMBROS_API);

// Manejar eliminación
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    callAPI('DELETE', MIEMBROS_API . '/' . $id);
    header('Location: miembros.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Miembros</title>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Lista de Miembros</h2>
            <a href="miembro_form.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Miembro
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($miembros): ?>
                        <?php foreach ($miembros as $miembro): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($miembro['idMiembro']); ?></td>
                                <td><?php echo htmlspecialchars($miembro['nombreMiembro']); ?></td>
                                <td><?php echo htmlspecialchars($miembro['correoMiembro']); ?></td>
                                <td><?php echo htmlspecialchars($miembro['telefono']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($miembro['fechaRegistro'])); ?></td>
                                <td>
                                    <a href="miembro_form.php?id=<?php echo $miembro['idMiembro']; ?>" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" 
                                       onclick="if(confirm('¿Está seguro de eliminar este miembro?')) window.location.href='miembros.php?delete=<?php echo $miembro['idMiembro']; ?>'"
                                       class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay miembros registrados</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 