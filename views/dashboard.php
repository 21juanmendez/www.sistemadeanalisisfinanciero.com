<?php
include('layout/parte1.php');
include('mensaje.php');

// Traemos los datos de las empresas para mostrarlos en el dashboard
include('../app/controllers/empresas/controller_empresas.php');

// Si no existe la sesión de admin te redirige al login
if (!isset($_SESSION['admin'])) {
    $_SESSION['icono'] = 'error';
    $_SESSION['mensaje_permiso'] = 'No tiene los permisos necesarios';
    header('Location:' . $URL . '/index.php');
}

// Array de colores para las tarjetas
$colores = ['bg-success', 'bg-info', 'bg-warning', 'bg-danger', 'bg-primary', 'bg-secondary'];
$i=0;//centinela para el id de la empresa
?>

<div class="container-fluid">
    <h1><b>Panel Administrativo</b></h1>
    <br>
    <div class="row">
        <?php foreach ($empresas as $empresa) : ?>
            <div class="col-3">
                <?php
                $i++;//incrementamos el id de la empresa
                // Selecciona un color aleatorio del array de colores
                $color = $colores[array_rand($colores)];
                ?>
                <div class="small-box <?php echo $color; ?>">
                    <div class="inner">
                        <h3><?php echo $i // Mostrar ID de la empresa ?></h3>
                        <p><b><?php echo $empresa['nombre_empresa']; // Mostrar nombre de la empresa ?></b></p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <a href="<?php echo $VIEWS?>/empresas/opciones.php?id_empresa=<?php echo $empresa['id_empresa']?>" class="small-box-footer">Más informacion <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
include('layout/parte2.php');
?>
