<?php

$id_empresa = $_GET['id_empresa'];
$arry_grafico = [];
$ratios_promedio = [];
$mensaje = 'Evolución de ratios financieros de Año 1 - Año 2';

//verificar si se reciben los años
if (isset($_POST['anio_inicio']) && isset($_POST['anio_fin'])) {
    $anio_inicio = $_POST['anio_inicio'];
    $anio_fin = $_POST['anio_fin'];
    $mensaje = "Evolución de ratios financieros de $anio_inicio a $anio_fin";

    $arry_grafico = graficoRatio($pdo, $anio_inicio, $anio_fin, $id_empresa);
    $ratios_promedio = graficoRatioPromedio($pdo, $id_empresa);

}

function graficoRatio($pdo, $anio_inicio, $anio_fin, $id_empresa){

    $sql = "SELECT * FROM ratios_financieros WHERE id_empresa = $id_empresa AND anio >= $anio_inicio AND anio <= $anio_fin";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $ratios = $statement->fetchAll();

    $anios = '';
    $prueba_acida = '';
    $liquidez_corriente = '';
    $capital_trabajo = '';
    $razon_capital_trabajo = '';
    $grado_endeudamiento = '';
    $grado_propiedad = '';
    $razon_endeudamiento_patrimonial = '';
    $roa = '';
    $roe = '';
    $margen_neto = '';
    $indice_eficiencia_operativa = '';
    $colors = '';

    foreach ($ratios as $ratio) {
        $anios .= '"'.$ratio['anio'].'",';
        $prueba_acida .= '"'.$ratio['prueba_acida'].'",';
        $liquidez_corriente .= '"'.$ratio['liquidez_corriente'].'",';
        $capital_trabajo .= '"'.$ratio['capital_trabajo'].'",';
        $razon_capital_trabajo .= '"'.$ratio['razon_capital_trabajo'].'",';
        $grado_endeudamiento .= '"'.$ratio['grado_endeudamiento'].'",';
        $grado_propiedad .= '"'.$ratio['grado_propiedad'].'",';
        $razon_endeudamiento_patrimonial .= '"'.$ratio['razon_endeudamiento_patrimonial'].'",';
        $roa .= '"'.$ratio['roa'].'",';
        $roe .= '"'.$ratio['roe'].'",';
        $margen_neto .= '"'.$ratio['margen_neto'].'",';
        $indice_eficiencia_operativa .= '"'.$ratio['indice_eficiencia_operativa'].'",';
        $colors .= '"'.randomColor().'",';
    }
    $anios = substr($anios, 0, -1);
    $prueba_acida = substr($prueba_acida, 0, -1);
    $liquidez_corriente = substr($liquidez_corriente, 0, -1);
    $capital_trabajo = substr($capital_trabajo, 0, -1);
    $razon_capital_trabajo = substr($razon_capital_trabajo, 0, -1);
    $grado_endeudamiento = substr($grado_endeudamiento, 0, -1);
    $grado_propiedad = substr($grado_propiedad, 0, -1);
    $razon_endeudamiento_patrimonial = substr($razon_endeudamiento_patrimonial, 0, -1);
    $roa = substr($roa, 0, -1);
    $roe = substr($roe, 0, -1);
    $margen_neto = substr($margen_neto, 0, -1);
    $indice_eficiencia_operativa = substr($indice_eficiencia_operativa, 0, -1);
    $colors = substr($colors, 0, -1);

    return array($anios, $prueba_acida, $liquidez_corriente, $capital_trabajo, $razon_capital_trabajo, $grado_endeudamiento, $grado_propiedad, $razon_endeudamiento_patrimonial, $roa, $roe, $margen_neto, $indice_eficiencia_operativa, $colors);
        
}

//funcion para graficos doughnut chart de ratios promedio de la industria
function graficoRatioPromedio($pdo, $id_empresa){
    $sql = "SELECT ri.* from empresa em
            INNER JOIN ratios_industrias ri ON em.id_tipoEmpresa = ri.id_tipoEmpresa
            WHERE em.id_empresa = $id_empresa";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $ratios = $statement->fetchAll();

    return $ratios;
}

//Generar colores aleatorios
function randomColor()
{
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}
