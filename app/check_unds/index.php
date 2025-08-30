<?php
	if(!isset($_SESSION)){
		session_start();
	}	
	if(isset($_GET['logout'])){
		$_SESSION = [];
	}
	$_SESSION['base_url'] = $_SERVER['DOCUMENT_ROOT'].'/'.explode('/',$_SERVER['REQUEST_URI'])[1].'/';
	$_SESSION['base_url1'] = 'http://'.$_SERVER['HTTP_HOST'].'/'.explode('/',$_SERVER['REQUEST_URI'])[1].'/';
	$mysqli = new mysqli("localhost", "root", "Ch4N64m4", "regmintra");
	if (mysqli_connect_errno()) {
	    printf("Falló la conexión: %s\n", mysqli_connect_error());
	    exit();
	}else{
		$u = base64_decode(trim($_GET["id"]));
		$consulta = "SELECT
unidades.*,
users.nombre_linea
FROM
users
INNER JOIN unidades ON users.usuario = unidades.cod_afiliado
WHERE
unidades.placa =".$u;
		if ($resultado = $mysqli->query($consulta)) {
			$obj = $resultado->fetch_object();
			$resultado->close();
			$mysqli->close();
		}
	}
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>RegMintra</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="<?= $_SESSION['base_url1']?>/assets/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?= $_SESSION['base_url1']?>/assets/css/font-awesome.css">
    </head>
	<div class="img-responsive"><img src="<?= $_SESSION['base_url1']?>assets/images/banner.png" width="100%"></div>
    <body class="hold-transition">
    	<hr>
    	<div class="container">
    		<div class="panel panel-default" style="box-shadow: 3px 3px 3px rgb(127, 140, 141)">
			  <div class="panel-body">
				<div class=" col-xs-12 col-md-4 text-center">
					<img src="<?= $_SESSION['base_url1'].$obj->qr;?>" class="rounded img-responsive text-center" height="100%" width="100%" alt="John Doe">
					<strong class="text-center"><span class="glyphicon glyphicon-camera text-danger" aria-hidden="true"></span> Foto Personal</strong>
				</div>
				<div class="col-xs-12 col-md-8">
					<div class="page-header">
					  <h1>Datos Personales <small class="text-danger">RegMintra <span class="glyphicon glyphicon-star" style="color: yellow" aria-hidden="true"></span><span class="glyphicon glyphicon-star-empty text-primary" aria-hidden="true"></span><span class="glyphicon glyphicon-star text-danger" aria-hidden="true"></span></small></h1>
					</div>

					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-bed text-danger" aria-hidden="true"></span> linea de Transporte:</strong> <?= $obj->nombre_linea?>.</div>
					<br><br>

					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-modal-window text-danger" aria-hidden="true"></span> Cedula:</strong> <?= $obj->nacionalidad." - ".$obj->cedula ?>.</div>
					<br><br>

					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-user text-danger" aria-hidden="true"></span> Nombre:</strong> <?= $obj->nombre." ".$obj->apellido ?>.</div>
					<br><br>

					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-phone-alt text-danger" aria-hidden="true"></span> Telefono:</strong> <?= $obj->telefono ?>.</div>
					<br><br>

					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-envelope text-danger" aria-hidden="true"></span> Correo:</strong> <?= $obj->email ?>.</div>
					<br><br>

					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-tags text-danger" aria-hidden="true"></span> Gremio de Transportista:</strong> <?= $obj->gremio ?>.</div>
				</div>
			  </div>
			</div>
        </div>
        </div>
        <script src="<?= $_SESSION['base_url1']?>assets/js/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="<?= $_SESSION['base_url1']?>assets/js/bootstrap.min.js"></script>
    </body>
</html>