<?php
include($_SERVER['DOCUMENT_ROOT'] . '/www-sistemadeanalisisfinanciero-com.onrender.com/app/config.php');
session_start();
//si no existe la session de admin te redirige al login, lo que no se es cuando existan las 2
if (!isset($_SESSION['admin']) && !isset($_SESSION['gerente'])) {
    $_SESSION['icono'] = 'error';
    $_SESSION['title'] = 'Error';
    $_SESSION['mensaje_permiso'] = 'No tienes los permisos necesarios';
    header('Location:' . $URL . '/');
}
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    if (isset($_SESSION['admin'])) { ?>
        <link rel="icon" type="image/png" href="<?php echo $URL ?>/public/templates/dist/img/AdminLTELogo.png">
        <title style=" text-align: center;"> Administrador</title>
    <?php
    } else { ?>
        <link rel="icon" type="image/png" href="<?php echo $URL ?>/public/imagenes/gerente.png">
        <title style=" text-align: center;"> Gerente</title>
    <?php
    }
    ?>
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo $URL ?>/public/templates/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $URL ?>/public/templates/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo $URL ?>/public/templates/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- ICONOS BOOOTSTRAP -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- jquery-->
    <script src="<?php echo $URL ?>/public/js/jquery-3.7.1.min.js"></script>
    <!-- SWEET ALERT2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?php echo $URL ?>/public/templates/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo $URL ?>/public/templates/dist/css/adminlte.min.css">
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
</head>

<body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="<?php echo $URL ?>/public/templates/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div>
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="<?php echo $VIEWS ?>/usuarios" class="nav-link">Dashboard</a>
                </li>
            </ul>


        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a class="brand-link">
                <?php
                if (isset($_SESSION['admin'])) { ?>
                    <img src="<?php echo $URL ?>/public/templates/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light"><?php echo $_SESSION['rol']; ?></span>
                <?php
                } else { ?>
                    <img src="<?php echo $URL ?>/public/imagenes/gerente.jpg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light"><?php echo $_SESSION['rol']; ?></span>
                <?php
                }
                ?>
            </a>


            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?php echo $URL ?>/public/imagenes/user.png" class="img-circle elevation-2" alt="User Image">
                    </div>

                    <div class="info">
                        <?php
                        if (isset($_SESSION['admin'])) { ?>
                            <span class="d-block text-white">Usuario: <b><?php echo $_SESSION['admin']; ?></b> </span>
                        <?php
                        } else { ?>
                            <span class="d-block text-white">Usuario: <b><?php echo $_SESSION['gerente']; ?></b> </span>
                        <?php
                        }
                        ?>
                    </div>

                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item menu-closed">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo $VIEWS ?>/dashboard.php" class="nav-link active">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Inicio</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item menu-closed">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon fas fa-building"></i>
                                <p>
                                    Empresas
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo $VIEWS ?>/empresas" class="nav-link active">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Listado de Empresas</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?php echo $VIEWS ?>/ratios_industria" class="nav-link active">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Ratios de la Industria</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <?php
                            if (isset($_SESSION['admin'])) { ?>
                                <a href="<?php echo $URL ?>/app/controllers/login/controller_cerrar.php?type=admin" class="nav-link active" style="background-color: crimson;">
                                    <i class="nav-icon fas fa-sign-out-alt"></i>
                                    <p>Cerrar sesion</p>
                                </a>
                            <?php
                            } else { ?>
                                <a href="<?php echo $URL ?>/app/controllers/login/controller_cerrar.php?type=gerente" class="nav-link active" style="background-color: crimson;">
                                    <i class="nav-icon fas fa-sign-out-alt"></i>
                                    <p>Cerrar sesion</p>
                                </a>
                            <?php
                            }
                            ?>
                        </li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="container-fluid">
                <br>


                <!-- Main content -->