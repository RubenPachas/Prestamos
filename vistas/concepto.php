<?php
// Activamos el almacenamiento en el buffer
ob_start();
session_start();

// Incluir el archivo de conexión
require '../config/Conexion.php';  // Asegúrate de que la ruta sea correcta

// Verificamos si el usuario está logueado
if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");  // Si no está logueado, redirige a la página de login
} else {
    require 'header.php';  // Incluye el encabezado de la página (menú de navegación)

    // Verificamos si el usuario tiene permisos para acceder a esta sección
    if ($_SESSION['Escritorio'] == 1) {

        // 1. Consultar el total de préstamos del día
        $sqlPrestamos = "SELECT SUM(monto) AS total_prestamos
                         FROM prestamos
                         WHERE DATE(fprestamo) = CURDATE() AND estado = 1";
        $resultPrestamos = ejecutarConsulta($sqlPrestamos);
        $prestamosDia = 0;
        if ($resultPrestamos) {
            $row = $resultPrestamos->fetch_assoc();
            $prestamosDia = $row['total_prestamos'] ? $row['total_prestamos'] : 0;
        }

        // 2. Consultar el total de pagos del día
        $sqlPagos = "SELECT SUM(cuota) AS total_pagos
                     FROM pagos
                     WHERE DATE(fecha) = CURDATE()";
        $resultPagos = ejecutarConsulta($sqlPagos);
        $pagosDia = 0;
        if ($resultPagos) {
            $row = $resultPagos->fetch_assoc();
            $pagosDia = $row['total_pagos'] ? $row['total_pagos'] : 0;
        }

        // 3. Consultar el total de gastos del día
        $sqlGastos = "SELECT SUM(gasto) AS total_gastos
                      FROM gastos
                      WHERE DATE(fecha) = CURDATE()";
        $resultGastos = ejecutarConsulta($sqlGastos);
        $gastosDia = 0;
        if ($resultGastos) {
            $row = $resultGastos->fetch_assoc();
            $gastosDia = $row['total_gastos'] ? $row['total_gastos'] : 0;
        }
?>

        <!-- Contenido HTML -->
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <header class="main-box-header clearfix">
                        <h3 class="box-title">Escritorio</h3>
                    </header>

                    <div class="row">
                        <div class="main-box-body clearfix">
                             <!-- Tarjeta de Prestamos del Día -->
                            <div class="col-sm-4">
                                <p class="small-box bg-aqua">
                                    <div class="inner">
                                        <h2 style="font-size: 17px;">Prestamos del Día</h2>
                                        <h2><?php echo "$" . number_format($prestamosDia, 2); ?></h2>
                                    </div>
                                </p>
                            </div>

                            <!-- Tarjeta de Pagos del Día -->
                            <div class="col-sm-4">
                                <p class="small-box bg-aqua">
                                    <div class="inner">
                                        <h2 style="font-size: 17px;">Pagos del Día</h2>
                                        <h2><?php echo "$" . number_format($pagosDia, 2); ?></h2>
                                    </div>
                                </p>
                            </div>

                            <!-- Tarjeta de Gastos del Día -->
                            <div class="col-sm-4">
                                <p class="small-box bg-aqua">
                                    <div class="inner">
                                           <h2 style="font-size: 17px;">Gastos del Día</h2>
                                        <h2><?php echo "$" . number_format($gastosDia, 2); ?></h2>
                                    </div>
                                </p>
                            </div>

                        </div>
                    </div>

                    <!-- Agregamos los gráficos -->
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Gráfico Barras (Prestamos, Pagos, Gastos)</h3>
                            <canvas id="barChart"></canvas>
                        </div>
                        <div class="col-md-6">
                            <h3>Gráfico Pastel (Distribución de Prestamos, Pagos, Gastos)</h3>
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php
    } else {
        require 'noacceso.php';  // Si el usuario no tiene permisos, muestra la página de acceso denegado
    }

    require 'footer.php';  // Incluye el pie de página
    ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Datos para el gráfico de barras
        var ctxBar = document.getElementById('barChart').getContext('2d');
        var barChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Prestamos', 'Pagos', 'Gastos'],
                datasets: [{
                    label: 'Monto en el Día',
                    data: [<?php echo $prestamosDia; ?>, <?php echo $pagosDia; ?>, <?php echo $gastosDia; ?>],
                    backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 159, 64, 0.2)'],
                    borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 159, 64, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true  // Configuración para que el eje Y comience desde cero
                    }
                }
            }
        });

        // Datos para el gráfico de pastel
        var ctxPie = document.getElementById('pieChart').getContext('2d');
        var pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Prestamos', 'Pagos', 'Gastos'],
                datasets: [{
                    label: 'Distribución',
                    data: [<?php echo $prestamosDia; ?>, <?php echo $pagosDia; ?>, <?php echo $gastosDia; ?>],
                    backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 159, 64, 0.2)']
                }]
            }
        });
    </script>

<?php
}
ob_end_flush();  // Finaliza el almacenamiento en el búfer y envía el contenido al navegador
?>
