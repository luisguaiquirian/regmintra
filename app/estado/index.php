<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
    
	include_once $_SESSION['base_url'].'partials/header.php';

        $system->sql = "SELECT * from estados WHERE id_estado =".$_SESSION['edo'];
		$est = $system->sql();    
          foreach ($system->sql() as $row) 
					{	
            $edo = $row->estado;
            //$mun = $row->municipio;
          }    
    
    $system->table = "users";
    $system->where = "perfil = 4 AND estado = ".$est[0]->id_estado;
	$tlineas = $system->count();
    
    $system->table = "rutas";
    $system->where = "estado = ".$est[0]->id_estado;
	$trutas = $system->count();
    
    $system->table = "unidades";
    $system->where = "activo = 1 AND estado = ".$est[0]->id_estado;
	$tuniact = $system->count();
    
    $system->table = "unidades";
    $system->where = "activo = 0 AND estado = ".$est[0]->id_estado;
	$tunides = $system->count();

?>

							<div class="row">
								<div class="col-md-12 col-lg-6 col-xl-6 animated bounceInRight slow">
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
														<h4 class="title">Total líneas del estado <?= $est[0]->estado ?></h4>
														<div class="info">
															<strong class="amount"><?= $tlineas ?></strong>
														</div>
													</div>
													<div class="summary-footer">
														<a href="#modalTransporte" data-toggle="modal" class="text-muted text-uppercase">(Ver todas)</a>													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
								<div class="col-md-12 col-lg-6 col-xl-6 animated bounceInRight delay-1s slow">
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
														<h4 class="title">Total Rutas del estado <?= $est[0]->estado ?></h4>
														<div class="info">
															<strong class="amount"><?= $trutas ?></strong>
														</div>
													</div>
													<div class="summary-footer">
														<a href="#modalRutas" data-toggle="modal" class="text-muted text-uppercase">(Ver todas)</a>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
								<div class="col-md-12 col-lg-6 col-xl-6 animated bounceInRight delay-2s slow">
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
													<div class="summary-footer">
														<a href="#modalUnidadActiva" data-toggle="modal" class="text-muted text-uppercase">(Ver todas)</a>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
								<div class="col-md-12 col-lg-6 col-xl-6 animated bounceInRight delay-3s slow">
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
													<div class="summary-footer">
														<a href="#modalUnidadInactiva" data-toggle="modal" class="text-muted text-uppercase">(Ver todas)</a>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
							</div>
<?
include_once $_SESSION['base_url'].'app/estado/modales/modal_transporte.php';
include_once $_SESSION['base_url'].'app/estado/modales/modal_rutas.php';
include_once $_SESSION['base_url'].'app/estado/modales/moda_unidades_activas.php';
include_once $_SESSION['base_url'].'app/estado/modales/modal_unidades_inactivas.php';
?>


<?    
	$system->sql = "SELECT
municipios.municipio,
(SELECT COUNT(*) from unidades where activo = 0 AND unidades.estado = users.estado AND unidades.municipio = users.municipio) AS tunides,
(SELECT COUNT(*) from unidades where activo = 1 AND unidades.estado = users.estado AND unidades.municipio = users.municipio) AS tuniact,
(SELECT COUNT(*) FROM users where users.estado = municipios.id_estado AND users.perfil = 4 AND users.municipio = municipios.id_municipio) AS tlineas,
(SELECT COUNT(*) FROM rutas where rutas.estado = users.estado AND rutas.municipio = users.municipio) AS trutas,
municipios.id_municipio,
municipios.id_estado
FROM
users
INNER JOIN municipios ON municipios.id_municipio = users.municipio AND municipios.id_estado = users.estado
WHERE
users.estado = ".$_SESSION['edo']."
GROUP BY
users.municipio";

    $users = $system->sql();
                                                                
	$title ="Lista Estadal";
                        
	$th = ['municipio','Total líneas de transporte.','Total Rutas','Total Unidades Activas','Total Unidades Inactivas'];
	$key_body = ['municipio','tlineas','trutas','tuniact','tunides'];
	$data = $system->sql();
	echo make_table_esta_1($title,$th,$key_body,$data);


	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
?>
