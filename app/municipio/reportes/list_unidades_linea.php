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
	$system = new System;
	$db = new Conexion();
    
    $id = base64_decode($_GET['id']);
    

    $system->sql = "SELECT nombre_linea FROM users WHERE cod_linea =".$id;
    
    $dat = $system->sql();

    
    // Logo
    $this->Image($_SESSION['base_url'].'assets/images/banner.png' , 10 ,8, 275 , 25,'png');
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
	$this->Cell(275, 10, utf8_decode("PLANILLA DE INSPECCIÓN DE UNIDADES"),0, 0, 'C', 0);
	$this->Ln(8);
	$this->SetFont('Arial', '', 12);
    $nombre_linea = utf8_decode($dat[0]->nombre_linea);
    $this->Cell(275, 10, "$nombre_linea " ,0, 0, 'C', 0);
    $this->SetFillColor( 234, 236, 238 );
    $this->Rect(10, 60, 275 , 5, 'F');	
    //$this->Line(10, 65, 285, 65);
	//$this->Line(10, 60, 285, 60);
	$this->SetFont('Arial', 'I', 8);
	$this->Ln(7);
	$cedula = utf8_decode("CÉDULA");
	$año = utf8_decode("AÑO");
	$this->Ln(4);
	$this->Cell(5, 8, 'N', 0,0,'C');
	$this->Cell(15, 8, $cedula, 0);
	$this->Cell(55, 8, "PROPIETARIO", 0,0,'L');
	$this->Cell(20, 8, "MARCA", 0,0,'L');
	$this->Cell(15, 8, "MODELO", 0);
	$this->Cell(8, 8, $año, 0);
	$this->Cell(15, 8, "TIPO", 0);
	$this->Cell(10, 8, "PLACA", 0);
	$this->Cell(15, 8, "PUESTOS", 0);
	$this->Cell(30, 8, "LUBRICANTE", 0);
	$this->Cell(10, 8, "CANT.", 0);
	$this->Cell(15, 8, "CAUCHOS", 0);
	$this->Cell(10, 8, "CANT.", 0);
	$this->Cell(15, 8, utf8_decode("BATERÍA"), 0);
	$this->Cell(15, 8, utf8_decode("¿ACTIVO?"), 0);
	$this->Cell(20, 8, utf8_decode("¿APROBADO?"), 0);
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
//$pdf = new PDF('P','mm','A4');
$pdf = new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 7);
//CONSULTA
    $id = base64_decode($_GET['id']);

    $i = 1;

	$system->sql = "SELECT
unidades.ruta,
unidades.cap,
unidades.color,
unidades.ano,
unidades.placa,
unidades.cant_lubri,
unidades.cant_neu,
unidades.acumulador,
unidades.obser,
unidades.activo,
unidades.verf,
CONCAT(users.nombre,' ',users.apellido) as propietario,
lubricantes.lubricante AS tipo_aceite,
cauchos.neumatico,
marcas_vehiculos.marca,
modelos_vehiculos.modelo,
tipo_unidad.tipo_unidad,
users.nacionalidad,
users.cedula
FROM
unidades
INNER JOIN cauchos ON cauchos.id = unidades.num_neu
INNER JOIN lubricantes ON lubricantes.id = unidades.tipo_lub
INNER JOIN marcas_vehiculos ON marcas_vehiculos.id = unidades.marca
INNER JOIN modelos_vehiculos ON modelos_vehiculos.id = unidades.modelo AND modelos_vehiculos.id_marca = unidades.marca
INNER JOIN users ON users.usuario = unidades.cod_afiliado
INNER JOIN tipo_unidad ON tipo_unidad.id = unidades.tipo_unidad
WHERE verf = 0 AND
unidades.cod_linea=".$id;	



    
foreach ($system->sql() as $row) 
    {
    $pdf->Cell(5, 5, $i, 0,0,'C');
	$pdf->Cell(15, 5, $row->nacionalidad.'-'.$row->cedula, 0,0,'L');
	$nombre = utf8_decode($row->propietario);        
	$pdf->Cell(55, 5, $row->propietario, 0,0,'L');
	$pdf->Cell(20, 5, $row->marca, 0,0,'L');
	$pdf->Cell(15, 5, $row->modelo, 0,0,'L');
	$pdf->Cell(8, 5, $row->ano, 0,0,'L');
	$pdf->Cell(15, 5, $row->tipo_unidad, 0,0,'L');
	$pdf->Cell(10, 5, $row->placa, 0,0,'L');
	$pdf->Cell(15, 5, $row->cap, 0,0,'C');
	$pdf->Cell(30, 5, $row->tipo_aceite, 0,0,'L');
	$pdf->Cell(10, 5, $row->cant_lubri, 0,0,'C');
	$pdf->Cell(15, 5, $row->neumatico, 0,0,'C');
	$pdf->Cell(10, 5, $row->cant_neu, 0,0,'C');
    if($row->acumulador){
	$pdf->Cell(15, 5, $row->acumulador." AMP", 0,0,'C');
	}else{
        $pdf->Cell(15, 5, $row->acumulador, 0,0,'C');
        }
        if($row->activo == 1){
        $pdf->Cell(15, 5,"SI", 0,0,'C');
        }else{
        $pdf->Cell(15, 5,"NO", 0,0,'C');
        }
	$pdf->Cell(20, 5, "SI___; NO___", 0,0,'C');
        
    $pdf->Ln(6);
	$i++;
    }

$archivo = "Planilla de Verificación.pdf";

$pdf->Output($archivo, 'I');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planilla de Verificación</title>
</head>

<body>
</body>
</html>