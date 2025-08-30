<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}

	include_once $_SESSION['base_url'].'partials/header.php';

    $system->table = "users";
    $system->where = "perfil = 4";
	$tlineas = $system->count();
    
    $system->table = "rutas";
	$trutas = $system->count();
    
    $system->table = "unidades";
    $system->where = "activo = 1";
	$tuniact = $system->count();
    
    $system->table = "unidades";
    $system->where = "activo = 0";
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
														<h4 class="title">Total líneas de transporte a Nivel Nacional</h4>
														<div class="info">
															<strong class="amount"><?= $tlineas ?></strong>
														</div>
													</div>
													<div class="summary-footer">
														<a href="#modalTransporte" data-toggle="modal" class="text-muted text-uppercase">(Ver todas)</a>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
								<div class="col-md-12 col-lg-6 col-xl-6 animated bounceInRight slow">
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
														<h4 class="title">Total Rutas a Nivel Nacional</h4>
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
								<div class="col-md-12 col-lg-6 col-xl-6 animated bounceInRight slow">
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
								<div class="col-md-12 col-lg-6 col-xl-6 animated bounceInRight slow">
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

	include_once $_SESSION['base_url'].'partials/footer.php';
if ($_SESSION['nivel'] > 3){
	include_once $_SESSION['base_url'].'partials/modal_change_password_2.php';
}else{
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
}
?>