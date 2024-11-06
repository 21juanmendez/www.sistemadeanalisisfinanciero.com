<?php

$id_empresa = $_GET['id_empresa'];

//$sql = "SELECT * FROM estados_financieros WHERE id_empresa = $id_empresa ORDER BY anio ASC";

$sql = "SELECT * FROM estados_financieros WHERE id_empresa = $id_empresa 
ORDER BY anio ASC, tipo_estado ASC";//SI QUIERES CAMBIAR EL ORDEN DE LA CONSULTA CAMBIA EL ASC POR DESC
$query=$pdo->prepare($sql);
$query->execute();
$estados_financieros = $query->fetchAll();
