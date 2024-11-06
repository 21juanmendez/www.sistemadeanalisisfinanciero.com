<?php

$sql = "SELECT * FROM tipoempresa";
$query = $pdo->prepare($sql);
$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $tipo_empresa){
    $id_tipoEmpresa= $tipo_empresa['id_tipoEmpresa'];
    $nombre_tipo_empresa= $tipo_empresa['nombre_tipoEmpresa'];
}