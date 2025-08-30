<?
	if(!isset($_SESSION)){session_start();}
	include_once $_SESSION['base_url'].'partials/header.php';
	$system->sql = "SELECT
Count(detalles_solicitudes.id) AS solicitudes,
detalles_solicitudes.id_rubro,
Sum(detalles_solicitudes.cantidad) AS cantidad_total,
rubros.descripcion
FROM
solicitudes
INNER JOIN detalles_solicitudes ON solicitudes.id = detalles_solicitudes.id_solicitud
INNER JOIN rubros ON detalles_solicitudes.id_rubro = rubros.id
WHERE
solicitudes.estado = ".$_SESSION['edo']." and
detalles_solicitudes.estatus = 1
GROUP BY
detalles_solicitudes.id_rubro";


?>
<section class="panel">
	<header class="panel-heading">
		<div class="panel-actions">
			<a href="#" class="fa fa-caret-down"></a>
			<a href="#" class="fa fa-times"></a>
		</div>
        <h4 class="panel-title text-center">Solicitudes del Estado <span class="text-danger"><?= $_SESSION['estado']?></span></h4>
	</header>
		<div class="panel-body">
			<!--Listados de las categorias-->
			<div class="list-group">
			  <?php foreach($system->sql() as $r): ?>
			  	<a href="javascript:void(0)" class="list-group-item" onclick="show_detalles_sol(<?= $r->id_rubro?>,<?= $_SESSION['edo']?>);">
			  		<span class="badge"><?= $r->cantidad_total?> Items</span>
			  		<span class="text-primary"><?= $r->descripcion;?></span> &nbsp:&nbsp<span class="text-danger"><?= $r->solicitudes;?>&nbspSolicitudes</span>
			  	</a>
			  <?php endforeach?>
			</div>
		</div>
	</section>

<?
include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/modal_carga.php'; 
include_once $_SESSION['base_url'].'partials/footer.php';?>
<script type="text/javascript">
	function show_detalles_sol(rubro,estado){
		$("#modal_carga").modal('show');
	$.getJSON('../operaciones.php',{
      item:btoa(rubro),
      estado:btoa(estado),
      action: 'traer_sol_Xitems_Gneral',
              }, function(data){  
                if(data.msg){
                  $("#modal_carga").modal('hide');
                  $('#id_modal_paso_dos').modal('hide');
                  limpiar_modal_dos();
                  toastr.info(data.msj);
                }else{
                  toastr.info(data.msj);
                  $("#modal_carga").modal('hide');
                  $('#id_modal_paso_dos').modal('hide');
                  limpiar_modal_dos();
                }
              }).fail(function(data) {
                toastr.error('Error! Problemas con el server.');
                $("#modal_carga").modal('hide');
              });
	}
</script>