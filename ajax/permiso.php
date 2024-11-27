<?php
// Se incluye el archivo que contiene la clase Permisos, la cual maneja las operaciones sobre permisos.
require_once "../modelos/Permiso.php";

// Se crea una instancia de la clase Permisos
$permiso = new Permisos();

// Se evalúa el valor de la variable 'op' recibida mediante el parámetro GET en la URL
switch ($_GET["op"]) {

    // Caso para listar todos los permisos
    case 'listar':
        // Llamamos al método listar() de la clase Permisos para obtener todos los registros de permisos desde la base de datos
        $rspta = $permiso->listar();
        
        // Declaramos un array vacío donde almacenaremos los datos de los permisos
        $data = array();

        // Iteramos sobre los resultados obtenidos de la base de datos
        while ($reg = $rspta->fetch_object()) {
            // Por cada permiso, agregamos los datos al array $data
            $data[] = array(
                "0" => $reg->idpermiso, // El ID del permiso
                "1" => $reg->permiso    // El nombre o descripción del permiso
            );
        }

        // Preparamos el array de resultados para el frontend, que será enviado al DataTable
        $results = array(
            "sEcho" => 1, // Información adicional para el DataTable (suele ser un contador o alguna información extra)
            "iTotalRecords" => count($data), // El número total de registros en el DataTable
            "iTotalDisplayRecords" => count($data), // El número total de registros que serán mostrados en la vista
            "aaData" => $data // Los datos reales que serán mostrados en la tabla
        );
        
        // Codificamos el array de resultados en formato JSON y lo enviamos al frontend
        echo json_encode($results);

        break;
}
?>
