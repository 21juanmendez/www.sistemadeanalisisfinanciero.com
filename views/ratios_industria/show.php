<?php
include('../layout/parte1.php');
include('../../app/controllers/ratios_industria/controller_ratios_industria.php');
include('../../app/controllers/ratios_industria/controller_read.php');
include('mensaje.php');
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><b><?php echo $nombre_ratio; ?></b></h3>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre del Ratio</label>
                                <p><?php echo $nombre_ratio ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo de Empresa</label>
                                <p><?php echo $nombre_tipoEmpresa; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Promedio de la industria</label>
                                <p><?php echo number_format($promedio, 4); ?></p>
                            </div>
                        </div>

                    </div>
                    <center>
                        <a href="index.php" class="btn btn-secondary">Volver</a>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('../layout/parte2.php');
?>
