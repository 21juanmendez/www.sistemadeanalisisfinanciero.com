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
            <div class="col-3 mb-4">
                <?php
                $i++;
                $color = $colores[array_rand($colores)];
                ?>
                <div class="small-box <?php echo $color; ?> shadow-lg rounded">
                    <div class="inner">
                        <h3><?php echo $i; ?></h3>
                        <p><b><?php echo $empresa['nombre_empresa']; ?></b></p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <a href="<?php echo $VIEWS?>/empresas/opciones.php?id_empresa=<?php echo $empresa['id_empresa']?>" class="small-box-footer">
                        Más información <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="container">
    <canvas id="myChart" width="500" height="150"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('myChart').getContext('2d');
        var chartData = {
            labels: <?php echo json_encode(array_column($empresas, 'nombre_empresa')); ?>,
            datasets: [{
                label: 'Cantidad de Cuentas por Empresa',
                data: <?php echo json_encode(array_column($empresas, 'cantidad_cuentas')); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2
            }]
        };

        var myChart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad de Cuentas'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Empresas'
                        }
                    }
                }
            }
        });
    });
</script>


<?php
include('layout/parte2.php');
?>
