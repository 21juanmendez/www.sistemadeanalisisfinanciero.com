<?php
include('../layout/parte1.php');
include('../../app/controllers/analisis/controller_read.php');
include('../../app/controllers/analisis/controller_analisis.php')
?>
<div class="container">
    <a href="../empresas/opciones.php?id_empresa=<?php echo $id_empresa ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> <b>Regresar</b>
    </a>
    <br><br>
    <!-- Título con estilo de AdminLTE -->
    <div class="card card-info">
        <div class="card-header d-flex justify-content-center" style="background-color: #17a2b8;">
            <h1 class="card-title" style="font-size: 2rem; font-weight: bold; color: white;">
                <i class="bi bi-bar-chart-line"></i>
                Analisis <?php echo $nombre_empresa['nombre_empresa']; ?>
            </h1>
        </div>
        <div class="card-body">
            <form action="index.php?id_empresa=<?php echo $id_empresa; ?>" method="POST">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="tipo_estado">Tipo de Estado Financiero</label>
                        <select id="tipo_estado" name="tipo_estado" class="form-select" required>
                            <option value="" disabled selected>Seleccione un tipo</option>
                            <option value="balance_general">Balance General</option>
                            <option value="estado_de_resultados">Estado de Resultados</option>
                        </select>
                    </div>
                    <input type="hidden" name="id_empresa" value="<?php echo $id_empresa; ?>">
                    <div class="d-flex justify-content-end col-md-8">
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
                            if (anioFin - anioInicio > 1) {
                                event.preventDefault();
                                error_anioInc.style.display = 'block';
                                mensaje_anioInc.innerHTML = 'seleccione un rango de 1 años';
                            } else {
                                error_anioInc.style.display = 'none';
                                mensaje_anioInc.innerHTML = '';
                            }
                        }
                    }

                });
            </script>
            <br>
            <!-- Tabla de analisis horizontal -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <!-- <th>Código</th> -->
                            <th rowspan="2" class="align-middle" style="text-align: center;">Nombre de cuenta</th>
                            <th rowspan="2" class="align-middle" style="text-align: center;"><?php echo !empty($anioss[0]) ? $anioss[0] : 'Año 1'; ?></th>
                            <th rowspan="2" class="align-middle" style="text-align: center;"><?php echo !empty($anioss[1]) ? $anioss[1] : 'Año 2'; ?></th>
                            <th colspan="2" style="text-align: center;"><?php echo !empty($anioss[0]) && !empty($anioss[1]) ? 'Analisis horizontal<br>' . $anioss[0] . ' - ' . $anioss[1] : 'Analisis horizontal<br>Año 1 - Año 2'; ?></th>
                            <th colspan="2" class="align-middle" style="text-align: center;">Analisis Vertical</th>
                        </tr>
                        <tr>
                            <th style="text-align: center">Var. Absoluta</th>
                            <th style="text-align: center;">Var. Relativa</th>
                            <th class="align-middle" style="text-align: center;"><?php echo !empty($anio_inicio) ? $anio_inicio : 'Año 1'; ?></th>
                            <th class="align-middle" style="text-align: center;"><?php echo !empty($anio_fin) ? $anio_fin : 'Año 2'; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($tipo_estado == 'Balance general') {
                            if (count($activosCorrientes) != 0) {
                                echo "<tr>";
                                echo "<td colspan='7' style='font-weight: bold;'>Activos Corrientes</td>";
                                echo "</tr>";
                            }
                            foreach ($activosCorrientes as $nombre_cuenta => $valores) {
                                echo "<tr>";
                                echo "<td>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='text-align: center;'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='text-align: center;'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                            foreach ($totalActivosCorrientes as $nombre_cuenta => $valores) {
                                echo "<tr'>";
                                echo "<td style='font-weight: bold; background-color: rgba(255, 192, 203, 0.5);'>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(255, 192, 203, 0.5);'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(255, 192, 203, 0.5);'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                            if (count($activosNoCorrientes) != 0) {
                                echo "<tr>";
                                echo "<td colspan='7' style='font-weight: bold;'>Activos No Corrientes</td>";
                                echo "</tr>";
                            }
                            foreach ($activosNoCorrientes as $nombre_cuenta => $valores) {
                                echo "<tr>";
                                echo "<td>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='text-align: center;'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='text-align: center;'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                            foreach ($totalActivosNoCorrientes as $nombre_cuenta => $valores) {
                                echo "<tr'>";
                                echo "<td style='font-weight: bold; background-color: rgba(128, 192, 203, 0.5);'>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(128, 192, 203, 0.5);'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(128, 192, 203, 0.5);'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                            foreach ($totalActivos as $nombre_cuenta => $valores) {
                                echo "<tr'>";
                                echo "<td style='font-weight: bold; background-color: rgba(0, 192, 203, 0.5);'>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(0, 192, 203, 0.5);'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(0, 192, 203, 0.5);'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }

                            if (count($pasivosCorrientes) != 0) {
                                echo "<tr>";
                                echo "<td colspan='7' style='font-weight: bold;'>Pasivos Corrientes</td>";
                                echo "</tr>";
                            }
                            foreach ($pasivosCorrientes as $nombre_cuenta => $valores) {
                                echo "<tr>";
                                echo "<td>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='text-align: center;'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='text-align: center;'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                            foreach ($totalPasivosCorrientes as $nombre_cuenta => $valores) {
                                echo "<tr'>";
                                echo "<td style='font-weight: bold; background-color: rgba(255, 192, 203, 0.5);'>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(255, 192, 203, 0.5);'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(255, 192, 203, 0.5);'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                            if (count($pasivosNoCorrientes) != 0) {
                                echo "<tr>";
                                echo "<td colspan='7' style='font-weight: bold;'>Pasivos No Corrientes</td>";
                                echo "</tr>";
                            }
                            foreach ($pasivosNoCorrientes as $nombre_cuenta => $valores) {
                                echo "<tr>";
                                echo "<td>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='text-align: center;'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='text-align: center;'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                            foreach ($totalPasivosNoCorrientes as $nombre_cuenta => $valores) {
                                echo "<tr'>";
                                echo "<td style='font-weight: bold; background-color: rgba(128, 192, 203, 0.5);'>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(128, 192, 203, 0.5);'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(128, 192, 203, 0.5);'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                            foreach ($totalPasivos as $nombre_cuenta => $valores) {
                                echo "<tr'>";
                                echo "<td style='font-weight: bold; background-color: rgba(0, 192, 203, 0.5);'>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(0, 192, 203, 0.5);'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(0, 192, 203, 0.5);'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }

                            if (count($patrimonio) != 0) {
                                echo "<tr>";
                                echo "<td colspan='7' style='font-weight: bold;'>Patrimonio</td>";
                                echo "</tr>";
                            }
                            foreach ($patrimonio as $nombre_cuenta => $valores) {
                                echo "<tr>";
                                echo "<td>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='text-align: center;'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='text-align: center;'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                            foreach ($totalPatrimonio as $nombre_cuenta => $valores) {
                                echo "<tr'>";
                                echo "<td style='font-weight: bold; background-color: rgba(255, 192, 203, 0.5);;'>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(255, 192, 203, 0.5);'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(255, 192, 203, 0.5);'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                            foreach ($totalPasivoPatrimonio as $nombre_cuenta => $valores) {
                                echo "<tr'>";
                                echo "<td style='font-weight: bold; background-color: rgba(0, 192, 203, 0.5);'>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa') {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(0, 192, 203, 0.5);'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        if ($key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                            echo "<td style='font-weight: bold; text-align: center; background-color: rgba(0, 192, 203, 0.5);'>" . $valor . "</td>";
                                        } else {
                                            echo "<td style='font-weight: bold; text-align: center; background-color: rgba(0, 192, 203, 0.5);'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                        }
                                    }
                                }
                                echo "</tr>";
                            }
                        } else if ($tipo_estado == 'Estado de resultados') {
                            if (count($ingresoOperacion) != 0) {
                                echo "<tr>";
                                echo "<td colspan='7' style='font-weight: bold;'>Ingresos de operacion</td>";
                                echo "</tr>";
                            }
                            foreach ($ingresoOperacion as $nombre_cuenta => $valores) {
                                echo "<tr>";
                                echo "<td>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='text-align: center;'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='text-align: center;'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                            foreach ($totalIngresoOperacion as $nombre_cuenta => $valores) {
                                echo "<tr'>";
                                echo "<td style='font-weight: bold; background-color: rgba(130, 192, 203, 0.5);'>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(130, 192, 203, 0.5);'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(130, 192, 203, 0.5);'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }

                            if (count($costoGastoOperacion) != 0) {
                                echo "<tr>";
                                echo "<td colspan='7' style='font-weight: bold;'>Costo y Gasto de operación</td>";
                                echo "</tr>";
                            }
                            foreach ($costoGastoOperacion as $nombre_cuenta => $valores) {
                                echo "<tr>";
                                echo "<td>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='text-align: center;'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='text-align: center;'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                            foreach ($totalCostoGastoOperacion as $nombre_cuenta => $valores) {
                                echo "<tr'>";
                                echo "<td style='font-weight: bold; background-color: rgba(130, 192, 203, 0.5);'>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(130, 192, 203, 0.5);'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(130, 192, 203, 0.5);'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }

                            foreach ($utilidadOperacion as $nombre_cuenta => $valores) {
                                echo "<tr'>";
                                echo "<td style='font-weight: bold; background-color: rgba(255, 192, 203, 0.5);'>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa') {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(255, 192, 203, 0.5);'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        if ($key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                            echo "<td style='font-weight: bold; text-align: center; background-color: rgba(255, 192, 203, 0.5);'>" . $valor . "</td>";
                                        } else {
                                            echo "<td style='font-weight: bold; text-align: center; background-color: rgba(255, 192, 203, 0.5);'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                        }
                                    }
                                }
                                echo "</tr>";
                            }


                            if (count($ingresoNoOperacion) != 0) {
                                echo "<tr>";
                                echo "<td colspan='7' style='font-weight: bold;'>Ingresos no operacionales</td>";
                                echo "</tr>";
                            }
                            foreach ($ingresoNoOperacion as $nombre_cuenta => $valores) {
                                echo "<tr>";
                                echo "<td>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='text-align: center;'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='text-align: center;'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                            foreach ($totalIngresoNoOperacion as $nombre_cuenta => $valores) {
                                echo "<tr'>";
                                echo "<td style='font-weight: bold; background-color: rgba(130, 192, 203, 0.5);'>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(130, 192, 203, 0.5);'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(130, 192, 203, 0.5);'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }

                            if (count($costoGastoNoOperacion) != 0) {
                                echo "<tr>";
                                echo "<td colspan='7' style='font-weight: bold;'>Costos y Gastos no operacionales</td>";
                                echo "</tr>";
                            }

                            foreach ($costoGastoNoOperacion as $nombre_cuenta => $valores) {
                                echo "<tr>";
                                echo "<td>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='text-align: center;'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='text-align: center;'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                            foreach ($totalCostoGastoNoOperacion as $nombre_cuenta => $valores) {
                                echo "<tr'>";
                                echo "<td style='font-weight: bold; background-color: rgba(130, 192, 203, 0.5);'>$nombre_cuenta</td>";
                                foreach ($valores as $key => $valor) {
                                    if ($key == 'variacion_relativa' || $key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin) {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(130, 192, 203, 0.5);'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    } else {
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(130, 192, 203, 0.5);'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }

                            foreach($utilidadNeta as $nombre_cuenta => $valores){
                                echo "<tr'>";
                                echo "<td style='font-weight: bold; background-color: rgba(255, 192, 203, 0.5);'>$nombre_cuenta</td>";
                                foreach($valores as $key => $valor){
                                    if($key == 'variacion_relativa'){
                                        echo "<td style='font-weight: bold; text-align: center; background-color: rgba(255, 192, 203, 0.5);'>" . number_format($valor, 2, '.', ',') . "%</td>";
                                    }else{
                                        if($key == 'porcentaje_' . $anio_inicio || $key == 'porcentaje_' . $anio_fin){
                                            echo "<td style='font-weight: bold; text-align: center; background-color: rgba(255, 192, 203, 0.5);'>" . $valor . "</td>";
                                        }else{
                                            echo "<td style='font-weight: bold; text-align: center; background-color: rgba(255, 192, 203, 0.5);'>$" . number_format($valor, 2, '.', ',') . "</td>";
                                        }
                                    }
                                }
                                echo "</tr>";
                            }
                        }
                        else{
                            echo "<tr>";
                            echo "<td colspan='7' style='font-weight: bold; text-align: center;'>No hay datos para mostrar, seleccione un estado financiero y su periodo</td>";
                            echo "</tr>";
                        }
                        ?>

                    </tbody>
                </table>

            </div>
        </div>
    </div>


    <?php
    include('../layout/parte2.php');
    ?>