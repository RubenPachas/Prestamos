<?php
// Activamos el almacenamiento en el buffer
ob_start();
session_start();

// Verificamos si el usuario está autenticado
if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");  // Si el usuario no está autenticado, lo redirigimos a la página de login
} else {
    require 'header.php';  // Incluimos el encabezado de la página (probablemente con el menú de navegación)

    // Verificamos si el usuario tiene permisos para gestionar clientes
    if ($_SESSION['Clientes'] == 1) {
?>
        <!-- Inicio Contenido PHP -->
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <header class="main-box-header clearfix">
                        <h2 class="box-title">Clientes 
                            <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)">
                                <i class="fa fa-plus-circle"></i> Nuevo
                            </button>
                        </h2>
                    </header>
                    <div class="main-box-body clearfix" id="listadoregistros">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-condensed table-hover" id="tbllistado">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>DNI</th>
                                        <th>Cliente</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Formulario para agregar/editar cliente -->
                    <div class="main-box-body clearfix" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                            <div class="row">
                                <div class="form-group col-md-5 col-sm-8 col-xs-12">
                                    <label>DNI</label>
                                    <input type="hidden" name="idcliente" id="idcliente">  <!-- Campo oculto para el ID del cliente -->
                                    <input type="text" name="cedula" id="cedula" class="form-control" placeholder="DNI" maxlength="8" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-8 col-sm-8 col-xs-12">
                                    <label>Nombre</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre" maxlength="50" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-8 col-sm-8 col-xs-12">
                                    <label>Dirección</label>
                                    <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Dirección" maxlength="100">
                                </div>
                            </div> 
                            <div class="row">
                                <div class="form-group col-md-5 col-sm-8 col-xs-12">
                                    <label>Teléfono</label>
                                    <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Teléfono" maxlength="9">
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
        <!-- Fin Contenido PHP -->
<?php
    } else {
        require 'noacceso.php';  // Si no tiene permisos, muestra mensaje de acceso denegado
    }

    require 'footer.php';  // Incluye el pie de página
?>
<script type="text/javascript" src="scripts/clientes.js"></script>  <!-- Carga el script JS -->
<?php 
}
ob_end_flush();  // Finaliza el almacenamiento en búfer de salida y envía el contenido generado al navegador
?>
