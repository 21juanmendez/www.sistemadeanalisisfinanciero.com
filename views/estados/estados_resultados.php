<?php
include('../../app/controllers/empresas/controller_read.php');
include('../../app/controllers/estados/EstadoResultadosController.php');

$id_estado = filter_input(INPUT_GET, 'id_estado', FILTER_VALIDATE_INT);
$controller = new EstadoResultadosController($pdo);

// Obtener los datos agrupados
$estadoDatos = $controller->obtenerEstadoResultados($id_estado);
$detallesAgrupados = $estadoDatos['agrupados'];
$anio = $estadoDatos['anio'];
$totalIngresosOperacion = $estadoDatos['totalIngresosOperacion'];
$totalCostosYGastosOperacion = $estadoDatos['totalCostosYGastosOperacion'];
$totalIngresosNoOperacion = $estadoDatos['totalIngresosNoOperacion'];
$totalCostosYGastosNoOperacion = $estadoDatos['totalCostosYGastosNoOperacion'];
$utilidadOperacion = $estadoDatos['utilidadOperacion'];
$utilidadNeta = $estadoDatos['utilidadNeta'];
?>

<div class="container">
    <a href="index.php?id_empresa=<?php echo $id_empresa ?>" class="btn btn-secondary my-3">
        <i class="bi bi-arrow-left"></i> <b>Regresar</b>
    </a>

    <!-- Título de la vista de Estado de Resultados -->
    <div class="card card-info mb-3">
        <div class="card-header d-flex justify-content-center" style="background-color: #17a2b8;">
            <h1 class="card-title text-white" style="font-size: 2rem; font-weight: bold;">
                <i class="fas fa-chart-line"></i> Estado de Resultados - Año <?php echo htmlspecialchars($anio); ?>
            </h1>
        </div>
        <div class="card-body">
            <!-- Tabla para mostrar el Estado de Resultados -->
            <table class="table table-hover table-bordered table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th><center>Clasificación</center></th>
                        <th><center>Tipo</center></th>
                        <th><center>Cuenta</center></th>
                        <th><center>Saldo</center></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detallesAgrupados as $clasificacion => $dataClasificacion): ?>
                        <?php foreach ($dataClasificacion['tipos'] as $tipo => $dataTipo): ?>
                            <?php foreach ($dataTipo['cuentas'] as $cuenta): ?>
                                <tr>
                                    <td class="text-center font-weight-bold"><?php echo htmlspecialchars($clasificacion); ?></td>
                                    <td class="text-center font-italic"><?php echo htmlspecialchars($tipo); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($cuenta['nombre_cuenta']); ?></td>
                                    <td class="text-center"><?php echo "$" . number_format($cuenta['saldo'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <!-- Total del tipo -->
                            <tr class="table-light font-weight-bold">
                                <td></td>
                                <td class="text-right" colspan="2">Total <?php echo htmlspecialchars($tipo); ?>:</td>
                                <td class="text-center"><?php echo "$" . number_format($dataTipo['total'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- Total de la clasificación -->
                        <tr class="table-info font-weight-bold">
                            <td colspan="3" class="text-right">Total <?php echo htmlspecialchars($clasificacion); ?>:</td>
                            <td class="text-center"><?php echo "$" . number_format($dataClasificacion['total'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <!-- Mostrar Totales Generales -->
                    <tr class="table-primary font-weight-bold">
                        <td colspan="3" class="text-right">Total Ingresos de Operación:</td>
                        <td class="text-center"><?php echo "$" . number_format($totalIngresosOperacion, 2); ?></td>
                    </tr>
                    <tr class="table-primary font-weight-bold">
                        <td colspan="3" class="text-right">Total Costos y Gastos de Operación:</td>
                        <td class="text-center"><?php echo "$" . number_format($totalCostosYGastosOperacion, 2); ?></td>
                    </tr>
                    <tr class="table-warning font-weight-bold">
                        <td colspan="3" class="text-right">Utilidad de Operación:</td>
                        <td class="text-center"><?php echo "$" . number_format($utilidadOperacion, 2); ?></td>
                    </tr>
                    <tr class="table-primary font-weight-bold">
                        <td colspan="3" class="text-right">Total Ingresos No Operación:</td>
                        <td class="text-center"><?php echo "$" . number_format($totalIngresosNoOperacion, 2); ?></td>
                    </tr>
                    <tr class="table-primary font-weight-bold">
                        <td colspan="3" class="text-right">Total Costos y Gastos No Operación:</td>
                        <td class="text-center"><?php echo "$" . number_format($totalCostosYGastosNoOperacion, 2); ?></td>
                    </tr>
                    <tr class="table-success font-weight-bold">
                        <td colspan="3" class="text-right">Utilidad Neta:</td>
                        <td class="text-center"><?php echo "$" . number_format($utilidadNeta, 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('../layout/parte2.php'); ?>
