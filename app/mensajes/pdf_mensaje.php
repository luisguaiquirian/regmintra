<?php

if(!isset($_SESSION)){
   session_start();
}

include_once $_SESSION['base_url'].'/class/system.php';
System::validar_logueo();
$system = new System; 

require('../../lib/fpdf/fpdf.php');
$message = base64_decode($_GET['message']);

$system->sql = "SELECT *, 
									date_format(created_at,'%d-%m-%Y') as fecha,
									IFNULL(
										(SELECT CONCAT(nombre,' ',apellido) from users where id = mensajes.id_usuario_envio),
										(SELECT usuario from users where id = mensajes.id_usuario_envio)
									) as usuario_emisor,
									IFNULL(
										(SELECT CONCAT(nombre,' ',apellido) from users where id = mensajes.id_usuario_receptor),
										(SELECT usuario from users where id = mensajes.id_usuario_receptor)
									) as usuario_receptor,
									case readed 
									WHEN 2 THEN 'No leido'
									ELSE 'leido'
									END as estado_mensaje
									FROM mensajes 
									where id = $message";

$res = $system->sql()[0];



class PDF extends FPDF
{
// Cabecera de página
	function Header(){
	    // Logo
	    $this->Image('../../assets/images/banner-1.png',0,0,220);
	    // Arial bold 15
	    $this->SetFont('Arial','B',15);
	    // Movernos a la derecha
	    // Salto de línea
	    $this->Ln(50);
	}

	// Pie de página
	function Footer(){
	    // Posición: a 1,5 cm del final
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Número de página
	    $this->Cell(0,10,'Paginas '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

	// Creación del objeto de la clase heredada
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Times','',14);
	$pdf->Cell(20,10,'De: '.ucwords(strtolower($res->usuario_emisor)),0);
	$pdf->Cell(120);
	$pdf->Cell(20,10,'Fecha: '.$res->fecha,0);
	$pdf->Ln(17);
	$pdf->Cell(20,0,'Para: '.ucwords(strtolower($res->usuario_receptor)),0);
	$pdf->Ln(20);
	$pdf->SetFont('Times','',20);
	$pdf->Cell(200,0,'Cuerpo del Mensaje',0,0,'C');
	$pdf->Ln(20);
	$pdf->SetFont('Times','',18);
	$pdf->Cell(50,10,$res->mensaje,0);
	    
	$pdf->Output();

?>