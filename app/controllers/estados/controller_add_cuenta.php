<?php
include('../../config.php');
session_start();

// Validar y sanitizar los datos
$id_estado = filter_input(INPUT_POST, 'id_estado', FILTER_VALIDATE_INT);
$id_cuenta = filter_input(INPUT_POST, 'id_cuenta', FILTER_VALIDATE_INT);
$saldo = filter_input(INPUT_POST, 'saldo', FILTER_VALIDATE_FLOAT);
$tipo_movimiento = filter_input(INPUT_POST, 'tipo_movimiento', FILTER_SANITIZE_STRING);

// Obtener el tipo de estado financiero
$sql_estado = "SELECT tipo_estado FROM estados_financieros WHERE id_estado = :id_estado";
$query_estado = $pdo->prepare($sql_estado);
$query_estado->bindParam(':id_estado', $id_estado);
$query_estado->execute();
$tipo_estado = $query_estado->fetchColumn();

// Validación para "Estado de Resultados" (sin tipo de movimiento)
if ($id_estado && $id_cuenta && $saldo !== false) {
    // Recuperar el nombre de la cuenta directamente de la base de datos
    $sql_cuenta = "SELECT nombre FROM cuentas WHERE id_cuenta = :id_cuenta";
    $query_cuenta = $pdo->prepare($sql_cuenta);
    $query_cuenta->bindParam(':id_cuenta', $id_cuenta);
    $query_cuenta->execute();
    $nombre_cuenta = $query_cuenta->fetchColumn();

    // Inserción condicional basada en el tipo de estado
    if ($tipo_estado === 'Estado de Resultados') {
        // Inserción para Estado de Resultados sin tipo de movimiento
        $sql = "INSERT INTO detalle_estado_financiero (id_estado, id_cuenta, saldo) VALUES (:id_estado, :id_cuenta, :saldo)";
        $query = $pdo->prepare($sql);
    } else {
        // Inserción para Balance General con tipo de movimiento
        if (!in_array($tipo_movimiento, ['Debe', 'Haber'])) {
            echo json_encode(['success' => false, 'message' => 'Tipo de movimiento inválido.']);
            exit;
        }
        $sql = "INSERT INTO detalle_estado_financiero (id_estado, id_cuenta, saldo, tipo_movimiento) VALUES (:id_estado, :id_cuenta, :saldo, :tipo_movimiento)";
        $query = $pdo->prepare($sql);
        $query->bindParam(':tipo_movimiento', $tipo_movimiento);
    }

    // Vinculación de parámetros comunes
    $query->bindParam(':id_estado', $id_estado);
    $query->bindParam(':id_cuenta', $id_cuenta);
    $query->bindParam(':saldo', $saldo);

    if ($query->execute()) {
        $nuevaCuenta = [
            'nombre_cuenta' => $nombre_cuenta,
            'saldo' => $saldo,
            'tipo_movimiento' => $tipo_movimiento ?? null // Puede ser null si es Estado de Resultados
        ];
        echo json_encode(['success' => true, 'nuevaCuenta' => $nuevaCuenta]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar en la base de datos.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
    exit;
}
