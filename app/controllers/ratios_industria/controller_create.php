<?php
include('../../config.php');
session_start();

// Validar y sanitizar los datos
$nombre_ratio = filter_input(INPUT_POST, 'nombre_ratio', FILTER_SANITIZE_STRING);
$id_tipoEmpresa = filter_input(INPUT_POST, 'id_tipoEmpresa', FILTER_VALIDATE_INT);
$promedio = filter_input(INPUT_POST, 'promedio', FILTER_VALIDATE_FLOAT);

// Inicializar el array de errores
$errores = [];

// Verificar que cada campo esté lleno y sea válido
if (empty($nombre_ratio)) {
    $errores[] = "El campo Nombre del Ratio es obligatorio.";
}
if (empty($id_tipoEmpresa)) {
    $errores[] = "El campo Tipo de Empresa es obligatorio.";
}
if ($promedio === false || $promedio < 0) {
    $errores[] = "El campo Promedio debe ser un número válido mayor o igual a 0.";
}

// Si hay errores, almacenarlos en la sesión y redirigir de vuelta
if (!empty($errores)) {
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
    $_SESSION['mensaje'] = implode(' ', $errores);
    header('Location:' . $VIEWS . "/ratios_industria/index.php");
    exit();
}

try {
    // Verificar si ya existe el ratio en el mismo tipo de empresa
    $sql_check = "SELECT COUNT(*) FROM ratios_industrias 
                  WHERE nombre_ratio_industria = :nombre_ratio_industria AND id_tipoEmpresa = :id_tipoEmpresa";
    $query_check = $pdo->prepare($sql_check);
    $query_check->bindParam(':nombre_ratio_industria', $nombre_ratio, PDO::PARAM_STR);
    $query_check->bindParam(':id_tipoEmpresa', $id_tipoEmpresa, PDO::PARAM_INT);
    $query_check->execute();
    $exists = $query_check->fetchColumn();

    if ($exists > 0) {
        $_SESSION['icono'] = 'error';
        $_SESSION['titulo'] = 'Error';
        $_SESSION['mensaje'] = "El ratio ya existe para este tipo de empresa.";
        header('Location:' . $VIEWS . "/ratios_industria/index.php");
        exit();
    }

    // Insertar el nuevo ratio en la base de datos
    $sql = "INSERT INTO ratios_industrias (id_tipoEmpresa, nombre_ratio_industria, promedio) 
            VALUES (:id_tipoEmpresa, :nombre_ratio_industria, :promedio)";
    $query = $pdo->prepare($sql);
    $query->bindParam(':id_tipoEmpresa', $id_tipoEmpresa, PDO::PARAM_INT);
    $query->bindParam(':nombre_ratio_industria', $nombre_ratio, PDO::PARAM_STR);
    $query->bindParam(':promedio', $promedio, PDO::PARAM_STR);

    if ($query->execute()) {
        $_SESSION['icono'] = 'success';
        $_SESSION['titulo'] = 'Éxito';
        $_SESSION['mensaje'] = "Ratio creado exitosamente.";
    } else {
        $_SESSION['icono'] = 'error';
        $_SESSION['titulo'] = 'Error';
        $_SESSION['mensaje'] = "Error al crear el ratio. Inténtalo nuevamente.";
    }
} catch (PDOException $e) {
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
    $_SESSION['mensaje'] = "Ocurrió un error al crear el ratio: " . $e->getMessage();
}

// Redirigir de vuelta a la vista de ratios de industria
header('Location:' . $VIEWS . "/ratios_industria/index.php");
exit();
