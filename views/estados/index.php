<?php
include('../layout/parte1.php');
include('../../app/controllers/empresas/controller_read.php');
include('../../app/controllers/estados/controller_estados.php');
include('../../app/controllers/catalogo/controller_catalogo.php');
include('mensaje.php');

// Obtener clasificaciones
$sql_clasificaciones = "SELECT id_clasificacion, nombre_clasificacion FROM clasificaciones";
$result_clasificaciones = $pdo->query($sql_clasificaciones)->fetchAll(PDO::FETCH_ASSOC);

// Obtener cuentas específicas para la empresa seleccionada junto con el tipo de cuenta
$sql_cuentas = "
    SELECT c.id_cuenta, c.nombre, c.id_clasificacion, tc.nombre_tipo AS tipo
    FROM cuentas c
    INNER JOIN tipos_cuenta tc ON c.id_tipo = tc.id_tipo
    WHERE c.id_empresa = :id_empresa";
$query_cuentas = $pdo->prepare($sql_cuentas);
$query_cuentas->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);
$query_cuentas->execute();
$result_cuentas = $query_cuentas->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><b>ESTADOS FINANCIEROS [<?php echo $nombre_empresa ?>]</b></h3>
                </div>
                <div class="card-body">
                    <a href="../empresas/opciones.php?id_empresa=<?php echo $id_empresa ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> <b>Regresar</b>
                    </a>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrarEstadoFinancieroModal">
                        <i class="bi bi-plus-lg"></i> <b>Crear Estado Financiero</b>
                    </a>

                    <br><br>
                    <div class="row">
                        <div class="col-md-12">
                            <table id="example3" class="table table-striped table-hover table-borderless">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">
                                            <center>#</center>
                                        </th>
                                        <th scope="col">
                                            <center>Tipo de Estado</center>
                                        </th>
                                        <th scope="col">
                                            <center>Año</center>
                                        </th>
                                        <th scope="col">
                                            <center>Acciones</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($estados_financieros as $estado) { // Supongamos que `$estados_financieros` tiene los datos de los estados financieros
                                    ?>
                                        <tr>
                                            <td>
                                                <center><?php echo $i++; ?></center>
                                            </td>
                                            <td>
                                                <center><?php echo $estado['tipo_estado']; ?></center>
                                            </td>
                                            <td>
                                                <center><?php echo $estado['anio']; ?></center>
                                            </td>
                                            <td>
                                                <center>
                                                    <!-- Botón en la tabla para abrir el modal y pasar id_estado, tipo_estado y anio -->
                                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarCuentaModal"
                                                        onclick="setEstadoId(<?php echo $estado['id_estado']; ?>, '<?php echo $estado['anio']; ?>', '<?php echo $estado['tipo_estado']; ?>')">
                                                        <i class="bi bi-plus"></i> Agregar
                                                    </button>

                                                    <script>
                                                        function setEstadoId(idEstado, anio, tipoEstado) {
                                                            // Asigna el id_estado y el año al modal
                                                            document.getElementById('id_estado').value = idEstado;
                                                            document.getElementById('anioEstado').innerText = anio; // Asigna el año en el lugar correcto del modal

                                                            // Muestra u oculta el campo "Tipo de Movimiento" según el tipo de estado
                                                            const tipoMovimientoContainer = document.getElementById('tipoMovimientoContainer');
                                                            if (tipoEstado === 'Estado de Resultados') {
                                                                tipoMovimientoContainer.style.display = 'none';
                                                            } else {
                                                                tipoMovimientoContainer.style.display = 'block';
                                                            }
                                                        }
                                                    </script>

                                                    <a href="<?php echo $VIEWS; ?>/estados/show.php?id_estado=<?php echo urlencode($estado['id_estado']); ?>
                                                        &tipo_estado=<?php echo urlencode($estado['tipo_estado']); ?>
                                                        &id_empresa=<?php echo urlencode($id_empresa); ?>"
                                                        class="btn btn-info">
                                                        <i class="bi bi-eye-fill"></i> Detalles
                                                    </a>

                                                    <a href="#" class="btn btn-danger" onclick="confirmDeleteEstado(<?php echo $estado['id_estado']; ?>)">
                                                        <i class="bi bi-trash-fill"></i> Eliminar
                                                    </a>

                                                    <script>
                                                        const swalWithBootstrapButtonsCuenta = Swal.mixin({
                                                            customClass: {
                                                                confirmButton: "btn btn-success me-2",
                                                                cancelButton: "btn btn-danger"
                                                            },
                                                            buttonsStyling: false
                                                        });

                                                        function confirmDeleteEstado(id_estado) {
                                                            swalWithBootstrapButtonsCuenta.fire({
                                                                title: '¿Estás seguro?',
                                                                text: "¡No podrás revertir esto!",
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonText: 'Sí, eliminarlo!',
                                                                cancelButtonText: 'No, cancelar!',
                                                                reverseButtons: false
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    // Realiza la solicitud de eliminación con fetch
                                                                    fetch(`<?php echo $URL; ?>/app/controllers/estados/controller_delete.php?id_estado=${id_estado}`, {
                                                                            method: 'GET'
                                                                        })
                                                                        .then(response => response.json())
                                                                        .then(data => {
                                                                            if (data.success) {
                                                                                // Eliminar la fila de la tabla de inmediato sin esperar recarga
                                                                                // Encuentra el botón que llamó a la función y usa su contexto para encontrar la fila a eliminar
                                                                                const button = document.querySelector(`a[onclick="confirmDeleteEstado(${id_estado})"]`);
                                                                                const row = button.closest("tr");
                                                                                if (row) {
                                                                                    row.remove(); // Remueve la fila de la tabla
                                                                                }

                                                                                // Mostrar mensaje de éxito
                                                                                swalWithBootstrapButtonsCuenta.fire({
                                                                                    title: 'Eliminado',
                                                                                    text: 'El estado financiero ha sido eliminado.',
                                                                                    icon: 'success',
                                                                                    timer: 2000,
                                                                                    showConfirmButton: false
                                                                                });
                                                                            } else {
                                                                                // Mostrar mensaje de error si ocurre algún problema
                                                                                swalWithBootstrapButtonsCuenta.fire({
                                                                                    title: 'Error',
                                                                                    text: data.message || 'Ocurrió un error al eliminar el estado financiero.',
                                                                                    icon: 'error'
                                                                                });
                                                                            }
                                                                        })
                                                                        .catch(error => {
                                                                            // Manejo de error en la solicitud
                                                                            swalWithBootstrapButtonsCuenta.fire({
                                                                                title: 'Error',
                                                                                text: 'Ocurrió un error en la solicitud al eliminar el estado financiero.',
                                                                                icon: 'error'
                                                                            });
                                                                            console.error("Error en la solicitud:", error);
                                                                        });
                                                                } else if (result.dismiss === Swal.DismissReason.cancel) {
                                                                    swalWithBootstrapButtonsCuenta.fire({
                                                                        title: 'Cancelado',
                                                                        text: 'El estado financiero está seguro :)',
                                                                        icon: 'error'
                                                                    });
                                                                }
                                                            });
                                                        }
                                                    </script>
                                                </center>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear un nuevo Estado Financiero -->
<div class="modal fade" id="registrarEstadoFinancieroModal" tabindex="-1" aria-labelledby="registrarEstadoFinancieroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0d6efd;">
                <h5 class="modal-title" id="registrarEstadoFinancieroModalLabel" style="color: white;"><b>Registrar Nuevo Estado Financiero</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="estadoFinancieroForm" action="<?php echo $URL ?>/app/controllers/estados/controller_create.php" method="POST" onsubmit="return validarFormulario()">
                <div class="modal-body">
                    <input type="hidden" name="id_empresa" value="<?php echo $id_empresa; ?>">

                    <div class="mb-3">
                        <label for="tipo_estado">Tipo de Estado Financiero</label>
                        <select id="tipo_estado" name="tipo_estado" class="form-select">
                            <option value="">Seleccione el tipo</option>
                            <option value="Balance General">Balance General</option>
                            <option value="Estado de Resultados">Estado de Resultados</option>
                        </select>
                        <small id="errorTipoEstado" class="text-danger"></small>
                    </div>
                    <div class="mb-3">
                        <label for="anio">Año</label>
                        <input type="number" id="anio" name="anio" class="form-control" placeholder="Ej. 2024">
                        <small id="errorAnio" class="text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Estado Financiero</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function validarFormulario() {
        let isValid = true;

        // Obtener los valores de los campos
        const tipoEstado = document.getElementById('tipo_estado').value.trim();
        const anio = document.getElementById('anio').value.trim();

        // Limpiar mensajes de error previos
        document.getElementById('errorTipoEstado').innerText = "";
        document.getElementById('errorAnio').innerText = "";

        // Validar el campo Tipo de Estado Financiero
        if (tipoEstado === "") {
            document.getElementById('errorTipoEstado').innerText = "Seleccione un tipo de estado financiero.";
            isValid = false;
        }

        // Validar el campo Año
        if (anio === "") {
            document.getElementById('errorAnio').innerText = "Ingrese un año válido.";
            isValid = false;
        } else if (anio < 2000 || anio > new Date().getFullYear() - 1) {
            document.getElementById('errorAnio').innerText = "El año debe ser entre 2000 y el año anterior al actual";
            isValid = false;
        }

        return isValid; // Prevenir envío si hay errores
    }
</script>


<!-- Modal para agregar una cuenta al estado financiero -->
<div class="modal fade" id="agregarCuentaModal" tabindex="-1" aria-labelledby="agregarCuentaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Header del modal con el año del estado -->
            <div class="modal-header" style="background-color: #0d6efd;">
                <h5 class="modal-title" id="agregarCuentaModalLabel" style="color: white;">
                    <b>Agregar Cuentas al Estado Financiero [<span id="anioEstado"></span>]</b>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="addCuentaForm" action="<?php echo $URL ?>/app/controllers/estados/controller_add_cuenta.php" method="POST" onsubmit="return validarFormularioCuenta()">
                <div class="modal-body">
                    <input type="hidden" id="id_estado" name="id_estado" value="">
                    <input type="hidden" name="id_empresa" value="<?php echo $id_empresa; ?>">

                    <!-- Clasificaciones como botones de selección -->
                    <div class="mb-3">
                        <label for="clasificacion">Clasificación</label>
                        <div class="d-flex justify-content-center">
                            <div id="clasificacionesContainer" class="d-flex flex-wrap gap-2">
                                <?php foreach ($result_clasificaciones as $clasificacion): ?>
                                    <button type="button" class="btn btn-outline-primary clasificacion-btn"
                                        data-id="<?php echo $clasificacion['id_clasificacion']; ?>"
                                        onclick="toggleClasificacion(this)">
                                        <?php echo $clasificacion['nombre_clasificacion']; ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Campo de Cuenta -->
                    <div class="mb-3">
                        <label for="cuenta">Cuenta</label>
                        <select id="cuenta" name="id_cuenta" class="form-select">
                            <option value="">Seleccione la cuenta</option>
                            <?php foreach ($result_cuentas as $cuenta): ?>
                                <option value="<?php echo $cuenta['id_cuenta']; ?>" data-clasificacion="<?php echo $cuenta['id_clasificacion']; ?>">
                                    <?php echo $cuenta['nombre'] . " (" . $cuenta['tipo'] . ")"; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small id="errorCuenta" class="text-danger d-none">Seleccione una cuenta válida.</small>
                        <small id="noCuentasMessage" class="text-danger mt-2" style="display: none;">
                            No hay cuentas disponibles para las clasificaciones seleccionadas.
                        </small>
                    </div>

                    <!-- Contenedor del Tipo de Movimiento -->
                    <div id="tipoMovimientoContainer" class="mb-3">
                        <label for="tipo_movimiento">Tipo de Movimiento</label>
                        <select name="tipo_movimiento" class="form-select">
                            <option value="">Seleccione el tipo</option>
                            <option value="Debe">Debe</option>
                            <option value="Haber">Haber</option>
                        </select>
                        <small id="errorMovimiento" class="text-danger d-none">Seleccione un tipo de movimiento.</small>
                    </div>
                    <!-- Campo oculto para el tipo de estado -->
                    <input type="hidden" id="tipo_estado" name="tipo_estado" value="<?php echo htmlspecialchars($estado_financiero['tipo_estado']); ?>">


                    <!-- Campo de Saldo -->
                    <div class="mb-3">
                        <label for="saldo">Saldo</label>
                        <input type="number" name="saldo" class="form-control" placeholder="Ej. 2000" step="0.01">
                        <small id="errorSaldo" class="text-danger d-none">Ingrese un saldo válido mayor o igual a 0.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Agregar Cuenta</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function abrirModalAgregarCuenta(idEstado, tipoEstado, anio) {
        // Establece el ID del estado en el modal
        document.getElementById('id_estado').value = idEstado;
        document.getElementById('anioEstado').textContent = anio;

        // Muestra u oculta el campo "Tipo de Movimiento" en función del tipo de estado
        const tipoMovimientoContainer = document.getElementById('tipoMovimientoContainer');
        if (tipoEstado === 'Estado de Resultados') {
            tipoMovimientoContainer.style.display = 'none';
        } else {
            tipoMovimientoContainer.style.display = 'block';
        }

        // Abre el modal
        const modal = new bootstrap.Modal(document.getElementById('agregarCuentaModal'));
        modal.show();
    }
</script>

<style>
    /* Espacio entre los botones de clasificación */
    .clasificacion-btn {
        margin-bottom: 5px;
    }

    /* Resaltar el botón seleccionado */
    .clasificacion-btn.active {
        background-color: #007bff;
        color: white;
    }
</style>

<script>
    // Array para almacenar clasificaciones seleccionadas
    const selectedClassifications = [];

    function toggleClasificacion(button) {
        const clasificacionId = button.dataset.id;

        // Alternar selección del botón
        if (button.classList.contains('active')) {
            button.classList.remove('active');
            const index = selectedClassifications.indexOf(clasificacionId);
            if (index > -1) {
                selectedClassifications.splice(index, 1);
            }
        } else {
            button.classList.add('active');
            selectedClassifications.push(clasificacionId);
        }

        filtrarCuentasPorClasificacion();
    }

    // Función para filtrar cuentas según las clasificaciones seleccionadas
    function filtrarCuentasPorClasificacion() {
        const cuentaSelect = document.getElementById('cuenta');
        const noCuentasMessage = document.getElementById('noCuentasMessage');

        // Si no hay clasificaciones seleccionadas, mostrar todas las cuentas
        if (selectedClassifications.length === 0) {
            for (const option of cuentaSelect.options) {
                option.style.display = 'block';
            }
            noCuentasMessage.style.display = 'none';
            cuentaSelect.removeAttribute('disabled');
            return;
        }

        // Si hay clasificaciones seleccionadas, aplicar el filtro
        let cuentasVisibles = 0;

        for (const option of cuentaSelect.options) {
            if (selectedClassifications.includes(option.dataset.clasificacion)) {
                option.style.display = 'block';
                cuentasVisibles++;
            } else {
                option.style.display = 'none';
            }
        }

        // Mostrar u ocultar el mensaje de "No hay cuentas"
        if (cuentasVisibles > 0) {
            noCuentasMessage.style.display = 'none';
            cuentaSelect.removeAttribute('disabled');
        } else {
            noCuentasMessage.style.display = 'block';
            cuentaSelect.setAttribute('disabled', 'disabled');
        }

        cuentaSelect.value = '';
    }


    function mostrarMensajeExito() {
        Swal.fire({
            title: '¡Cuenta agregada!',
            text: 'La cuenta se ha registrado exitosamente en el estado financiero.',
            icon: 'success',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            background: '#f0f9ff',
            customClass: {
                popup: 'animate__animated animate__bounceIn'
            }
        });
    }

    function mostrarNotificacionToastr() {
        toastr.success('La cuenta se ha agregado correctamente al estado financiero.', '¡Éxito!', {
            timeOut: 3000,
            progressBar: true,
            positionClass: 'toast-top-right'
        });
    }


    // Agregar cuenta al estado financiero con AJAX para no recargar la página
    document.getElementById('addCuentaForm').addEventListener('submit', function(event) {
        event.preventDefault();

        // Ejecutar la función de validación en el frontend
        if (!validarFormularioCuenta()) {
            console.log('Validación fallida en el frontend');
            return; // Si la validación falla, detener el envío
        }

        const formData = new FormData(this);

        fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta del servidor:', data); // Depuración: imprime la respuesta del servidor
                if (data.success) {
                    mostrarNotificacionToastr();
                    document.querySelector('[name="saldo"]').value = ''; // Limpia el campo de saldo
                    actualizarTablaCuentas(data.nuevaCuenta); // Actualiza la tabla de cuentas sin recargar la página
                } else {
                    console.error('Error al agregar la cuenta:', data.message);
                    alert(data.message || 'Error al agregar la cuenta.');
                }
            })
            .catch(error => console.error('Error en la solicitud AJAX:', error));
    });

    function validarFormularioCuenta() {
        let isValid = true;

        // Validar cuenta seleccionada
        const cuentaSelect = document.getElementById('cuenta');
        const errorCuenta = document.getElementById('errorCuenta');
        if (cuentaSelect.value === "") {
            errorCuenta.classList.remove("d-none");
            isValid = false;
        } else {
            errorCuenta.classList.add("d-none");
        }

        // Validar saldo
        const saldoInput = document.querySelector('input[name="saldo"]');
        const errorSaldo = document.getElementById('errorSaldo');
        if (saldoInput.value === "") {
            errorSaldo.classList.remove("d-none");
            isValid = false;
        } else {
            errorSaldo.classList.add("d-none");
        }

        // Obtener el valor de tipo_estado para determinar si es Balance General o Estado de Resultados
        const tipoEstado = document.getElementById('tipo_estado').value;

        // Validar tipo de movimiento solo si es un Balance General
        if (tipoEstado === "Balance General") {
            const movimientoSelect = document.querySelector('select[name="tipo_movimiento"]');
            const errorMovimiento = document.getElementById('errorMovimiento');

            if (movimientoSelect && movimientoSelect.value === "") {
                errorMovimiento.classList.remove("d-none");
                isValid = false;
            } else if (movimientoSelect) {
                errorMovimiento.classList.add("d-none");
            }
        }

        return isValid; // Devolver true solo si todos los campos son válidos
    }

    function actualizarTablaCuentas(nuevaCuenta) {
        const tableBody = document.querySelector('#tablaCuentas tbody');
        const row = document.createElement('tr');

        row.innerHTML = `
            <td>${nuevaCuenta.nombre_cuenta}</td>
            <td>${nuevaCuenta.tipo_movimiento === 'Debe' ? '$' + nuevaCuenta.saldo : ''}</td>
            <td>${nuevaCuenta.tipo_movimiento === 'Haber' ? '$' + nuevaCuenta.saldo : ''}</td>
        `;

        tableBody.appendChild(row);
    }
</script>

<?php
include('../layout/parte2.php');
?>