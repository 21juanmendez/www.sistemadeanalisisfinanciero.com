<?php
$tipo_estado = '';
$anio_inicio = '';
$anio_fin = '';
$detalles_anios = [];
//Balance general
//activos
$activosCorrientes = [];
$activosNoCorrientes = [];
$totalActivosCorrientes = [];
$totalActivosNoCorrientes = [];
$totalActivos = [];

//pasivos
$pasivosCorrientes = [];
$pasivosNoCorrientes = [];
$totalPasivosCorrientes = [];
$totalPasivosNoCorrientes = [];
$totalPasivos = [];

//patrimonio
$patrimonio = [];
$totalPatrimonio = [];

//pasivo y patrimonio
$totalPasivoPatrimonio = [];

//Estado de resultados
$ingresoOperacion = [];
$totalIngresoOperacion = [];
$ingresoNoOperacion = [];
$totalIngresoNoOperacion = [];
$costoGastoOperacion = [];
$totalCostoGastoOperacion = [];
$costoGastoNoOperacion = [];
$totalCostoGastoNoOperacion = [];

//utilidades
$utilidadOperacion = [];
$utilidadNeta = [];

if (isset($_POST['id_empresa']) && isset($_POST['tipo_estado']) && isset($_POST['anio_inicio']) && isset($_POST['anio_fin'])) {
    $id_empresa = $_POST['id_empresa'];
    $tipo_estado = $_POST['tipo_estado'];
    $tipo_estado = str_replace('_', ' ', ucwords($tipo_estado));
    $anio_inicio = $_POST['anio_inicio'];
    $anio_fin = $_POST['anio_fin'];

    $anioss = [$anio_inicio, $anio_fin];

    // Consultas para cada año seleccionado
    foreach ($anioss as $anio) {
        $sql = "SELECT df.*, ef.anio, c.nombre AS nombre_cuenta, tc.nombre_tipo AS tipo, cl.nombre_clasificacion AS clasificacion
            FROM detalle_estado_financiero df
            INNER JOIN cuentas c ON df.id_cuenta = c.id_cuenta
            INNER JOIN tipos_cuenta tc ON c.id_tipo = tc.id_tipo
            INNER JOIN clasificaciones cl ON c.id_clasificacion = cl.id_clasificacion
            INNER JOIN estados_financieros ef ON df.id_estado = ef.id_estado
            WHERE ef.id_empresa = :id_empresa AND ef.tipo_estado = :tipo_estado AND ef.anio = :anio";

        $query = $pdo->prepare($sql);
        $query->bindParam(':id_empresa', $id_empresa);
        $query->bindParam(':tipo_estado', $tipo_estado);
        $query->bindParam(':anio', $anio);
        $query->execute();
        $detalles = $query->fetchAll(PDO::FETCH_ASSOC);
        $detalles_anios[$anio] = $detalles;
    }

    if ($tipo_estado == 'Balance general') {
        // Recorre las cuentas de ambos años para asegurarse de que cada cuenta exista en ambos
        foreach ($detalles_anios as $anio => $detalles) {
            foreach ($detalles as $detalle) {
                if ($detalle['tipo'] == 'Corriente' && $detalle['clasificacion'] == 'Activo') {
                    $nombre_cuenta = $detalle['nombre_cuenta'];
                    $saldo = $detalle['saldo'];

                    // Inicializa la cuenta con 0 para ambos años si aún no existe
                    if (!isset($activosCorrientes[$nombre_cuenta])) {
                        $activosCorrientes[$nombre_cuenta] = array_fill_keys($anioss, 0);
                    }

                    // Asigna el saldo actual al año correspondiente
                    $activosCorrientes[$nombre_cuenta][$anio] = $saldo;

                    // Suma el saldo al total de activos corrientes para ese año
                    if (!isset($totalActivosCorrientes['Total activos corrientes'][$anio])) {
                        $totalActivosCorrientes['Total activos corrientes'][$anio] = 0;
                    }
                    if (!isset($totalActivos['Total activos'][$anio])) {
                        $totalActivos['Total activos'][$anio] = 0;
                    }
                    $totalActivosCorrientes['Total activos corrientes'][$anio] += $saldo;
                    $totalActivos['Total activos'][$anio] += $saldo;
                } else if ($detalle['tipo'] == 'No Corriente' && $detalle['clasificacion'] == 'Activo') {
                    $nombre_cuenta = $detalle['nombre_cuenta'];
                    $saldo = $detalle['saldo'];

                    // Inicializa la cuenta con 0 para ambos años si aún no existe
                    if (!isset($activosNoCorrientes[$nombre_cuenta])) {
                        $activosNoCorrientes[$nombre_cuenta] = array_fill_keys($anioss, 0);
                    }

                    // Asigna el saldo actual al año correspondiente
                    $activosNoCorrientes[$nombre_cuenta][$anio] = $saldo;

                    // Suma el saldo al total de activos no corrientes para ese año
                    if (!isset($totalActivosNoCorrientes['Total activos no corrientes'][$anio])) {
                        $totalActivosNoCorrientes['Total activos no corrientes'][$anio] = 0;
                    }
                    if (!isset($totalActivos['Total activos'][$anio])) {
                        $totalActivos['Total activos'][$anio] = 0;
                    }
                    $totalActivosNoCorrientes['Total activos no corrientes'][$anio] += $saldo;
                    $totalActivos['Total activos'][$anio] += $saldo;
                } else if ($detalle['tipo'] == 'Corriente' && $detalle['clasificacion'] == 'Pasivo') {
                    $nombre_cuenta = $detalle['nombre_cuenta'];
                    $saldo = $detalle['saldo'];

                    // Inicializa la cuenta con 0 para ambos años si aún no existe
                    if (!isset($pasivosCorrientes[$nombre_cuenta])) {
                        $pasivosCorrientes[$nombre_cuenta] = array_fill_keys($anioss, 0);
                    }

                    // Asigna el saldo actual al año correspondiente
                    $pasivosCorrientes[$nombre_cuenta][$anio] = $saldo;

                    // Suma el saldo al total de activos corrientes para ese año
                    if (!isset($totalPasivosCorrientes['Total pasivos corrientes'][$anio])) {
                        $totalPasivosCorrientes['Total pasivos corrientes'][$anio] = 0;
                    }

                    if (!isset($totalPasivos['Total pasivos'][$anio])) {
                        $totalPasivos['Total pasivos'][$anio] = 0;
                    }

                    if (!isset($totalPasivoPatrimonio['Total pasivo y patrimonio'][$anio])) {
                        $totalPasivoPatrimonio['Total pasivo y patrimonio'][$anio] = 0;
                    }

                    $totalPasivosCorrientes['Total pasivos corrientes'][$anio] += $saldo;
                    $totalPasivos['Total pasivos'][$anio] += $saldo;
                    $totalPasivoPatrimonio['Total pasivo y patrimonio'][$anio] += $saldo;
                } else if ($detalle['tipo'] == 'No Corriente' && $detalle['clasificacion'] == 'Pasivo') {
                    $nombre_cuenta = $detalle['nombre_cuenta'];
                    $saldo = $detalle['saldo'];

                    // Inicializa la cuenta con 0 para ambos años si aún no existe
                    if (!isset($pasivosNoCorrientes[$nombre_cuenta])) {
                        $pasivosNoCorrientes[$nombre_cuenta] = array_fill_keys($anioss, 0);
                    }

                    // Asigna el saldo actual al año correspondiente
                    $pasivosNoCorrientes[$nombre_cuenta][$anio] = $saldo;

                    // Suma el saldo al total de activos no corrientes para ese año
                    if (!isset($totalPasivosNoCorrientes['Total pasivos no corrientes'][$anio])) {
                        $totalPasivosNoCorrientes['Total pasivos no corrientes'][$anio] = 0;
                    }

                    if (!isset($totalPasivos['Total pasivos'][$anio])) {
                        $totalPasivos['Total pasivos'][$anio] = 0;
                    }

                    if (!isset($totalPasivoPatrimonio['Total pasivo y patrimonio'][$anio])) {
                        $totalPasivoPatrimonio['Total pasivo y patrimonio'][$anio] = 0;
                    }

                    $totalPasivosNoCorrientes['Total pasivos no corrientes'][$anio] += $saldo;
                    $totalPasivos['Total pasivos'][$anio] += $saldo;
                    $totalPasivoPatrimonio['Total pasivo y patrimonio'][$anio] += $saldo;
                } else if ($detalle['clasificacion'] == 'Patrimonio') {
                    $nombre_cuenta = $detalle['nombre_cuenta'];
                    $saldo = $detalle['saldo'];

                    // Inicializa la cuenta con 0 para ambos años si aún no existe
                    if (!isset($patrimonio[$nombre_cuenta])) {
                        $patrimonio[$nombre_cuenta] = array_fill_keys($anioss, 0);
                    }

                    // Asigna el saldo actual al año correspondiente
                    $patrimonio[$nombre_cuenta][$anio] = $saldo;

                    // Suma el saldo al total de activos no corrientes para ese año
                    if (!isset($totalPatrimonio['Total patrimonio'][$anio])) {
                        $totalPatrimonio['Total patrimonio'][$anio] = 0;
                    }

                    if (!isset($totalPasivoPatrimonio['Total pasivo y patrimonio'][$anio])) {
                        $totalPasivoPatrimonio['Total pasivo y patrimonio'][$anio] = 0;
                    }

                    $totalPatrimonio['Total patrimonio'][$anio] += $saldo;
                    $totalPasivoPatrimonio['Total pasivo y patrimonio'][$anio] += $saldo;
                }
            }
        }

        // Calculo de variaciones absolutas y relativas para analisis horizontal y vertical
        foreach ($activosCorrientes as $nombre_cuenta => $saldos) {
            $saldo_anio_inicio = $saldos[$anio_inicio] ?? 0;
            $saldo_anio_fin = $saldos[$anio_fin] ?? 0;

            $variacion_absoluta = $saldo_anio_fin - $saldo_anio_inicio;
            $variacion_relativa = ($saldo_anio_fin != 0) ? ($variacion_absoluta / $saldo_anio_fin) * 100 : 0;

            $activosCorrientes[$nombre_cuenta]['variacion_absoluta'] = $variacion_absoluta;
            $activosCorrientes[$nombre_cuenta]['variacion_relativa'] = $variacion_relativa;
        }

        foreach ($activosNoCorrientes as $nombre_cuenta => $saldos) {
            $saldo_anio_inicio = $saldos[$anio_inicio] ?? 0;
            $saldo_anio_fin = $saldos[$anio_fin] ?? 0;

            $variacion_absoluta = $saldo_anio_fin - $saldo_anio_inicio;
            $variacion_relativa = ($saldo_anio_fin != 0) ? ($variacion_absoluta / $saldo_anio_fin) * 100 : 0;

            $activosNoCorrientes[$nombre_cuenta]['variacion_absoluta'] = $variacion_absoluta;
            $activosNoCorrientes[$nombre_cuenta]['variacion_relativa'] = $variacion_relativa;
        }

        foreach ($pasivosCorrientes as $nombre_cuenta => $saldos) {
            $saldo_anio_inicio = $saldos[$anio_inicio] ?? 0;
            $saldo_anio_fin = $saldos[$anio_fin] ?? 0;

            $variacion_absoluta = $saldo_anio_fin - $saldo_anio_inicio;
            $variacion_relativa = ($saldo_anio_fin != 0) ? ($variacion_absoluta / $saldo_anio_fin) * 100 : 0;

            $pasivosCorrientes[$nombre_cuenta]['variacion_absoluta'] = $variacion_absoluta;
            $pasivosCorrientes[$nombre_cuenta]['variacion_relativa'] = $variacion_relativa;
        }

        foreach ($pasivosNoCorrientes as $nombre_cuenta => $saldos) {
            $saldo_anio_inicio = $saldos[$anio_inicio] ?? 0;
            $saldo_anio_fin = $saldos[$anio_fin] ?? 0;

            $variacion_absoluta = $saldo_anio_fin - $saldo_anio_inicio;
            $variacion_relativa = ($saldo_anio_fin != 0) ? ($variacion_absoluta / $saldo_anio_fin) * 100 : 0;

            $pasivosNoCorrientes[$nombre_cuenta]['variacion_absoluta'] = $variacion_absoluta;
            $pasivosNoCorrientes[$nombre_cuenta]['variacion_relativa'] = $variacion_relativa;
        }

        foreach ($patrimonio as $nombre_cuenta => $saldos) {
            $saldo_anio_inicio = $saldos[$anio_inicio] ?? 0;
            $saldo_anio_fin = $saldos[$anio_fin] ?? 0;

            $variacion_absoluta = $saldo_anio_fin - $saldo_anio_inicio;
            $variacion_relativa = ($saldo_anio_fin != 0) ? ($variacion_absoluta / $saldo_anio_fin) * 100 : 0;

            $patrimonio[$nombre_cuenta]['variacion_absoluta'] = $variacion_absoluta;
            $patrimonio[$nombre_cuenta]['variacion_relativa'] = $variacion_relativa;
        }

        // Calcula variaciones absolutas y relativas para el total de activos corrientes
        $total_anio_inicio = $totalActivosCorrientes['Total activos corrientes'][$anio_inicio] ?? 0;
        $total_anio_fin = $totalActivosCorrientes['Total activos corrientes'][$anio_fin] ?? 0;
        $total_variacion_absoluta = $total_anio_fin - $total_anio_inicio;
        $total_variacion_relativa = ($total_anio_fin != 0) ? ($total_variacion_absoluta / $total_anio_fin) * 100 : 0;

        $totalActivosCorrientes['Total activos corrientes']['variacion_absoluta'] = $total_variacion_absoluta;
        $totalActivosCorrientes['Total activos corrientes']['variacion_relativa'] = $total_variacion_relativa;
        $totalActivosCorrientes['Total activos corrientes']['porcentaje_' . $anio_inicio] = $totalActivosCorrientes['Total activos corrientes'][$anio_inicio] / $totalActivos['Total activos'][$anio_inicio] * 100;
        $totalActivosCorrientes['Total activos corrientes']['porcentaje_' . $anio_fin] = $totalActivosCorrientes['Total activos corrientes'][$anio_fin] / $totalActivos['Total activos'][$anio_fin] * 100;


        // Calcula variaciones absolutas y relativas para el total de activos no corrientes
        $total_anio_inicio = $totalActivosNoCorrientes['Total activos no corrientes'][$anio_inicio] ?? 0;
        $total_anio_fin = $totalActivosNoCorrientes['Total activos no corrientes'][$anio_fin] ?? 0;
        $total_variacion_absoluta = $total_anio_fin - $total_anio_inicio;
        $total_variacion_relativa = ($total_anio_fin != 0) ? ($total_variacion_absoluta / $total_anio_fin) * 100 : 0;

        $totalActivosNoCorrientes['Total activos no corrientes']['variacion_absoluta'] = $total_variacion_absoluta;
        $totalActivosNoCorrientes['Total activos no corrientes']['variacion_relativa'] = $total_variacion_relativa;
        $totalActivosNoCorrientes['Total activos no corrientes']['porcentaje_' . $anio_inicio] = $totalActivosNoCorrientes['Total activos no corrientes'][$anio_inicio] / $totalActivos['Total activos'][$anio_inicio] * 100;
        $totalActivosNoCorrientes['Total activos no corrientes']['porcentaje_' . $anio_fin] = $totalActivosNoCorrientes['Total activos no corrientes'][$anio_fin] / $totalActivos['Total activos'][$anio_fin] * 100;

        // Calcula variaciones absolutas y relativas para el total de activos [corrientes y no corrientes]  
        $total_anio_inicio = $totalActivos['Total activos'][$anio_inicio] ?? 0;
        $total_anio_fin = $totalActivos['Total activos'][$anio_fin] ?? 0;
        $total_variacion_absoluta = $total_anio_fin - $total_anio_inicio;
        $total_variacion_relativa = ($total_anio_fin != 0) ? ($total_variacion_absoluta / $total_anio_fin) * 100 : 0;

        $totalActivos['Total activos']['variacion_absoluta'] = $total_variacion_absoluta;
        $totalActivos['Total activos']['variacion_relativa'] = $total_variacion_relativa;
        $totalActivos['Total activos']['porcentaje_' . $anio_inicio] = 100;
        $totalActivos['Total activos']['porcentaje_' . $anio_fin] = 100;



        // Calcula variaciones absolutas y relativas para el total de pasivos corrientes
        $total_anio_inicio = $totalPasivosCorrientes['Total pasivos corrientes'][$anio_inicio] ?? 0;
        $total_anio_fin = $totalPasivosCorrientes['Total pasivos corrientes'][$anio_fin] ?? 0;
        $total_variacion_absoluta = $total_anio_fin - $total_anio_inicio;
        $total_variacion_relativa = ($total_anio_fin != 0) ? ($total_variacion_absoluta / $total_anio_fin) * 100 : 0;

        $totalPasivosCorrientes['Total pasivos corrientes']['variacion_absoluta'] = $total_variacion_absoluta;
        $totalPasivosCorrientes['Total pasivos corrientes']['variacion_relativa'] = $total_variacion_relativa;
        $totalPasivosCorrientes['Total pasivos corrientes']['porcentaje_' . $anio_inicio] = $totalPasivosCorrientes['Total pasivos corrientes'][$anio_inicio] / $totalPasivos['Total pasivos'][$anio_inicio] * 100;
        $totalPasivosCorrientes['Total pasivos corrientes']['porcentaje_' . $anio_fin] = $totalPasivosCorrientes['Total pasivos corrientes'][$anio_fin] / $totalPasivos['Total pasivos'][$anio_fin] * 100;

        // Calcula variaciones absolutas y relativas para el total de pasivos no corrientes
        $total_anio_inicio = $totalPasivosNoCorrientes['Total pasivos no corrientes'][$anio_inicio] ?? 0;
        $total_anio_fin = $totalPasivosNoCorrientes['Total pasivos no corrientes'][$anio_fin] ?? 0;
        $total_variacion_absoluta = $total_anio_fin - $total_anio_inicio;
        $total_variacion_relativa = ($total_anio_fin != 0) ? ($total_variacion_absoluta / $total_anio_fin) * 100 : 0;

        $totalPasivosNoCorrientes['Total pasivos no corrientes']['variacion_absoluta'] = $total_variacion_absoluta;
        $totalPasivosNoCorrientes['Total pasivos no corrientes']['variacion_relativa'] = $total_variacion_relativa;
        $totalPasivosNoCorrientes['Total pasivos no corrientes']['porcentaje_' . $anio_inicio] = $totalPasivosNoCorrientes['Total pasivos no corrientes'][$anio_inicio] / $totalPasivos['Total pasivos'][$anio_inicio] * 100;
        $totalPasivosNoCorrientes['Total pasivos no corrientes']['porcentaje_' . $anio_fin] = $totalPasivosNoCorrientes['Total pasivos no corrientes'][$anio_fin] / $totalPasivos['Total pasivos'][$anio_fin] * 100;

        // Calcula variaciones absolutas y relativas para el total de pasivos [corrientes y no corrientes]
        $total_anio_inicio = $totalPasivos['Total pasivos'][$anio_inicio] ?? 0;
        $total_anio_fin = $totalPasivos['Total pasivos'][$anio_fin] ?? 0;
        $total_variacion_absoluta = $total_anio_fin - $total_anio_inicio;
        $total_variacion_relativa = ($total_anio_fin != 0) ? ($total_variacion_absoluta / $total_anio_fin) * 100 : 0;

        $totalPasivos['Total pasivos']['variacion_absoluta'] = $total_variacion_absoluta;
        $totalPasivos['Total pasivos']['variacion_relativa'] = $total_variacion_relativa;
        $totalPasivos['Total pasivos']['porcentaje_' . $anio_inicio] = 100;
        $totalPasivos['Total pasivos']['porcentaje_' . $anio_fin] = 100;


        // Calcula variaciones absolutas y relativas para el total de patrimonio
        $total_anio_inicio = $totalPatrimonio['Total patrimonio'][$anio_inicio] ?? 0;
        $total_anio_fin = $totalPatrimonio['Total patrimonio'][$anio_fin] ?? 0;
        $total_variacion_absoluta = $total_anio_fin - $total_anio_inicio;
        $total_variacion_relativa = ($total_anio_fin != 0) ? ($total_variacion_absoluta / $total_anio_fin) * 100 : 0;

        $totalPatrimonio['Total patrimonio']['variacion_absoluta'] = $total_variacion_absoluta;
        $totalPatrimonio['Total patrimonio']['variacion_relativa'] = $total_variacion_relativa;
        $totalPatrimonio['Total patrimonio']['porcentaje_' . $anio_inicio] = 100;
        $totalPatrimonio['Total patrimonio']['porcentaje_' . $anio_fin] = 100;

        // Calcula variaciones absolutas y relativas para el total de pasivo y patrimonio
        $total_anio_inicio = $totalPasivoPatrimonio['Total pasivo y patrimonio'][$anio_inicio] ?? 0;
        $total_anio_fin = $totalPasivoPatrimonio['Total pasivo y patrimonio'][$anio_fin] ?? 0;
        $total_variacion_absoluta = $total_anio_fin - $total_anio_inicio;
        $total_variacion_relativa = ($total_anio_fin != 0) ? ($total_variacion_absoluta / $total_anio_fin) * 100 : 0;

        $totalPasivoPatrimonio['Total pasivo y patrimonio']['variacion_absoluta'] = $total_variacion_absoluta;
        $totalPasivoPatrimonio['Total pasivo y patrimonio']['variacion_relativa'] = $total_variacion_relativa;
        $totalPasivoPatrimonio['Total pasivo y patrimonio']['porcentaje_' . $anio_inicio] = '-';
        $totalPasivoPatrimonio['Total pasivo y patrimonio']['porcentaje_' . $anio_fin] = '-';

        //calculos para el analisis vertical
        // Cálculo del análisis vertical para Activos Corrientes
        foreach ($activosCorrientes as $nombre_cuenta => $saldos) {
            foreach ($anioss as $anio) {
                $total_activos_anio =   $totalActivos['Total activos'][$anio] ?? 1; // Evitar división por cero
                $saldo_cuenta_anio = $saldos[$anio] ?? 0;

                // Cálculo del porcentaje vertical
                $porcentaje_vertical = ($saldo_cuenta_anio / $total_activos_anio) * 100;

                // Almacena el resultado en el array
                $activosCorrientes[$nombre_cuenta]['porcentaje_' . $anio] = $porcentaje_vertical;
            }
        }

        // Cálculo del análisis vertical para Activos No Corrientes
        foreach ($activosNoCorrientes as $nombre_cuenta => $saldos) {
            foreach ($anioss as $anio) {
                $total_activos_anio =   $totalActivos['Total activos'][$anio] ?? 1; // Evitar división por cero
                $saldo_cuenta_anio = $saldos[$anio] ?? 0;

                // Cálculo del porcentaje vertical
                $porcentaje_vertical = ($saldo_cuenta_anio / $total_activos_anio) * 100;

                // Almacena el resultado en el array
                $activosNoCorrientes[$nombre_cuenta]['porcentaje_' . $anio] = $porcentaje_vertical;
            }
        }

        // Cálculo del análisis vertical para Pasivos Corrientes
        foreach ($pasivosCorrientes as $nombre_cuenta => $saldos) {
            foreach ($anioss as $anio) {
                $total_pasivos_anio =   $totalPasivos['Total pasivos'][$anio] ?? 1; // Evitar división por cero
                $saldo_cuenta_anio = $saldos[$anio] ?? 0;

                // Cálculo del porcentaje vertical
                $porcentaje_vertical = ($saldo_cuenta_anio / $total_pasivos_anio) * 100;

                // Almacena el resultado en el array
                $pasivosCorrientes[$nombre_cuenta]['porcentaje_' . $anio] = $porcentaje_vertical;
            }
        }

        // Cálculo del análisis vertical para Pasivos No Corrientes
        foreach ($pasivosNoCorrientes as $nombre_cuenta => $saldos) {
            foreach ($anioss as $anio) {
                $total_pasivos_anio =   $totalPasivos['Total pasivos'][$anio] ?? 1; // Evitar división por cero
                $saldo_cuenta_anio = $saldos[$anio] ?? 0;

                // Cálculo del porcentaje vertical
                $porcentaje_vertical = ($saldo_cuenta_anio / $total_pasivos_anio) * 100;

                // Almacena el resultado en el array
                $pasivosNoCorrientes[$nombre_cuenta]['porcentaje_' . $anio] = $porcentaje_vertical;
            }
        }

        // Cálculo del análisis vertical para Patrimonio
        foreach ($patrimonio as $nombre_cuenta => $saldos) {
            foreach ($anioss as $anio) {
                $total_patrimonio_anio =   $totalPatrimonio['Total patrimonio'][$anio] ?? 1; // Evitar división por cero
                $saldo_cuenta_anio = $saldos[$anio] ?? 0;

                // Cálculo del porcentaje vertical
                $porcentaje_vertical = ($saldo_cuenta_anio / $total_patrimonio_anio) * 100;

                // Almacena el resultado en el array
                $patrimonio[$nombre_cuenta]['porcentaje_' . $anio] = $porcentaje_vertical;
            }
        }
    } else if ($tipo_estado == 'Estado de resultados') {
        // Recorre las cuentas de ambos años para asegurarse de que cada cuenta exista en ambos
        foreach ($detalles_anios as $anio => $detalles) {
            foreach ($detalles as $detalle) {
                if ($detalle['tipo'] == 'Operación' && $detalle['clasificacion'] == 'Ingreso') {
                    $nombre_cuenta = $detalle['nombre_cuenta'];
                    $saldo = $detalle['saldo'];

                    // Inicializa la cuenta con 0 para ambos años si aún no existe
                    if (!isset($ingresoOperacion[$nombre_cuenta])) {
                        $ingresoOperacion[$nombre_cuenta] = array_fill_keys($anioss, 0);
                    }

                    // Asigna el saldo actual al año correspondiente
                    $ingresoOperacion[$nombre_cuenta][$anio] = $saldo;

                    // Suma el saldo al total de ingresos de operación para ese año
                    if (!isset($totalIngresoOperacion['Total ingresos de operacion'][$anio])) {
                        $totalIngresoOperacion['Total ingresos de operacion'][$anio] = 0;
                    }
                    $totalIngresoOperacion['Total ingresos de operacion'][$anio] += $saldo;
                } else if ($detalle['tipo'] == 'No Operación' && $detalle['clasificacion'] == 'Ingreso') {
                    $nombre_cuenta = $detalle['nombre_cuenta'];
                    $saldo = $detalle['saldo'];

                    // Inicializa la cuenta con 0 para ambos años si aún no existe
                    if (!isset($ingresoNoOperacion[$nombre_cuenta])) {
                        $ingresoNoOperacion[$nombre_cuenta] = array_fill_keys($anioss, 0);
                    }

                    // Asigna el saldo actual al año correspondiente
                    $ingresoNoOperacion[$nombre_cuenta][$anio] = $saldo;

                    // Suma el saldo al total de ingresos no operacionales para ese año
                    if (!isset($totalIngresoNoOperacion['Total ingresos no operacionales'][$anio])) {
                        $totalIngresoNoOperacion['Total ingresos no operacionales'][$anio] = 0;
                    }
                    $totalIngresoNoOperacion['Total ingresos no operacionales'][$anio] += $saldo;
                } else if ($detalle['tipo'] == 'Operación' && $detalle['clasificacion'] == 'Costo y Gasto') {
                    $nombre_cuenta = $detalle['nombre_cuenta'];
                    $saldo = $detalle['saldo'];

                    // Inicializa la cuenta con 0 para ambos años si aún no existe
                    if (!isset($costoGastoOperacion[$nombre_cuenta])) {
                        $costoGastoOperacion[$nombre_cuenta] = array_fill_keys($anioss, 0);
                    }

                    // Asigna el saldo actual al año correspondiente
                    $costoGastoOperacion[$nombre_cuenta][$anio] = $saldo;

                    // Suma el saldo al total de costos y gastos de operación para ese año
                    if (!isset($totalCostoGastoOperacion['Total costos y gastos de operacion'][$anio])) {
                        $totalCostoGastoOperacion['Total costos y gastos de operacion'][$anio] = 0;
                    }
                    $totalCostoGastoOperacion['Total costos y gastos de operacion'][$anio] += $saldo;
                } else if ($detalle['tipo'] == 'No Operación' && $detalle['clasificacion'] == 'Costo y Gasto') {
                    $nombre_cuenta = $detalle['nombre_cuenta'];
                    $saldo = $detalle['saldo'];

                    // Inicializa la cuenta con 0 para ambos años si aún no existe
                    if (!isset($costoGastoNoOperacion[$nombre_cuenta])) {
                        $costoGastoNoOperacion[$nombre_cuenta] = array_fill_keys($anioss, 0);
                    }

                    // Asigna el saldo actual al año correspondiente
                    $costoGastoNoOperacion[$nombre_cuenta][$anio] = $saldo;

                    // Suma el saldo al total de costos y gastos no operacionales para ese año
                    if (!isset($totalCostoGastoNoOperacion['Total costos y gastos no operacionales'][$anio])) {
                        $totalCostoGastoNoOperacion['Total costos y gastos no operacionales'][$anio] = 0;
                    }
                    $totalCostoGastoNoOperacion['Total costos y gastos no operacionales'][$anio] += $saldo;
                }
            }
        }

        // Calculo de variaciones absolutas y relativas para analisis horizontal y vertical
        foreach ($ingresoOperacion as $nombre_cuenta => $saldos) {
            $saldo_anio_inicio = $saldos[$anio_inicio] ?? 0;
            $saldo_anio_fin = $saldos[$anio_fin] ?? 0;

            $variacion_absoluta = $saldo_anio_fin - $saldo_anio_inicio;
            $variacion_relativa = ($saldo_anio_fin != 0) ? ($variacion_absoluta / $saldo_anio_fin) * 100 : 0;

            $ingresoOperacion[$nombre_cuenta]['variacion_absoluta'] = $variacion_absoluta;
            $ingresoOperacion[$nombre_cuenta]['variacion_relativa'] = $variacion_relativa;
        }

        //calculo de variaciones absolutas y relativas para el total de ingresos de operacion
        $total_anio_inicio = $totalIngresoOperacion['Total ingresos de operacion'][$anio_inicio] ?? 0;
        $total_anio_fin = $totalIngresoOperacion['Total ingresos de operacion'][$anio_fin] ?? 0;
        $total_variacion_absoluta = $total_anio_fin - $total_anio_inicio;
        $total_variacion_relativa = ($total_anio_fin != 0) ? ($total_variacion_absoluta / $total_anio_fin) * 100 : 0;

        $totalIngresoOperacion['Total ingresos de operacion']['variacion_absoluta'] = $total_variacion_absoluta;
        $totalIngresoOperacion['Total ingresos de operacion']['variacion_relativa'] = $total_variacion_relativa;
        $totalIngresoOperacion['Total ingresos de operacion']['porcentaje_' . $anio_inicio] = 100;
        $totalIngresoOperacion['Total ingresos de operacion']['porcentaje_' . $anio_fin] = 100;

        //calculo de analisis vertical para ingresos de operacion
        foreach ($ingresoOperacion as $nombre_cuenta => $saldos) {
            foreach ($anioss as $anio) {
                $total_ingresos_anio =   $totalIngresoOperacion['Total ingresos de operacion'][$anio] ?? 1; // Evitar división por cero
                $saldo_cuenta_anio = $saldos[$anio] ?? 0;

                // Cálculo del porcentaje vertical
                $porcentaje_vertical = ($saldo_cuenta_anio / $total_ingresos_anio) * 100;

                // Almacena el resultado en el array
                $ingresoOperacion[$nombre_cuenta]['porcentaje_' . $anio] = $porcentaje_vertical;
            }
        }

        //calculos para costos y gastos operacionales
        foreach ($costoGastoOperacion as $nombre_cuenta => $saldos) {
            $saldo_anio_inicio = $saldos[$anio_inicio] ?? 0;
            $saldo_anio_fin = $saldos[$anio_fin] ?? 0;

            $variacion_absoluta = $saldo_anio_fin - $saldo_anio_inicio;
            $variacion_relativa = ($saldo_anio_fin != 0) ? ($variacion_absoluta / $saldo_anio_fin) * 100 : 0;

            $costoGastoOperacion[$nombre_cuenta]['variacion_absoluta'] = $variacion_absoluta;
            $costoGastoOperacion[$nombre_cuenta]['variacion_relativa'] = $variacion_relativa;
        }

        //calculo de variaciones absolutas y relativas para el total de costos y gastos operacionales
        $total_anio_inicio = $totalCostoGastoOperacion['Total costos y gastos de operacion'][$anio_inicio] ?? 0;
        $total_anio_fin = $totalCostoGastoOperacion['Total costos y gastos de operacion'][$anio_fin] ?? 0;
        $total_variacion_absoluta = $total_anio_fin - $total_anio_inicio;
        $total_variacion_relativa = ($total_anio_fin != 0) ? ($total_variacion_absoluta / $total_anio_fin) * 100 : 0;

        $totalCostoGastoOperacion['Total costos y gastos de operacion']['variacion_absoluta'] = $total_variacion_absoluta;
        $totalCostoGastoOperacion['Total costos y gastos de operacion']['variacion_relativa'] = $total_variacion_relativa;
        $totalCostoGastoOperacion['Total costos y gastos de operacion']['porcentaje_' . $anio_inicio] = 100;
        $totalCostoGastoOperacion['Total costos y gastos de operacion']['porcentaje_' . $anio_fin] = 100;

        //calculo de analisis vertical para costos y gastos operacionales
        foreach ($costoGastoOperacion as $nombre_cuenta => $saldos) {
            foreach ($anioss as $anio) {
                $total_ingresos_anio =   $totalCostoGastoOperacion['Total costos y gastos de operacion'][$anio] ?? 1; // Evitar división por cero
                $saldo_cuenta_anio = $saldos[$anio] ?? 0;

                // Cálculo del porcentaje vertical
                $porcentaje_vertical = ($saldo_cuenta_anio / $total_ingresos_anio) * 100;

                // Almacena el resultado en el array
                $costoGastoOperacion[$nombre_cuenta]['porcentaje_' . $anio] = $porcentaje_vertical;
            }
        }


        //calculos para ingresos no operacionales
        foreach($ingresoNoOperacion as $nombre_cuenta => $saldos){
            $saldo_anio_inicio = $saldos[$anio_inicio] ?? 0;
            $saldo_anio_fin = $saldos[$anio_fin] ?? 0;

            $variacion_absoluta = $saldo_anio_fin - $saldo_anio_inicio;
            $variacion_relativa = ($saldo_anio_fin != 0) ? ($variacion_absoluta / $saldo_anio_fin) * 100 : 0;

            $ingresoNoOperacion[$nombre_cuenta]['variacion_absoluta'] = $variacion_absoluta;
            $ingresoNoOperacion[$nombre_cuenta]['variacion_relativa'] = $variacion_relativa;
        }

        //calculo de variaciones absolutas y relativas para el total de ingresos no operacionales
        $total_anio_inicio = $totalIngresoNoOperacion['Total ingresos no operacionales'][$anio_inicio] ?? 0;
        $total_anio_fin = $totalIngresoNoOperacion['Total ingresos no operacionales'][$anio_fin] ?? 0;
        $total_variacion_absoluta = $total_anio_fin - $total_anio_inicio;
        $total_variacion_relativa = ($total_anio_fin != 0) ? ($total_variacion_absoluta / $total_anio_fin) * 100 : 0;

        $totalIngresoNoOperacion['Total ingresos no operacionales']['variacion_absoluta'] = $total_variacion_absoluta;
        $totalIngresoNoOperacion['Total ingresos no operacionales']['variacion_relativa'] = $total_variacion_relativa;
        $totalIngresoNoOperacion['Total ingresos no operacionales']['porcentaje_' . $anio_inicio] = 100;
        $totalIngresoNoOperacion['Total ingresos no operacionales']['porcentaje_' . $anio_fin] = 100;

        //calculo de analisis vertical para ingresos no operacionales
        foreach ($ingresoNoOperacion as $nombre_cuenta => $saldos) {
            foreach ($anioss as $anio) {
                $total_ingresos_anio =   $totalIngresoNoOperacion['Total ingresos no operacionales'][$anio] ?? 1; // Evitar división por cero
                $saldo_cuenta_anio = $saldos[$anio] ?? 0;

                // Cálculo del porcentaje vertical
                $porcentaje_vertical = ($saldo_cuenta_anio / $total_ingresos_anio) * 100;

                // Almacena el resultado en el array
                $ingresoNoOperacion[$nombre_cuenta]['porcentaje_' . $anio] = $porcentaje_vertical;
            }
        }

        //calculos para costos y gastos no operacionales
        foreach($costoGastoNoOperacion as $nombre_cuenta => $saldos){
            $saldo_anio_inicio = $saldos[$anio_inicio] ?? 0;
            $saldo_anio_fin = $saldos[$anio_fin] ?? 0;

            $variacion_absoluta = $saldo_anio_fin - $saldo_anio_inicio;
            $variacion_relativa = ($saldo_anio_fin != 0) ? ($variacion_absoluta / $saldo_anio_fin) * 100 : 0;

            $costoGastoNoOperacion[$nombre_cuenta]['variacion_absoluta'] = $variacion_absoluta;
            $costoGastoNoOperacion[$nombre_cuenta]['variacion_relativa'] = $variacion_relativa;
        }

        //calculo de variaciones absolutas y relativas para el total de costos y gastos no operacionales
        $total_anio_inicio = $totalCostoGastoNoOperacion['Total costos y gastos no operacionales'][$anio_inicio] ?? 0;
        $total_anio_fin = $totalCostoGastoNoOperacion['Total costos y gastos no operacionales'][$anio_fin] ?? 0;
        $total_variacion_absoluta = $total_anio_fin - $total_anio_inicio;
        $total_variacion_relativa = ($total_anio_fin != 0) ? ($total_variacion_absoluta / $total_anio_fin) * 100 : 0;

        $totalCostoGastoNoOperacion['Total costos y gastos no operacionales']['variacion_absoluta'] = $total_variacion_absoluta;
        $totalCostoGastoNoOperacion['Total costos y gastos no operacionales']['variacion_relativa'] = $total_variacion_relativa;
        $totalCostoGastoNoOperacion['Total costos y gastos no operacionales']['porcentaje_' . $anio_inicio] = 100;
        $totalCostoGastoNoOperacion['Total costos y gastos no operacionales']['porcentaje_' . $anio_fin] = 100;

        //calculo de analisis vertical para costos y gastos no operacionales
        foreach ($costoGastoNoOperacion as $nombre_cuenta => $saldos) {
            foreach ($anioss as $anio) {
                $total_ingresos_anio =   $totalCostoGastoNoOperacion['Total costos y gastos no operacionales'][$anio] ?? 1; // Evitar división por cero
                $saldo_cuenta_anio = $saldos[$anio] ?? 0;

                // Cálculo del porcentaje vertical
                $porcentaje_vertical = ($saldo_cuenta_anio / $total_ingresos_anio) * 100;

                // Almacena el resultado en el array
                $costoGastoNoOperacion[$nombre_cuenta]['porcentaje_' . $anio] = $porcentaje_vertical;
            }
        }

        //calcular utilidad de operacion
        $utilidadOperacion['Utilidad de operacion'][$anio_inicio] = $totalIngresoOperacion['Total ingresos de operacion'][$anio_inicio] - $totalCostoGastoOperacion['Total costos y gastos de operacion'][$anio_inicio];
        $utilidadOperacion['Utilidad de operacion'][$anio_fin] = $totalIngresoOperacion['Total ingresos de operacion'][$anio_fin] - $totalCostoGastoOperacion['Total costos y gastos de operacion'][$anio_fin];

        //analisis horizontal para utilidad de operacion
        $total_anio_inicio = $utilidadOperacion['Utilidad de operacion'][$anio_inicio] ?? 0;
        $total_anio_fin = $utilidadOperacion['Utilidad de operacion'][$anio_fin] ?? 0;
        $total_variacion_absoluta = $total_anio_fin - $total_anio_inicio;
        $total_variacion_relativa = ($total_anio_fin != 0) ? ($total_variacion_absoluta / $total_anio_fin) * 100 : 0;

        $utilidadOperacion['Utilidad de operacion']['variacion_absoluta'] = $total_variacion_absoluta;
        $utilidadOperacion['Utilidad de operacion']['variacion_relativa'] = $total_variacion_relativa;
        $utilidadOperacion['Utilidad de operacion']['porcentaje_' . $anio_inicio] = '-';
        $utilidadOperacion['Utilidad de operacion']['porcentaje_' . $anio_fin] = '-';


        //calcular utilidad neta
        $utilidadNeta['Utilidad neta'][$anio_inicio] = $utilidadOperacion['Utilidad de operacion'][$anio_inicio] + $totalIngresoNoOperacion['Total ingresos no operacionales'][$anio_inicio] - $totalCostoGastoNoOperacion['Total costos y gastos no operacionales'][$anio_inicio];
        $utilidadNeta['Utilidad neta'][$anio_fin] = $utilidadOperacion['Utilidad de operacion'][$anio_fin] + $totalIngresoNoOperacion['Total ingresos no operacionales'][$anio_fin] - $totalCostoGastoNoOperacion['Total costos y gastos no operacionales'][$anio_fin];

        //analisis horizontal para utilidad neta
        $total_anio_inicio = $utilidadNeta['Utilidad neta'][$anio_inicio] ?? 0;
        $total_anio_fin = $utilidadNeta['Utilidad neta'][$anio_fin] ?? 0;
        $total_variacion_absoluta = $total_anio_fin - $total_anio_inicio;
        $total_variacion_relativa = ($total_anio_fin != 0) ? ($total_variacion_absoluta / $total_anio_fin) * 100 : 0;

        $utilidadNeta['Utilidad neta']['variacion_absoluta'] = $total_variacion_absoluta;
        $utilidadNeta['Utilidad neta']['variacion_relativa'] = $total_variacion_relativa;
        $utilidadNeta['Utilidad neta']['porcentaje_' . $anio_inicio] = '-';
        $utilidadNeta['Utilidad neta']['porcentaje_' . $anio_fin] = '-';

    }
}
