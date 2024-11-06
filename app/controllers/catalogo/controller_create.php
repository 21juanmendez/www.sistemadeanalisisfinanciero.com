<?php
include('../../config.php');
session_start();

$id_empresa = filter_input(INPUT_POST, 'id_empresa', FILTER_VALIDATE_INT);
$nombre_cuenta = filter_input(INPUT_POST, 'nombre_cuenta', FILTER_SANITIZE_STRING);
$id_tipoCuenta = filter_input(INPUT_POST, 'id_tipoCuenta', FILTER_VALIDATE_INT);
$id_clasificacion = filter_input(INPUT_POST, 'id_clasificacion', FILTER_VALIDATE_INT);
$saldo = filter_input(INPUT_POST, 'saldo', FILTER_VALIDATE_FLOAT);

$response = ['success' => false, 'message' => ''];

if (!$id_empresa || !$nombre_cuenta || !$id_tipoCuenta || !$id_clasificacion || $saldo === false) {
    $response['message'] = 'Todos los campos son obligatorios y deben ser válidos.';
    echo json_encode($response);
    exit;
}

try {
    $sql = "INSERT INTO cuentas (id_empresa, nombre, id_tipo, id_clasificacion, saldo, fyh_creacion) 
            VALUES (:id_empresa, :nombre, :id_tipoCuenta, :id_clasificacion, :saldo, NOW())";
    $query = $pdo->prepare($sql);
    $query->bindParam(':id_empresa', $id_empresa);
    $query->bindParam(':nombre', $nombre_cuenta);
    $query->bindParam(':id_tipoCuenta', $id_tipoCuenta);
    $query->bindParam(':id_clasificacion', $id_clasificacion);
    $query->bindParam(':saldo', $saldo);

    if ($query->execute()) {
        $id_cuenta = $pdo->lastInsertId();
        $response['success'] = true;
        $response['message'] = "Cuenta creada exitosamente.";
        $response['id_cuenta'] = $id_cuenta;

        // Obtener el nombre de tipo y clasificación
        $sql_tipo = "SELECT nombre_tipo FROM tipos_cuenta WHERE id_tipo = :id_tipoCuenta";
        $query_tipo = $pdo->prepare($sql_tipo);
        $query_tipo->bindParam(':id_tipoCuenta', $id_tipoCuenta);
        $query_tipo->execute();
        $response['nombre_tipo'] = $query_tipo->fetchColumn();

        $sql_clasificacion = "SELECT nombre_clasificacion FROM clasificaciones WHERE id_clasificacion = :id_clasificacion";
        $query_clasificacion = $pdo->prepare($sql_clasificacion);
        $query_clasificacion->bindParam(':id_clasificacion', $id_clasificacion);
        $query_clasificacion->execute();
        $response['nombre_clasificacion'] = $query_clasificacion->fetchColumn();

        $response['saldo'] = number_format($saldo, 2);
    } else {
        $response['message'] = "Error al crear la cuenta. Intente nuevamente.";
    }
} catch (PDOException $e) {
    $response['message'] = "Error en la base de datos: " . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
