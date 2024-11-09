<?php
include('../layout/parte1.php');
include('../../app/controllers/empresas/controller_read.php');

// Importa el controlador de ratios
include('../../app/controllers/ratios/RatiosController.php');

// Verifica el ID de la empresa
$id_empresa = filter_input(INPUT_GET, 'id_empresa', FILTER_VALIDATE_INT);
if (!$id_empresa) {
    echo "Empresa no válida.";
    exit;
}

// Instancia el controlador de ratios financieros
$ratiosController = new RatiosController($pdo);

// Calcula y guarda los ratios en la base de datos para todos los años
$ratiosController->calcularYGuardarRatiosPorAno($id_empresa);
// Obtiene los ratios
$liquidezCorriente = $ratiosController->calcularLiquidezCorrientePorAno($id_empresa);
$pruebaAcida = $ratiosController->calcularPruebaAcidaPorAno($id_empresa);
$capitalTrabajo = $ratiosController->calcularCapitalTrabajoPorAno($id_empresa);
$razonCapitalTrabajo = $ratiosController->calcularRazonCapitalTrabajoPorAno($id_empresa);
$gradoEndeudamiento = $ratiosController->calcularGradoEndeudamientoPorAno($id_empresa);
$gradoPropiedad = $ratiosController->calcularGradoPropiedadPorAno($id_empresa);
$razonEndeudamientoPatrimonial = $ratiosController->calcularRazonEndeudamientoPatrimonialPorAno($id_empresa);
$utilidades = $ratiosController->calcularUtilidadesPorAno($id_empresa); // Nueva función para utilidades

// Calcula el ROA en base a la utilidad neta y los activos totales
$roa = [];
foreach ($utilidades as $anio => $data) {
    $activos_totales = $ratiosController->obtenerActivosTotalesPorAno($id_empresa, $anio);
    $roa[$anio] = $activos_totales > 0 ? $data['utilidad_neta'] / $activos_totales : null;
}
// Calcula el ROE en base a la utilidad neta y el patrimonio total
$roe = [];
foreach ($utilidades as $anio => $data) {
    $patrimonio_total = $ratiosController->obtenerPatrimonioTotalPorAno($id_empresa, $anio);
    $roe[$anio] = $patrimonio_total > 0 ? $data['utilidad_neta'] / $patrimonio_total : null;
}
// Calcula el Margen Neto en base a la utilidad neta y los ingresos operativos
$margenNeto = [];
foreach ($utilidades as $anio => $data) {
    $ingresos_operativos = $data['ingresos_operacion'];
    $margenNeto[$anio] = $ingresos_operativos > 0 ? $data['utilidad_neta'] / $ingresos_operativos : null;
}

// Calcula el Índice de Eficiencia Operativa en base al costo y gasto de operación y los ingresos operativos
$indiceEficienciaOperativa = [];
foreach ($utilidades as $anio => $data) {
    $ingresos_operativos = $data['ingresos_operacion'];
    $costos_gastos_operacion = $data['costos_gastos_operacion'];
    $indiceEficienciaOperativa[$anio] = $ingresos_operativos > 0 ? $costos_gastos_operacion / $ingresos_operativos : null;
}
// Organiza los datos para la tabla
$anios = array_keys($liquidezCorriente);
$ratiosData = [
    'Razón de Circulante (Liquidez Corriente)' => array_column($liquidezCorriente, 'liquidez_corriente'),
    'Prueba Ácida (Razón Rápida)' => array_column($pruebaAcida, 'prueba_acida'),
    'Capital de Trabajo' => array_column($capitalTrabajo, 'capital_trabajo'),
    'Razón de Capital de Trabajo' => array_column($razonCapitalTrabajo, 'razon_capital_trabajo'),
    'Grado de Endeudamiento' => array_column($gradoEndeudamiento, 'grado_endeudamiento'),
    'Grado de Propiedad' => array_column($gradoPropiedad, 'grado_propiedad'),
    'Razón de Endeudamiento Patrimonial' => array_column($razonEndeudamientoPatrimonial, 'razon_endeudamiento_patrimonial'),
    'Rentabilidad sobre Activos (ROA)' => $roa, // Añadir el nuevo ratio de ROA
    'Rentabilidad sobre Patrimonio (ROE)' => $roe, // Ratio de ROE
    'Margen Neto' => $margenNeto, // Añadir el nuevo ratio de Margen Neto
    'Índice de Eficiencia Operativa' => $indiceEficienciaOperativa // Añadir el nuevo ratio de Índice de Eficiencia Operativa
];
?>

<div class="container">
    <a href="../empresas/opciones.php?id_empresa=<?php echo $id_empresa ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> <b>Regresar</b>
    </a>
    <br><br>
    <!-- Card para la vista de Ratios Financieros -->
    <div class="card card-info mb-3">
        <div class="card-header d-flex flex-column justify-content-center" style="background-color: #17a2b8; text-align: center;">
            <h1 class="card-title" style="font-size: 2rem; font-weight: bold; color: white;">
                <i class="fas fa-chart-line"></i> Ratios
            </h1>
            <h1 class="card-title" style="font-size: 2rem; font-weight: bold; color: white;">
                <?php echo htmlspecialchars($nombre_empresa); ?>
            </h1>
        </div>
        <div class="card-body">
            <!-- Tabla para mostrar los Ratios Financieros -->
            <table class="table table-hover table-bordered table-striped">
                <thead class="bg-primary text-white table-dark">
                    <tr>
                        <th>
                            <center>Ratio / Año</center>
                        </th>
                        <?php foreach ($anios as $anio): ?>
                            <th>
                                <center><?php echo htmlspecialchars($anio); ?></center>
                            </th>
                        <?php endforeach; ?>
                        <th>
                            <center>Promedio</center>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Colores para filas importantes
                    $importantRowColor = "#d1ecf1"; // Color para filas importantes (ratios principales)

                    // Renderizar cada ratio en una fila
                    foreach ($ratiosData as $ratio => $valores): ?>
                        <tr style="background-color: <?php echo $importantRowColor; ?>;">
                            <td class="font-weight-bold">
                                <center><?php echo htmlspecialchars($ratio); ?></center>
                            </td>
                            <?php foreach ($valores as $valor): ?>
                                <td>
                                    <center><?php echo is_null($valor) ? 'N/A' : number_format($valor, 4); ?></center>
                                </td>
                            <?php endforeach; ?>
                            <!-- Calcular y mostrar el promedio de cada ratio -->
                            <td>
                                <center>
                                    <b>
                                        <?php
                                        $filteredValues = array_filter($valores, fn($v) => $v !== null);
                                        $promedio = count($filteredValues) > 0 ? array_sum($filteredValues) / count($filteredValues) : null;
                                        echo is_null($promedio) ? 'N/A' : number_format($promedio, 4);
                                        ?>
                                    </b>
                                </center>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('../layout/parte2.php'); ?>
