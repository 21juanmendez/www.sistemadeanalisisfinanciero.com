<?php
include('../layout/parte1.php');
include('../../app/controllers/empresas/controller_read.php');
include('../../app/controllers/catalogo/controller_catalogo.php');
include('mensaje.php');

// Obtener tipos de cuenta
$sql_tipos = "SELECT id_tipo, nombre_tipo FROM tipos_cuenta";
$result_tipos = $pdo->query($sql_tipos);

// Obtener clasificaciones
$sql_clasificaciones = "SELECT id_clasificacion, nombre_clasificacion FROM clasificaciones";
$result_clasificaciones = $pdo->query($sql_clasificaciones);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><b>CATÁLOGO DE CUENTAS [<?php echo $nombre_empresa ?>]</b></h3>
                </div>
                <div class="card-body">
                    <a href="../empresas/opciones.php?id_empresa=<?php echo $id_empresa ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> <b>Regresar</b>
                    </a>

                    <!-- Botón para abrir el modal -->
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrarCuentaModal">
                        <i class="bi bi-plus-lg"></i> <b>Agregar Cuenta</b>
                    </a>

                    <!-- Modal -->
                    <div class="modal fade" id="registrarCuentaModal" tabindex="-1" aria-labelledby="registrarCuentaModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="registrarCuentaModalLabel"><b>Agregar Cuenta [<?php echo $nombre_empresa ?>]</b></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="cuentaForm" method="POST">
                                        <input type="hidden" name="id_empresa" value="<?php echo $id_empresa; ?>">
                                        <div class="row g-3">
                                            <!-- Nombre de la cuenta -->
                                            <div class="col-md-6">
                                                <label for="nombre_cuenta" class="form-label">Nombre de la Cuenta</label>
                                                <input type="text" class="form-control" id="nombre_cuenta" name="nombre_cuenta" placeholder="Ej. Caja">
                                                <small id="nombreError" class="text-danger"></small>
                                            </div>
                                            <!-- Tipo de cuenta -->
                                            <div class="col-md-6">
                                                <label for="id_tipoCuenta" class="form-label">Tipo de Cuenta</label>
                                                <select class="form-select" id="id_tipoCuenta" name="id_tipoCuenta">
                                                    <option value="">Selecciona un tipo de cuenta</option>
                                                    <?php foreach ($result_tipos as $tipo): ?>
                                                        <option value="<?php echo $tipo['id_tipo']; ?>">
                                                            <?php echo $tipo['nombre_tipo']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <small id="tipoError" class="text-danger"></small>
                                            </div>
                                        </div>
                                        <div class="row g-3 mt-3">
                                            <!-- Clasificación -->
                                            <div class="col-md-6">
                                                <label for="clasificacion" class="form-label">Clasificación</label>
                                                <div class="d-flex flex-wrap">
                                                    <?php foreach ($result_clasificaciones as $clasificacion): ?>
                                                        <div class="form-check me-3">
                                                            <input class="form-check-input" type="radio" id="clasificacion<?php echo $clasificacion['id_clasificacion']; ?>" name="id_clasificacion" value="<?php echo $clasificacion['id_clasificacion']; ?>">
                                                            <label class="form-check-label" for="clasificacion<?php echo $clasificacion['id_clasificacion']; ?>">
                                                                <?php echo $clasificacion['nombre_clasificacion']; ?>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <small id="clasificacionError" class="text-danger"></small>
                                            </div>
                                            <!-- Saldo -->
                                            <div class="col-md-6">
                                                <label for="saldo" class="form-label">Saldo</label>
                                                <input type="number" class="form-control" id="saldo" name="saldo" value="0.0" placeholder="Ej. 2000" step="0.01">
                                                <small id="saldoError" class="text-danger"></small>
                                            </div>
                                        </div>
                                        <div class="modal-footer mt-4">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary">Guardar Cuenta</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        // Función para mostrar la notificación con toastr
                        function mostrarNotificacionToastr(mensaje) {
                            toastr.success(mensaje, '¡Éxito!', {
                                timeOut: 3000,
                                progressBar: true,
                                positionClass: 'toast-top-right'
                            });
                        }

                        document.getElementById('cuentaForm').addEventListener('submit', function(event) {
                            event.preventDefault(); // Evita el envío tradicional

                            // Limpiar mensajes de error previos
                            document.getElementById("nombreError").innerText = "";
                            document.getElementById("tipoError").innerText = "";
                            document.getElementById("clasificacionError").innerText = "";
                            document.getElementById("saldoError").innerText = "";

                            // Validaciones en el frontend
                            const nombre = document.getElementById("nombre_cuenta").value.trim();
                            const tipoCuenta = document.getElementById("id_tipoCuenta").value;
                            const clasificacionRadios = document.getElementsByName("id_clasificacion");
                            const saldo = document.getElementById("saldo").value.trim();

                            let isValid = true;

                            // Validación del nombre
                            if (!nombre) {
                                document.getElementById("nombreError").innerText = "El nombre de la cuenta es obligatorio";
                                isValid = false;
                            }

                            // Validación del tipo de cuenta
                            if (!tipoCuenta) {
                                document.getElementById("tipoError").innerText = "Debe seleccionar un tipo de cuenta";
                                isValid = false;
                            }

                            // Validación de la clasificación
                            if (![...clasificacionRadios].some(radio => radio.checked)) {
                                document.getElementById("clasificacionError").innerText = "Debe seleccionar una clasificación";
                                isValid = false;
                            }

                            // Validación del saldo
                            if (!saldo || parseFloat(saldo) < 0) {
                                document.getElementById("saldoError").innerText = "El saldo debe ser un número mayor o igual a 0";
                                isValid = false;
                            }

                            // Si no es válido, detener el envío
                            if (!isValid) {
                                return;
                            }

                            // Crear FormData y enviar mediante AJAX si es válido
                            const formData = new FormData(this);

                            fetch('<?php echo $URL; ?>/app/controllers/catalogo/controller_create.php', {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    console.log('Respuesta del servidor:', data); // Depuración: imprime la respuesta completa en consola
                                    if (data.success) {
                                        // Mostrar mensaje de éxito con la función de notificación personalizada
                                        mostrarNotificacionToastr('La cuenta se ha agregado correctamente.');

                                        // Limpiar campos del formulario, excepto el ID de la empresa
                                        document.getElementById("nombre_cuenta").value = "";
                                        document.getElementById("saldo").value = "";
                                        document.getElementById("id_tipoCuenta").value = "";
                                        clasificacionRadios.forEach(radio => radio.checked = false);

                                        // Verificar si la tabla está vacía
                                        const tableBody = document.querySelector('#example2 tbody');
                                        const isTableEmpty = tableBody.rows.length === 0;

                                        // Agregar la nueva cuenta a la tabla sin recargar
                                        const newRow = document.createElement('tr');
                                        newRow.innerHTML = `
                                            <td><center>${formData.get('nombre_cuenta')}</center></td>
                                            <td><center>${data.nombre_clasificacion}</center></td>
                                            <td><center>${data.nombre_tipo}</center></td>
                                            <td><center>${data.saldo}</center></td>
                                            <td><center>
                                                <a href="#" class="btn btn-info"><i class="bi bi-eye"></i></a>
                                                <a href="#" class="btn btn-warning"><i class="bi bi-pencil-square"></i></a>
                                                <a href="#" class="btn btn-danger" onclick="confirmDeleteCuenta(${data.id_cuenta}, ${data.id_empresa})">
                                                    <i class="bi bi-trash-fill"></i>
                                                </a>
                                            </center></td>
                                        `;
                                        tableBody.appendChild(newRow);

                                        // Destruir y reconstruir DataTable si la tabla estaba vacía al agregar la primera fila
                                        if (isTableEmpty) {
                                            $('#example2').DataTable().destroy();
                                            $('#example2').DataTable();
                                        } else {
                                            // Solo agregar la fila si ya existe DataTable
                                            $('#example2').DataTable().row.add(newRow).draw();
                                        }
                                    } else {
                                        // Mostrar mensaje de error con toastr
                                        toastr.error(data.message || "Error al agregar la cuenta.", 'Error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error en la solicitud:', error);
                                    toastr.error("Ocurrió un error al enviar la solicitud.", 'Error');
                                });
                        });
                    </script>


                    <br><br>
                    <div class="row">
                        <div class="col-md-12">
                            <table id="example2" class="table table-striped table-hover table-borderless">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">
                                            <center>Nombre de Cuenta</center>
                                        </th>
                                        <th scope="col">
                                            <center>Clasificación</center>
                                        </th>
                                        <th scope="col">
                                            <center>Tipo</center>
                                        </th>
                                        <th scope="col">
                                            <center>Saldo</center>
                                        </th>
                                        <th scope="col">
                                            <center>Acciones</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí se agregarán dinámicamente las filas de la tabla -->
                                    <?php
                                    foreach ($cuentas as $cuenta) {
                                    ?>
                                        <tr>
                                            <td>
                                                <center><?php echo $cuenta['nombre']; ?></center>
                                            </td>
                                            <td>
                                                <center><?php echo $cuenta['nombre_clasificacion']; ?></center>
                                            </td>
                                            <td>
                                                <center><?php echo $cuenta['nombre_tipo']; ?></center>
                                            </td>
                                            <td>
                                                <center><?php echo $cuenta['saldo']; ?></center>
                                            </td>
                                            <td>
                                                <center>
                                                    <a href="<?php echo $VIEWS ?>/catalogo/show.php?id_empresa=<?php echo $id_empresa ?>&id_cuenta=<?php echo $cuenta['id_cuenta'] ?>" class="btn btn-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>

                                                    <a href="<?php echo $VIEWS ?>/catalogo/update.php?id_empresa=<?php echo $id_empresa ?>&id_cuenta=<?php echo $cuenta['id_cuenta'] ?>" class="btn btn-warning">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <!-- Botón para eliminar la cuenta -->
                                                    <a href="#" class="btn btn-danger" onclick="confirmDeleteCuenta(<?php echo $cuenta['id_cuenta']; ?>, <?php echo $id_empresa; ?>)">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </a>

                                                    <!-- Script para mostrar alerta de confirmación al eliminar -->
                                                    <script>
                                                        const swalWithBootstrapButtonsCuenta = Swal.mixin({
                                                            customClass: {
                                                                confirmButton: "btn btn-success me-2", // Botón rojo para confirmar
                                                                cancelButton: "btn btn-danger" // Botón gris para cancelar
                                                            },
                                                            buttonsStyling: false
                                                        });

                                                        function confirmDeleteCuenta(id_cuenta, id_empresa) {
                                                            swalWithBootstrapButtonsCuenta.fire({
                                                                title: '¿Estás seguro?',
                                                                text: "¡No podrás revertir esto!",
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonText: 'Sí, eliminarla!',
                                                                cancelButtonText: 'No, cancelar!',
                                                                reverseButtons: false
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    // Redirige al controlador de eliminación de la cuenta si se confirma
                                                                    window.location.href = '<?php echo $URL; ?>/app/controllers/catalogo/controller_delete.php?id_cuenta=' + id_cuenta + '&id_empresa=' + id_empresa;
                                                                } else if (result.dismiss === Swal.DismissReason.cancel) {
                                                                    swalWithBootstrapButtonsCuenta.fire({
                                                                        title: 'Cancelado',
                                                                        text: 'La cuenta está segura :)',
                                                                        icon: 'error'
                                                                    });
                                                                }
                                                            });
                                                        }
                                                    </script>
                                                </center>
                                            </td>
                                        </tr>

                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
include('../layout/parte2.php');
?>