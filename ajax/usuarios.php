<?php 
session_start(); 
require_once "../modelos/Usuarios.php";

$usuarios = new Usuarios();

// Captura de datos de entrada
$idusuario = isset($_POST["idusuario"]) ? limpiarCadena($_POST["idusuario"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
$login = isset($_POST["login"]) ? limpiarCadena($_POST["login"]) : "";
$clave = isset($_POST["clave"]) ? limpiarCadena($_POST["clave"]) : "";
$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        // Subida de imagen
        if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
            $imagen = $_POST["imagenactual"];
        } else {
            $ext = explode(".", $_FILES["imagen"]["name"]);
            if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png") {
                $imagen = round(microtime(true)) . '.' . end($ext);
                move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/" . $imagen);
            }
        }

        // Hashing de la contraseña
        $clavehash = hash("SHA256", $clave);

        // Insertar o actualizar usuario
        if (empty($idusuario)) {
            $rspta = $usuarios->insertar($nombre, $direccion, $telefono, $login, $clavehash, $imagen, $_POST['permiso']);
            echo $rspta ? "Usuario registrado" : "Usuario no se pudo registrar";
        } else {
            $rspta = $usuarios->editar($idusuario, $nombre, $direccion, $telefono, $login, $clavehash, $imagen, $_POST['permiso']);
            echo $rspta ? "Usuario actualizado" : "Usuario no se pudo actualizar";
        }
        break;

    case 'desactivar':
        $rspta = $usuarios->desactivar($idusuario);
        echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";
        break;

    case 'activar':
        $rspta = $usuarios->activar($idusuario);
        echo $rspta ? "Usuario activado" : "Usuario no se puede activar";
        break;

    case 'mostrar':
        $rspta = $usuarios->mostrar($idusuario);
        // Codificar el resultado utilizando JSON
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $usuarios->listar();
        // Declarar el array de datos
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->estado) ? '<button class="btn btn-warning" onclick="mostrar(' . $reg->idusuario . ')"><i class="fa fa-pencil"></i></button>' .
                    ' <button class="btn btn-danger" onclick="desactivar(' . $reg->idusuario . ')"><i class="fa fa-close"></i></button>' :
                    '<button class="btn btn-warning" onclick="mostrar(' . $reg->idusuario . ')"><i class="fa fa-pencil"></i></button>' .
                    ' <button class="btn btn-primary" onclick="activar(' . $reg->idusuario . ')"><i class="fa fa-check"></i></button>',
                "1" => $reg->nombre,
                "2" => $reg->direccion,
                "3" => $reg->telefono,
                "4" => $reg->login,
                "5" => "<img src='../files/usuarios/" . $reg->imagen . "' height='50px' width='50px'>",
                "6" => ($reg->estado) ? '<span class="label bg-primary">Activado</span>' : '<span class="label bg-danger">Desactivado</span>'
            );
        }

        $results = array(
            "sEcho" => 1, // Información para el DataTables
            "iTotalRecords" => count($data), // Total de registros
            "iTotalDisplayRecords" => count($data), // Total de registros a mostrar
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case 'permisos':
        // Obtener todos los permisos de la tabla permisos
        require_once "../modelos/Permiso.php";
        $permiso = new Permisos();
        $rspta = $permiso->listar();

        // Obtener los permisos asignados al usuario
        $id = $_GET['id'];
        $marcados = $usuarios->listarmarcados($id);
        // Array para almacenar los permisos marcados
        $valores = array();

        // Almacenar los permisos asignados en el array
        while ($per = $marcados->fetch_object()) {
            array_push($valores, $per->idpermiso);
        }

        // Mostrar la lista de permisos
        while ($reg = $rspta->fetch_object()) {
            $sw = in_array($reg->idpermiso, $valores) ? 'checked' : '';
            echo '<li><input type="checkbox" ' . $sw . '  name="permiso[]" value="' . $reg->idpermiso . '">' . $reg->permiso . '</li>';
        }
        break;

    case 'verificar':
        $logina = $_POST['logina'];
        $clavea = $_POST['clavea'];

        // Hashing de la contraseña
        $clavehash = hash("SHA256", $clavea);

        $rspta = $usuarios->verificar($logina, $clavehash);

        $fetch = $rspta->fetch_object();

        if (isset($fetch)) {
            // Inicializar las variables de sesión
            $_SESSION['idusuario'] = $fetch->idusuario;
            $_SESSION['nombre'] = $fetch->nombre;
            $_SESSION['imagen'] = $fetch->imagen;
            $_SESSION['login'] = $fetch->login;

            // Obtener los permisos del usuario
            $marcados = $usuarios->listarmarcados($fetch->idusuario);
            $valores = array();

            // Almacenar los permisos marcados
            while ($per = $marcados->fetch_object()) {
                array_push($valores, $per->idpermiso);
            }

            // Establecer los accesos del usuario
            in_array(1, $valores) ? $_SESSION['Escritorio'] = 1 : $_SESSION['Escritorio'] = 0;
            in_array(2, $valores) ? $_SESSION['Clientes'] = 1 : $_SESSION['Clientes'] = 0;
            in_array(3, $valores) ? $_SESSION['Prestamos'] = 1 : $_SESSION['Prestamos'] = 0;
            in_array(4, $valores) ? $_SESSION['Pagos'] = 1 : $_SESSION['Pagos'] = 0;
            in_array(5, $valores) ? $_SESSION['Usuarios'] = 1 : $_SESSION['Usuarios'] = 0;
            in_array(6, $valores) ? $_SESSION['Gastos'] = 1 : $_SESSION['Gastos'] = 0;
            in_array(7, $valores) ? $_SESSION['Consultas'] = 1 : $_SESSION['Consultas'] = 0;
        }
        echo json_encode($fetch);
        break;

    case 'salir':
        // Limpiar las variables de sesión
        session_unset();
        // Destruir la sesión
        session_destroy();
        // Redirigir al login
        header("Location: ../index.php");
        break;
}
?>
