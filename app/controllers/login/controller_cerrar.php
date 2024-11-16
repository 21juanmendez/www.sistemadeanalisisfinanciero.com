<?php
include("../../config.php");
session_start();

if (isset($_GET['type'])) {
    $type = $_GET['type'];

    if ($type == 'admin' && isset($_SESSION['admin'])) {
        unset($_SESSION['admin']);
        unset($_SESSION['email']);
        $_SESSION['title'] = 'Correcto';
        $_SESSION['mensaje'] = 'Sesión cerrada correctamente';
        $_SESSION['icono'] = 'success';
    } elseif ($type == 'gerente' && isset($_SESSION['gerente'])) {
        unset($_SESSION['gerente']);
        $_SESSION['title'] = 'Correcto';
        $_SESSION['mensaje'] = 'Sesión cerrada correctamente';
        $_SESSION['icono'] = 'success';
    } else {
        $_SESSION['title'] = 'Error';
        $_SESSION['mensaje'] = 'No se pudo cerrar la sesión';
        $_SESSION['icono'] = 'error';
    }

    // Redirige al index después de configurar el mensaje
    header('Location: ' . $URL . '/index.php');
    exit();
} else {
    $_SESSION['title'] = 'Ops...';
    $_SESSION['mensaje'] = 'No se especificó el tipo de sesión a cerrar';
    $_SESSION['icono'] = 'info';
    header('Location: ' . $URL . '/index.php');
    exit();
}
