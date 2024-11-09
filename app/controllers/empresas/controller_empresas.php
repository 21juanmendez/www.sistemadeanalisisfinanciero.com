<?php

// Verificar si se desea mostrar todas las empresas
$mostrar_todas = isset($_GET['mostrar_todas']) && $_GET['mostrar_todas'] == '1';

// Modificar la consulta según el valor de mostrar_todas
$sql = "SELECT empresa.*, tipoempresa.nombre_tipoEmpresa, 
        (SELECT COUNT(*) FROM cuentas WHERE cuentas.id_empresa = empresa.id_empresa) AS cantidad_cuentas
        FROM empresa 
        INNER JOIN tipoempresa ON empresa.id_tipoEmpresa = tipoempresa.id_tipoEmpresa";

if (!$mostrar_todas) {
    $sql .= " WHERE empresa.estado = 1"; // Solo empresas activas si mostrar_todas no está activado
}

$query = $pdo->prepare($sql);
$query->execute();
$empresas = $query->fetchAll(PDO::FETCH_ASSOC);

