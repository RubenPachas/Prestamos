<?php
// Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
} else {
    require 'header.php';

    if ($_SESSION['Escritorio'] == 1) {
        // Asumimos que las variables $prestamosDia, $pagosDia y $gastosDia están definidas y contienen los datos
        // Esto podría ser consultas a la base de datos, dependiendo de tu lógica
        $prestamosDia = 1000;  // Ejemplo de valor, reemplaza con tu lógica
        $pagosDia = 500;       // Ejemplo de valor, reemplaza con tu lógica
        $gastosDia = 200;      // Ejemplo de valor, reemplaza con tu lógica
        ?>
        <!-- Inicio Contenido PHP -->
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <header class="main-box-header clearfix">
                        <h2 class="box-title">Escritorio</h2>
                    </header>

                    <div class="row">
                        <div class="main-box-body clearfix">
                            <!-- Tarjeta de Prestamos del Día -->
                            <div class="col-sm-4">
                                <a href="prestamos_dia.php" class="small-box bg-aqua">
                                    <div class="inner">
                                        <h4 style="font-size: 17px;">Prestamos del Día</h4>
                                        <p><?php echo "$" . number_format($prestamosDia, 2); ?></p>
                                    </div>
                                </a>
                            </div>

                            <!-- Tarjeta de Pagos del Día -->
                            <div class="col-sm-4">
                                <a href="pagos_dia.php" class="small-box bg-aqua">
                                    <div class="inner">
                                        <h4 style="font-size: 17px;">Pagos del Día</h4>
                                        <p><?php echo "$" . number_format($pagosDia, 2); ?></p>
                                    </div>
                                </a>
                            </div>

                            <!-- Tarjeta de Gastos del Día -->
                            <div class="col-sm-4">
                                <a href="gastos_dia.php" class="small-box bg-aqua">
                                    <div class="inner">
                                        <h4 style="font-size: 17px;">Gastos del Día</h4>
                                        <p><?php echo "$" . number_format($gastosDia, 2); ?></p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Fin Contenido PHP -->

        <!-- Modal para mostrar detalles -->
        <div id="modalDetalles" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="modalTitulo">Detalles</h4>
                    </div>
                    <div class="modal-body" id="modalCuerpo">
                        <!-- Aquí irán los detalles -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
    } else {
        require 'noacceso.php';
    }

    require 'footer.php';
?>

<script type="text/javascript" src="scripts/clientes.js"></script>

<!-- Incluir jQuery y Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
// Agregar evento de clic a las tarjetas
$(document).ready(function() {
    $(".small-box").click(function() {
        // Obtener el tipo de tarjeta que fue clickeada
        var tipo = $(this).find('h4').text();

        // Cargar el contenido adecuado en el modal según la tarjeta
        if (tipo === "Prestamos del Día") {
            $("#modalTitulo").text("Detalles de los Prestamos del Día");
            $("#modalCuerpo").html("<p>Detalles de los préstamos realizados hoy...</p>");
        } else if (tipo === "Pagos del Día") {
            $("#modalTitulo").text("Detalles de los Pagos del Día");
            $("#modalCuerpo").html("<p>Detalles de los pagos realizados hoy...</p>");
        } else if (tipo === "Gastos del Día") {
            $("#modalTitulo").text("Detalles de los Gastos del Día");
            $("#modalCuerpo").html("<p>Detalles de los gastos realizados hoy...</p>");
        }

        // Mostrar el modal
        $('#modalDetalles').modal('show');
    });
});
</script>

<?php
}
ob_end_flush();
?>
