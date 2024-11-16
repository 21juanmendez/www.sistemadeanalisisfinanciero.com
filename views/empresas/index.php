<?php
include('../layout/parte1.php');
include('../../app/controllers/empresas/controller_empresas.php');
include('../../app/controllers/tipo_empresas/controller_read.php');
include('mensaje.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><b>LISTADO DE EMPRESAS</b></h3>
                </div>
                <div class="card-body">
                    <?php
                    if (isset($_SESSION['admin'])) { ?>
                        <!-- Sección de Filtro para Mostrar Todas las Empresas alineada a la derecha con estilo mejorado -->
                        <div class="d-flex align-items-center justify-content-between mt-2 p-2" style="border: 1px solid #dee2e6; border-radius: 8px; background-color: #f8f9fa;">
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrarEmpresaModal">
                                <i class="bi bi-plus-lg"></i> <b>Nueva Empresa</b>
                            </a>

                            <div class="d-flex align-items-center">
                                <label class="form-check-label me-4" for="mostrarTodas" style="font-size: 1.1rem; font-weight: 600; color: #495057;">
                                    Mostrar todas las empresas
                                </label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="mostrarTodas" onchange="toggleMostrarTodas()"
                                        <?php echo isset($_GET['mostrar_todas']) && $_GET['mostrar_todas'] == '1' ? 'checked' : ''; ?>>
                                </div>
                            </div>
                        </div>
                        <style>
                            /* Estilo personalizado para el interruptor */
                            .form-check-input {
                                width: 2.5em;
                                height: 1.5em;
                                background-color: #6c757d;
                                border-radius: 15px;
                                transition: background-color 0.3s ease, box-shadow 0.3s ease;
                            }

                            .form-check-input:checked {
                                background-color: #0d6efd;
                                box-shadow: 0px 0px 10px rgba(13, 110, 253, 0.4);
                            }
                        </style>
                    <?php
                    } 
                    ?>

                    <!-- Modal -->
                    <div class="modal fade" id="registrarEmpresaModal" tabindex="-1" aria-labelledby="registrarEmpresaModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: #0d6efd;">
                                    <h5 class="modal-title" id="registrarEmpresaModalLabel" style="color: white;"><b>Registrar Nueva Empresa</b></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Formulario dentro del modal -->
                                    <form id="empresaForm" action="<?php echo $URL; ?>/app/controllers/empresas/controller_create.php" method="POST">
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label">Nombre de la Empresa</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre">
                                            <small id="errorNombre" class="text-danger"></small>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tipo" class="form-label">Tipo de Empresa</label>
                                            <select class="form-select" id="tipo" name="tipo">
                                                <option value="" selected>Seleccionar tipo de empresa</option>
                                                <?php foreach ($result as $tipo_empresa): ?>
                                                    <option value="<?php echo $tipo_empresa['id_tipoEmpresa']; ?>">
                                                        <?php echo $tipo_empresa['nombre_tipoEmpresa']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small id="errorTipo" class="text-danger"></small>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <table id="example1" class="table table-striped table-hover table-borderless">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">
                                            <center>#</center>
                                        </th>
                                        <th scope="col">
                                            <center>Nombre</center>
                                        </th>
                                        <th scope="col">
                                            <center>Tipo de empresa</center>
                                        </th>
                                        <th scope="col">
                                            <center>Acciones</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($empresas as $empresa) {
                                    ?>
                                        <tr>
                                            <td>
                                                <center><?php echo $i++; ?></center>
                                            </td>
                                            <td>
                                                <center><?php echo $empresa['nombre_empresa']; ?></center>
                                            </td>
                                            <td>
                                                <center><?php echo $empresa['nombre_tipoEmpresa']; ?></center>
                                            </td>
                                            <td>
                                                <center>
                                                    <?php
                                                    if (isset($_SESSION['admin'])) { ?>
                                                        <a href="<?php echo $VIEWS; ?>/empresas/opciones.php?id_empresa=<?php echo $empresa['id_empresa']; ?>" class="btn btn-dark">
                                                            <i class="bi bi-columns-gap"></i>
                                                        </a>
                                                        <a href="<?php echo $VIEWS; ?>/empresas/show.php?id_empresa=<?php echo $empresa['id_empresa']; ?>" class="btn btn-info">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="<?php echo $VIEWS; ?>/empresas/update.php?id_empresa=<?php echo $empresa['id_empresa']; ?>" class="btn btn-warning">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>

                                                        <!-- Botón para activar/desactivar con ícono dinámico -->
                                                        <a href="#" class="btn <?php echo $empresa['estado'] == 1 ? 'btn-success' : 'btn-secondary'; ?>"
                                                            onclick="toggleStatus(<?php echo $empresa['id_empresa']; ?>, <?php echo $empresa['estado']; ?>)">
                                                            <i class="bi bi-toggle-<?php echo $empresa['estado'] == 1 ? 'on' : 'off'; ?>"></i>
                                                        </a>

                                                        <!-- Script para mostrar alerta de confirmación al cambiar estado -->
                                                        <script>
                                                            function toggleStatus(id, currentStatus) {
                                                                // Define el nuevo estado y el texto de confirmación basado en el estado actual
                                                                const newStatus = currentStatus === 1 ? 0 : 1;
                                                                const confirmationText = currentStatus === 1 ? 'desactivar' : 'activar';

                                                                Swal.fire({
                                                                    title: `¿Estás seguro de que deseas ${confirmationText} esta empresa?`,
                                                                    icon: 'warning',
                                                                    showCancelButton: true,
                                                                    confirmButtonText: 'Sí, cambiar estado!',
                                                                    cancelButtonText: 'No, cancelar!',
                                                                    reverseButtons: false,
                                                                    customClass: {
                                                                        confirmButton: 'btn btn-danger me-2', // Clase para el botón de confirmar en rojo
                                                                        cancelButton: 'btn btn-secondary' // Clase para el botón de cancelar en gris
                                                                    },
                                                                    buttonsStyling: false // Desactiva el estilo predeterminado de SweetAlert para aplicar clases de Bootstrap
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        // Redirige a la URL con los parámetros de id y estado
                                                                        window.location.href = '<?php echo $URL; ?>/app/controllers/empresas/controller_toggle_status.php?id_empresa=' + id + '&estado=' + newStatus;
                                                                    }
                                                                });
                                                            }
                                                        </script>

                                                        <!-- Botón para eliminar la empreas-->
                                                        <a href="#" class="btn btn-danger" onclick="confirmDelete(<?php echo $empresa['id_empresa']; ?>)">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </a>
                                                        <!-- Script para mostrar alerta de confirmación al eliminar-->
                                                        <script>
                                                            const swalWithBootstrapButtons = Swal.mixin({
                                                                customClass: {
                                                                    confirmButton: "btn btn-success me-2", // Añade "me-2" para margen derecho
                                                                    cancelButton: "btn btn-danger"
                                                                },
                                                                buttonsStyling: false
                                                            });

                                                            function confirmDelete(id) {
                                                                swalWithBootstrapButtons.fire({
                                                                    title: '¿Estás seguro?',
                                                                    text: "¡No podrás revertir esto!",
                                                                    icon: 'warning',
                                                                    showCancelButton: true,
                                                                    confirmButtonText: 'Sí, eliminarlo!',
                                                                    cancelButtonText: 'No, cancelar!',
                                                                    reverseButtons: false
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        // Redirige al controlador de eliminación si se confirma
                                                                        window.location.href = '<?php echo $URL; ?>/app/controllers/empresas/controller_delete.php?id_empresa=' + id;
                                                                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                                                                        swalWithBootstrapButtons.fire({
                                                                            title: 'Cancelado',
                                                                            text: 'La empresa está segura :)',
                                                                            icon: 'error'
                                                                        });
                                                                    }
                                                                });
                                                            }
                                                        </script>
                                                    <?php
                                                    } else { ?>
                                                        <a href="<?php echo $VIEWS; ?>/empresas/opciones.php?id_empresa=<?php echo $empresa['id_empresa']; ?>" class="btn btn-dark">
                                                            <i class="bi bi-columns-gap"></i>
                                                        </a>
                                                        <a href="<?php echo $VIEWS; ?>/empresas/show.php?id_empresa=<?php echo $empresa['id_empresa']; ?>" class="btn btn-info">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    <?php
                                                    }
                                                    ?>


                                                </center>
                                            </td>
                                        </tr>
                                    <?php
                                    } // Fin del foreach
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

<script>
    // Función para redirigir con el parámetro de "mostrar todas"
    function toggleMostrarTodas() {
        const checkbox = document.getElementById('mostrarTodas');
        const mostrarTodas = checkbox.checked ? 1 : 0;
        window.location.href = "<?php echo $_SERVER['PHP_SELF']; ?>?mostrar_todas=" + mostrarTodas;
    }
</script>
<!-- Script para validar los campos -->
<script>
    document.getElementById('empresaForm').addEventListener('submit', function(event) {
        let isValid = true;

        // Validación para Nombre de la Empresa
        const nombreEmpresa = document.getElementById('nombre').value.trim();
        const errorNombre = document.getElementById('errorNombre');
        if (nombreEmpresa === "") {
            errorNombre.innerText = "El nombre de la empresa es obligatorio";
            errorNombre.style.display = 'block';
            isValid = false;
        } else {
            errorNombre.style.display = 'none';
        }

        // Validación para Tipo de Empresa
        const tipoEmpresa = document.getElementById('tipo').value;
        const errorTipo = document.getElementById('errorTipo');
        if (tipoEmpresa === "") {
            errorTipo.innerText = "Debe seleccionar un tipo de empresa";
            errorTipo.style.display = 'block';
            isValid = false;
        } else {
            errorTipo.style.display = 'none';
        }

        // Si hay errores, se previene el envío del formulario
        if (!isValid) {
            event.preventDefault();
        }
    });
</script>


<?php
include('../layout/parte2.php');
?>