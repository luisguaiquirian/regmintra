<?php

if(!isset($_SESSION)){
   session_start();
}

include_once $_SESSION['base_url'].'/class/system.php';

System::validar_logueo();
$system = new System; 
$asig = base64_decode($_GET['asig']);

require('../../../lib/fpdf/fpdf.php');
require('../../../lib/phpqrcode/phpqrcode.php');
$name = time().'.png';
$ruta = $_SESSION['base_url']."assets/images/Qr/notaDespacho/".$name; 
QRcode::png($asig,$ruta,QR_ECLEVEL_L,10,2);


$system->sql = "
SELECT
t1.id,
t1.cantidad_asig,
u2.serial,
u4.tel_contac,
u4.telefono,
u4.nombre as almacen_destino,
u4.direccion as direccion_destino,
u6.descripcion,
u6.marca,
u6.codigo,
u6.modelo,
u6.precio,
(SELECT nombre from almacenes where id = u5.almacen) as almacen_origen,
(SELECT direccion from almacenes where id = u5.almacen) as direccion_origen,
(SELECT tel_contac from almacenes where id = u5.almacen) as telefono_origen,
u2.cantidad_asignada
FROM
mov_items as t1
INNER JOIN mov_items_almacenes u1 ON t1.id = u1.id_mov
INNER JOIN asignaciones u2 ON u1.id_asignacion = u2.id
INNER JOIN asignaciones_solicitud u3 ON u3.id_asignacion = u2.id
INNER JOIN almacenes u4 ON u4.id = t1.destino
INNER JOIN inventario u5 ON u5.id = t1.inventario_salida
INNER JOIN productos u6 ON u2.id_producto = u6.id
WHERE u1.estatus = 5 and t1.id = $asig
GROUP BY
u1.id_asignacion";

$res1 = $system->sql();
$res = $res1[0];

$header = ['Codigo','Descripcion','Modelo','Cantidad','Precio','Subtotal'];

class PDF extends FPDF
{
	public $image_trans = "../../../assets/images/logo.png";
	// Pie de página
	function Footer(){
	    // Posición: a 1,5 cm del final
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Número de página
	    $this->Cell(0,10,'Paginas '.$this->PageNo().'/{nb}',0,0,'C');
	}

	function FancyTable($header,$data){
    // Colors, line width and bold font
    $this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
    // Header
    $w = array(32, 32, 32,32,32,32);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    
    // Data
    $fill = false;
    foreach($data as $row){
        $this->Cell($w[0],6,$row->codigo,0,0,'C',$fill);
        $this->Cell($w[1],6,$row->descripcion,0,0,'C',$fill);
        $this->Cell($w[2],6,$row->modelo,0,0,'C',$fill);
        $this->Cell($w[2],6,$row->cantidad_asig,0,0,'C',$fill);
        $this->Cell($w[2],6,number_format($row->precio,2,',','.'),0,0,'C',$fill);
        $this->Cell($w[2],6,number_format($row->precio * $row->cantidad_asig,2,',','.'),0,0,'C',$fill);
        $this->Ln();
        $fill = !$fill;
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
	}

}


	// Creación del objeto de la clase heredada
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Times','',35);
	$pdf->Cell(20,10,'Serial',0);
	$pdf->Cell(120);
	$pdf->Cell( 40, 40, $pdf->Image($pdf->image_trans, $pdf->GetX(), $pdf->GetY(), 50.78), 0, 0, 'L', false );
	$pdf->Ln(20);
	$pdf->SetFont('Times','',20);
	$pdf->Cell(20,10,$res->serial,0);
	$pdf->Ln(20);
	$pdf->Cell(20,10,'Fecha emision: '.date('d-m-Y'),0);
	$pdf->Ln(20);
	$pdf->Cell(0,0,'',1,0);
	$pdf->Ln(20);
	$pdf->SetFont('Times','',14);
	$pdf->Cell(20,0,'Almacen Destino: ',0,0);
	$pdf->Cell(110);
	$pdf->Cell(0,0,'Almacen Origen:',0);
	$pdf->Ln(10);	
	$pdf->SetFont('Times','',12);
	$pdf->Cell(20,0,'Nombre: '.$res->almacen_destino,0);
	$pdf->Cell(110);
	$pdf->Cell(20,0,'Nombre: '.$res->almacen_origen,0);
	$pdf->Ln(7);	
	$pdf->Cell(20,0,'Celular: '.$res->tel_contac,0);
	$pdf->Cell(110);
	$pdf->Cell(20,0,'Celular: '.$res->telefono_origen,0);
	$pdf->Ln(20);	
	$pdf->Cell(20,0,'Almac.Direccion Destino: '.$res->direccion_destino,0);
	$pdf->Ln(7);	
	$pdf->Cell(20,0,'Almac.Direccion Origen: '.$res->direccion_origen,0);
	$pdf->Ln(20);	
	$pdf->FancyTable($header,$res1);	
	$pdf->Ln(40);
	$pdf->Cell(65);
	$pdf->Cell( 40, 20, $pdf->Image($ruta, $pdf->GetX(), $pdf->GetY(), 50.78), 0, 0, 'C', false);	
	    
	$pdf->Output();

?>