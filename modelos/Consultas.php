<?php 
// Incluimos el archivo de conexión a la base de datos, lo cual permitirá ejecutar consultas en la base de datos.
require "../config/Conexion.php";

// Definimos la clase Consultas que tiene métodos para ejecutar consultas específicas sobre las ventas, compras, pagos, y otros.
Class Consultas
{
    // Constructor de la clase, actualmente no hace nada pero es útil para inicializar la clase si fuera necesario.
	public function __construct()
	{
	}

    // Método para obtener compras dentro de un rango de fechas
	public function comprasfecha($fecha_inicio, $fecha_fin)
	{
        // Se define la consulta SQL para obtener los detalles de las compras entre las fechas proporcionadas
		$sql = "SELECT DATE(i.fecha_hora) as fecha, 
                        u.nombre as usuario, 
                        p.nombre as proveedor, 
                        i.tipo_comprobante, 
                        i.serie_comprobante, 
                        i.num_comprobante, 
                        i.total_compra, 
                        i.impuesto, 
                        i.estado 
                FROM ingreso i 
                INNER JOIN persona p ON i.idproveedor = p.idpersona 
                INNER JOIN usuario u ON i.idusuario = u.idusuario 
                WHERE DATE(i.fecha_hora) >= '$fecha_inicio' 
                AND DATE(i.fecha_hora) <= '$fecha_fin'";

        // Ejecutamos la consulta SQL definida y devolvemos el resultado
		return ejecutarConsulta($sql);		
	}

    // Método para obtener ventas dentro de un rango de fechas para un cliente específico
	public function ventasfechacliente($fecha_inicio, $fecha_fin, $idcliente)
	{
        // Se define la consulta SQL para obtener las ventas de un cliente dentro del rango de fechas especificado
		$sql = "SELECT DATE(v.fecha_hora) as fecha, 
                        u.nombre as usuario, 
                        p.nombre as cliente, 
                        v.tipo_comprobante, 
                        v.serie_comprobante, 
                        v.num_comprobante, 
                        v.total_venta, 
                        v.impuesto, 
                        v.estado 
                FROM venta v 
                INNER JOIN persona p ON v.idcliente = p.idpersona 
                INNER JOIN usuario u ON v.idusuario = u.idusuario 
                WHERE DATE(v.fecha_hora) >= '$fecha_inicio' 
                AND DATE(v.fecha_hora) <= '$fecha_fin' 
                AND v.idcliente = '$idcliente'";

        // Ejecutamos la consulta SQL definida y devolvemos el resultado
		return ejecutarConsulta($sql);		
	}

    // Método para obtener el total de los montos de préstamos del día de hoy
	public function totalmontohoy()
	{
        // Consulta SQL para sumar todos los montos de préstamos realizados hoy
		$sql = "SELECT IFNULL(SUM(monto), 0) as total_montos 
                FROM prestamos 
                WHERE DATE(fprestamo) = CURDATE()";

        // Ejecutamos la consulta SQL definida y devolvemos el resultado
		return ejecutarConsulta($sql);
	}

    // Método para obtener el total de los pagos realizados el día de hoy
	public function totalpagoshoy()
	{
        // Consulta SQL para sumar todas las cuotas de pagos realizados hoy
		$sql = "SELECT IFNULL(SUM(cuota), 0) as total_pagos 
                FROM pagos 
                WHERE DATE(fecha) = CURDATE()";

        // Ejecutamos la consulta SQL definida y devolvemos el resultado
		return ejecutarConsulta($sql);
	}
    
    // Método para obtener el total de los gastos realizados el día de hoy
	public function totalgastohoy()
	{
        // Consulta SQL para sumar todos los gastos registrados hoy
		$sql = "SELECT IFNULL(SUM(gasto), 0) as total_gasto 
                FROM gastos 
                WHERE DATE(fecha) = CURDATE()";

        // Ejecutamos la consulta SQL definida y devolvemos el resultado
		return ejecutarConsulta($sql);
	}

    /* Comentado temporalmente. Métodos para obtener las compras de los últimos 10 días y las ventas de los últimos 12 meses.

    // Método para obtener el total de compras de los últimos 10 días
	public function comprasultimos_10dias()
	{
        // Consulta SQL para obtener las compras realizadas en los últimos 10 días, agrupadas por día y ordenadas de manera descendente
		$sql = "SELECT CONCAT(DAY(fecha_hora),'-',MONTH(fecha_hora)) as fecha, 
                        SUM(total_compra) as total 
                FROM ingreso 
                GROUP BY fecha_hora 
                ORDER BY fecha_hora DESC 
                LIMIT 0,10";
        
        // Ejecutamos la consulta SQL definida y devolvemos el resultado
		return ejecutarConsulta($sql);
	}

    // Método para obtener las ventas de los últimos 12 meses
	public function ventasultimos_12meses()
	{
        // Consulta SQL para obtener el total de ventas por mes en los últimos 12 meses
		$sql = "SELECT DATE_FORMAT(fecha_hora, '%M') as fecha, 
                        SUM(total_venta) as total 
                FROM venta 
                GROUP BY MONTH(fecha_hora) 
                ORDER BY fecha_hora DESC 
                LIMIT 0,10";

        // Ejecutamos la consulta SQL definida y devolvemos el resultado
		return ejecutarConsulta($sql);
	} */

}
?>
