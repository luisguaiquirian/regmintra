<?php

if(!isset($_SESSION)){
   session_start();
}

include_once $_SESSION['base_url'].'/class/system.php';

System::validar_logueo();
$system = new System; 
$asig = base64_decode($_GET['idsol']);

require('../../lib/fpdf/fpdf.php');




$system->sql = "SELECT
detalles_solicitudes.id_solicitud AS idsol,
detalles_solicitudes.id as id_det,
asignaciones_solicitud.cantidad,
unidades.placa,
rubros.descripcion AS descrubro,
productos.descripcion,
asignaciones.fec_aprobado,
CONCAT(users.nombre,' ',users.apellido) AS nombre,
users.cedula,
users.nombre_linea,
users.qr,
asignaciones.id AS id_asig,
almacenes.nombre AS nombre_almacen,
almacenes.direccion,
CONCAT('ESTADO: ',estados.estado,' /MUNICIPIO: ',municipios.municipio,' /PAROQUIA: ',parroquias.parroquia) as direccion2,
almacenes.telefono,
solicitudes.fec_solicitud as fech,
productos.precio
FROM
detalles_solicitudes
INNER JOIN asignaciones_solicitud ON asignaciones_solicitud.id_detalle = detalles_solicitudes.id
INNER JOIN unidades ON unidades.id = detalles_solicitudes.id_unidad
INNER JOIN rubros ON rubros.id = detalles_solicitudes.id_rubro
INNER JOIN asignaciones ON asignaciones_solicitud.id_asignacion = asignaciones.id
INNER JOIN productos ON asignaciones.id_producto = productos.id
INNER JOIN users ON detalles_solicitudes.id_user = users.id
INNER JOIN mov_items ON asignaciones.id_mov = mov_items.id
INNER JOIN almacenes ON almacenes.id = mov_items.destino
INNER JOIN estados ON estados.id_estado = almacenes.estado
INNER JOIN municipios ON municipios.id_estado = estados.id_estado
INNER JOIN parroquias ON parroquias.id_estado = municipios.id_estado AND parroquias.id_municipio = municipios.id_municipio
INNER JOIN solicitudes ON solicitudes.id_user = users.id
WHERE
detalles_solicitudes.estatus = 9 AND
detalles_solicitudes.id =".$asig."
GROUP BY
detalles_solicitudes.id_rubro";

$res1 = $system->sql();
$res = $res1[0];

    $fech = date("d/m/Y",strtotime($res->fech));
    $fech_apro = date("d/m/Y",strtotime($res->fec_aprobado));

$ruta = $_SESSION['base_url']."assets/images/Qr/asignaciones/".$res->id_det.'.png'; 


$header = ['Item','Descripcion','Precio U','Cantidad','Sub-total'];

class PDF extends FPDF
{
	public $image_trans = "../../assets/images/logo.png";
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
    $w = array(38, 38, 38,38,38);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration

    // Data
    $fill = false;
    foreach($data as $row){
        $this->Cell($w[0],6,utf8_decode($row->descrubro),0,0,'C',$fill);
        $this->Cell($w[1],6,utf8_decode($row->descripcion),0,0,'C',$fill);
        $this->Cell($w[2],6,number_format($row->precio,2,',','.'),0,0,'C',$fill);
        $this->Cell($w[2],6,$row->cantidad,0,0,'C',$fill);
        $this->Cell($w[2],6,number_format($row->precio * $row->cantidad,2,',','.'),0,0,'C',$fill);
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
	$pdf->SetFont('Times','',25);
	$pdf->Cell(20,10,'Nota de Retiro',0);
	$pdf->Cell(120);
	$pdf->Cell( 40, 40, $pdf->Image($pdf->image_trans, $pdf->GetX(), $pdf->GetY(), 50.78), 0, 0, 'L', false );
	$pdf->Ln(10);
	$pdf->SetFont('Times','',20);
	$pdf->Cell(20,10,'# '.$res->id_det,0);
	$pdf->Ln(20);
	$pdf->SetFont('Times','',14);
	$pdf->Cell(0,0,'Fecha de solicitud: '.$fech,0);
	$pdf->Cell(0,0,'Fecha de asignacion: '.$fech_apro,0,0,'R');
	$pdf->Ln(10);
//	$pdf->Cell(0,0,'',1,0);
	$pdf->Ln(10);
	$pdf->SetFont('Times','',20);
	$pdf->Cell(0,0,'DIRECCION DE RETIRO',0,0,'C');
	$pdf->Ln(5);	
	$pdf->Cell(0,0,'',1,0);
	$pdf->Ln(10);	
	$pdf->SetFont('Times','',12);
	$pdf->Cell(20,0,'NOMBRE ALMACEN: '.$res->nombre_almacen,0);
	$pdf->Cell(10);
	$pdf->Cell(20,0,'',0);
	$pdf->Ln(7);	
	$pdf->Cell(20,0,'DIRECCION: '.$res->direccion,0);
	$pdf->Ln(7);		
    $pdf->Cell(20,0,$res->direccion2,0);
	$pdf->Ln(7);	
	$pdf->Cell(20,0,'TELEFONO: '.$res->telefono,0);
	$pdf->Ln(7);	
    $pdf->Cell(0,0,'',1,0);
	$pdf->Ln(7);	
	$pdf->SetFont('Times','',20);
	$pdf->Cell(0,0,'DATOS DEL BENEFICIARIO',0,0,'C');
	$pdf->Ln(5);
    $pdf->Cell(0,0,'',1,0);
	$pdf->Ln(7);
	$pdf->SetFont('Times','',12);
	$pdf->Cell(20,0,'NOMBRE: '.$res->nombre,0);
	$pdf->Cell(10);
	$pdf->Cell(20,0,'',0);
	$pdf->Ln(7);	
	$pdf->Cell(20,0,'CEDULA: '.$res->cedula,0);
	$pdf->Ln(7);		
	$pdf->Cell(20,0,'LINEA DE TRANSPORTE: '.$res->nombre_linea,0);
	$pdf->Ln(5);	
	$pdf->Cell(0,0,'',1,0);
	$pdf->Ln(5);	
	$pdf->SetFont('Times','',20);
	$pdf->Cell(0,0,'DATOS DEL PRODUCTO',0,0,'C');
	$pdf->Ln(5);
    $pdf->Cell(0,0,'',1,0);
	$pdf->Ln(7);
	$pdf->SetFont('Times','',14);
	$pdf->FancyTable($header,$res1);	
	$pdf->Ln(40);
	$pdf->Cell(65);
	$pdf->Cell( 40, 20, $pdf->Image($ruta, $pdf->GetX(), $pdf->GetY(), 50.78), 0, 0, 'C', false);	
	    
	$pdf->Output();

?>