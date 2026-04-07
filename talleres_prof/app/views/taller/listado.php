<!DOCTYPE html>
<html>

<head>

    <title>Listado Talleres</title>

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
            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <a href="index.php?page=admin" class="btn btn-outline-secondary btn-sm">Gestionar Solicitudes</a>
            <?php endif; ?>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted"><?= htmlspecialchars($_SESSION['user'] ?? 'Usuario') ?></span>
            <button id="btnLogout" class="btn btn-danger btn-sm">Cerrar sesión</button>
        </div>
    </nav>

    <main>
        <h3>Talleres Disponibles</h3>

        <div id="mensaje" class="mt-2 mb-2"></div>

        <table class="table table-bordered" id="tabla-talleres">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Cupo Disponible</th>
                    <th>Cupo Máximo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="talleres-body">
                <tr>
                    <td colspan="6" class="text-center">Cargando talleres...</td>
                </tr>
            </tbody>
        </table>
    </main>

    <script src="public/js/taller.js"></script>

</body>

</html>
