<?php
include('../layout/parte1.php');
include('../../app/controllers/empresas/controller_read.php');
include('mensaje.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><b>INFORMACION DE EMPRESA [<?php echo $nombre_empresa?>]</b></h3>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre de Empresa</label>
                                <p><?php echo $nombre_empresa; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group  ">
                                <label>Tipo de Empresa</label>
                                <p><?php echo $nombre_tipoEmpresa; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group  ">
                                <label>Fecha y Hora de Creación</label>
                                <p><?php echo $fyh_creacion; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group  ">
                                <label>Fecha y Hora de Actualización</label>
                                <p><?php echo $fyh_actualizacion; ?></p>
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