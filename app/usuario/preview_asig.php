<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}

	include_once $_SESSION['base_url'].'partials/header.php';

$asig = base64_decode($_GET['idasig']);

   $system->sql="SELECT
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
CONCAT(estados.estado,'/',municipios.municipio,'/',parroquias.parroquia) as direccion2,
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

$ruta = $_SESSION['base_url']."assets/images/Qr/asignaciones/".$res->id_det; 

?>



					<!-- start: page -->

					<section class="panel">
						<div class="panel-body">
							<div class="invoice">
								<header class="clearfix">
									<div class="row">
										<div class="col-sm-5 mt-md">
											<h2 class="h2 mt-none mb-sm text-dark text-bold">NOTA DE RETIRO</h2>
											<h4 class="h4 m-none text-dark text-bold">#<?= $res->id_det ?></h4>
										</div>
										<div class="col-sm-7 text-right mt-md mb-md">
											<address class="ib mr-xlg text-dark text-left">
												<spam class="text-bold">DIRECCIÓN DE RETIRO</spam>
												<br/>
												<spam class="text-semibold">Almacén: </spam><?= $res->nombre_almacen ?>
												<br/>
												<spam class="text-semibold">Dirección: </spam><?= $res->direccion ?>
												<br/>
												<?= $res->direccion2 ?>
												<br/>
												<spam class="text-semibold">Teléfono: </spam><?= $res->telefono ?>			
											</address>
											<div class="ib">
												<img src="../../assets/images/Qr/asignaciones/<?= $res->id_det ?>.png" height="110px" alt="QR" />
											</div>
										</div>
									</div>
								</header>
								<div class="bill-info">
									<div class="row">
										<div class="col-md-7">
											<div class="bill-to">
												<p class="h5 mb-xs text-dark text-semibold">Beneficiario:</p>
												<address class="text-dark">
													<spam class="text-semibold">Nombre: </spam><?= $res->nombre ?>
													<br/>
													<spam class="text-semibold">Cédula: </spam><?= $res->cedula ?>
													<br/>
													<spam class="text-semibold">Línea de transporte: </spam><?= $res->nombre_linea ?>
												</address>
											</div>
										</div>
										<div class="col-md-5">
											<div class="bill-data text-right text-semibold">
												<p class="mb-none">
													<span class="text-dark">Fecha de Solicitud</span>
													<span class="value"><?= $fech ?></span>
												</p>
												<p class="mb-none">
													<span class="text-dark">Fecha de asignación:</span>
													<span class="value"><?= $fech_apro ?></span>
												</p>
											</div>
										</div>
									</div>
								</div>
							
								<div class="table-responsive">
									<table class="table invoice-items">
										<thead>
											<tr class="h4 text-dark">
												<th id="cell-item"   class="text-semibold">Item</th>
												<th id="cell-desc"   class="text-semibold">Descripción</th>
												<th id="cell-price"  class="text-center text-semibold">Precio U</th>
												<th id="cell-qty"    class="text-center text-semibold">Cant.</th>
												<th id="cell-total"  class="text-center text-semibold">Sub-Total</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td class="text-semibold text-dark"><?= $res->descrubro ?></td>
												<td><?= $res->descripcion ?></td>
												<td class="text-center"><?= number_format($res->precio,2,',','.') ?></td>
												<td class="text-center"><?= $res->cantidad ?></td>
												<td class="text-center"><?= number_format($res->precio * $res->cantidad,2,',','.') ?></td>
											</tr>
										</tbody>
									</table>
								</div>
							
								<div class="invoice-summary">
									<div class="row">
										<div class="col-sm-4 col-sm-offset-8">
											<table class="table h5 text-dark">
												<tbody>
													<tr class="h4">
														<td colspan="2">Total</td>
														<td class="text-left"><?= number_format($res->precio * $res->cantidad,2,',','.') ?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

							<div class="text-right mr-lg">
								<a href="carta_retiro.php?idsol=<?= $_GET['idasig'] ?>" target="_blank" class="btn btn-primary ml-sm"><i class="fa fa-print"></i> Imprimir</a>
							</div>
						</div>
					</section>

					<!-- end: page -->
<?

	include_once $_SESSION['base_url'].'partials/footer.php';
if ($_SESSION['nivel'] > 3){
	include_once $_SESSION['base_url'].'partials/modal_change_password_2.php';
}else{
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
}
?>