<?php
$sql = "SELECT 
            r.id_ratio_industria, 
            r.nombre_ratio_industria, 
            r.promedio, 
            t.nombre_tipoEmpresa 
        FROM ratios_industrias r
        INNER JOIN tipoempresa t
        ON r.id_tipoEmpresa = t.id_tipoEmpresa";

$query = $pdo->prepare($sql);
$query->execute();
$ratios_industria = $query->fetchAll(PDO::FETCH_ASSOC);

