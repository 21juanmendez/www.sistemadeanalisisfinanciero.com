<?php

// Validar el id_empresa para asegurar que sea un entero válido
$id_empresa = filter_input(INPUT_GET, 'id_empresa', FILTER_VALIDATE_INT);

if ($id_empresa === false) {
    die("Parámetro inválido.");
}

// Preparar la consulta de forma segura y optimizada
$sql = "SELECT c.id_cuenta, c.nombre, c.saldo, c.fyh_creacion, c.fyh_actualizacion, 
               tp.nombre_tipo, cl.nombre_clasificacion 
        FROM cuentas c
        INNER JOIN tipos_cuenta tp ON tp.id_tipo = c.id_tipo
        INNER JOIN clasificaciones cl ON cl.id_clasificacion = c.id_clasificacion
        WHERE c.id_empresa = :id_empresa";

$query = $pdo->prepare($sql);
$query->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);
$query->execute();
$cuentas = $query->fetchAll();
