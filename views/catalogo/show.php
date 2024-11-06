<?php
include('../layout/parte1.php');
include('../../app/controllers/empresas/controller_read.php');
include('../../app/controllers/catalogo/controller_read.php');

include('mensaje.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><b>INFORMACION DE CUENTA</b></h3>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre de Cuenta </label>
                                <p><?php echo $nombre; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group  ">
                                <label>Clasificacion</label>
                                <p><?php echo $clasificacion; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group  ">
                                <label>Tipo de Cuenta</label>
                                <p><?php echo $tipo_cuenta; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Saldo</label>
                                <p><?php echo $saldo; ?></p>
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
                        <a href="index.php?id_empresa=<?php echo $id_empresa ?>" class="btn btn-secondary">Volver</a>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('../layout/parte2.php');
?>