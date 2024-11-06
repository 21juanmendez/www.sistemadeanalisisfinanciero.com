<?php
include('../../config.php');
session_start();

$response = ['success' => false, 'message' => ''];

if (isset($_GET['id_estado'])) {
    $id_estado = filter_input(INPUT_GET, 'id_estado', FILTER_VALIDATE_INT);

    if ($id_estado) {
        try {
            // Preparar la consulta para eliminar el estado financiero
            $sql = "DELETE FROM estados_financieros WHERE id_estado = :id_estado";
            $query = $pdo->prepare($sql);
            $query->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);

            if ($query->execute()) {
                $response['success'] = true;
                $response['message'] = "Estado financiero eliminado exitosamente.";
            } else {
                $response['message'] = "No se pudo eliminar el estado financiero. Inténtalo de nuevo.";
            }
        } catch (PDOException $e) {
            // Manejo de errores de base de datos
            $response['message'] = "Error en la base de datos: " . $e->getMessage();
        }
    } else {
        $response['message'] = "ID de estado inválido.";
    }
} else {
    $response['message'] = "No se proporcionó un ID de estado.";
}

// Configurar el tipo de contenido como JSON y enviar la respuesta
header('Content-Type: application/json');
echo json_encode($response);
exit;
