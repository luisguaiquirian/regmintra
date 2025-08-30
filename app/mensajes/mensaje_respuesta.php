<?php

	if(!isset($_SESSION)){
    	session_start();
  }


	include_once $_SESSION['base_url'].'partials/header.php';
	$register = null;

	if(isset($_GET['response'])){
		$system->table = "mensajes";
		$register = $system->find(base64_decode($_GET['response']));
	}

?>
	
	<section class="panel panel-dark">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
        <h4 class="panel-title text-center">Redactar Mensaje Nuevo</h4>
		</header>
		<div class="panel-body">
			<form class="form-horizontal" id="form_message">
				<input type="hidden" name="action" id="action" value="response">
				<input type="hidden" id="id_singular" name="id_singular" value="<?= $register->id_usuario_envio ?>">
				<fieldset id="mensaje_div">
					<div class="row form-group"  >
						<div class="col-md-12 col-sm-12">
							<label class="control-label">Texto del Mensaje</label>
							<textarea class="form-control" required="" id="mensaje" name="mensaje" rows="5"></textarea>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-6 col-sm-6">
							<button type="button" id="btn_limpiar" class="btn btn-danger btn-block">Limpiar <i class="fa fa-refresh"></i></button>
						</div>
						<div class="col-md-6 col-sm-6">
							<button type="submit" class="btn btn-primary btn-block">Enviar Mensaje <i class="fa fa-send"></i></button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</section>

<?php
	include_once $_SESSION['base_url'].'partials/footer.php';
?>

<script type="text/javascript">
	$(function(){
		
		$('#btn_limpiar').click(function(e){
			$('#mensaje').val('')
		})

		$('#form_message').submit(function(e){
			e.preventDefault()
			
			let id_singular = $('#id_singular').val()

			let object = {
				id_singular,
				message: $('#mensaje').val(),
				action : $('#action').val()
			}

			$.ajax({
				url:"./operaciones.php",
				data: object,
				dataType: "JSON",
				type: "POST",
				success: function(data){
					if(data.r){
						toastr.success('Mensajeria Enviada', 'Éxito!')
						setTimeout(() => {
							window.location.href = "<?= $_SESSION['base_url1'].'app/mensajes/mensajes_recibidos.php' ?>"						
						},1000)
					}else{
						toastr.danger('Contacte con Soporte', 'Error!')
					}
				}
			})
		})

		
	})
</script>