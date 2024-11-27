<?php 
// Incluimos la conexión a la base de datos. 
// Esta es una práctica común para que la clase pueda ejecutar consultas SQL.
require "../config/Conexion.php";
    
// Definimos la clase Prestamo.
Class Prestamo
{
    // Constructor de la clase. No realiza ninguna acción específica en este caso.
    public function __construct()
    {   
    }

    // Método para insertar un nuevo préstamo.
    // Recibe los datos del cliente, monto, interés, saldo, plazo, etc., que se insertarán en la base de datos.
    public function insertar($idcliente, $usuario, $fprestamo, $monto, $interes, $saldo, $formapago, $fechapago, $plazo, $fplazo)
    {
        // La consulta SQL inserta un nuevo préstamo en la tabla `prestamos`.
        // Los valores se toman de los parámetros de la función.
        $sql = "INSERT INTO prestamos (idcliente, usuario, fprestamo, monto, interes, saldo, formapago, fpago, plazo, fplazo, estado) 
                VALUES ('$idcliente', '$usuario', '$fprestamo', '$monto', '$interes', '$saldo', '$formapago', '$fechapago', '$plazo', '$fplazo', '1')";
        
        // Ejecutamos la consulta SQL con la función `ejecutarConsulta` (presumiblemente definida en otro archivo o clase) 
        // que maneja la ejecución de las consultas en la base de datos.
        return ejecutarConsulta($sql);
    }

    // Método para editar un préstamo existente.
    // Recibe el ID del préstamo a editar junto con los nuevos datos para actualizarlo.
    public function editar($idprestamo, $idcliente, $usuario, $fprestamo, $monto, $interes, $saldo, $formapago, $fechapago, $plazo, $fplazo)
    {
        // La consulta SQL actualiza el préstamo con el ID especificado con los nuevos valores proporcionados.
        $sql = "UPDATE prestamos SET 
                    idcliente = '$idcliente',
                    usuario = '$usuario',
                    fprestamo = '$fprestamo',
                    monto = '$monto',
                    interes = '$interes',  
                    saldo = '$saldo',
                    formapago = '$formapago',
                    fpago = '$fechapago',
                    plazo = '$plazo',
                    fplazo = '$fplazo' 
                WHERE idprestamo = '$idprestamo'";

        // Ejecuta la consulta de actualización.
        return ejecutarConsulta($sql);
    }

    // Método para eliminar un préstamo.
    // Recibe el ID del préstamo que se quiere eliminar.
    public function eliminar($idprestamo)
    {
        // La consulta SQL elimina el préstamo con el ID especificado de la tabla `prestamos`.
        $sql = "DELETE FROM prestamos WHERE idprestamo = '$idprestamo'";

        // Ejecuta la consulta de eliminación.
        return ejecutarConsulta($sql);
    }

    // Método para cancelar un préstamo cuando el saldo es 0.
    // Recibe el ID del préstamo, pero la cancelación se basa en la condición de saldo actual.
    public function cancelado($idprestamo)
    {
        // La consulta SQL actualiza el estado del préstamo a '0' (cancelado) si el saldo es 0.
        // La condición es que el saldo del préstamo sea 0, lo que implica que el préstamo está saldado.
        $sql = "UPDATE prestamos SET estado = '0' WHERE saldo = 0";

        // Ejecuta la consulta de cancelación.
        return ejecutarConsulta($sql);
    }

    // Método para mostrar los detalles de un préstamo específico.
    // Recibe el ID del préstamo y retorna sus detalles.
    public function mostrar($idprestamo)
    {
        // La consulta SQL selecciona el préstamo con el ID proporcionado y une la tabla `prestamos`
        // con las tablas `clientes` y `usuarios` para obtener los nombres de cliente y usuario.
        // Se usa `DATE()` para formatear las fechas.
        $sql = "SELECT p.idprestamo, c.nombre as cliente, u.nombre as usuario, DATE(p.fprestamo) as fecha,
                       p.monto, p.interes, p.saldo, p.formapago, DATE(p.fpago) as fechap, p.plazo, 
                       DATE(p.fplazo) as fechaf, p.estado 
                FROM prestamos p 
                INNER JOIN clientes c ON p.idcliente = c.idcliente 
                INNER JOIN usuarios u ON p.usuario = u.idusuario
                WHERE p.idprestamo = '$idprestamo'";

        // Ejecuta la consulta y devuelve los resultados de una sola fila (el préstamo específico).
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar todos los préstamos.
    // Este método devuelve una lista de todos los préstamos, junto con sus detalles.
    public function listar()
    {
        // La consulta SQL selecciona todos los préstamos y sus detalles (con los nombres del cliente y el usuario).
        // Similar al método anterior, pero aquí no hay un filtro por ID de préstamo.
        $sql = "SELECT p.idprestamo, c.nombre as cliente, u.nombre as usuario, DATE(p.fprestamo) as fecha,
                       p.monto, p.interes, p.saldo, p.formapago, DATE(p.fpago) as fechap, p.plazo, 
                       DATE(p.fplazo) as fechaf, p.estado 
                FROM prestamos p 
                INNER JOIN clientes c ON p.idcliente = c.idcliente 
                INNER JOIN usuarios u ON p.usuario = u.idusuario";

        // Ejecuta la consulta y devuelve todos los registros de los préstamos.
        return ejecutarConsulta($sql);
    }

    // Método para seleccionar préstamos activos para su visualización en un `select` (desplegable).
    // Este método es útil cuando se quiere mostrar un listado de préstamos activos en un formulario.
    public function select()
    {
        // La consulta SQL selecciona los ID de los préstamos y los nombres de los clientes,
        // pero solo aquellos préstamos que están activos (estado = 1).
        $sql = "SELECT p.idprestamo, c.nombre 
                FROM prestamos p 
                INNER JOIN clientes c ON p.idcliente = c.idcliente 
                WHERE p.estado = 1 
                ORDER BY c.nombre ASC";

        // Ejecuta la consulta y devuelve los préstamos activos para ser usados en un `select`.
        return ejecutarConsulta($sql);
    }
}
?>
