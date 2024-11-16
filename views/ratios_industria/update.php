<?php
include('../layout/parte1.php');
include('../../app/controllers/ratios_industria/controller_read.php');
include('mensaje.php');
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title"><b>EDITAR RATIO DE INDUSTRIA</b></h3>
                </div>
                <div class="card-body">



                    <!-- Campo oculto para el ID del ratio -->
                    <form action="<?php echo $URL; ?>/app/controllers/ratios_industria/controller_update.php" method="POST">
                        <input type="hidden" name="id_ratio_industria" value="<?php echo $id_ratio_industria; ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre_ratio">Nombre del Ratio de Industria</label>
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
                                            // Comparar con el valor actual del ratio y marcar como seleccionado si coincide
                                            $selected = ($nombre_ratio === $ratio) ? 'selected' : '';
                                            echo '<option value="' . $ratio . '" ' . $selected . '>' . $ratio . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <!-- Campo para el Tipo de Empresa -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_tipoEmpresa">Tipo de Empresa</label>
                                    <select class="form-select" id="id_tipoEmpresa" name="id_tipoEmpresa" required>
                                        <option value="">Seleccionar Tipo de Empresa</option>
                                        <?php
                                        // Consultar todos los tipos de empresa desde la base de datos
                                        $sql = "SELECT id_tipoEmpresa, nombre_tipoEmpresa FROM tipoempresa";
                                        $query = $pdo->prepare($sql);
                                        $query->execute();
                                        $tipos_empresas = $query->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($tipos_empresas as $tipo) {
                                            // Si el tipo de empresa actual coincide con el del ratio, marcarlo como seleccionado
                                            $selected = ($tipo['nombre_tipoEmpresa'] === $nombre_tipoEmpresa) ? 'selected' : '';
                                            echo "<option value='{$tipo['id_tipoEmpresa']}' {$selected}>{$tipo['nombre_tipoEmpresa']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Campo para el Promedio -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="promedio">Promedio</label>
                                    <input type="number" class="form-control" id="promedio" name="promedio" step="0.0001" value="<?php echo $promedio; ?>" required>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <center>
                            <button type="submit" class="btn btn-warning">Actualizar</button>
                            <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        </center>
                    </form>







                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('../layout/parte2.php');
?>