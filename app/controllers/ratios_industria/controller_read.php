<?php

// Obtener el ID desde la URL
$id_ratio_industria = isset($_GET['id_ratio_industria']) ? (int) $_GET['id_ratio_industria'] : null;

if (!$id_ratio_industria) {
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
    $_SESSION['mensaje'] = 'El identificador del ratio es inválido.';
    header('Location: index.php');
    exit();
}

try {
    // Consulta para obtener los detalles del ratio
    $sql = "SELECT r.id_ratio_industria, r.nombre_ratio_industria, r.promedio, t.nombre_tipoEmpresa 
            FROM ratios_industrias r
            JOIN tipoempresa t ON r.id_tipoEmpresa = t.id_tipoEmpresa
            WHERE r.id_ratio_industria = :id_ratio_industria";

    $query = $pdo->prepare($sql);
    $query->bindParam(':id_ratio_industria', $id_ratio_industria, PDO::PARAM_INT);
    $query->execute();

    $ratio = $query->fetch(PDO::FETCH_ASSOC);

    if (!$ratio) {
        $_SESSION['icono'] = 'error';
        $_SESSION['titulo'] = 'Error';
        $_SESSION['mensaje'] = 'El ratio de industria no existe.';
        header('Location:' . $VIEWS . "/ratios_industria/index.php");
        exit();
    }

    // Asignar los valores a las variables para la vista
    $id_ratio_industria = $ratio['id_ratio_industria'];
    $nombre_ratio = $ratio['nombre_ratio_industria'];
    $nombre_tipoEmpresa = $ratio['nombre_tipoEmpresa'];
    $promedio = $ratio['promedio'];
} catch (PDOException $e) {
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
    $_SESSION['mensaje'] = 'Error al obtener el ratio: ' . $e->getMessage();
    header('Location:' . $VIEWS . "/ratios_industria/index.php");
    exit();
}
?>
