$(function () {

    const urlBase = "index.php";

    // ── Cargar talleres disponibles ──
    function cargarTalleres() {
        $.ajax({
            url: urlBase,
            type: 'GET',
            data: { option: 'talleres_json' },
            dataType: 'json',
            success: function (talleres) {
                let tbody = $("#talleres-body");
                tbody.empty();

                if (talleres.length === 0) {
                    tbody.append('<tr><td colspan="6" class="text-center">No hay talleres disponibles.</td></tr>');
                    return;
                }

                $.each(talleres, function (i, t) {
                    let fila = `<tr>
                        <td>${t.id}</td>
                        <td>${t.nombre}</td>
                        <td>${t.descripcion}</td>
                        <td>${t.cupo_disponible}</td>
                        <td>${t.cupo_maximo}</td>
                        <td>
                            <button class="btn btn-success btn-sm btn-solicitar" data-id="${t.id}">
                                Solicitar inscripción
                            </button>
                        </td>
                    </tr>`;
                    tbody.append(fila);
                });
            },
            error: function () {
                $("#talleres-body").html('<tr><td colspan="6" class="text-center text-danger">Error al cargar talleres.</td></tr>');
            }
        });
    }

    // Cargar al iniciar
    cargarTalleres();

    // ── Solicitar inscripción ──
    $(document).on("click", ".btn-solicitar", function () {
        let btn = $(this);
        let tallerId = btn.data("id");

        btn.prop("disabled", true).text("Enviando...");

        $.ajax({
            url: urlBase,
            type: 'POST',
            data: {
                option: 'solicitar',
                taller_id: tallerId
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    btn.text("✓ Solicitud enviada").removeClass("btn-success").addClass("btn-secondary");
                    $("#mensaje").html('<div class="alert alert-success">Solicitud enviada correctamente. Espera la aprobación del administrador.</div>');
                } else {
                    btn.prop("disabled", false).text("Solicitar inscripción");
                    $("#mensaje").html('<div class="alert alert-danger">' + response.error + '</div>');
                }
            },
            error: function () {
                btn.prop("disabled", false).text("Solicitar inscripción");
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
