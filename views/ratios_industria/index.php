<?php
include('../layout/parte1.php');
include('../../app/controllers/ratios_industria/controller_ratios_industria.php');
include('../../app/controllers/tipo_empresas/controller_read.php');
include('mensaje.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><b>LISTADO DE RATIOS DE LA INDUSTRIA</b></h3>
                </div>
                <div class="card-body">
                    <?php
                    if (isset($_SESSION['admin'])) { ?>
                        <!-- Sección de Filtro para Mostrar Todas las Empresas alineada a la derecha con estilo mejorado -->
                        <div class="d-flex align-items-center justify-content-between mt-2 p-2">
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrarEmpresaModal">
                                <i class="bi bi-plus-lg"></i> <b>Agregar Ratio</b>
                            </a>
                        </div>
                    <?php
                    }
                    ?>
                    <!-- Modal -->
                    <div class="modal fade" id="registrarEmpresaModal" tabindex="-1" aria-labelledby="registrarEmpresaModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: #0d6efd;">
                                    <h5 class="modal-title" id="registrarEmpresaModalLabel" style="color: white;"><b>Registrar Nuevo Ratio Industria</b></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="ratioForm" action="<?php echo $URL; ?>/app/controllers/ratios_industria/controller_create.php" method="POST">
                                        <!-- Selección del Ratio -->
                                        <div class="mb-3">
                                            <label for="nombre_ratio" class="form-label">Nombre del Ratio de Industria</label>
                                            <select class="form-select" id="nombre_ratio" name="nombre_ratio" required>
                                                <option value="" selected>Seleccionar ratio de industria</option>
                                                <?php
                                                $ratios_fijos = [
                                                    "Capital de Trabajo",
                                                    "Grado de Endeudamiento",
                                                    "Grado de Propiedad",
                                                    "Margen Neto",
                                                    "Prueba Ácida (Razón Rápida)",
                                                    "Razón de Capital de Trabajo",
                                                    "Razón de Circulante (Liquidez Corriente)",
                                                    "Razón de Endeudamiento Patrimonial",
                                                    "Rentabilidad sobre Activos (ROA)",
                                                    "Rentabilidad sobre Patrimonio (ROE)",
                                                    "Índice de Eficiencia Operativa"
                                                ];
                                                foreach ($ratios_fijos as $ratio) {
                                                    echo '<option value="' . $ratio . '">' . $ratio . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <small id="errorNombre" class="text-danger"></small>
                                        </div>
                                        <!-- Selección del Tipo de Empresa -->
                                        <div class="mb-3">
                                            <label for="id_tipoEmpresa" class="form-label">Tipo de Empresa</label>
                                            <select class="form-select" id="id_tipoEmpresa" name="id_tipoEmpresa" required>
                                                <option value="" selected>Seleccionar tipo de empresa</option>
                                                <?php
                                                $sql = "SELECT id_tipoEmpresa, nombre_tipoEmpresa FROM tipoempresa";
                                                $query = $pdo->prepare($sql);
                                                $query->execute();
                                                $tipos_empresas = $query->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($tipos_empresas as $tipo_empresa) {
                                                    echo '<option value="' . $tipo_empresa['id_tipoEmpresa'] . '">' . $tipo_empresa['nombre_tipoEmpresa'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <small id="errorTipo" class="text-danger"></small>
                                        </div>
                                        <!-- Campo para el Promedio -->
                                        <div class="mb-3">
                                            <label for="promedio" class="form-label">Promedio</label>
                                            <input type="number" step="0.0001" class="form-control" id="promedio" name="promedio" required>
                                            <small id="errorPromedio" class="text-danger"></small>
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
                            <table id="example4" class="table table-striped table-hover table-borderless">
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
                                            <center>Promedio</center>
                                        </th>
                                        <th scope="col">
                                            <center>Acciones</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($ratios_industria as $ratios) {
                                    ?>
                                        <tr>
                                            <td>
                                                <center><?php echo $i++; ?></center>
                                            </td>
                                            <td>
                                                <center><?php echo $ratios['nombre_ratio_industria']; ?></center>
                                            </td>
                                            <td>
                                                <center><?php echo $ratios['nombre_tipoEmpresa']; ?></center>
                                            </td>
                                            <td>
                                                <center><?php echo $ratios['promedio']; ?></center>
                                            </td>
                                            <td>
                                                <center>
                                                    <?php
                                                    if (isset($_SESSION['admin'])) { ?>

                                                        <a href="<?php echo $VIEWS; ?>/ratios_industria/show.php?id_ratio_industria=<?php echo $ratios['id_ratio_industria']; ?>" class="btn btn-info">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="<?php echo $VIEWS; ?>/ratios_industria/update.php?id_ratio_industria=<?php echo $ratios['id_ratio_industria']; ?>" class="btn btn-warning">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-danger" onclick="confirmDelete(<?php echo $ratios['id_ratio_industria']; ?>)">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </a>
                                                        <script>
                                                            const swalWithBootstrapButtons = Swal.mixin({
                                                                customClass: {
                                                                    confirmButton: "btn btn-success me-2", // Botón con margen a la derecha
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
                                                                    confirmButtonText: 'Sí, eliminarlo',
                                                                    cancelButtonText: 'No, cancelar',
                                                                    reverseButtons: false
                                                                }).then((result) => {
                                                                    if (result.isConfirmed) {
                                                                        // Redirige al controlador de eliminación si se confirma
                                                                        window.location.href = '<?php echo $URL; ?>/app/controllers/ratios_industria/controller_delete.php?id_ratio_industria=' + id;
                                                                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                                                                        swalWithBootstrapButtons.fire({
                                                                            title: 'Cancelado',
                                                                            text: 'El ratio está seguro :)',
                                                                            icon: 'error'
                                                                        });
                                                                    }
                                                                });
                                                            }
                                                        </script>
                                                    <?php
                                                    } else { ?>
                                                        <a href="<?php echo $VIEWS; ?>/ratios_industria/show.php?id_ratio_industria=<?php echo $ratios['id_ratio_industria']; ?>" class="btn btn-info">
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



<?php
include('../layout/parte2.php');
?>