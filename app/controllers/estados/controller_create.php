<?php
include('../../config.php');
session_start();

// Validar y sanitizar los datos
$id_empresa = filter_input(INPUT_POST, 'id_empresa', FILTER_VALIDATE_INT);
$tipo_estado = filter_input(INPUT_POST, 'tipo_estado', FILTER_SANITIZE_STRING);
$anio = filter_input(INPUT_POST, 'anio', FILTER_VALIDATE_INT);

// Inicializar el array de errores
$errores = [];

// Verificar que cada campo esté lleno y sea válido
if (empty($id_empresa)) {
    $errores[] = "El campo ID de empresa es obligatorio y debe ser un número válido.";
}
if (empty($tipo_estado)) {
    $errores[] = "El campo Tipo de Estado Financiero es obligatorio.";
} elseif (!in_array($tipo_estado, ['Balance General', 'Estado de Resultados'])) {
    $errores[] = "El Tipo de Estado Financiero es inválido.";
}
if (empty($anio)) {
    $errores[] = "El campo Año es obligatorio.";
} elseif ($anio < 2000 || $anio > (date('Y') - 1)) {
    $errores[] = "El año debe estar entre 2000 y el año anterior al actual.";
}

// Si hay errores, almacenarlos en la sesión y redirigir de vuelta
if (!empty($errores)) {
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
    $_SESSION['mensaje'] = implode(' ', $errores);
    header('Location:' . $VIEWS . "/estados/index.php?id_empresa=" . $id_empresa);
    exit();
}

try {
    // Preparar la consulta para insertar el estado financiero
    $sql = "INSERT INTO estados_financieros (id_empresa, tipo_estado, anio) VALUES (:id_empresa, :tipo_estado, :anio)";
    $query = $pdo->prepare($sql);
    $query->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);
    $query->bindParam(':tipo_estado', $tipo_estado, PDO::PARAM_STR);
    $query->bindParam(':anio', $anio, PDO::PARAM_INT);

    if ($query->execute()) {
        $_SESSION['icono'] = 'success';
        $_SESSION['titulo'] = 'Éxito';
        $_SESSION['mensaje'] = "Estado financiero creado exitosamente.";
    } else {
        $_SESSION['icono'] = 'error';
        $_SESSION['titulo'] = 'Error';
        $_SESSION['mensaje'] = "Error al crear el estado financiero. Inténtalo nuevamente.";
    }
} catch (PDOException $e) {
    // Capturar cualquier error de base de datos
    $_SESSION['icono'] = 'error';
    $_SESSION['titulo'] = 'Error';
    $_SESSION['mensaje'] = "Ocurrió un error al crear el estado financiero: " . $e->getMessage();
}

// Redirigir de vuelta a la vista de estados financieros de la empresa
header('Location:' . $VIEWS . "/estados/index.php?id_empresa=" . $id_empresa);
exit();
