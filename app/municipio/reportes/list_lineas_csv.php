<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}

    $_SESSION['base_url'] = $_SERVER['DOCUMENT_ROOT'].'/'.explode('/',$_SERVER['REQUEST_URI'])[1].'/';
	$_SESSION['base_url1'] = 'http://'.$_SERVER['HTTP_HOST'].'/'.explode('/',$_SERVER['REQUEST_URI'])[1].'/';

    include_once $_SESSION['base_url'].'/class/system.php';

  System::validar_logueo();

  $system = new System; 


if(isset($_POST['exportar_csv']))
{
	// NOMBRE DEL ARCHIVO Y CHARSET
	header('Content-Type:text/csv; charset=latin1');
	header('Content-Disposition: attachment; filename="Reporte_Fechas_Ingreso.csv"');

	// SALIDA DEL ARCHIVO
	$salida=fopen('php://output', 'w');
	// ENCABEZADOS
	fputcsv($salida, array('Id Alumno', 'Nombre', 'Carrera', 'Grupo', 'Fecha de Ingreso'));
	// QUERY PARA CREAR EL REPORTE
	$reporteCsv=$conexion->query("SELECT *  FROM alumnos where fecha_ingreso BETWEEN '$fecha1' AND '$fecha2' ORDER BY id_alumno");
	while($filaR= $reporteCsv->fetch_assoc())
		fputcsv($salida, array($filaR['id_alumno'], 
								$filaR['nombre'],
								$filaR['carrera'],
								$filaR['grupo'],
								$filaR['fecha_ingreso']));

}

?>