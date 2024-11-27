<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

// Verificamos si el usuario está logueado
if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");  // Redirige al login si no está logueado
} else {
    require 'header.php';  // Incluye el encabezado con la barra de navegación

    // Verificamos si el usuario tiene permisos para ver la sección de Gastos
    if ($_SESSION['Gastos'] == 1) {
        // Aquí empieza el contenido de la página
        ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <header class="main-box-header clearfix">
                        <h2 class="box-title">Gastos <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Nuevo</button></h2>
                    </header>

                    <!-- Listado de Gastos -->
                    <div class="main-box-body clearfix" id="listadoregistros">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-condensed table-hover" id="tbllistado">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Fecha</th>
                                        <th>Concepto</th>
                                        <th>Gasto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Formulario para registrar nuevo gasto -->
                    <div class="main-box-body clearfix" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                            <div class="row">
                                <div class="form-group col-sm-5 col-xs-12">
                                    <label>Usuario</label>
                                    <input type="hidden" name="idgasto" id="idgasto">
                                    <select id="idusuario" name="idusuario" class="form-control selectpicker" data-live-search="true" required></select>
                                    <input type="hidden" name="fecha" id="fecha">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-5 col-xs-12">
                                    <label>Concepto</label>
                                    <input type="text" name="concepto" id="concepto" class="form-control" placeholder="Concepto" maxlength="50" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-2 col-xs-12">
                                    <label>Gasto</label>
                                    <input type="number" name="gasto" id="gasto" class="form-control" placeholder="Gasto" required>
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                                <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php
    } else {
        // Si no tiene permisos para acceder, mostramos una página de acceso denegado
        require 'noacceso.php';
    }

    // Incluye el pie de página
    require 'footer.php';
?>
    <!-- Carga el script de JavaScript para manejar los gastos -->
    <script type="text/javascript" src="scripts/gastos.js"></script>
<?php 
}
ob_end_flush();
?>
