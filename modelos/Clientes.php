<?php
// Incluimos el archivo de conexión a la base de datos, que debe contener las configuraciones necesarias
// para establecer una conexión a la base de datos, como el host, usuario, contraseña, y nombre de la base de datos.
require "../config/Conexion.php";

// Definimos la clase 'Clientes', que encapsula las operaciones relacionadas con los clientes en la base de datos.
class Clientes
{
    // Implementamos el constructor de la clase, que en este caso no realiza ninguna acción
    public function __construct() {}

    // Método para insertar un nuevo cliente en la base de datos
    public function insertar($cedula, $nombre, $direccion, $telefono)
    {
        // Definimos la consulta SQL para insertar un nuevo registro en la tabla 'clientes'
        // Se insertan los valores de cédula, nombre, dirección, teléfono y el estado (1 para activo)
        $sql = "INSERT INTO clientes (cedula, nombre, direccion, telefono, estado)
                VALUES ('$cedula', '$nombre', '$direccion', '$telefono', '1')";
        // Ejecutamos la consulta y devolvemos el resultado
        return ejecutarConsulta($sql);
    }

    // Método para editar un cliente existente
    public function editar($idcliente, $cedula, $nombre, $direccion, $telefono)
    {
        // Definimos la consulta SQL para actualizar los datos de un cliente específico
        // El cliente a editar se selecciona por su 'idcliente', y se actualizan los campos de cédula, nombre, dirección y teléfono
        $sql = "UPDATE clientes
                SET cedula='$cedula', nombre='$nombre', direccion='$direccion', telefono='$telefono'
                WHERE idcliente='$idcliente'";
        // Ejecutamos la consulta y devolvemos el resultado
        return ejecutarConsulta($sql);
    }

    // Método para desactivar un cliente (cambiar el estado a '0', es decir, inactivo)
    public function desactivar($idcliente)
    {
        // Consulta SQL para actualizar el estado del cliente a '0', indicando que está desactivado
        $sql = "UPDATE clientes SET estado='0' WHERE idcliente='$idcliente'";
        // Ejecutamos la consulta y devolvemos el resultado
        return ejecutarConsulta($sql);
    }

    // Método para activar un cliente (cambiar el estado a '1', es decir, activo)
    public function activar($idcliente)
    {
        // Consulta SQL para actualizar el estado del cliente a '1', indicando que está activo
        $sql = "UPDATE clientes SET estado='1' WHERE idcliente='$idcliente'";
        // Ejecutamos la consulta y devolvemos el resultado
        return ejecutarConsulta($sql);
    }

    // Método para obtener los datos de un cliente específico (por su idcliente)
    public function mostrar($idcliente)
    {
        // Consulta SQL para seleccionar todos los campos de un cliente específico
        // usando su 'idcliente' para identificarlo
        $sql = "SELECT * FROM clientes WHERE idcliente='$idcliente'";
        // Ejecutamos la consulta y devolvemos el resultado como una fila única
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar todos los clientes
    public function listar()
    {
        // Consulta SQL para seleccionar todos los clientes de la base de datos
        $sql = "SELECT * FROM clientes";
        // Ejecutamos la consulta y devolvemos el resultado
        return ejecutarConsulta($sql);
    }

    // Método para obtener una lista de clientes activos, útil para mostrar en un selector (dropdown)
    public function select()
    {
        // Consulta SQL para seleccionar el 'idcliente' y el 'nombre' de todos los clientes activos
        // Los resultados se ordenan por el nombre en orden ascendente
        $sql = "SELECT idcliente, nombre FROM clientes WHERE estado=1 ORDER BY nombre ASC";
        // Ejecutamos la consulta y devolvemos el resultado
        return ejecutarConsulta($sql);
    }
}
?>
