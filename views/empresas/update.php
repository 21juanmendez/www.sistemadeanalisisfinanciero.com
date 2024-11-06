<?php
include('../layout/parte1.php');
include('../../app/controllers/empresas/controller_read.php');
include('../../app/controllers/tipo_empresas/controller_read.php');
include('mensaje.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title"><b>EDITAR EMPRESA [<?php echo $nombre_empresa ?>]</b></h3>
                </div>
                <div class="card-body">

                    <form action="../../app/controllers/empresas/controller_update.php" method="post" onsubmit="return validateForm()">
                        <input type="hidden" name="id_empresa" value="<?php echo $id_empresa; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre de Empresa</label>
                                    <input name="nombre_empresa" type="text" class="form-control" id="nombre_empresa" value="<?php echo $nombre_empresa; ?>">
                                    <small id="nombreError" class="text-danger"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo" class="form-label">Tipo de Empresa</label>
                                    <select class="form-select" id="tipo" name="tipo_empresa" required>
                                        <!-- Opciones dinámicas desde la base de datos -->
                                        <?php foreach ($result as $tipo_empresa): ?>
                                            <option value="<?php echo $tipo_empresa['id_tipoEmpresa']; ?>" <?php echo ($tipo_empresa['nombre_tipoEmpresa'] == $nombre_tipoEmpresa) ? 'selected' : ''; ?>>
                                                <?php echo $tipo_empresa['nombre_tipoEmpresa']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small id="tipoError" class="text-danger"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha y Hora de Creación</label>
                                    <p><?php echo $fyh_creacion; ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha y Hora de Actualización</label>
                                    <p><?php echo $fyh_actualizacion; ?></p>
                                </div>
                            </div>
                        </div>
                        <center>
                            <button type="submit" class="btn btn-warning">Actualizar</button>
                            <a href="index.php" class="btn btn-secondary">Volver</a>
                        </center>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function validateForm() {
        let isValid = true;

        // Validación del campo Nombre de Empresa
        const nombreEmpresa = document.getElementById("nombre_empresa");
        const nombreError = document.getElementById("nombreError");
        if (nombreEmpresa.value.trim() === "") {
            nombreError.innerText = "El nombre de la empresa es obligatorio.";
            isValid = false;
        } else {
            nombreError.innerText = "";
        }

        // Validación del campo Tipo de Empresa
        const tipoEmpresa = document.getElementById("tipo");
        const tipoError = document.getElementById("tipoError");
        if (tipoEmpresa.value === "") {
            tipoError.innerText = "Debe seleccionar un tipo de empresa.";
            isValid = false;
        } else {
            tipoError.innerText = "";
        }   

        return isValid;
    }
</script>

<?php
include('../layout/parte2.php');
?>