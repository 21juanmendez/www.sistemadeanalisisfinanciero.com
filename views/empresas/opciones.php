<?php
include('../layout/parte1.php');
include('../../app/controllers/empresas/controller_read.php');
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <a href="index.php" class="btn btn-secondary mb-4">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>
        <div class="col-md-4">
            <div class="text-center">
                <h2><b><i class="bi bi-building-fill-check"></i><?php echo $nombre_empresa; ?></b></h2>
                <p class="text-muted">Gestión y análisis financiero</p>
            </div>
            <br>
        </div>
        <div class="col-md-4"></div>
    </div>

    <div class="row">
        <!-- Card: Gestionar Catálogo -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-primary shadow">
                <div class="card-header bg-primary text-white text-center">
                    <i class="bi bi-folder-fill" style="font-size: 2rem;"></i>
                    <h5 class="card-title m-0 mt-2">Catálogo</h5>
                </div>
                <div class="card-body text-center">
                    <p class="card-text">Organiza y gestiona las cuentas.</p>
                    <a href="../catalogo/index.php?id_empresa=<?php echo $id_empresa; ?>" class="btn btn-primary">
                        <i class="bi bi-arrow-right-circle"></i> Abrir
                    </a>
                </div>
            </div>
        </div>

        <!-- Card: Registrar Estados Financieros -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-success shadow">
                <div class="card-header bg-success text-white text-center">
                    <i class="bi bi-file-earmark-bar-graph-fill" style="font-size: 2rem;"></i>
                    <h5 class="card-title m-0 mt-2">Estados Financieros</h5>
                </div>
                <div class="card-body text-center">
                    <p class="card-text">Registra balances y resultados.</p>
                    <a href="../estados/index.php?id_empresa=<?php echo $id_empresa; ?>" class="btn btn-success">
                        <i class="bi bi-arrow-right-circle"></i> Abrir
                    </a>
                </div>
            </div>
        </div>

        <!-- Card: Análisis Completo (incluye Ratios y Análisis Financiero) -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-warning shadow">
                <div class="card-header bg-warning text-white text-center">
                    <i class="bi bi-bar-chart-fill" style="font-size: 2rem;"></i>
                    <h5 class="card-title m-0 mt-2">Análisis Completo</h5>
                </div>
                <div class="card-body text-center">
                    <p class="card-text">Evalúa ratios y salud financiera.</p>
                    <button onclick="seleccionarVista()" class="btn btn-warning text-white">
                        <i class="bi bi-arrow-right-circle"></i> Abrir
                    </button>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function seleccionarVista() {
                Swal.fire({
                    title: 'Selecciona una vista',
                    text: "¿A qué sección deseas ir?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Mostrar gráficos',
                    cancelButtonText: 'Análisis Financiero',
                    denyButtonText: 'Ratios Financieros',
                    showCloseButton: true,
                    showDenyButton: true,
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirigir a la vista de Gráficos
                        window.location.href = "../analisis/graficos.php?id_empresa=<?php echo $id_empresa; ?>";
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Redirigir a la vista de Análisis Financiero Completo
                        window.location.href = "../analisis/index.php?id_empresa=<?php echo $id_empresa; ?>";
                    } else if (result.isDenied) {
                        // Redirigir a la vista de Ratios Financieros
                        window.location.href = "../ratios/index.php?id_empresa=<?php echo $id_empresa; ?>";
                    }
                });
            }
        </script>

    </div>
</div>


<?php
include('../layout/parte2.php');
?>