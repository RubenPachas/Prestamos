<?php 
// Se incluye el archivo de la clase Clientes, que contiene los métodos para interactuar con la base de datos
require_once "../modelos/Clientes.php"; 

// Se crea una instancia de la clase Clientes
$cliente = new Clientes();

// Se capturan los datos enviados desde un formulario mediante el método POST. 
// Si no están definidos, se asigna un valor vacío.
$idcliente = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
$cedula = isset($_POST["cedula"]) ? limpiarCadena($_POST["cedula"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";

// El valor de 'op' en la URL se usa para determinar qué operación realizar.
switch ($_GET["op"]) {
        
    // Caso para registrar o editar un cliente
    case 'guardaryeditar':
        // Si no existe el idcliente, significa que es un nuevo cliente (registro)
        if (empty($idcliente)) {
            // Se llama al método insertar() de la clase Clientes para registrar el nuevo cliente
            $rspta = $cliente->insertar($cedula, $nombre, $direccion, $telefono); 
            // Si la inserción fue exitosa, se muestra un mensaje de éxito, de lo contrario un mensaje de error
            echo $rspta ? "Cliente registrado" : "Cliente no se pudo registrar";
        } else {
            // Si ya existe el idcliente, significa que se va a editar un cliente existente
            $rspta = $cliente->editar($idcliente, $cedula, $nombre, $direccion, $telefono); 
            // Se muestra un mensaje de éxito o error según el resultado de la edición
            echo $rspta ? "Cliente actualizado" : "Cliente no se pudo actualizar";
        }
    break;

    // Caso para desactivar un cliente
    case 'desactivar':
        // Se llama al método desactivar() de la clase Clientes pasando el id del cliente
        $rspta = $cliente->desactivar($idcliente); 
        // Se muestra un mensaje según si el cliente fue desactivado o no
        echo $rspta ? "Cliente Desactivado" : "Cliente no se puede desactivar";
    break;

    // Caso para activar un cliente
    case 'activar':
        // Se llama al método activar() de la clase Clientes pasando el id del cliente
        $rspta = $cliente->activar($idcliente); 
        // Se muestra un mensaje según si el cliente fue activado o no
        echo $rspta ? "Cliente activado" : "Cliente no se puede activar";
    break;
        
    // Caso para mostrar un cliente específico
    case 'mostrar':
        // Se llama al método mostrar() de la clase Clientes pasando el id del cliente
        $rspta = $cliente->mostrar($idcliente); 
        // Se codifica el resultado en formato JSON para enviarlo al frontend
        echo json_encode($rspta); 
    break;

    // Caso para listar todos los clientes
    case 'listar':
        // Se llama al método listar() de la clase Clientes para obtener la lista de clientes
        $rspta = $cliente->listar($idcliente); 
        // Se crea un array vacío para almacenar los datos que se enviarán al frontend
        $data = Array();

        // Se recorre el resultado de la consulta a la base de datos, obteniendo cada registro
        while ($reg = $rspta->fetch_object()) { 
            // Se va construyendo un array con los datos que se enviarán al frontend
            $data[] = array(
                // En la primera columna se coloca un botón con acciones dependiendo del estado del cliente
                "0" => ($reg->estado) ? 
                    '<button class="btn btn-warning" onclick="mostrar(' . $reg->idcliente . ')"><i class="fa fa-pencil"></i></button>' .
                    ' <button class="btn btn-danger" onclick="desactivar(' . $reg->idcliente . ')"><i class="fa fa-close"></i></button>' :
                    '<button class="btn btn-warning" onclick="mostrar(' . $reg->idcliente . ')"><i class="fa fa-pencil"></i></button>' .
                    ' <button class="btn btn-primary" onclick="activar(' . $reg->idcliente . ')"><i class="fa fa-check"></i></button>',
                // La segunda columna muestra la cédula del cliente
                "1" => $reg->cedula, 
                // La tercera columna muestra el nombre del cliente
                "2" => $reg->nombre, 
                // La cuarta columna muestra la dirección del cliente
                "3" => $reg->direccion, 
                // La quinta columna muestra el teléfono del cliente
                "4" => $reg->telefono,
                // La sexta columna muestra el estado del cliente (activado o desactivado)
                "5" => ($reg->estado) ? '<span class="label bg-primary">Activado</span>' : '<span class="bg-warning">Desactivado</span>'
            );
        }

        // Se prepara el array de resultados para ser enviado al frontend
        $results = array(
            "sEcho" => 1, // Información adicional para el DataTable (una tabla dinámica en frontend)
            "iTotalRecords" => count($data), // Número total de registros en la base de datos
            "iTotalDisplayRecords" => count($data), // Número total de registros a mostrar
            "aaData" => $data // Los datos de los clientes (el array que contiene la información a mostrar)
        );

        // Se codifican los resultados en formato JSON y se envían al frontend
        echo json_encode($results);
    break;
}
?>
