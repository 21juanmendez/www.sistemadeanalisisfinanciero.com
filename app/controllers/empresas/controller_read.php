<?php

$id_empresa = $_GET['id_empresa'];

$sql = "SELECT e.*, te.nombre_tipoEmpresa 
    FROM empresa e 
    INNER JOIN tipoempresa te ON e.id_tipoEmpresa = te.id_tipoEmpresa 
    WHERE e.id_empresa = $id_empresa";

$query = $pdo->prepare($sql);
$query->execute();
$empresas = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($empresas as $empresa) {
    $id_empresa = $empresa['id_empresa'];
    $nombre_tipoEmpresa = $empresa['nombre_tipoEmpresa'];
    $nombre_empresa = $empresa['nombre_empresa'];
    $fyh_creacion = $empresa['fyh_creacion'];
    $fyh_actualizacion = $empresa['fyh_actualizacion'];
}

