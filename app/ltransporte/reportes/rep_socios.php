<?php
	if(!isset($_SESSION)){
		session_start();
	}	
	if(isset($_GET['logout'])){
		$_SESSION = [];
	}
	

    $_SESSION['base_url'] = $_SERVER['DOCUMENT_ROOT'].'/'.explode('/',$_SERVER['REQUEST_URI'])[1].'/';
	$_SESSION['base_url1'] = 'http://'.$_SERVER['HTTP_HOST'].'/'.explode('/',$_SERVER['REQUEST_URI'])[1].'/';


	require($_SESSION['base_url'].'lib/fpdf/fpdf.php');

    include_once $_SESSION['base_url'].'/class/system.php';


class PDF extends FPDF

{

// Cabecera de página
function Header()
{
	


    // Logo
    $this->Image($_SESSION['base_url'].'assets/images/banner.png' , 10 ,8, 190 , 25,'png');
	$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	$fecha = $dias[date('w',strtotime("- 6 hour"))]." ".date('d',strtotime("- 6 hour"))." de ".$meses[date('n')-1]. " del ".date('Y');
	$this->SetFont('Arial', '', 10);
	$this->Cell(18, 10, '', 0);
	$this->SetFont('Arial', '', 9);
	$this->Ln(11);
	$this->Ln(11);
	$this->Cell(270, 10, $fecha, 0, 0, 'R', 0);
	$this->Ln(8);
	$this->SetFont('Arial', '', 18);
	$this->Cell(190, 10, "Listado de Socios",0, 0, 'C', 0);
	$this->Ln(8);
	$this->SetFont('Arial', '', 12);
    $nombre_linea = utf8_decode($_SESSION['nom_linea']);
    $this->Cell(190, 10, "$nombre_linea" ,0, 0, 'C', 0);
	$this->Line(10, 65, 200, 65);
	$this->Line(10, 60, 200, 60);
	$this->SetFont('Arial', 'I', 8);
	$this->Ln(7);
	$cedula = utf8_decode("CÉDULA");
	$telefono = utf8_decode("TELÉFONO");
	$this->Ln(4);
	$this->Cell(5, 8, 'N', 0,0,'C');
	$this->Cell(20, 8, $cedula, 0);
	$this->Cell(60, 8, "NOMBRE COMPLETO", 0,0,'L');
	$this->Cell(20, 8, "USUARIO", 0,0,'L');
	$this->Cell(50, 8, "E-MAIL", 0);
	$this->Cell(20, 8, $telefono, 0);
	// Salto de línea
	$this->Ln(6);
	
}
// Pie de página
function Footer()
{
	$pag = utf8_decode("Página");
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
	$this->Line(10, 280, 200, 280);
    // Número de página
    $this->Cell(0,10,''.$pag.' '.$this->PageNo().'/{nb}',0,0,'C');
}
    
}
$db = new Conexion();
$system = new System;
$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 7);
//CONSULTA

    $i = 1;

	$system->sql = "SELECT * FROM users WHERE  perfil = 5 AND cod_linea=".$_SESSION['user'];	
    foreach ($system->sql() as $row) 
    {
 
	$pdf->Cell(5, 5, $i, 0,0,'C');
	$pdf->Cell(20, 5, $row->nacionalidad.'-'.$row->cedula, 0,0,'L');
	$nombre = utf8_decode($row->nombre);        
	$apellido = utf8_decode($row->apellido);        
	$pdf->Cell(60, 5, $nombre.' '.$apellido, 0,0,'L');
	$pdf->Cell(20, 5, $row->usuario, 0,0,'L');
	$pdf->Cell(50, 5, $row->email, 0,0,'L');
	$pdf->Cell(20, 5, $row->telefono, 0,0,'L');
	//$pdf->Cell(50, 5, substr($row->nombre_empresa, 0, 40), 0,0,'L');
	$pdf->Ln(6);
	$i++;

    }

$archivo = "Listado de Socios.pdf";

$pdf->Output($archivo, 'I');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Listado de Socios</title>
</head>

<body>
</body>
</html>