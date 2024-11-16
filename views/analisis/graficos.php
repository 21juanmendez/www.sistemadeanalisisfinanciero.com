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

    <form action="graficos.php?id_empresa=<?php echo $id_empresa; ?>" method="POST">
        <div class="row">
            <div class="d-flex justify-content-end mr-2">
                <div class="form-group">
                    <label for="anio_inicio">Año inicio</label>
                    <select id="anio_inicio" name="anio_inicio" class="form-select" required>
                        <option value="" disabled selected>Seleccione un año</option>
                        <?php
                        foreach ($anio_1 as $anio) {
                            echo "<option value='$anio[anio]'>$anio[anio]</option>";
                        }
                        ?>
                    </select>
                    <div id="error_anioInc" style="display: none;">
                        <i class="bi bi-info-circle text-danger"></i>
                        <span id="mensaje_anioInc" class="text-danger"></span>
                    </div>
                </div>
                <div class="form-group ml-2">
                    <label for="anio_fin">Año fin</label>
                    <select id="anio_fin" name="anio_fin" class="form-select" required>
                        <option value="" disabled selected>Seleccione un año</option>
                        <?php
                        foreach ($anio_2 as $anio) {
                            echo "<option value='$anio[anio]'>$anio[anio]</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group ml-2">
                    <label for="filtrar">&nbsp;</label>
                    <button id="filtrar" type="submit" class="btn btn-primary form-control">
                        <i class="bi bi-funnel"></i> <span class="d-none d-sm-inline">Filtrar</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
    <script>
        document.getElementById('filtrar').addEventListener('click', function(event) {
            var anioInicio = document.getElementById('anio_inicio').value;
            var anioFin = document.getElementById('anio_fin').value;
            const error_anioInc = document.getElementById('error_anioInc');
            const error_anioFin = document.getElementById('error_anioFin');

            if (anioInicio > anioFin) {
                event.preventDefault();
                error_anioInc.style.display = 'block';
                mensaje_anioInc.innerHTML = 'Años no válidos';
            } else {
                error_anioInc.style.display = 'none';
                mensaje_anioInc.innerHTML = '';
                if (anioInicio == anioFin && (anioInicio != "" && anioFin != "")) {
                    event.preventDefault();
                    error_anioInc.style.display = 'block';
                    mensaje_anioInc.innerHTML = 'Los años no pueden ser iguales';
                } else {
                    error_anioInc.style.display = 'none';
                    mensaje_anioInc.innerHTML = '';
                }
            }

        });
    </script>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-graph-up-arrow"></i>
                        <?php echo $mensaje; ?>
                    </h3>
                </div><!-- /.card-header -->
                <div class="card-body" style="height: 405px; width: 100%;">
                    <canvas id="line-chart" style="height: 360px; width: 100%; display: none"></canvas>
                    <div id="message" class="d-flex align-items-center justify-content-center" style="height: 100%;"></div>
                </div><!-- /.card-body -->
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3" style="height: 450px; width: 100%; overflow: hidden;">
            <div class="card-header">
                <h3 class="card-title">
                <i class="bi bi-list-stars"></i>
                <span id="chart-title">Ratios promedio de la industria</span>
                </h3>
            </div><!-- /.card-header -->
            <div class="col-md-12 mt-2" style="overflow-y: auto; max-height: calc(100% - 56px);">
                <?php
                if (!empty($ratios_promedio)) {
                ?>
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
                <?php
                } else {
                    echo '<div class="d-flex align-items-center justify-content-center" style="height: 100%;"><h4 class="text-center"><strong>Para mostrar los datos,</strong><br>Selecciona un rango de años</h4></div>';
                }
                ?>
            </div><!-- /.card-body -->
            </div>
        </div>
        </div>
    </div>
</div>

<script>
    <?php
    if (!empty($arry_grafico)) {
    ?>
        document.getElementById('line-chart').style.display = 'block';
        var ctx = document.getElementById('line-chart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [<?php echo $arry_grafico[0]; ?>],
                datasets: [{
                    label: 'Liquidez Corriente',
                    data: [<?php echo $arry_grafico[1]; ?>],
                    backgroundColor: 'rgba(25, 99, 132, 1)',
                    borderColor: 'rgba(25, 99, 132, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                }, {
                    label: 'Prueba Ácida',
                    data: [<?php echo $arry_grafico[2]; ?>],
                    backgroundColor: 'rgba(90, 162, 235, 1)',
                    borderColor: 'rgba(90, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                }, {
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
                }, {
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
                }]
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
    <?php
    } else {
    ?>
        document.getElementById('message').innerHTML = '<h4 class="text-center"><strong>No hay datos para mostrar,</strong><br>Selecciona un rango de años</h4>';
    <?php
    }
    ?>
</script>
<?php
include('../layout/parte2.php');
?>