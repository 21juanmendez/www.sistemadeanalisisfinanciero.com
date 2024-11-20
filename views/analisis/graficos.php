<?php
include('../layout/parte1.php');
include('../../app/controllers/analisis/controller_read.php');
include('../../app/controllers/analisis/controller_graficos.php');
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <a href="../empresas/opciones.php?id_empresa=<?php echo $id_empresa ?>" class="btn btn-secondary mb-4">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>
        <div class="col-md-4">
            <div class="text-center">
                <h2><b><i class="bi bi-bar-chart-fill"></i> Análisis Financiero</b></h2>
                <p class="text-muted">Gráficos de análisis financiero</p>
            </div>
            <br>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-graph-up-arrow"></i>
                        Evolución de ratios financieros de <?php echo $anio_min ?> a <?php echo $anio_max ?>
                    </h3>
                </div><!-- /.card-header -->
                <div class="card-body" style="height: 500px; width: 100%;">
                    <canvas id="line-chart" style="height: 460px; width: 100%;"></canvas>
                </div><!-- /.card-body -->
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3" style="height: 545px; width: 100%; overflow: hidden;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-list-stars"></i>
                        <span id="chart-title">Ratios promedio de la industria</span>
                    </h3>
                </div><!-- /.card-header -->
                <div class="col-md-12 mt-2" style="overflow-y: auto; max-height: calc(100% - 56px);">
                    <table class="table table-bordered mt-2">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center">Ratios</th>
                                <th class="text-center">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($ratios_promedio as $ratio) {
                                echo "<tr>";
                                echo "<td>$ratio[nombre_ratio_industria]</td>";
                                echo "<td>$ratio[promedio]</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div><!-- /.card-body -->
            </div>
        </div>
    </div>
</div>

<script>
    var ctx = document.getElementById('line-chart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [<?php echo $arry_grafico[0]; ?>],
            datasets: [{
                    label: 'Prueba Ácida',
                    data: [<?php echo $arry_grafico[1]; ?>],
                    backgroundColor: 'rgba(25, 99, 132, 1)',
                    borderColor: 'rgba(25, 99, 132, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                },
                {
                    label: 'Liquidez Corriente',
                    data: [<?php echo $arry_grafico[2]; ?>],
                    backgroundColor: 'rgba(90, 162, 235, 1)',
                    borderColor: 'rgba(90, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                },
                {
                    label: 'Capital de Trabajo',
                    data: [<?php echo $arry_grafico[3]; ?>],
                    backgroundColor: 'rgba(54, 162, 235, 1)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                },
                {
                    label: 'Razón de Capital de Trabajo',
                    data: [<?php echo $arry_grafico[4]; ?>],
                    backgroundColor: 'rgba(255, 10, 9, 1)',
                    borderColor: 'rgba(255, 10, 9, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                }

                , {
                    label: 'Grado de Endeudamiento',
                    data: [<?php echo $arry_grafico[5]; ?>],
                    backgroundColor: 'rgba(153, 102, 255, 1)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                }, {
                    label: 'Grado de Propiedad',
                    data: [<?php echo $arry_grafico[6]; ?>],
                    backgroundColor: 'rgba(255, 159, 64, 1)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                },
                {
                    label: 'Razón de Endeudamiento Patrimonial',
                    data: [<?php echo $arry_grafico[7]; ?>],
                    backgroundColor: 'rgba(220, 12, 132, 1)',
                    borderColor: 'rgba(220, 12, 132, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                },
                 {
                    label: 'ROA',
                    data: [<?php echo $arry_grafico[8]; ?>],
                    backgroundColor: 'rgba(255, 20, 86, 1)',
                    borderColor: 'rgba(255, 20, 86, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                }, {
                    label: 'ROE',
                    data: [<?php echo $arry_grafico[9]; ?>],
                    backgroundColor: 'rgba(255, 206, 86, 1)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                }, {
                    label: 'Margen Neto',
                    data: [<?php echo $arry_grafico[10]; ?>],
                    backgroundColor: 'rgba(75, 192, 192, 1)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                }, {
                    label: 'Índice de Eficiencia Operativa',
                    data: [<?php echo $arry_grafico[11]; ?>],
                    backgroundColor: 'rgba(200, 10, 255, 1)',
                    borderColor: 'rgba(200, 10, 255, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                },
                x: {
                    title: {
                        display: true,
                        text: 'Años',
                        font: {
                            weight: 'bold',
                            size: 15
                        }
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Valores',
                        font: {
                            weight: 'bold',
                            size: 15
                        }
                    },
                    beginAtZero: true
                }
            }
        }
    });
</script>
<?php
include('../layout/parte2.php');
?>