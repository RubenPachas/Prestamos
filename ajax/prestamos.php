<?php
// Se incluye el archivo que contiene la clase Prestamo, que maneja las operaciones sobre préstamos
require_once "../modelos/Prestamos.php";

// Se crea una instancia de la clase Prestamo
$prestamo = new Prestamo();

// Se recogen los datos enviados por POST, y se limpia cualquier cadena (esto previene inyecciones SQL)
$idprestamo = isset($_POST["idprestamo"]) ? limpiarCadena($_POST["idprestamo"]) : "";
$idcliente = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
$usuario = isset($_POST["usuario"]) ? limpiarCadena($_POST["usuario"]) : "";
$fprestamo = isset($_POST["fprestamo"]) ? limpiarCadena($_POST["fprestamo"]) : "";
$monto = isset($_POST["monto"]) ? limpiarCadena($_POST["monto"]) : "";
$interes = isset($_POST["interes"]) ? limpiarCadena($_POST["interes"]) : "";
$saldo = isset($_POST["saldo"]) ? limpiarCadena($_POST["saldo"]) : "";
$formapago = isset($_POST["formapago"]) ? limpiarCadena($_POST["formapago"]) : "";
$fechapago = isset($_POST["fechapago"]) ? limpiarCadena($_POST["fechapago"]) : "";
$plazo = isset($_POST["plazo"]) ? limpiarCadena($_POST["plazo"]) : "";
$fplazo = isset($_POST["fplazo"]) ? limpiarCadena($_POST["fplazo"]) : "";

// Control de operaciones basado en el parámetro 'op' recibido por GET
switch ($_GET["op"]) {
    // Caso para guardar o editar un préstamo
    case 'guardaryeditar':
        // Si no existe el idprestamo, entonces es un nuevo préstamo
        if (empty($idprestamo)) {
            $rspta = $prestamo->insertar($idcliente, $usuario, $fprestamo, $monto, $interes, $saldo, $formapago, $fechapago, $plazo, $fplazo);
            echo $rspta ? "Préstamo registrado" : "Préstamo no se pudo registrar";
        } else {
            // Si existe idprestamo, entonces es una actualización
            $rspta = $prestamo->editar($idprestamo, $idcliente, $usuario, $fprestamo, $monto, $interes, $saldo, $formapago, $fechapago, $plazo, $fplazo);
            echo $rspta ? "Préstamo actualizado" : "Préstamo no se pudo actualizar";
        }
        break;

    // Caso para eliminar un préstamo
    case 'eliminar':
        $rspta = $prestamo->eliminar($idprestamo);
        echo $rspta ? "Préstamo eliminado" : "Préstamo no se puede eliminar";
        break;

    // Caso para marcar un préstamo como cancelado
    case 'cancelado':
        $rspta = $prestamo->cancelado($idprestamo);
        echo $rspta ? "Préstamo cancelado" : "Préstamo no se puede cancelar";
        break;

    // Caso para mostrar los detalles de un préstamo específico
    case 'mostrar':
        $rspta = $prestamo->mostrar($idprestamo);
        // Se codifica el resultado como JSON para que el frontend lo procese
        echo json_encode($rspta);
        break;

    // Caso para listar todos los préstamos
    case 'listar':
        $rspta = $prestamo->listar();
        // Se crea un array para almacenar los datos
        $data = array();

        // Se recorren los resultados obtenidos de la base de datos
        while ($reg = $rspta->fetch_object()) {
            // Se agrega cada préstamo a la lista de datos
            $data[] = array(
                "0" => '<button class="btn btn-warning" onclick="mostrar(' . $reg->idprestamo . ')"> <i class="fa fa-pencil"> </i></button>' .
                    ' <button class="btn btn-danger" onclick="eliminar(' . $reg->idprestamo . ')"> <i class="fa fa-trash"> </i></button>',
                "1" => $reg->cliente,
                "2" => $reg->usuario,
                "3" => $reg->fecha,
                "4" => $reg->monto,
                "5" => $reg->interes,
                "6" => $reg->saldo,
                "7" => $reg->formapago,
                "8" => $reg->fechap,
                "9" => $reg->plazo,
                "10" => $reg->fechaf,
                "11" => ($reg->estado) ? '<span class="label bg-success">Activado</span>' : '<span class="label bg-danger">Cancelado</span>'
            );
        }

        // Se prepara la respuesta en formato JSON para el frontend
        $results = array(
            "sEcho" => 1, // Información adicional para el datatable
            "iTotalRecords" => count($data), // Número total de registros
            "iTotalDisplayRecords" => count($data), // Número total de registros a mostrar
            "aaData" => $data // Los datos reales a mostrar
        );
        echo json_encode($results);
        break;

    // Caso para seleccionar clientes (para crear un préstamo)
    case 'selectCliente':
        require_once "../modelos/Clientes.php";
        $cliente = new Clientes();
        $rspta = $cliente->select();
        // Se crea una opción para cada cliente
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idcliente . '>' . $reg->nombre . '</option>';
        }
        break;

    // Caso para seleccionar usuarios (para asociar un préstamo a un usuario)
    case "selectUsuario":
        require_once "../modelos/Usuarios.php";
        $usuario = new Usuarios();
        $rspta = $usuario->select();
        // Se crea una opción para cada usuario
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idusuario . '>' . $reg->nombre . '</option>';
        }
        break;
}
?>
