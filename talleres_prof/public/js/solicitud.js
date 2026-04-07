$(function () {

    const urlBase = "index.php";

    // ── Cargar solicitudes pendientes ──
    function cargarSolicitudes() {
        $.ajax({
            url: urlBase,
            type: 'GET',
            data: { option: 'solicitudes_json' },
            dataType: 'json',
            success: function (solicitudes) {
                let tbody = $("#solicitudes-body");
                tbody.empty();

                if (solicitudes.length === 0) {
                    tbody.append('<tr><td colspan="6" class="text-center">No hay solicitudes pendientes.</td></tr>');
                    return;
                }

                $.each(solicitudes, function (i, s) {
                    let fila = `<tr id="row-${s.id}">
                        <td>${s.id}</td>
                        <td>${s.taller_nombre}</td>
                        <td>${s.usuario_nombre}</td>
                        <td>${s.cupo_disponible}</td>
                        <td>${s.fecha_solicitud}</td>
                        <td>
                            <button class="btn btn-success btn-sm btn-aprobar me-1" data-id="${s.id}">
                                ✓ Aprobar
                            </button>
                            <button class="btn btn-danger btn-sm btn-rechazar" data-id="${s.id}">
                                ✗ Rechazar
                            </button>
                        </td>
                    </tr>`;
                    tbody.append(fila);
                });
            },
            error: function () {
                $("#solicitudes-body").html('<tr><td colspan="6" class="text-center text-danger">Error al cargar solicitudes.</td></tr>');
            }
        });
    }

    // Cargar al iniciar
    cargarSolicitudes();

    // ── Aprobar solicitud ──
    $(document).on("click", ".btn-aprobar", function () {
        let btn = $(this);
        let solicitudId = btn.data("id");

        btn.prop("disabled", true).text("Aprobando...");
        btn.closest("tr").find(".btn-rechazar").prop("disabled", true);

        $.ajax({
            url: urlBase,
            type: 'POST',
            data: {
                option: 'aprobar',
                id_solicitud: solicitudId
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $("#mensaje").html('<div class="alert alert-success">Solicitud aprobada. Cupo descontado.</div>');
                    cargarSolicitudes(); // Recargar tabla
                } else {
                    btn.prop("disabled", false).text("✓ Aprobar");
                    btn.closest("tr").find(".btn-rechazar").prop("disabled", false);
                    $("#mensaje").html('<div class="alert alert-danger">' + response.error + '</div>');
                }
            },
            error: function () {
                btn.prop("disabled", false).text("✓ Aprobar");
                $("#mensaje").html('<div class="alert alert-danger">Error de conexión.</div>');
            }
        });
    });

    // ── Rechazar solicitud ──
    $(document).on("click", ".btn-rechazar", function () {
        let btn = $(this);
        let solicitudId = btn.data("id");

        btn.prop("disabled", true).text("Rechazando...");
        btn.closest("tr").find(".btn-aprobar").prop("disabled", true);

        $.ajax({
            url: urlBase,
            type: 'POST',
            data: {
                option: 'rechazar',
                id_solicitud: solicitudId
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $("#mensaje").html('<div class="alert alert-warning">Solicitud rechazada.</div>');
                    cargarSolicitudes(); // Recargar tabla
                } else {
                    btn.prop("disabled", false).text("✗ Rechazar");
                    btn.closest("tr").find(".btn-aprobar").prop("disabled", false);
                    $("#mensaje").html('<div class="alert alert-danger">' + response.error + '</div>');
                }
            },
            error: function () {
                btn.prop("disabled", false).text("✗ Rechazar");
                $("#mensaje").html('<div class="alert alert-danger">Error de conexión.</div>');
            }
        });
    });

    // ── Cerrar sesión ──
    $("#btnLogout").on("click", function () {
        $.post(urlBase, { option: "logout" }, function () {
            window.location.href = "index.php?page=login";
        });
    });

})
