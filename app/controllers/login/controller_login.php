<?php
include('../../config.php');

$nombre_usuario = $_POST['nombre_usuario'];
$contraseña = $_POST['contraseña'];

$sql = "SELECT * FROM usuario WHERE nombre_usuario = :nombre_usuario";
$query = $pdo->prepare($sql);
$query->bindParam(':nombre_usuario', $nombre_usuario);
$query->execute();

// devuelve un arreglo según la consulta {id, nombre, email, password, cargo, fecha}
$usuarios = $query->fetchAll(PDO::FETCH_ASSOC);

if (empty($usuarios)) {
    session_start();
    $_SESSION['title'] = 'Error';
    $_SESSION['mensaje'] = 'El usuario no existe';
    $_SESSION['icono'] = 'error';
    header('Location: ' . $URL . '/index.php');
} else {
    $sql = "SELECT * FROM usuario usuario INNER JOIN 
    rol rol ON usuario.id_rol = rol.id_rol WHERE usuario.nombre_usuario = :nombre_usuario";
    $query = $pdo->prepare($sql);
    $query->bindParam(':nombre_usuario', $nombre_usuario);
    $query->execute();
    $usuario = $query->fetch(PDO::FETCH_ASSOC);

    session_start();
    // Verificar la contraseña en texto plano
    if ($contraseña === $usuario['contraseña'] && ($usuario['nombre'] == "ADMINISTRADOR" || $usuario['nombre'] == "Administrador")) {
        $_SESSION['admin'] = $usuario['nombre_usuario'];
        $_SESSION['rol'] = $usuario['nombre'];
        $_SESSION['title']= "Bienvenido";
        $_SESSION['mensaje'] = $usuario['nombre_usuario'];
        $_SESSION['icono'] = 'success';
        header('Location: ' . $VIEWS . "/dashboard.php");
    
    } elseif ($contraseña === $usuario['contraseña'] && ($usuario['nombre'] == "GERENTE" || $usuario['nombre'] == "gerente")) {
        $_SESSION['gerente'] = $usuario['nombre_usuario'];
        $_SESSION['rol'] = $usuario['nombre'];
        $_SESSION['title']= "Bienvenido";
        $_SESSION['mensaje'] = $usuario['nombre_usuario'];
        $_SESSION['icono'] = 'success';
        header('Location: ' . $VIEWS . "/dashboard.php");
    } else {
        // Contraseña incorrecta
        $_SESSION['title']= "Error";
        $_SESSION['mensaje'] = 'Contraseña incorrecta';
        $_SESSION['icono'] = 'error';
        header('Location: ' . $URL . '/index.php');
    }
}
