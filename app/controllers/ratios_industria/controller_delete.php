<?php
include('../../config.php');
session_start();

// Verificar si se recibe el ID
$id_ratio_industria = isset($_GET['id_ratio_industria']) ? (int) $_GET['id_ratio_industria'] : null;

if (!$id_ratio_industria) {
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
    $_SESSION['mensaje'] = 'El identificador del ratio es inválido.';
    header('Location:' . $VIEWS . "/ratios_industria/index.php");
    exit();
}

try {
    // Verificar si el ratio existe
    $sql_check = "SELECT COUNT(*) FROM ratios_industrias WHERE id_ratio_industria = :id_ratio_industria";
    $query_check = $pdo->prepare($sql_check);
    $query_check->bindParam(':id_ratio_industria', $id_ratio_industria, PDO::PARAM_INT);
    $query_check->execute();
    $exists = $query_check->fetchColumn();

    if (!$exists) {
        $_SESSION['icono'] = 'error';
        $_SESSION['titulo'] = 'Error';
        $_SESSION['mensaje'] = 'El ratio no existe.';
        header('Location:' . $VIEWS . "/ratios_industria/index.php");
        exit();
    }

    // Eliminar el ratio
    $sql_delete = "DELETE FROM ratios_industrias WHERE id_ratio_industria = :id_ratio_industria";
    $query_delete = $pdo->prepare($sql_delete);
    $query_delete->bindParam(':id_ratio_industria', $id_ratio_industria, PDO::PARAM_INT);

    if ($query_delete->execute()) {
        $_SESSION['icono'] = 'success';
        $_SESSION['titulo'] = 'Éxito';
        $_SESSION['mensaje'] = 'El ratio fue eliminado exitosamente.';
    } else {
        $_SESSION['icono'] = 'error';
        $_SESSION['titulo'] = 'Error';
        $_SESSION['mensaje'] = 'Error al intentar eliminar el ratio.';
    }
} catch (PDOException $e) {
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
    $_SESSION['mensaje'] = 'Error en la base de datos: ' . $e->getMessage();
}

// Redirigir de vuelta al índice
header('Location:' . $VIEWS . "/ratios_industria/index.php");
exit();
