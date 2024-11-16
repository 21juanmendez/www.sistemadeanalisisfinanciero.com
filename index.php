<?php
include('app/config.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--Icono y Titulo de la Página-->
    <link rel="icon" type="image/png" href="<?php echo $URL ?>/public/imagenes/anf.png">
    <title>Login</title>
    <!-- ICONOS BOOOTSTRAP -->
    <link href=" <?php echo $URL ?>/public/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?php echo $URL ?>/public/templates/plugins/fontawesome-free/css/all.min.css">
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo $URL ?>/public/templates/dist/css/adminlte.min.css">
    <!-- jquery-->
    <script src="<?php echo $URL ?>/public/templates/plugins/jquery/jquery.min.js"></script>
    <!-- SWEET ALERT2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<h1 style="text-align: center;"><b>Inicio de sesión</b></h1>
<body class="login-page" style="min-height: 539.977px;"  >
    <div class="login-box" >
        <div class="card-body login-card-body">
            <center>
                <img src="<?php echo $URL ?>/public/imagenes/anf.png" alt="Logo" style="width: 60%;" text-align="center">
            </center>
            <p class="login-box-msg">Ingresa tus datos</p>
            <form id="loginForm" action="<?php echo $URL ?>/app/controllers/login/controller_login.php" method="post">
                <div class="input-group mb-3">
                    <input type="text" id="nombre_usuario" name="nombre_usuario" class="form-control" placeholder="Nombre de usuario" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <small id="userWarning" style="color: red; display: none;">No puedes usar espacios en el nombre de usuario.</small>
                <div class="input-group mb-3">
                    <input type="password" name="contraseña" class="form-control" placeholder="Contraseña" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
            </form>
        </div>
    </div>
    </div>
    <?php
    //Tiene que ser despues del jquery y sweetalert2 en los demas esta arriba xq ya los tienen en el layout que se incluye
    session_start();
    include('mensaje.php');
    include('mensaje_permiso.php');
    ?>

    <!-- SCRIPTS -->
    <script>
        const nombreUsuarioInput = document.getElementById('nombre_usuario');
        const userWarning = document.getElementById('userWarning');

        // Impedir que se agreguen espacios en el campo de usuario
        nombreUsuarioInput.addEventListener('input', function() {
            const value = nombreUsuarioInput.value;

            if (/\s/.test(value)) {
                nombreUsuarioInput.value = value.replace(/\s/g, ''); // Remueve los espacios
                userWarning.style.display = 'block'; // Muestra advertencia
            } else {
                userWarning.style.display = 'none'; // Oculta advertencia
            }
        });
    </script>

    <!-- jQuery -->
    <script src="<?php echo $URL ?>/public/templates/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo $URL ?>/public/templates/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo $URL ?>/public/templates/dist/js/adminlte.min.js"></script>
</body>

</html>