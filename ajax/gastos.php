<?php
// Se incluye el archivo que contiene la clase Gastos, la cual maneja las operaciones de los gastos.
require_once "../modelos/Gastos.php"; 

// Se crea una instancia de la clase Gastos
$gastos = new Gastos();

// Se capturan los valores que vienen del formulario mediante POST, y si no están definidos, se asigna un valor vacío.
$idgasto = isset($_POST["idgasto"]) ? limpiarCadena($_POST["idgasto"]) : "";
$idusuario = isset($_POST["idusuario"]) ? limpiarCadena($_POST["idusuario"]) : "";
$fecha = isset($_POST["fecha"]) ? limpiarCadena($_POST["fecha"]) : "";
$concepto = isset($_POST["concepto"]) ? limpiarCadena($_POST["concepto"]) : "";
$gasto = isset($_POST["gasto"]) ? limpiarCadena($_POST["gasto"]) : "";

// El valor de 'op' en la URL se usa para determinar qué operación realizar (insertar, editar, eliminar, mostrar, listar)
switch ($_GET["op"]) {
        
    // Caso para registrar o editar un gasto
    case 'guardaryeditar':
        // Si no existe el idgasto, significa que es un nuevo gasto (registro)
        if (empty($idgasto)) {
            // Llamamos al método 'insertar' de la clase Gastos para registrar un nuevo gasto
            $rspta = $gastos->insertar($idusuario, $fecha, $concepto, $gasto); 
            // Si la inserción fue exitosa, mostramos un mensaje de éxito, de lo contrario mostramos un mensaje de error
            echo $rspta ? "Gasto registrado" : "Gasto no se pudo registrar";
        } else {
            // Si ya existe el idgasto, significa que se va a editar un gasto ya registrado
            $rspta = $gastos->editar($idgasto, $idusuario, $fecha, $concepto, $gasto);
            // Mostramos un mensaje dependiendo de si la actualización fue exitosa o no
            echo $rspta ? "Gasto actualizado" : "Gasto no se pudo actualizar";
        }
    break;
        
    // Caso para eliminar un gasto
    case 'eliminar':
        // Llamamos al método 'eliminar' de la clase Gastos para eliminar el gasto con el idgasto proporcionado
        $rspta = $gastos->eliminar($idgasto); 
        // Mostramos un mensaje de éxito o error según el resultado de la operación
        echo $rspta ? "Gasto Eliminado" : "Gasto no se puede eliminar";
    break;
        
    // Caso para mostrar la información de un gasto específico
    case 'mostrar':
        // Llamamos al método 'mostrar' de la clase Gastos para obtener la información del gasto con el idgasto proporcionado
        $rspta = $gastos->mostrar($idgasto); 
        // Codificamos el resultado en formato JSON para devolverlo al frontend (interfaz gráfica)
        echo json_encode($rspta);
    break;
        
    // Caso para listar todos los gastos
    case 'listar':
        // Llamamos al método 'listar' de la clase Gastos para obtener todos los gastos
        $rspta = $gastos->listar(); 
        // Inicializamos un array vacío para almacenar los datos
        $data = Array();
        
        // Iteramos sobre los registros obtenidos de la base de datos
        while ($reg = $rspta->fetch_object()) { 
            // Por cada gasto, se agrega un objeto al array con los datos correspondientes
            $data[] = array(
                // En la primera columna se colocan los botones de acción (editar y eliminar)
                "0" => '<button class="btn btn-warning" onclick="mostrar(' . $reg->idgasto . ')"> <i class="fa fa-pencil"> </i></button>' .
                       ' <button class="btn btn-danger" onclick="eliminar(' . $reg->idgasto . ')"> <i class="fa fa-trash"> </i></button>',
                // En la segunda columna se muestra el nombre del usuario asociado al gasto
                "1" => $reg->Usuario, 
                // En la tercera columna se muestra la fecha del gasto
                "2" => $reg->fecha, 
                // En la cuarta columna se muestra el concepto del gasto
                "3" => $reg->concepto,
                // En la quinta columna se muestra el monto del gasto
                "4" => $reg->gasto 
            );
        }
        
        // Se prepara el array de resultados para enviarlo al frontend
        $results = array(
            "sEcho" => 1, // Información para el DataTable (tabla dinámica en frontend)
            "iTotalRecords" => count($data), // Número total de registros
            "iTotalDisplayRecords" => count($data), // Número total de registros que se mostrarán
            "aaData" => $data // Datos de los gastos
        );
        
        // Codificamos los resultados en formato JSON y los enviamos al frontend
        echo json_encode($results);
    break;
        
    // Caso para obtener el listado de usuarios y mostrarlos en un select
    case "selectUsuario":
        // Se incluye el archivo que contiene la clase Usuarios, la cual maneja las operaciones sobre los usuarios
        require_once "../modelos/Usuarios.php"; 
        $usuario = new Usuarios();
        
        // Llamamos al método 'select' de la clase Usuarios para obtener el listado de usuarios
        $rspta = $usuario->select();
        
        // Iteramos sobre los resultados y generamos las opciones del select (dropdown)
        while ($reg = $rspta->fetch_object()) {
            // Para cada usuario, agregamos una opción con el idusuario y el nombre del usuario
            echo '<option value=' . $reg->idusuario . '>' . $reg->nombre . '</option>';
        }
    break;
}

?>
