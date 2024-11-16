<?php
include('../../config.php');
session_start();

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener y validar los datos enviados
    $id_ratio_industria = filter_input(INPUT_POST, 'id_ratio_industria', FILTER_VALIDATE_INT);
    $nombre_ratio = filter_input(INPUT_POST, 'nombre_ratio', FILTER_SANITIZE_STRING);
    $id_tipoEmpresa = filter_input(INPUT_POST, 'id_tipoEmpresa', FILTER_VALIDATE_INT);
    $promedio = filter_input(INPUT_POST, 'promedio', FILTER_VALIDATE_FLOAT);

    $errores = [];

    // Validar campos
    if (empty($id_ratio_industria)) {
        $errores[] = "El ID del ratio es obligatorio.";
    }
    if (empty($nombre_ratio)) {
        $errores[] = "El nombre del ratio es obligatorio.";
    }
    if (empty($id_tipoEmpresa)) {
        $errores[] = "El tipo de empresa es obligatorio.";
    }
    if ($promedio === false || $promedio < 0) {
        $errores[] = "El promedio debe ser un número válido mayor o igual a 0.";
    }

    // Si hay errores, redirigir con mensaje de error
    if (!empty($errores)) {
        $_SESSION['icono'] = 'error';
        $_SESSION['titulo'] = 'Error';
        $_SESSION['mensaje'] = implode(' ', $errores);
        header('Location:' . $VIEWS . "/ratios_industria/index.php");
        exit();
    }

    try {
        // Obtener los valores actuales del ratio
        $sql_current = "SELECT nombre_ratio_industria, id_tipoEmpresa, promedio 
                        FROM ratios_industrias 
                        WHERE id_ratio_industria = :id_ratio_industria";
        $query_current = $pdo->prepare($sql_current);
        $query_current->bindParam(':id_ratio_industria', $id_ratio_industria, PDO::PARAM_INT);
        $query_current->execute();
        $current_ratio = $query_current->fetch(PDO::FETCH_ASSOC);

        if (!$current_ratio) {
            $_SESSION['icono'] = 'error';
            $_SESSION['titulo'] = 'Error';
            $_SESSION['mensaje'] = 'El ratio no existe.';
            header('Location:' . $VIEWS . "/ratios_industria/index.php");
            exit();
        }

        // Verificar si no se realizaron cambios
        if (
            $current_ratio['nombre_ratio_industria'] === $nombre_ratio &&
            $current_ratio['id_tipoEmpresa'] == $id_tipoEmpresa &&
            $current_ratio['promedio'] == $promedio
        ) {
            $_SESSION['icono'] = 'info';
            $_SESSION['titulo'] = 'Sin cambios';
            $_SESSION['mensaje'] = 'No se realizaron cambios en el ratio.';
            header('Location:'. $VIEWS.'/ratios_industria/update.php?id_ratio_industria=' . $id_ratio_industria);
            exit();
        }

        // Verificar si ya existe el ratio en el mismo tipo de empresa (excluyendo el registro actual)
        $sql_check = "SELECT COUNT(*) FROM ratios_industrias 
                      WHERE nombre_ratio_industria = :nombre_ratio_industria 
                      AND id_tipoEmpresa = :id_tipoEmpresa 
                      AND id_ratio_industria != :id_ratio_industria";
        $query_check = $pdo->prepare($sql_check);
        $query_check->bindParam(':nombre_ratio_industria', $nombre_ratio, PDO::PARAM_STR);
        $query_check->bindParam(':id_tipoEmpresa', $id_tipoEmpresa, PDO::PARAM_INT);
        $query_check->bindParam(':id_ratio_industria', $id_ratio_industria, PDO::PARAM_INT);
        $query_check->execute();
        $exists = $query_check->fetchColumn();

        if ($exists > 0) {
            $_SESSION['icono'] = 'error';
            $_SESSION['titulo'] = 'Error';
            $_SESSION['mensaje'] = "El ratio ya existe para este tipo de empresa.";
            header('Location:'. $VIEWS.'/ratios_industria/update.php?id_ratio_industria=' . $id_ratio_industria);
            exit();
        }

        // Actualizar el registro en la base de datos
        $sql = "UPDATE ratios_industrias 
                SET nombre_ratio_industria = :nombre_ratio_industria, 
                    id_tipoEmpresa = :id_tipoEmpresa, 
                    promedio = :promedio 
                WHERE id_ratio_industria = :id_ratio_industria";
        $query = $pdo->prepare($sql);
        $query->bindParam(':id_ratio_industria', $id_ratio_industria, PDO::PARAM_INT);
        $query->bindParam(':nombre_ratio_industria', $nombre_ratio, PDO::PARAM_STR);
        $query->bindParam(':id_tipoEmpresa', $id_tipoEmpresa, PDO::PARAM_INT);
        $query->bindParam(':promedio', $promedio, PDO::PARAM_STR);

        if ($query->execute()) {
            $_SESSION['icono'] = 'success';
            $_SESSION['titulo'] = 'Éxito';
            $_SESSION['mensaje'] = 'El ratio fue actualizado exitosamente.';
        } else {
            $_SESSION['icono'] = 'error';
            $_SESSION['titulo'] = 'Error';
            $_SESSION['mensaje'] = 'Ocurrió un error al actualizar el ratio.';
        }
    } catch (PDOException $e) {
        $_SESSION['icono'] = 'error';
        $_SESSION['titulo'] = 'Error';
        $_SESSION['mensaje'] = 'Error en la base de datos: ' . $e->getMessage();
    }

    // Redirigir al índice después de actualizar
    header('Location:' . $VIEWS . "/ratios_industria/index.php");
    exit();
} else {
    // Si no es un método POST, redirigir al índice
    header('Location:' . $VIEWS . "/ratios_industria/index.php");
    exit();
}
