<?php

	if(!isset($_SESSION)){
    	session_start();
  }

	include_once $_SESSION['base_url'].'partials/header.php';

	$system->sql = "SELECT *, 
									date_format(created_at,'%d-%m-%Y') as fecha,
									IFNULL(
										(SELECT CONCAT(nombre,' ',apellido) from users where id = mensajes.id_usuario_envio),
										(SELECT usuario from users where id = mensajes.id_usuario_envio)
									) as usuario_emisor,
									case readed 
									WHEN 2 THEN 'No leido'
									ELSE 'leido'
									END as estado_mensaje
									FROM mensajes 
									where id_usuario_receptor = $_SESSION[user_id]";
	$res = $system->sql();

	$system->sql = "SELECT * FROM (
		SELECT 
		(SELECT count(*) from mensajes where readed = 1 and id_usuario_receptor = $_SESSION[user_id]) as mensajes_leidos,
		(SELECT count(*) from mensajes where readed = 2 and id_usuario_receptor = $_SESSION[user_id]) as mensajes_pendientes
		from mensajes

	) as t1";
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
								<h4 class="title">Total Mensajes Leidos</h4>
								<div class="info">
									<strong class="amount"><?= $res1[0]->mensajes_leidos ?></strong>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
		<div class="col-md-6 col-lg-6 col-xl-12 animated bounceInRight slow">
			<section class="panel panel-featured-left panel-featured-secondary">
				<div class="panel-body">
					<div class="widget-summary">
						<div class="widget-summary-col widget-summary-col-icon">
							<div class="summary-icon bg-danger">
								<i style="margin-top: 20%" class="fa fa-envelope"></i>
							</div>
						</div>
						<div class="widget-summary-col">
							<div class="summary">
								<h4 class="title">Total Mensajes Pendientes</h4>
								<div class="info">
									<strong class="amount"><?= $res1[0]->mensajes_pendientes ?></strong>
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
        <h4 class="panel-title text-center">Bandeja de Entrada</h4>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped" id="datatable-default">
				<thead>
					<tr>
						<th class="text-center">Emisor</th>
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
									<td>$row->usuario_emisor</td>
									<td>$row->fecha</td>
									<td>$row->estado_mensaje</td>
									<td>
										<button type='button' 
											data-toggle='modal' 
											data-target='#modal_message' 
											class='btn btn-primary' 
											data-mensaje='$row->mensaje'
											data-id='$row->id'
											data-estado='$row->readed'>
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
					<a href="#" id="btn_response" class="btn btn-danger" >responder</a>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
			let mensaje = e.relatedTarget.dataset.mensaje,
					id = e.relatedTarget.dataset.id,
					estado = parseInt(e.relatedTarget.dataset.estado)

			document.getElementById('h4_texto').innerHTML = mensaje
			$('#btn_response').attr('href',"<?= $_SESSION['base_url1'].'app/mensajes/mensaje_respuesta.php?response='?>"+btoa(id))
			if(estado === 2){
				$.ajax({
					url: './operaciones.php',
					data: {id, action: 'read'},
					dataType: 'JSON',
					type: "POST",
					success: function(res){
						if(res.r){
							let table = $('#datatable-default')
							table.DataTable().destroy()
							table.dataTable({
								data: res.mensajes,
								columns: [
									{data: 'usuario_emisor'},
									{data: 'fecha'},
									{data: 'estado_mensaje'},
									{data: {'data': 'data'}, 'render' : function(datos){
										return `
											<button type='button' 
												data-toggle='modal' 
												data-target='#modal_message' 
												class='btn btn-primary btn-block' 
												data-mensaje='${datos.mensaje}'
												data-id='${datos.id}'
												data-estado='${datos.readed}'
												>
												Ver Mensaje
											</button>
										`
									}}
								]
							})
						}
					}
				})
			}
		})
	})
</script>