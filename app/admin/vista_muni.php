<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
    
	include_once $_SESSION['base_url'].'partials/header.php';
    
    if(isset($_GET['id']))
	{
		// si existe el where de modificar buscamos el registro
        
        $system->table = "estados";
		$est = $system->find(base64_decode($_GET['id']));
	
	}
    
    $system->table = "users";
    $system->where = "perfil = 4 AND estado = ".$est->id_estado;
	$tlineas = $system->count();
    
    $system->table = "rutas";
    $system->where = "estado = ".$est->id_estado;
	$trutas = $system->count();
    
    $system->table = "unidades";
    $system->where = "activo = 1 AND estado = ".$est->id_estado;
	$tuniact = $system->count();
    
    $system->table = "unidades";
    $system->where = "activo = 0 AND estado = ".$est->id_estado;
	$tunides = $system->count();
    
    $id = base64_decode($_GET['id']);
?>

							<div class="row">
								<div class="col-md-12 col-lg-6 col-xl-6">
									<section class="panel panel-featured-left panel-featured-primary">
										<div class="panel-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-primary">
														<i class="fa fa-taxi"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Total líneas del estado <?= $est->estado ?></h4>
														<div class="info">
															<strong class="amount"><?= $tlineas ?></strong>
														</div>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
								<div class="col-md-12 col-lg-6 col-xl-6">
									<section class="panel panel-featured-left panel-featured-secondary">
										<div class="panel-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-warning">
														<i class="fa fa-road"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Total Rutas del estado <?= $est->estado ?></h4>
														<div class="info">
															<strong class="amount"><?= $trutas ?></strong>
														</div>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
								<div class="col-md-12 col-lg-6 col-xl-6">
									<section class="panel panel-featured-left panel-featured-tertiary">
										<div class="panel-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-tertiary">
														<i class="fa fa-automobile"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Total unidades Operativas</h4>
														<div class="info">
															<strong class="amount"><?= $tuniact ?></strong>
														</div>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
								<div class="col-md-12 col-lg-6 col-xl-6">
									<section class="panel panel-featured-left panel-featured-quartenary">
										<div class="panel-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-danger">
														<i class="fa fa-automobile"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Total unidades inactivas</h4>
														<div class="info">
															<strong class="amount"><?= $tunides ?></strong>
														</div>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
							</div>

     <a href="<?= $_SESSION['base_url1'].'app/admin/index.php' ?>" class="btn btn-block btn-info">Volver al Panel</a><br>
<?    
	$system->sql = "SELECT
(SELECT COUNT(unidades.id) from unidades where activo = 0 AND unidades.estado = users.estado AND unidades.municipio = users.municipio) AS tunides,
(SELECT COUNT(unidades.id) from unidades where activo = 1 AND unidades.estado = users.estado AND unidades.municipio = users.municipio) AS tuniact,
(SELECT COUNT(rutas.id) FROM rutas where rutas.estado = users.estado AND rutas.municipio = users.municipio) AS trutas,
(SELECT COUNT(users.usuario) FROM users where users.estado = municipios.id_estado AND users.perfil = 4 AND users.municipio = municipios.id_municipio) AS tlineas,
municipios.municipio,
municipios.id_municipio,
municipios.id_estado
FROM
users
INNER JOIN municipios ON municipios.id_municipio = users.municipio AND municipios.id_estado = users.estado
WHERE
users.estado =".$id."
GROUP BY
users.municipio";
  
	$title ="Listado Municipal";

                        
	$th = ['Municipio','Total líneas de transporte.','Total Rutas','Total Unidades Activas','Total Unidades Inactivas'];
	$key_body = ['municipio','tlineas','trutas','tuniact','tunides'];
	$data = $system->sql();
	echo make_table_muni($title,$th,$key_body,$data);


	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
?>
