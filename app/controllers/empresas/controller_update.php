<?php

include('../../config.php');
session_start(); // Iniciar la sesión al inicio para manejar mensajes de error

$id_empresa = $_POST['id_empresa'];
$id_tipoEmpresa = trim($_POST['tipo_empresa']);
$nombre_empresa = trim($_POST['nombre_empresa']);

// Validación de campos vacíos
if (empty($id_tipoEmpresa) || empty($nombre_empresa)) {
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
    $_SESSION['mensaje'] = 'El nombre de la empresa y el tipo de empresa son obligatorios';
    header('Location:' . $VIEWS . '/empresas/update.php?id_empresa=' . $id_empresa);
    exit(); // Detener la ejecución si hay campos vacíos
}

// Obtener los valores actuales de la empresa desde la base de datos
$SQL_SELECT = "SELECT id_tipoEmpresa, nombre_empresa FROM empresa WHERE id_empresa = ?";
$query_select = $pdo->prepare($SQL_SELECT);
$query_select->execute([$id_empresa]);
$empresa_actual = $query_select->fetch(PDO::FETCH_ASSOC);

// Verificar si hay cambios
$cambio_realizado = false;
if ($empresa_actual) {
    if ($empresa_actual['id_tipoEmpresa'] != $id_tipoEmpresa || $empresa_actual['nombre_empresa'] != $nombre_empresa) {
        $cambio_realizado = true;
    }
}

if ($cambio_realizado) {
    // Si hubo cambios, realizar la actualización
    $fyh_actualizacion = date('Y-m-d H:i:s');
    $SQL_UPDATE = "UPDATE empresa SET id_tipoEmpresa = ?, nombre_empresa = ?, fyh_actualizacion = ? WHERE id_empresa = ?";
    $query_update = $pdo->prepare($SQL_UPDATE);

    if ($query_update->execute([$id_tipoEmpresa, $nombre_empresa, $fyh_actualizacion, $id_empresa])) {
        $_SESSION['icono'] = 'success';
        $_SESSION['titulo'] = 'Éxito';
        $_SESSION['mensaje'] = 'Empresa actualizada correctamente';
    } else {
        $_SESSION['icono'] = 'error';
        $_SESSION['titulo'] = 'Error';
        $_SESSION['mensaje'] = 'Error al actualizar la empresa';
    }
} else {
    // Si no hubo cambios, mostrar mensaje sin actualización
    $_SESSION['icono'] = 'info';
    $_SESSION['titulo'] = 'Opps...';
    $_SESSION['mensaje'] = 'No se realizaron cambios en la empresa';
}

// Redirigir a la vista de empresas
header('Location:' . $VIEWS . '/empresas/index.php');
exit();
