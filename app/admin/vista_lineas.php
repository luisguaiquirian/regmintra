<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
    
	include_once $_SESSION['base_url'].'partials/header.php';
    
    if(isset($_GET['id']) and $_GET['id_mun'])
	{
		// si existe el where de modificar buscamos el registro
        
        $system->sql = "SELECT * from municipios WHERE id_estado =".base64_decode($_GET['id'])." AND id_municipio =".base64_decode($_GET['id_mun']);
		$est = $system->sql();

	
	}
    
    $system->table = "users";
    $system->where = "perfil = 4 AND municipio = ".$est[0]->id_municipio;
	$tlineas = $system->count();
    
    $system->table = "rutas";
    $system->where = "estado = ".$est[0]->id_estado." AND municipio =".$est[0]->id_municipio;
	$trutas = $system->count();
    
    $system->table = "unidades";
    $system->where = "activo = 1 AND estado = ".$est[0]->id_estado." AND municipio =".$est[0]->id_municipio;
	$tuniact = $system->count();
    
    $system->table = "unidades";
    $system->where = "activo = 0 AND estado = ".$est[0]->id_estado." AND municipio =".$est[0]->id_municipio;
	$tunides = $system->count();
    
    $id = base64_decode($_GET['id']);
    $idmun = base64_decode($_GET['id_mun']);
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
														<h4 class="title">Total líneas del Municipio <?= $est[0]->municipio ?></h4>
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
														<h4 class="title">Total Rutas del Municipio <?= $est[0]->municipio ?></h4>
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
     <a href="<?= $_SESSION['base_url1'].'app/admin/vista_muni.php?id='.base64_encode($id) ?>" class="btn btn-block btn-info">Volver al listado municipal</a><br>
<?    
	$system->sql = "SELECT
                    users.id,
                    users.nombre_linea,
                    CONCAT(users.nombre,' ',users.apellido) AS responsable,
                    users.telefono
                    FROM
                    users
                    WHERE 
                    users.perfil = 4 AND estado=".$id." AND municipio=".$idmun;
  
	$title ="Líneas de transporte del municipio ".$est[0]->municipio;
                        
	$th = ['Nombre de  la línea','responsable','teléfono'];
	$key_body = ['nombre_linea','responsable','telefono'];
	$data = $system->sql();
	echo make_table_lin($title,$th,$key_body,$data);


	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
?>
