<?php
include('../layout/parte1.php');
include('../../app/controllers/empresas/controller_read.php');
?>

<div class="container-fluid">
    <a href="index.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> <b>Regresar</b>
    </a>
    <div class="text-center">
        <h1><b><?php echo $nombre_empresa; ?></b></h1>
        <p class="text-muted">Gestiona y analiza toda la información financiera de la empresa</p>
    </div>
    <br>

    <div class="row">
        <!-- Card: Gestionar Catálogo -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-primary shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title m-0"><i class="bi bi-folder-fill me-2"></i>Gestionar Catálogo</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Registrar y gestionar cuentas para la empresa seleccionada.</p>
                    <a href="../catalogo/index.php?id_empresa=<?php echo $id_empresa; ?>" class="btn btn-primary"><i class="bi bi-journal-plus me-2"></i>Ir a Catálogo</a>
                </div>
            </div>
        </div>

        <!-- Card: Registrar Estados Financieros -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-success shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title m-0"><i class="bi bi-file-earmark-bar-graph-fill me-2"></i>Registrar Estados Financieros</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Registrar estados financieros como Balance General y Estado de Resultados.</p>
                    <a href="../estados/index.php?id_empresa=<?php echo $id_empresa;?>" class="btn btn-success"><i class="bi bi-plus-circle me-2"></i>Agregar Estado Financiero</a>
                </div>
            </div>
        </div>

        <!-- Card: Análisis Financiero -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-warning shadow">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title m-0"><i class="bi bi-bar-chart-fill me-2"></i>Análisis Financiero</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Realizar análisis financieros detallados para evaluar la salud económica de la empresa.</p>
                    <a href="../analisis/index.php?id_empresa=<?php echo $id_empresa; ?>" class="btn btn-warning text-white"><i class="bi bi-graph-up-arrow me-2"></i>Ir a Análisis Financiero</a>
                </div>
            </div>
        </div>
        <!-- Card: Análisis Financiero -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-warning shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title m-0"><i class="bi bi-bar-chart-fill me-2"></i>Análisis Financiero</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Visualiza los principales ratios financieros de la empresa seleccionada.</p>
                    <a href="../ratios/index.php?id_empresa=<?php echo $id_empresa; ?>" class="btn btn-info text-white">
                        <i class="bi bi-graph-up-arrow me-2"></i> Ver Ratios
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
include('../layout/parte2.php');
?>