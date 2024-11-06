<?php
include('../../config.php');
session_start(); // Inicio de sesión

$nombre = trim($_POST['nombre']);
$tipo = trim($_POST['tipo']);
$fyh_creacion = date('Y-m-d H:i:s'); // Obtener la fecha y hora actual

// Validación de campos vacíos
if (empty($nombre) || empty($tipo)) {
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
    $_SESSION['mensaje'] = 'El nombre de la empresa y el tipo de empresa son obligatorios';
    header('Location:' . $VIEWS . '/empresas/index.php');
    exit(); // Detener la ejecución si hay campos vacíos
}

$sql = "INSERT INTO empresa (id_tipoEmpresa, nombre_empresa, fyh_creacion) VALUES (:tipo, :nombre, :fyh_creacion)";
$query = $pdo->prepare($sql);
$query->bindParam(':tipo', $tipo);
$query->bindParam(':nombre', $nombre);
$query->bindParam(':fyh_creacion', $fyh_creacion);

if ($query->execute()) {
    $_SESSION['icono'] = 'success';
    $_SESSION['titulo'] = 'Correcto';
    $_SESSION['mensaje'] = 'Empresa creada correctamente';
    header('Location:' . $VIEWS . '/empresas/index.php');
} else {    
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
    $_SESSION['mensaje'] = 'Error al crear la empresa';
    header('Location:' . $VIEWS . '/empresas/index.php');
}
