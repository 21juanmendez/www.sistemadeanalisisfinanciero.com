<?php
include('../layout/parte1.php');
//para poder traer el id de la empresa y usarlo en el botn de regresar
include('../../app/controllers/empresas/controller_read.php');
$id_estado = filter_input(INPUT_GET, 'id_estado', FILTER_VALIDATE_INT);
$tipo_estado = filter_input(INPUT_GET, 'tipo_estado', FILTER_SANITIZE_STRING);

// Verificar que el estado exista en la base de datos
$sql = "SELECT * FROM estados_financieros WHERE id_estado = :id_estado";
$query = $pdo->prepare($sql);
$query->bindParam(':id_estado', $id_estado);
$query->execute();
$estado_financiero = $query->fetch();

if (!$estado_financiero) {
    echo "Estado financiero no encontrado.";
    exit;
}

// Redirigir a la vista correspondiente si es un estado de resultados
if ($estado_financiero['tipo_estado'] === 'Estado de Resultados') {
    include('estados_resultados.php');
    exit;
}

// Obtener los datos del estado financiero (Balance General)
$sql = "SELECT * FROM estados_financieros WHERE id_estado = :id_estado";
$query = $pdo->prepare($sql);
$query->bindParam(':id_estado', $id_estado);
$query->execute();
$estado_financiero = $query->fetch();

// Obtener los detalles del estado financiero
$sql_detalles = "SELECT df.*, c.nombre AS nombre_cuenta 
                 FROM detalle_estado_financiero df
                 INNER JOIN cuentas c ON df.id_cuenta = c.id_cuenta
                 WHERE df.id_estado = :id_estado";
$query_detalles = $pdo->prepare($sql_detalles);
$query_detalles->bindParam(':id_estado', $id_estado);
$query_detalles->execute();
$detalles = $query_detalles->fetchAll();
?>

<div class="container">
    <a href="index.php?id_empresa=<?php echo $id_empresa ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> <b>Regresar</b>
    </a>
    <br><br>
    <!-- Título con estilo de AdminLTE -->
    <div class="card card-info">
        <div class="card-header d-flex justify-content-center" style="background-color: #17a2b8;">
        <div class="card-header d-flex flex-column justify-content-center" style="background-color: #17a2b8; text-align: center;">
                <h1 class="card-title" style="font-size: 2rem; font-weight: bold; color: white;">
                    <i class="fas fa-balance-scale"></i>
                    <?php echo htmlspecialchars($estado_financiero['tipo_estado']); ?>
                </h1>
                <h1 class="card-title" style="font-size: 2rem; font-weight: bold; color: white;">
                    <?php echo htmlspecialchars($nombre_empresa); ?>
                </h1>
                <h1 class="card-title" style="font-size: 2rem; font-weight: bold; color: white;">
                    Hasta el 31 de diciembre del <?php echo htmlspecialchars($estado_financiero['anio']); ?>
                </h1>
            </div>
        </div>
        <div class="card-body">
            <!-- Tabla de cuentas con estilos de AdminLTE -->
            <table class="table table-hover table-bordered table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>
                            <center>Cuenta</center>
                        </th>
                        <th>
                            <center>Debe</center>
                        </th>
                        <th>
                            <center>Haber</center>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_debe = 0;
                    $total_haber = 0;
                    foreach ($detalles as $detalle) {
                        $saldo = number_format($detalle['saldo'], 2);
                        if ($detalle['tipo_movimiento'] == 'Debe') {
                            $total_debe += $detalle['saldo'];
                        } else {
                            $total_haber += $detalle['saldo'];
                        }
                    ?>
                        <tr>
                            <td>
                                <center><?php echo htmlspecialchars($detalle['nombre_cuenta']); ?></center>
                            </td>
                            <td class="text-right">
                                <center><?php echo $detalle['tipo_movimiento'] == 'Debe' ? "$" . $saldo : ''; ?></center>
                            </td>
                            <td class="text-right">
                                <center><?php echo $detalle['tipo_movimiento'] == 'Haber' ? "$" . $saldo : ''; ?></center>
                            </td>
                        </tr>
                    <?php } ?>
                    <!-- Fila de totales con estilo personalizado -->
                    <tr class="table-info" style="background-color: #f8f9fa; font-weight: bold;">
                        <th>
                            <center>Totales</center>
                        </th>
                        <th style="color: <?php echo $total_debe > 0 ? 'green' : 'red'; ?>;">
                            <center><?php echo "$" . number_format($total_debe, 2); ?></center>
                        </th>
                        <th style="color: <?php echo $total_haber > 0 ? 'green' : 'red'; ?>;">
                            <center><?php echo "$" . number_format($total_haber, 2); ?></center>
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include('../layout/parte2.php');
?>