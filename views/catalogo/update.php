<?php
include('../layout/parte1.php');
include('../../app/controllers/empresas/controller_read.php');
include('../../app/controllers/catalogo/controller_read.php');

// Obtener tipos de cuenta
$sql_tipos = "SELECT id_tipo, nombre_tipo FROM tipos_cuenta";
$result_tipos = $pdo->query($sql_tipos);

// Obtener clasificaciones
$sql_clasificaciones = "SELECT id_clasificacion, nombre_clasificacion FROM clasificaciones";
$result_clasificaciones = $pdo->query($sql_clasificaciones);
include('mensaje.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title"><b>EDITAR CUENTA</b></h3>
                </div>
                <div class="card-body">
                    <!-- Formulario de edición -->
                    <form action="<?php echo $URL ?>/app/controllers/catalogo/controller_update.php" method="POST" onsubmit="return validateForm()">
                        <!-- Enviar el id_cuenta oculto para la actualización -->
                        <input type="hidden" name="id_cuenta" value="<?php echo $id_cuenta; ?>">
                        <input type="hidden" name="id_empresa" value="<?php echo $id_empresa; ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nombre de Cuenta </label>
                                    <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo htmlspecialchars($nombre); ?>">
                                    <small id="nombreError" class="text-danger d-none">El nombre de la cuenta es obligatorio.</small>
                                </div>
                            </div>

                            <!-- Tipo de cuenta -->
                            <div class="col-md-6">
                                <label for="id_tipoCuenta" class="form-label">Tipo de Cuenta</label>
                                <select class="form-select" id="id_tipoCuenta" name="id_tipo">
                                    <?php foreach ($result_tipos as $tipo): ?>
                                        <option value="<?php echo $tipo['id_tipo']; ?>" <?php echo ($tipo['id_tipo'] == $cuentas['id_tipo']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($tipo['nombre_tipo']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small id="tipoError" class="text-danger d-none">El tipo de cuenta es obligatorio.</small>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Clasificación -->
                            <div class="col-md-6">
                                <label for="clasificacion" class="form-label">Clasificación</label>
                                <div class="d-flex flex-wrap">
                                    <?php foreach ($result_clasificaciones as $clasificacion_item): ?>
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="radio" id="clasificacion<?php echo $clasificacion_item['id_clasificacion']; ?>" name="id_clasificacion" value="<?php echo $clasificacion_item['id_clasificacion']; ?>" <?php echo ($clasificacion_item['id_clasificacion'] == $cuentas['id_clasificacion']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="clasificacion<?php echo $clasificacion_item['id_clasificacion']; ?>">
                                                <?php echo htmlspecialchars($clasificacion_item['nombre_clasificacion']); ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <small id="clasificacionError" class="text-danger d-none">Selecciona una clasificación.</small>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Saldo</label>
                                    <input type="number" class="form-control" name="saldo" id="saldo" value="<?php echo htmlspecialchars($saldo); ?>" step="0.01">
                                    <small id="saldoError" class="text-danger d-none">El saldo es obligatorio.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha y Hora de Creación</label>
                                    <p><?php echo htmlspecialchars($fyh_creacion); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha y Hora de Actualización</label>
                                    <p><?php echo htmlspecialchars($fyh_actualizacion ?? 'No disponible', ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                            </div>


                            <center>
                                <button type="submit" class="btn btn-warning">Actualizar</button>
                                <a href="index.php?id_empresa=<?php echo $id_empresa ?>" class="btn btn-secondary">Volver</a>
                            </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Función de validación
    function validateForm() {
        let isValid = true;

        // Validación para el campo Nombre
        const nombre = document.getElementById('nombre');
        const nombreError = document.getElementById('nombreError');
        if (nombre.value.trim() === '') {
            nombreError.classList.remove('d-none');
            isValid = false;
        } else {
            nombreError.classList.add('d-none');
        }

        // Validación para el campo Tipo de Cuenta
        const tipoCuenta = document.getElementById('id_tipoCuenta');
        const tipoError = document.getElementById('tipoError');
        if (tipoCuenta.value === '') {
            tipoError.classList.remove('d-none');
            isValid = false;
        } else {
            tipoError.classList.add('d-none');
        }

        // Validación para el campo Clasificación (radio buttons)
        const clasificacion = document.getElementsByName('id_clasificacion');
        const clasificacionError = document.getElementById('clasificacionError');
        let clasificacionChecked = false;
        for (const radio of clasificacion) {
            if (radio.checked) {
                clasificacionChecked = true;
                break;
            }
        }
        if (!clasificacionChecked) {
            clasificacionError.classList.remove('d-none');
            isValid = false;
        } else {
            clasificacionError.classList.add('d-none');
        }

        // Validación para el campo Saldo
        const saldo = document.getElementById('saldo');
        const saldoError = document.getElementById('saldoError');

        // Validar que el saldo no esté vacío y que sea mayor o igual a 0
        if (saldo.value.trim() === '' || parseFloat(saldo.value) < 0) {
            saldoError.innerText = "El saldo debe ser un número mayor o igual a 0.";
            saldoError.classList.remove('d-none');
            isValid = false;
        } else {
            saldoError.classList.add('d-none');
        }


        return isValid; // Solo permite el envío si todos los campos son válidos
    }
</script>

<?php
include('../layout/parte2.php');
?>