<?php 
// Incluimos inicialmente la conexión a la base de datos
// Este archivo es necesario para que las consultas se puedan ejecutar en la base de datos.
require "../config/Conexion.php";

// Definimos la clase `Usuarios`, que gestiona los usuarios del sistema.
Class Usuarios
{
    // Constructor de la clase. No realiza ninguna acción en este caso.
    public function __construct()
    {

    }

    // Método para insertar un nuevo usuario.
    // Este método recibe todos los datos necesarios para crear un nuevo usuario y asignarle permisos.
    public function insertar($nombre, $direccion, $telefono, $login, $clave, $imagen, $permisos)
    {
        // La consulta SQL inserta un nuevo registro en la tabla `usuarios`.
        // Utilizamos los parámetros recibidos para insertar los datos del usuario.
        $sql = "INSERT INTO usuarios (nombre, direccion, telefono, login, clave, imagen, estado)
                VALUES ('$nombre', '$direccion', '$telefono', '$login', '$clave', '$imagen', '1')";
        
        // Ejecutamos la consulta SQL de inserción y obtenemos el ID del usuario recién insertado.
        $idusuarionew = ejecutarConsulta_retornarID($sql);

        // Inicializamos una variable para contar los permisos asignados y un flag de éxito.
        $num_elementos = 0;
        $sw = true;

        // Asignamos los permisos al nuevo usuario.
        // Mientras haya permisos en el array `$permisos`, insertamos cada permiso en la tabla `usuariopermiso`.
        while ($num_elementos < count($permisos)) {
            $sql_detalle = "INSERT INTO usuariopermiso(idusuario, idpermiso) 
                            VALUES('$idusuarionew', '$permisos[$num_elementos]')";
            // Ejecutamos la consulta para insertar cada permiso. Si algo falla, el flag `$sw` se pone a `false`.
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos = $num_elementos + 1;
        }

        // Retornamos el flag `$sw` para indicar si la operación fue exitosa.
        return $sw;
    }

    // Método para editar un usuario existente.
    // Este método recibe el ID del usuario a editar y los nuevos datos.
    public function editar($idusuario, $nombre, $direccion, $telefono, $login, $clave, $imagen, $permisos)
    {
        // La consulta SQL actualiza los datos del usuario con el ID especificado.
        $sql = "UPDATE usuarios SET 
                    nombre = '$nombre',
                    direccion = '$direccion',
                    telefono = '$telefono',
                    login = '$login',
                    clave = '$clave',
                    imagen = '$imagen' 
                WHERE idusuario = '$idusuario'";
        
        // Ejecutamos la consulta de actualización.
        ejecutarConsulta($sql);

        // Primero, eliminamos los permisos existentes para este usuario.
        $sqldel = "DELETE FROM usuariopermiso WHERE idusuario = '$idusuario'";
        ejecutarConsulta($sqldel);

        // Reasignamos los permisos del usuario después de actualizar sus datos.
        $num_elementos = 0;
        $sw = true;

        // Asignamos los nuevos permisos al usuario.
        while ($num_elementos < count($permisos)) {
            $sql_detalle = "INSERT INTO usuariopermiso(idusuario, idpermiso) 
                            VALUES('$idusuario', '$permisos[$num_elementos]')";
            // Ejecutamos la consulta para insertar cada permiso. Si algo falla, el flag `$sw` se pone a `false`.
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos = $num_elementos + 1;
        }

        // Retornamos el flag `$sw` para indicar si la operación fue exitosa.
        return $sw;
    }

    // Método para desactivar un usuario.
    // Este método recibe el ID del usuario y cambia su estado a '0' (desactivado).
    public function desactivar($idusuario)
    {
        // La consulta SQL actualiza el estado del usuario a '0' (desactivado).
        $sql = "UPDATE usuarios SET estado = '0' WHERE idusuario = '$idusuario'";
        return ejecutarConsulta($sql);
    }

    // Método para activar un usuario.
    // Este método recibe el ID del usuario y cambia su estado a '1' (activado).
    public function activar($idusuario)
    {
        // La consulta SQL actualiza el estado del usuario a '1' (activado).
        $sql = "UPDATE usuarios SET estado = '1' WHERE idusuario = '$idusuario'";
        return ejecutarConsulta($sql);
    }

    // Método para obtener los datos de un usuario específico.
    // Recibe el ID del usuario y retorna sus datos completos.
    public function mostrar($idusuario)
    {
        // La consulta SQL selecciona todos los datos del usuario con el ID proporcionado.
        $sql = "SELECT * FROM usuarios WHERE idusuario = '$idusuario'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar todos los usuarios.
    // Este método retorna todos los usuarios registrados en el sistema.
    public function listar()
    {
        // La consulta SQL selecciona todos los registros de la tabla `usuarios`.
        $sql = "SELECT * FROM usuarios";
        return ejecutarConsulta($sql);
    }

    // Método para listar los permisos asignados a un usuario específico.
    // Este método recibe el ID del usuario y retorna los permisos que tiene asignados.
    public function listarmarcados($idusuario)
    {
        // La consulta SQL selecciona todos los permisos del usuario con el ID proporcionado.
        $sql = "SELECT * FROM usuariopermiso WHERE idusuario = '$idusuario'";
        return ejecutarConsulta($sql);
    }

    // Método para verificar el acceso al sistema.
    // Este método recibe un `login` y una `clave`, y verifica si estos coinciden con algún usuario activo.
    public function verificar($login, $clave)
    {
        // La consulta SQL selecciona el usuario con el login y la clave proporcionados.
        // Además, verifica que el usuario esté activo (estado = '1').
        $sql = "SELECT idusuario, nombre, direccion, telefono, imagen, login 
                FROM usuarios 
                WHERE login = '$login' AND clave = '$clave' AND estado = '1'";
        return ejecutarConsulta($sql);
    }

    // Método para seleccionar todos los usuarios activos para su uso en un `select`.
    // Este método es útil cuando se quiere mostrar un listado de usuarios activos en un formulario.
    public function select()
    {
        // La consulta SQL selecciona todos los usuarios activos (estado = 1) ordenados por nombre.
        $sql = "SELECT * FROM usuarios WHERE estado = 1 ORDER BY nombre DESC";
        return ejecutarConsulta($sql);
    }
}
?>
