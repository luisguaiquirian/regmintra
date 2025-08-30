<?php
	if(!isset($_SESSION)){
		session_start();
	}	
	if(isset($_GET['logout'])){
		$_SESSION = [];
	}
	$_SESSION['base_url'] = $_SERVER['DOCUMENT_ROOT'].'/'.explode('/',$_SERVER['REQUEST_URI'])[1].'/';
	$_SESSION['base_url1'] = 'http://'.$_SERVER['HTTP_HOST'].'/'.explode('/',$_SERVER['REQUEST_URI'])[1].'/';
	$mysqli = new mysqli("localhost", "root", "mtnl", "regmintra");
        mysqli_set_charset($mysqli,"utf8");
	if (mysqli_connect_errno()) {
	    printf("Falló la conexión: %s\n", mysqli_connect_error());
	    exit();
	}else{
		$user = base64_decode(trim($_GET['u']));
		$consulta = "SELECT * FROM users where id = $user and perfil = 5";
		if ($resultado = $mysqli->query($consulta)) {
			$obj = $resultado->fetch_object();
			$resultado->close();
			unset($consulta);
			$consulta="SELECT count(id) as sum FROM unidades where cod_afiliado = $obj->usuario";
			if ($resultado = $mysqli->query($consulta)) {
				$obj2 = $resultado->fetch_object();
				$resultado->close();
				unset($consulta);
			}
			$mysqli->close();
		}
	}
	require($_SESSION['base_url'].'lib/fpdf/fpdf.php');

	$valor['nombres']=$obj->nombre.' '.$obj->apellido;
	$valor['linea']=$obj->nombre_linea;
	$valor['direccion']=$obj->direccion;
	$valor['cant_veh']=$obj2->sum;
	$valor['cod_carnet_patria']=$obj->cod_cp;
	$valor['foto']=$obj->foto;
	$valor['qr']=$obj->qr;


$pdf = new FPDF();
$pdf->AddPage();
/* seleccionamos el tipo, estilo y tamaño de la letra a utilizar */
$pdf->SetFont('courier','B', 5);

$pdf->Image($_SESSION['base_url1'].'assets/images/carnet.jpeg',60,50,86,110,'jpeg');
$pdf->SetXY(72,76);

$pdf->Cell(10,13,utf8_decode($valor['nombres']),'C');
$pdf->SetXY(72,80);
$pdf->Cell(10,13,utf8_decode($valor['linea']),'C');
$pdf->SetXY(72,85);
$pdf->Cell(10,13,utf8_decode($valor['direccion']),'C');
$pdf->SetXY(72,89);
$pdf->Cell(10,13,utf8_decode($valor['cant_veh']),'C');
$pdf->SetXY(98,89);
$pdf->Cell(10,13,utf8_decode($valor['cod_carnet_patria']),'C');

$pdf->Image($_SESSION['base_url1'].'assets/images/fotos/'.$valor['foto'],113,66,19,19);

$pdf->Image($_SESSION['base_url1'].$valor['qr'],117,121,19,19);

$pdf->Output('CarnetTransportista-'.$valor['cod_carnet_patria'],'I');
?>
