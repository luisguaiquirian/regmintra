<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
    
	include_once $_SESSION['base_url'].'partials/header.php';

        $system->sql = "SELECT * from municipios WHERE id_estado =".$_SESSION['edo']." AND id_municipio=".$_SESSION['mun'];
		$est = $system->sql();    
 
    
    $system->table = "users";
    $system->where = "perfil = 4 AND estado = ".$_SESSION['edo']." and municipio=".$_SESSION['mun'];
	$tlineas = $system->count();
    
    $system->table = "rutas";
    $system->where = "estado = ".$_SESSION['edo']." and municipio=".$_SESSION['mun'];
	$trutas = $system->count();
    
    $system->table = "unidades";
    $system->where = "estado = ".$_SESSION['edo']." and municipio=".$_SESSION['mun'];
	$tuniact = $system->count();
    
    $system->table = "unidades";
    $system->where = "activo = 0 AND estado = ".$_SESSION['edo']." and municipio=".$_SESSION['mun'];
	$tunides = $system->count(); 

	$system->sql = "SELECT SUM(unidades.cap) as puestos from unidades WHERE estado = ".$_SESSION['edo']." and municipio=".$_SESSION['mun'];
	$tpuestos = $system->sql();
    
	$system->sql = "SELECT SUM(unidades.cap) as puestos from unidades where activo=1 and estado = ".$_SESSION['edo']." and municipio=".$_SESSION['mun'];
	$tpuestos_activos = $system->sql();
    
    if ($tpuestos_activos[0]->puestos > 0){
        
    $tpa = $tpuestos_activos[0]->puestos;   
    
    }
    else
    {
        
         $tpa = 0;       
    }
    
     if ($tpuestos[0]->puestos > 0){

         $tp = $tpuestos[0]->puestos;
         
    }
    else{
        
        $tp = 0;
        
    }
    
    $tunidades = $tuniact + $tunides;

    if ($tpuestos[0]->puestos > 0){  
        
        $porcen = ($tpuestos_activos[0]->puestos * 100) / $tpuestos[0]->puestos;
        
        }
        else
        {
            $porcen = 0;
        }
        
        if ($tuniact > 0){  
        
$porcen2 = ($tunides * 100) / $tuniact;        
            
        }
        else
        {
            $porcen2 = 0;
        }
    

?>

							<div class="row">
								<div class="col-md-4 col-lg-4 col-xl-4 animated bounceInRight slow">
									<section class="panel panel-featured-left panel-featured-primary">
										<div class="panel-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-primary">
														<i class="fa fa-book"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Total líneas del municipio <strong><?= $est[0]->municipio ?></strong></h4>
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
								<div class="col-md-4 col-lg-4 col-xl-4 animated bounceInRight slow">
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
														<h4 class="title">Total unidades municipio <strong><?= $est[0]->municipio ?></strong></h4>
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
								<div class="col-md-4 col-lg-4 col-xl-4 animated bounceInRight slow">
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
														<h4 class="title">Total Capacidad de transporte</h4>
														<div class="info">
															<strong class="amount"><?= $tpuestos[0]->puestos ?></strong>
														</div>
													</div>
													<div class="summary-footer">
                                                        <span class="text-muted text-uppercase">Puestos</span>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
								<div class="col-md-4 col-lg-4 col-xl-4 animated bounceInRight slow">
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
														<h4 class="title">Total Rutas del municipio <strong><?= $est[0]->municipio ?></strong></h4>
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
								<div class="col-md-4 col-lg-4 col-xl-4 animated bounceInRight slow">
									<section class="panel panel-featured-left panel-featured-quartenary">
										<div class="panel-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
															<canvas id="gaugeBasic2" width="90" height="55" data-plugin-options='{ "value": <?= $tunides ?>, "maxValue": <?= $tunidades ?> }'></canvas>
												</div>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Total unidades inactivas</h4>
														<div class="info">
															<strong class="amount" id="gaugeBasicTextfield2"></strong>
														</div>
													</div>
													<div class="summary-footer">
														<a href="#modalUnidadInactiva" data-toggle="modal" class="text-muted text-uppercase">(Ver todas)</a> <?= round($porcen2)."%" ?>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
								<div class="col-md-4 col-lg-4 col-xl-4 animated bounceInRight slow">
									<section class="panel panel-featured-left panel-featured-quartenary">
										<div class="panel-body">
											<div class="widget-summary">
															<canvas id="gaugeBasic" width="90" height="55" data-plugin-options='{ "value": <?= $tpa ?>, "maxValue": <?= $tp ?> }'></canvas>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Capacidad Operativa</h4>
														<div class="info">
															<strong class="amount" id="gaugeBasicTextfield"></strong>
														</div>
													</div>
													<div class="summary-footer">
														<span class="text-muted text-uppercase"><?= round($porcen)."%" ?></span>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
							</div>


<?
include_once $_SESSION['base_url'].'app/municipio/modales/modal_transporte.php';
include_once $_SESSION['base_url'].'app/municipio/modales/modal_rutas.php';
include_once $_SESSION['base_url'].'app/municipio/modales/moda_unidades_activas.php';
include_once $_SESSION['base_url'].'app/municipio/modales/modal_unidades_inactivas.php';
?>
<?    
	$system->sql = "SELECT
users.nombre_linea,
(SELECT COUNT(*) from unidades where activo = 0 AND users.usuario = unidades.cod_linea) AS tunides,
(SELECT COUNT(*) from unidades where activo = 1 AND users.usuario = unidades.cod_linea) AS tuniact
FROM
users
where users.estado = ".$_SESSION['edo']." AND users.municipio =".$_SESSION['mun']." AND perfil = 4"; 
                                                            

    $users = $system->sql();
                                                                
	$title ="Líneas de transporte municipales";
                        
	$th = ['Nombre de  la línea','unidades operativas','unidades inactivas'];
	$key_body = ['nombre_linea','tuniact','tunides'];
	$data = $system->sql();
	echo make_tableuf($title,$th,$key_body,$data);


	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
?>