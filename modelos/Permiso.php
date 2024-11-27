<?php 
// Incluimos inicialmente la conexión a la base de datos, la cual permite ejecutar las consultas SQL en la base de datos.
require "../config/Conexion.php";

// Definimos la clase Permisos, que gestionará los permisos en el sistema.
Class Permisos
{
    // Constructor de la clase, aunque no tiene implementación en este caso.
    public function __construct()
    {

    }

    // Método para listar todos los registros de permisos almacenados en la base de datos.
    public function listar()
    {
        // Creamos la consulta SQL que selecciona todos los registros de la tabla 'permisos'.
        // La consulta retorna todas las filas y columnas de la tabla.
        $sql = "SELECT * FROM permisos";

        // Ejecutamos la consulta utilizando la función 'ejecutarConsulta' (que se supone que está definida en otro archivo o clase).
        // Esta función generalmente se encarga de ejecutar la consulta y devolver el resultado, que puede ser un conjunto de filas (resultados).
        return ejecutarConsulta($sql);        
    }
}

?>
