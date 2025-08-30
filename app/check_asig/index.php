<?php
/*	if(!isset($_SESSION)){
		session_start();
	}	
	if(isset($_GET['logout'])){
		$_SESSION = [];
	}
*/
	$_SESSION['base_url'] = $_SERVER['DOCUMENT_ROOT'].'/'.explode('/',$_SERVER['REQUEST_URI'])[1].'/';
	$_SESSION['base_url1'] = 'http://'.$_SERVER['HTTP_HOST'].'/'.explode('/',$_SERVER['REQUEST_URI'])[1].'/';

  include_once $_SESSION['base_url'].'/class/system.php';

  $system = new System; 


		$u = base64_decode(trim($_GET['id']));
        $system->sql="SELECT
                    asignaciones.fec_reg,
                    asignaciones.serial,
                    almacenes.codigo,
                    almacenes.nombre,
                    almacenes.direccion,
                    almacenes.telefono,
                    CONCAT(users.nacionalidad,'-',users.cedula) AS ced_vene,
                    CONCAT(users.nombre,' ',users.apellido) AS beneficiario,
                    users.nombre_linea,
                    asignaciones_solicitud.cantidad,
                    unidades.placa,
                    marcas_vehiculos.marca,
                    unidades.color,
                    modelos_vehiculos.modelo,
                    users.nacionalidad,
                    unidades.ano,
                    estados.estado,
                    municipios.municipio,
                    asignaciones.cantidad_solicitud,
                    rubros.descripcion as des_pro,
                    productos.marca as mar_pro,
                    productos.modelo as mod_pro,
                    users.foto,
                    asignaciones.precio,
                    asignaciones.monto_total
                    FROM
                    detalles_solicitudes
                    INNER JOIN users ON users.id = detalles_solicitudes.id_user
                    INNER JOIN asignaciones_solicitud ON asignaciones_solicitud.id_detalle = detalles_solicitudes.id
                    INNER JOIN unidades ON unidades.id = detalles_solicitudes.id_unidad
                    INNER JOIN estatus ON estatus.id = detalles_solicitudes.estatus
                    INNER JOIN asignaciones ON asignaciones_solicitud.id_asignacion = asignaciones.id
                    INNER JOIN almacenes ON asignaciones.almacen_destino = almacenes.id
                    INNER JOIN marcas_vehiculos ON marcas_vehiculos.id = unidades.marca
                    INNER JOIN modelos_vehiculos ON modelos_vehiculos.id_marca = unidades.marca AND modelos_vehiculos.id = unidades.modelo
                    INNER JOIN estados ON estados.id_estado = almacenes.estado
                    INNER JOIN municipios ON municipios.id_estado = almacenes.estado AND municipios.id_municipio = almacenes.municipio
                    INNER JOIN rubros ON rubros.id = detalles_solicitudes.id_rubro
                    INNER JOIN productos ON productos.tipo = rubros.id
                    WHERE
                    detalles_solicitudes.id =".$u;

            $check_asig = $system->sql();


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
				<div class="col-xs-12 col-md-12">
					<div class="page-header">
					  <h1 class="text-center">Datos de la asignación <small class="text-danger">RegMintra <span class="glyphicon glyphicon-star" style="color: yellow" aria-hidden="true"></span><span class="glyphicon glyphicon-star-empty text-primary" aria-hidden="true"></span><span class="glyphicon glyphicon-star text-danger" aria-hidden="true"></span></small></h1>
					</div>
				</div>
				<div class=" col-xs-12 col-md-4 text-center">
					<img src="<?= $_SESSION['base_url1'].'assets/images/fotos/'.$check_asig[0]->foto ?>" class="rounded  text-center" width="70%" alt="Foto"><br><br>
					<strong class="text-center"><span class="glyphicon glyphicon-camera text-danger" aria-hidden="true"></span> Foto Personal</strong>
				    <br><br>		
					<img src="<?= $_SESSION['base_url1'].'assets/images/Qr/asignaciones/'.$u.'.png' ?>" class="text-center" width="70%" alt="Qr"><br>
					<strong class="text-center"><span class="glyphicon glyphicon-qrcode text-danger" aria-hidden="true"></span> Código de la asignación</strong><br><br>
					<strong class="text-center"><span class="glyphicon glyphicon-play text-danger" aria-hidden="true"></span> Serial <?= $check_asig[0]->serial ?></strong><br><br>
					<strong class="text-center"><span class="glyphicon glyphicon-calendar text-danger" aria-hidden="true"></span> Fecha: <?= date("d/m/Y", strtotime($check_asig[0]->fec_reg)); ?></strong><br><br>
				</div>				
				<div class="col-xs-12 col-md-8">
					<div class="page-header">
					  <h1><small class="text-danger"><span class="glyphicon glyphicon-star" style="color: yellow" aria-hidden="true"></span><span class="glyphicon glyphicon-star-empty text-primary" aria-hidden="true"></span><span class="glyphicon glyphicon-star text-danger" aria-hidden="true"></span></small> Datos del Beneficiario</h1>
					</div>
					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-modal-window text-danger" aria-hidden="true"></span> Cédula:</strong> <?= $check_asig[0]->ced_vene ?>.</div>
					<br><br>

					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-user text-danger" aria-hidden="true"></span> Nombre:</strong> <?= $check_asig[0]->beneficiario ?>.</div>
					<br><br>

					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-tags text-danger" aria-hidden="true"></span> Línea de Transporte:</strong> <?= $check_asig[0]->nombre_linea ?>.</div>
					<div class="page-header">
					  <h1><small class="text-danger"><span class="glyphicon glyphicon-star" style="color: yellow" aria-hidden="true"></span><span class="glyphicon glyphicon-star-empty text-primary" aria-hidden="true"></span><span class="glyphicon glyphicon-star text-danger" aria-hidden="true"></span></small> Datos del Vehículo</h1>
					</div>				
					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-modal-window text-danger" aria-hidden="true"></span> Placa:</strong> <?= $check_asig[0]->placa ?>.</div>
					<br><br>
					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-modal-window text-danger" aria-hidden="true"></span> Marca:</strong> <?= $check_asig[0]->marca ?>.</div>
					<br><br>
					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-modal-window text-danger" aria-hidden="true"></span> Modelo:</strong> <?= $check_asig[0]->modelo ?>.</div>
					<br><br>
					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-calendar text-danger" aria-hidden="true"></span> Año:</strong> <?= $check_asig[0]->ano ?>.</div>
					<br><br>
					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-modal-window text-danger" aria-hidden="true"></span> Color:</strong> <?= $check_asig[0]->color ?>.</div>
					<div class="page-header">
					  <h1><small class="text-danger"><span class="glyphicon glyphicon-star" style="color: yellow" aria-hidden="true"></span><span class="glyphicon glyphicon-star-empty text-primary" aria-hidden="true"></span><span class="glyphicon glyphicon-star text-danger" aria-hidden="true"></span></small> Datos del Almacen</h1>
					</div>				
					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-user text-danger" aria-hidden="true"></span> Nombre:</strong> <?= $check_asig[0]->nombre ?>.</div>
					<br><br>
					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-modal-window text-danger" aria-hidden="true"></span> Dirección:</strong> <?= $check_asig[0]->direccion ?>.</div>
					<br><br>
					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-phone-alt text-danger" aria-hidden="true"></span> Teléfono:</strong> <?= $check_asig[0]->telefono ?>.</div>
					<br><br>
					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-modal-window text-danger" aria-hidden="true"></span> Estado:</strong> <?= $check_asig[0]->estado ?>.</div>
					<br><br>
					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-modal-window text-danger" aria-hidden="true"></span> Municipio:</strong> <?= $check_asig[0]->municipio ?>.</div>
					<div class="page-header">
					  <h1><small class="text-danger"><span class="glyphicon glyphicon-star" style="color: yellow" aria-hidden="true"></span><span class="glyphicon glyphicon-star-empty text-primary" aria-hidden="true"></span><span class="glyphicon glyphicon-star text-danger" aria-hidden="true"></span></small> Insumo Asignado</h1>
					</div>	
					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-modal-window text-danger" aria-hidden="true"></span> Descripción:</strong> <?= $check_asig[0]->des_pro ?>.</div>
					<br><br>
					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-modal-window text-danger" aria-hidden="true"></span> Marca:</strong> <?= $check_asig[0]->mar_pro ?>.</div>
					<br><br>
					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-modal-window text-danger" aria-hidden="true"></span> Modelo:</strong> <?= $check_asig[0]->mod_pro ?>.</div>
					<br><br>
					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-modal-window text-danger" aria-hidden="true"></span> Cantidad:</strong> <?= $check_asig[0]->cantidad ?>.</div>
					<br><br>
					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-modal-window text-danger" aria-hidden="true"></span> Precio del Producto:</strong> BS. <?= $check_asig[0]->precio ?>.</div>
					<br><br>
					<div class="col-xs-12"><strong><span class="glyphicon glyphicon-modal-window text-danger" aria-hidden="true"></span> Monto Total:</strong> BS. <?= $check_asig[0]->monto_total ?>.</div>
					<br><br>
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