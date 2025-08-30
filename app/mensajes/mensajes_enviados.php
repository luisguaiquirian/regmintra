<?php

	if(!isset($_SESSION)){
    	session_start();
  }


	include_once $_SESSION['base_url'].'partials/header.php';

	$system->sql = "SELECT *, 
									date_format(created_at,'%d-%m-%Y') as fecha,
									IFNULL(
										(SELECT CONCAT(nombre,' ',apellido) from users where id = mensajes.id_usuario_receptor),
										(SELECT usuario from users where id = mensajes.id_usuario_receptor)
									) as usuario_receptor,
									case readed 
									WHEN 2 THEN 'No leido'
									ELSE 'leido'
									END as estado_mensaje
									FROM mensajes 
									where id_usuario_envio = $_SESSION[user_id]";
	$res = $system->sql();

	$system->sql = "SELECT count(*) as total from mensajes where id_usuario_envio = $_SESSION[user_id]";
	$res1 = $system->sql();
?>
	
	<div class="row">
		<div class="col-md-6 col-lg-6 col-xl-12 animated bounceInRight slow">
			<section class="panel panel-featured-left panel-featured-tertiary">
				<div class="panel-body">
					<div class="widget-summary">
						<div class="widget-summary-col widget-summary-col-icon">
							<div class="summary-icon bg-tertiary">
								<i style="margin-top: 20%" class="fa fa-envelope"></i>
							</div>
						</div>
						<div class="widget-summary-col">
							<div class="summary">
								<h4 class="title">Total Mensajes Enviados</h4>
								<div class="info">
									<strong class="amount"><?= $res1[0]->total?></strong>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
	<section class="panel panel-dark">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
        <h4 class="panel-title text-center">Bandeja de Salida</h4>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped" id="datatable-default">
				<thead>
					<tr>
						<th class="text-center">Receptor</th>
						<th class="text-center">Fecha Emisión</th>
						<th class="text-center">Estado</th>
						<th class="text-center">Mensaje</th>
					</tr>
				</thead>
				<tbody class="text-center">
					<?php
						foreach ($res as $row) {
							echo "
								<tr>
									<td>$row->usuario_receptor</td>
									<td>$row->fecha</td>
									<td>$row->estado_mensaje</td>
									<td>
										<button type='button' 
											data-toggle='modal' 
											data-target='#modal_message' 
											class='btn btn-primary' 
											data-mensaje='$row->mensaje'>
											Ver Mensaje
										</button>
										<a target='_blank' href='./pdf_mensaje.php?message=".base64_encode($row->id)."' class='btn btn-danger'>
											Imprimir Mensaje
										</a>
									</td>
								</tr>
							";
						}
					?>
				</tbody>
			</table>
		</div>
	</section>	
	<div class="modal fade" id="modal_message">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header" style="background-color: black; color: white;">
					<h3 class="modal-title">Texto del Mensaje</h3>
				</div>
				<div class="modal-body">
					<h4 style="text-align: justify; text-indent: 3em;" id="h4_texto"></h4>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

<?php
	include_once $_SESSION['base_url'].'partials/footer.php';
?>

<script type="text/javascript">
	$(function(){
		$('#modal_message').on('show.bs.modal',function(e){
			let mensaje = e.relatedTarget.dataset.mensaje
			document.getElementById('h4_texto').innerHTML = mensaje
		})
	})
</script>