<?php

// Validar y sanitizar el parámetro id_cuenta
$id_cuenta = filter_input(INPUT_GET, 'id_cuenta', FILTER_VALIDATE_INT);

if ($id_cuenta === false) {
    die("Parámetro inválido."); // Detener el script si id_cuenta no es un número válido
}

// Preparar la consulta SQL de manera segura
$sql = "SELECT c.*, cl.nombre_clasificacion, tp.nombre_tipo FROM cuentas c
INNER JOIN clasificaciones cl ON cl.id_clasificacion = c.id_clasificacion
INNER JOIN tipos_cuenta tp ON tp.id_tipo = c.id_tipo
WHERE c.id_cuenta = :id_cuenta";

$query = $pdo->prepare($sql);
$query->bindParam(':id_cuenta', $id_cuenta, PDO::PARAM_INT); // Enlazar el parámetro de forma segura
$query->execute();
$cuentas = $query->fetch();

// Verificar si se encontró el registro
if (!$cuentas) {
    die("No se encontró la cuenta."); // Mensaje en caso de que no exista el registro
}

// Asignar los datos de la cuenta a variables
$nombre = $cuentas['nombre'];
$tipo_cuenta = $cuentas['nombre_tipo'];
$clasificacion = $cuentas['nombre_clasificacion'];
$saldo = $cuentas['saldo'];
$fyh_creacion = $cuentas['fyh_creacion'];
$fyh_actualizacion = $cuentas['fyh_actualizacion'];
