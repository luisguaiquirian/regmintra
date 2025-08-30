<?
  include_once $_SESSION['base_url'].'/class/system.php';
  include_once $_SESSION['base_url'].'/helpers/view_functions.php';
    $_SESSION['base_perfil'] = $_SESSION['base_url1'].explode('/',$_SERVER['REQUEST_URI'])[2].'/'.explode('/',$_SERVER['REQUEST_URI'])[3].'/';

  System::validar_logueo();
  
  $system = new System; 

  $a = isset($_GET["a"]) ?  base64_decode($_GET["a"]) : null;    
  $b = isset($_GET["b"]) ?  base64_decode($_GET["b"]) : null;

  $system->table = "detalles_solicitudes";
  $system->where = "estatus = 5 AND id_user =".$_SESSION['user_id'];
	$tasig = $system->count();

  $system->table = "mensajes";
  $system->where = "readed = 2 AND id_usuario_receptor =".$_SESSION['user_id'];
  $count_messages = $system->count();
    
    
?>
<!doctype html>
<html class="fixed">
  <head>
    <!-- Basic -->
    <meta charset="UTF-8">

    <title>RegMintra</title>
    <meta name="keywords" content="HTML5 Admin Template" />
    <meta name="description" content="Porto Admin - Responsive HTML5 Template">
    <meta name="author" content="okler.net">
    
    <link rel="shortcut icon" href="<?= $_SESSION['base_url1'].'favicon.ico' ?>">
    <link rel="icon" href="<?= $_SESSION['base_url1'].'favicon.ico' ?>">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- Web Fonts  -->
    <!--<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">-->

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="<?= $_SESSION['base_url1'].'assets/vendor/bootstrap/css/bootstrap.css' ?>" />
    <link rel="stylesheet" href="<?= $_SESSION['base_url1'].'assets/vendor/font-awesome/css/font-awesome.css' ?>" />
    <link rel="stylesheet" href="<?= $_SESSION['base_url1'].'assets/vendor/magnific-popup/magnific-popup.css' ?>" />
    <link rel="stylesheet" href="<?= $_SESSION['base_url1'].'assets/vendor/bootstrap-datepicker/css/datepicker3.css' ?>" />
    <link rel="stylesheet" href="<?= $_SESSION['base_url1'].'assets/css/animate.css' ?>" />

    <!-- Specific Page Vendor CSS -->
    <link rel="stylesheet" href="<?= $_SESSION['base_url1'].'assets/vendor/select2/select2.css' ?>" />
    <link rel="stylesheet" href="<?= $_SESSION['base_url1'].'assets/vendor/jquery-datatables-bs3/assets/css/datatables.css' ?>" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="<?= $_SESSION['base_url1'].'assets/stylesheets/theme.css' ?>" />

    <!-- Skin CSS -->
    <link rel="stylesheet" href="<?= $_SESSION['base_url1'].'assets/stylesheets/skins/default.css' ?>" />

    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="<?= $_SESSION['base_url1'].'assets/stylesheets/theme-custom.css' ?>">

    <!-- Head Libs -->
    <script src="<?= $_SESSION['base_url1'].'assets/vendor/modernizr/modernizr.js' ?>"></script>

    <link rel="stylesheet" href="<?= $_SESSION['base_url1'].'assets/css/toastr.min.css' ?>">
    <link rel="stylesheet" href="<?= $_SESSION['base_url1'].'assets/css/style.css' ?>">
    <link rel="stylesheet" href="<?= $_SESSION['base_url1'].'assets/css/alertify.min.css' ?>">
    <link rel="stylesheet" href="<?= $_SESSION['base_url1'].'assets/css/themes/semantic.min.css' ?>">
    <link rel="stylesheet" href="<?= $_SESSION['base_url1'].'assets/js/multiselect/css/multi-select.css' ?>">

    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
  </head>
   
  <body>
    <section class="body">
     <!-- start: header -->
      <!-- start: header -->
      <header class="header">
        <div class="logo-container">
          <a href="../" class="logo">
            <img src="<?= $_SESSION['base_url1'].'assets/images/logo.png' ?>" height="45" alt="Logo" /> 
          </a>
          <div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
          </div>
        </div>
      
        <!-- start: search & user box -->
        <div class="header-right">
      
					<span class="separator"></span>
			
					<ul class="notifications">
						<li>
							<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
								<i class="fa fa-envelope"></i>
								<span class="badge"><?= $count_messages ?></span>
							</a>
			
							<div class="dropdown-menu notification-menu">
								<div class="notification-title">
									<span class="pull-right label label-default"><?= $count_messages ?></span>
									Mensajes
								</div>
			
								<div class="content">
									<ul>
                    <?php
                      $system->sql = "SELECT 
                      (SELECT 
                        IFNULL(
                          (SELECT CONCAT(nombre,' ',apellido) from users where id = mensajes.id_usuario_envio),
                          (SELECT usuario from users where id = mensajes.id_usuario_envio)
                        ) 
                      ) as nombre_sender,
                      date_format(created_at, '%d-%m-%Y') as fecha
                      from mensajes where id_usuario_receptor = $_SESSION[user_id] and readed = 2
                      ORDER BY created_at desc
                      LIMIT 5";
                      foreach ($system->sql() as $row){
                        echo '
      										<li>
      											<a href="#" class="clearfix">
      												<figure class="image">
      													<img src="'.$_SESSION['base_url1'].'assets/images/fotos/E_17_M_LIBERTADOR.png'.'" alt="Joseph Doe Junior" class="img-circle" width="22px"/>
      												</figure>
      												<span class="title">Emisor: '.$row->nombre_sender.'</span>
      												<span class="message">Fecha: '.$row->fecha.'.</span>
      											</a>
      										</li>
                        ';
                      }
                      ?>
									</ul>
									<hr />
									<div class="text-right">
										<a href="<?= $_SESSION['base_url1'].'app/mensajes/mensajes_recibidos.php' ?>" class="view-more">Ir a los Mensajes</a>
									</div>
								</div>
							</div>
						</li>
						<li>
							<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
								<i class="fa fa-bell"></i>
								<span class="badge"><?= $tasig ?></span>
							</a>
			
							<div class="dropdown-menu notification-menu">
								<div class="notification-title">
									<span class="pull-right label label-default"><?= $tasig ?></span>
									Asignaciones
								</div>
			
								<div class="content">
								<ul>
						<?
                            $system->sql = "SELECT
detalles_solicitudes.id_solicitud as idsol,
asignaciones_solicitud.cantidad,
unidades.placa,
rubros.descripcion as descrubro,
productos.descripcion
FROM
detalles_solicitudes
INNER JOIN asignaciones_solicitud ON asignaciones_solicitud.id_detalle = detalles_solicitudes.id
INNER JOIN unidades ON unidades.id = detalles_solicitudes.id_unidad
INNER JOIN rubros ON rubros.id = detalles_solicitudes.id_rubro
INNER JOIN asignaciones ON asignaciones_solicitud.id_asignacion = asignaciones.id
INNER JOIN productos ON asignaciones.id_producto = productos.id
WHERE
detalles_solicitudes.estatus = 5 AND
detalles_solicitudes.id_user ='".$_SESSION['user_id']."' 
GROUP BY
detalles_solicitudes.id_rubro";
                          foreach ($system->sql() as $rs) 
							{
									echo '<a href="'.$_SESSION['base_url1'].'app/usuario/aceptar_insumo.php?ids='.base64_encode($rs->idsol).'" class="clearfix"><div class="image">
				                    <i class="fa fa-thumbs-up bg-warning"></i>
								    </div>
                                    <li class="title">'.$rs->descrubro.': '.$rs->descripcion.'</li>
                                    <span class="message">Cantidad: '.$rs->cantidad.'</span></a><hr />';	
							}
						?>
                                    </ul>
<!--									<ul>

										<li>
											<a href="#" class="clearfix">
												<div class="image">
													<i class="fa fa-thumbs-down bg-danger"></i>
												</div>
												<span class="title">Server is Down!</span>
												<span class="message">Just now</span>
											</a>
										</li>

									</ul>
-->									
<!--			
									<div class="text-right">
										<a href="#" class="view-more">Ver Todas</a>
									</div>-->
								</div>
							</div>
						</li>
					</ul>
			
					<span class="separator"></span>      
          <div id="userbox" class="userbox">
            <a href="#" data-toggle="dropdown">
              <figure class="profile-picture">
                <img src="<?= $_SESSION['base_url1'].'assets/images/fotos/'.$_SESSION['foto'].'" alt="Foto de perfil' ?>" class="img-circle" data-lock-picture="assets/images/!logged-user.jpg" />
              </figure>
              <div class="profile-info" data-lock-name="<?= $_SESSION['nom']." ".$_SESSION['ape']; ?>" data-lock-email="<?= $_SESSION['email']; ?>">
                <span class="name">Usuario: <?= $_SESSION['user'] ?></span>
                <span class="role">Nombre: <?= $_SESSION['nom']." ".$_SESSION['ape']; ?></span>
              </div>
      
              <i class="fa custom-caret"></i>
            </a>
      
            <div class="dropdown-menu">
              <ul class="list-unstyled">
                <li class="divider"></li>
                <li>
                  <a role="menuitem" tabindex="-1" href="<?= $_SESSION['base_perfil'].'perfil.php' ?>"><i class="fa fa-user"></i> Mi Perfil</a>
                </li>
                <li>
                  <?
                    $ruta_logout = substr($_SESSION['base_url1'], 0, strlen($_SESSION['base_url1']) -1);
                  ?>

                  <a href="<?= $ruta_logout.'?logout=1' ?>" role="menuitem" tabindex="-1" href="pages-signin.html">
                    <i class="fa fa-power-off"></i> Salir</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <!-- end: search & user box -->
      </header>
      <!-- end: header -->
      <?
        include_once 'menu_lateral.php';
      ?>
      <section role="main" class="content-body">
        <header class="page-header">
          <h2>ESCRITORIO
            <? 
              if ($_SESSION['nivel'] == 5){ 
                  echo $_SESSION['nom'].' '.$_SESSION['ape']; 
              }elseif ($_SESSION['nivel'] == 4){
                  echo $_SESSION['nom_linea'];
              }elseif ($_SESSION['nivel'] == 3){
                  echo "MUNICIPIO ".$_SESSION['municipio'];
              }elseif ($_SESSION['nivel'] == 2){
                  echo "ESTADO ".$_SESSION['estado'];
              }
            ?></h2>
        
          <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
              <li>
                <a href="../">
                  <i class="fa fa-home"></i>
                </a>
              </li>
              <? if (!empty($a)) { ?>
              <li><span class="text-info"><?= $a ?></span></li>
              <li><span class="text-success"><?= $b ?></span></li>
              <? } ?>              
              <li></li>
            </ol>
        
<!--            <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
          </div>
        </header>

        <!-- start: page -->
        <div class="row">
          <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12">
