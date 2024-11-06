<?php
include('../../config.php');
session_start();

$id_empresa = filter_input(INPUT_GET, 'id_empresa', FILTER_VALIDATE_INT);
$nuevo_estado = filter_input(INPUT_GET, 'estado', FILTER_VALIDATE_INT);

if ($id_empresa === false || ($nuevo_estado !== 0 && $nuevo_estado !== 1)) {
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
    $_SESSION['mensaje'] = 'Parámetros inválidos.';
    header('Location: ' . $VIEWS . '/empresas/index.php');
    exit();
}

$sql = "UPDATE empresa SET estado = :nuevo_estado WHERE id_empresa = :id_empresa";
$query = $pdo->prepare($sql);
$query->bindParam(':nuevo_estado', $nuevo_estado, PDO::PARAM_INT);
$query->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);

if ($query->execute()) {
    $_SESSION['icono'] = 'success';
    $_SESSION['titulo'] = 'Correcto';
    $_SESSION['mensaje'] = $nuevo_estado ? 'Empresa activada correctamente' : 'Empresa desactivada correctamente';
} else {
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
    $_SESSION['mensaje'] = 'Error al cambiar el estado de la empresa';
}

header('Location: ' . $VIEWS . '/empresas/index.php');
exit();
