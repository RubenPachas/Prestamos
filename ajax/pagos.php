<?php
// Se incluye el archivo que contiene la clase Pago, que maneja las operaciones sobre los pagos de préstamos
require_once "../modelos/Pagos.php"; 

// Se crea una instancia de la clase Pago
$pago = new Pago();

// Se capturan los valores que llegan desde un formulario mediante el método POST. Si no están definidos, se les asigna una cadena vacía.
$idpago = isset($_POST["idpago"]) ? limpiarCadena($_POST["idpago"]) : "";
$idprestamo = isset($_POST["idprestamo"]) ? limpiarCadena($_POST["idprestamo"]) : "";
$usuario = isset($_POST["usuario"]) ? limpiarCadena($_POST["usuario"]) : "";
$fecha = isset($_POST["fecha"]) ? limpiarCadena($_POST["fecha"]) : "";
$cuota = isset($_POST["cuota"]) ? limpiarCadena($_POST["cuota"]) : "";

// Dependiendo del parámetro 'op' en la URL, se ejecuta una operación diferente
switch ($_GET["op"]) {

    // Caso para guardar o editar un pago
    case 'guardaryeditar':
        // Si no existe el idpago, se asume que es un nuevo pago y se llama al método insertar
        if (empty($idpago)) {
            // Llamamos al método insertar() de la clase Pago para registrar el nuevo pago
            $rspta = $pago->insertar($idprestamo, $usuario, $fecha, $cuota);
            // Si la inserción fue exitosa, mostramos un mensaje de éxito, de lo contrario un mensaje de error
            echo $rspta ? "Pago Registrado" : "Pago No se Pudo Registrar";
        } else {
            // Si ya existe el idpago, se llama al método editar() para actualizar el pago
            $rspta = $pago->editar($idpago, $idprestamo, $usuario, $fecha, $cuota);
            // Si la actualización fue exitosa, mostramos un mensaje de éxito, de lo contrario un mensaje de error
            echo $rspta ? "Pago Actualizado" : "Pago no se pudo actualizar";
        }
    break;

    // Caso para eliminar un pago
    case 'eliminar':
        // Llamamos al método eliminar() de la clase Pago para eliminar el pago con el idpago especificado
        $rspta = $pago->eliminar($idpago);
        // Mostramos un mensaje de éxito o error según el resultado de la operación
        echo $rspta ? "Pago Eliminado" : "Pago no se puede eliminar";
    break;

    // Caso para mostrar la información de un pago específico
    case 'mostrar':
        // Llamamos al método mostrar() de la clase Pago para obtener la información del pago con el idpago
        $rspta = $pago->mostrar($idpago);
        // Codificamos el resultado como JSON y lo enviamos al frontend
        echo json_encode($rspta);
    break;

    // Caso para listar todos los pagos
    case 'listar':
        // Llamamos al método listar() de la clase Pago para obtener todos los pagos registrados
        $rspta = $pago->listar();
        // Inicializamos un array vacío para almacenar los datos que se enviarán al frontend
        $data = Array();

        // Iteramos sobre los resultados obtenidos de la base de datos
        while ($reg = $rspta->fetch_object()) {
            // Por cada pago, se agrega un objeto al array con los datos correspondientes
            $data[] = array(
                // En la primera columna se colocan los botones de acción (editar y eliminar)
                "0" => '<button class="btn btn-warning" onclick="mostrar(' . $reg->idpago . ')"> <i class="fa fa-pencil"> </i></button>' .
                    ' <button class="btn btn-danger" onclick="eliminar(' . $reg->idpago . ')"> <i class="fa fa-trash"> </i></button>',
                // En la segunda columna se muestra el nombre del cliente
                "1" => $reg->cliente,
                // En la tercera columna se muestra el nombre del usuario que realizó el pago
                "2" => $reg->usuario,
                // En la cuarta columna se muestra la fecha del pago
                "3" => $reg->fecha,
                // En la quinta columna se muestra el monto de la cuota del pago
                "4" => $reg->cuota
            );
        }

        // Preparamos el array de resultados para enviarlo al frontend
        $results = array(
            "sEcho" => 1, // Información adicional para DataTable (tabla dinámica en frontend)
            "iTotalRecords" => count($data), // Número total de registros
            "iTotalDisplayRecords" => count($data), // Número total de registros a mostrar
            "aaData" => $data // Los datos de los pagos
        );
        // Codificamos los resultados en formato JSON y los enviamos al frontend
        echo json_encode($results);
    break;

    // Caso para obtener el listado de préstamos y mostrarlos en un select
    case 'selectPrestamo':
        // Se incluye el archivo que contiene la clase Prestamo, que maneja las operaciones sobre los préstamos
        require_once "../modelos/Prestamos.php";
        $prestamo = new Prestamo();

        // Llamamos al método select() de la clase Prestamo para obtener todos los préstamos registrados
        $rspta = $prestamo->select();
        // Iteramos sobre los resultados y generamos las opciones del select (dropdown)
        while ($reg = $rspta->fetch_object()) {
            // Para cada préstamo, agregamos una opción con el idprestamo y el nombre del préstamo
            echo '<option value="' . $reg->idprestamo . '">' . $reg->nombre . '</option>';
        }
    break;
}
?>
