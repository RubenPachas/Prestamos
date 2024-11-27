<?php
// Incluimos la conexión a la base de datos, lo que permitirá ejecutar consultas SQL sobre la base de datos.
require "../config/Conexion.php";

// Definimos la clase Pago, que maneja las operaciones CRUD (Crear, Leer, Actualizar, Eliminar) sobre los pagos realizados.
Class Pago
{
    // Constructor de la clase. En este caso, no tiene ninguna implementación, pero es útil para la estructura de la clase.
    public function __construct()
    {   
    }

    // Método para insertar un nuevo pago en la base de datos.
    public function insertar($idprestamo, $usuario, $fecha, $cuota)
    {
        // Creamos la consulta SQL para insertar un nuevo pago en la tabla 'pagos'.
        // Los valores se insertan directamente en la consulta utilizando los parámetros recibidos.
        $sql = "INSERT INTO pagos (idprestamo, usuario, fecha, cuota) 
                VALUES ('$idprestamo', '$usuario', '$fecha', '$cuota')";

        // Ejecutamos la consulta a través de la función 'ejecutarConsulta' (se supone que esta función está definida en otro lugar).
        // Esta función generalmente devuelve un valor booleano o el número de filas afectadas por la consulta.
        return ejecutarConsulta($sql);
    }

    // Método para actualizar los detalles de un pago existente.
    public function editar($idpago, $idprestamo, $usuario, $fecha, $cuota)
    {
        // Creamos la consulta SQL para actualizar un registro de pago existente identificado por 'idpago'.
        // Actualizamos los valores de 'idprestamo', 'usuario', 'fecha', y 'cuota' en el pago correspondiente.
        $sql = "UPDATE pagos 
                SET idprestamo = '$idprestamo',
                    usuario = '$usuario',
                    fecha = '$fecha',
                    cuota = '$cuota'
                WHERE idpago = '$idpago'";

        // Ejecutamos la consulta y devolvemos el resultado de la operación.
        return ejecutarConsulta($sql);
    }

    // Método para eliminar un pago de la base de datos, identificado por su 'idpago'.
    public function eliminar($idpago)
    {
        // Creamos la consulta SQL para eliminar un pago específico identificado por 'idpago'.
        $sql = "DELETE FROM pagos WHERE idpago = '$idpago'";

        // Ejecutamos la consulta de eliminación y devolvemos el resultado.
        return ejecutarConsulta($sql);
    }

    // Método para obtener los detalles de un pago específico. Devuelve una sola fila con la información.
    public function mostrar($idpago)
    {
        // Creamos la consulta SQL para obtener los detalles de un pago específico.
        // La consulta utiliza un INNER JOIN para obtener información adicional relacionada con el cliente del pago.
        $sql = "SELECT c.nombre AS cliente, 
                        g.usuario, 
                        g.fecha, 
                        g.cuota 
                FROM pagos g 
                INNER JOIN prestamos p ON g.idprestamo = p.idprestamo 
                INNER JOIN clientes c ON p.idcliente = c.idcliente
                WHERE g.idpago = '$idpago'"; // Aquí se debería usar el parámetro $idpago para filtrar los resultados.

        // Ejecutamos la consulta y devolvemos el resultado de la fila seleccionada.
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar todos los pagos registrados en la base de datos.
    // Realiza una consulta SQL con un JOIN entre las tablas 'pagos', 'prestamos' y 'clientes' para obtener la información detallada de cada pago.
    public function listar()
    {
        // Creamos la consulta SQL para listar todos los pagos, con los detalles del cliente asociado a cada pago.
        $sql = "SELECT g.idpago,
                        c.nombre AS cliente, 
                        g.usuario, 
                        g.fecha, 
                        g.cuota 
                FROM pagos g 
                INNER JOIN prestamos p ON g.idprestamo = p.idprestamo 
                INNER JOIN clientes c ON p.idcliente = c.idcliente";

        // Ejecutamos la consulta SQL y devolvemos el resultado, que será una lista de pagos.
        return ejecutarConsulta($sql);
    }
}
?>
