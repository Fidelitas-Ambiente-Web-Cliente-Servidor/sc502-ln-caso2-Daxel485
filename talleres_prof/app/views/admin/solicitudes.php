<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Solicitudes pendientes</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
    <script src="public/js/jquery-4.0.0.min.js"></script>
</head>
<body class="container mt-5">

    <nav class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded">
        <div>
            <a href="index.php?page=talleres" class="btn btn-outline-primary btn-sm me-2">Talleres</a>
            <a href="index.php?page=admin" class="btn btn-outline-secondary btn-sm">Gestionar Solicitudes</a>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted">Admin: <?= htmlspecialchars($_SESSION['user'] ?? 'Administrador') ?></span>
            <button id="btnLogout" class="btn btn-danger btn-sm">Cerrar sesión</button>
        </div>
    </nav>

    <main>
        <h2>Solicitudes pendientes de aprobación</h2>

        <div id="mensaje" class="mt-2 mb-2"></div>

        <table class="table table-bordered" id="tabla-solicitudes">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Taller</th>
                    <th>Solicitante</th>
                    <th>Cupos disp.</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="solicitudes-body">
                <tr>
                    <td colspan="6" class="text-center">Cargando solicitudes...</td>
                </tr>
            </tbody>
        </table>
    </main>

    <script src="public/js/solicitud.js"></script>

</body>
</html>
