<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}

	include_once $_SESSION['base_url'].'partials/header.php';
    

   
    
    $system->table = "users";
    $system->where = "perfil = 5 AND cod_linea = ".$_SESSION['user'];
	$tlineas = $system->count();
    
    $system->sql = "SELECT
Count(unidades.ruta) AS trutas,
rutas.ruta
FROM
unidades
INNER JOIN rutas ON unidades.ruta = rutas.id WHERE
unidades.cod_linea =".$_SESSION['user'];
    
    $trutas = $system->sql();
    
    $system->table = "unidades";
    $system->where = "activo = 1 AND cod_linea = ".$_SESSION['user'];
	$tuniact = $system->count();
    
    $system->table = "unidades";
    $system->where = "activo = 0 AND cod_linea = ".$_SESSION['user'];
	$tunides = $system->count();    
   

?>

                							<div class="row">
								<div class="col-md-12 col-lg-6 col-xl-6 animated bounceInRight slow">
									<section class="panel panel-featured-left panel-featured-primary">
										<div class="panel-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-primary">
														<i class="fa fa-users"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Total Afiliados</h4>
														<div class="info">
															<strong class="amount"><?= $tlineas ?></strong>
														</div>
													</div>
													<div class="summary-footer">
														<a href="./vista_lineas_nac.php" class="text-muted text-uppercase">(Ver todas)</a>
													</div>
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
														<h4 class="title">Total Rutas</h4>
														<div class="info">
															<strong class="amount"><?= $trutas[0]->trutas ?></strong>
														</div>
													</div>
													<div class="summary-footer">
														<a href="./vista_lineas_nac.php" class="text-muted text-uppercase">(Ver todas)</a>
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
														<a href="./vista_lineas_nac.php" class="text-muted text-uppercase">(Ver todas)</a>
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
														<a href="./vista_lineas_nac.php" class="text-muted text-uppercase">(Ver todas)</a>
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
