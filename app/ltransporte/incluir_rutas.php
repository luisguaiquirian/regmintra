<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
	include_once $_SESSION['base_url'].'partials/header.php';

    $year = date("Y");

	$register = null;

	$options_voc = "<option></option>";
	$options_fuente = "<option></option>";
	$options_sex = "";
	$options_jefe = "";
	$options_carnet = "";

	if(isset($_GET['modificar']))
	{
		// si existe el where de modificar buscamos el registrp
		$system->table = "rutas";

		$register = $system->find(base64_decode($_GET['modificar']));
		
	}


?>
	
	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
	
			<h2 class="panel-title">Incluir Rutas de transporte</h2>
		</header>
		<div class="panel-body">
			<form action="./operaciones.php" class="form-horizontal" id="form_registrar" method="POST">
		
				<input type="hidden" name="id_modificar" value="<?= $register ? $register->id : '' ?>">
				<input id="estado" type="hidden" name="estado" value="<?= $_SESSION[edo] ?>">
				<input id="municipio" type="hidden" name="municipio" value="<?= $_SESSION[mun] ?>">
				<input id="cod_linea" type="hidden" name="cod_linea" value="<?= $_SESSION[cod_linea] ?>">
				<input type="hidden" id="action" name="action" value="<?= $register ? 'modificar' : 'grabar_ruta' ?>">

				<fieldset>
					<div class="form-group">
                       <label class="col-md-2 col-sm-2 control-label" for="ruta">Nombre de la ruta:</label>
                      <div class="col-md-10">                     
                        <input class="form-control" id="ruta" placeholder="Origen y destino de la ruta" name="ruta" required="" onKeyUp="this.value=this.value.toUpperCase();" value="<?= $register ? $register->ruta : '' ?>">
                      </div>              
                    </div>    
                    <div class="form-group">
						<div class="col-md-4 col-sm-4 col-sm-offset-3 col-md-offset-3">
							<button type="submit" class="btn btn-danger btn-block">Guardar&nbsp;<i class="fa fa-send"></i></button>
						</div>
						<div class="col-md-4 col-sm-4">
							<button type="reset" class="btn btn-info btn-block">Limpiar&nbsp;<i class="fa fa-send"></i></button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</section>
<?
	include_once $_SESSION['base_url'].'partials/footer.php';
?>
<script>
$(function(){

    $('#form_registrar').submit(function(e) {
		e.preventDefault()

		$.ajax({
			url: './operaciones.php',
			type: 'POST',
			dataType: 'JSON',
			data: $(this).serialize(),
		})
		.done(function(data) {
			if(data.r)
			{
				let action = $('#action').val()

				if(action === "grabar_ruta")
				{
					$('#form_registrar')[0].reset()
	                //$('#fields_ocultos').hide()
	                //toastr.success('Decreto guardado con éxito!', 'Éxito!')					
                    window.location.replace('./incluir_rutas.php')        
				}
				if(action === "modificar")
				{
	                //toastr.success('Decreto guardado con éxito!', 'Éxito!')					
                    window.location.replace('./vista_ruta.php')        
				}
	                
			}
			else
			{    
                $('#form_registrar')[0].reset()
                toastr.error('La ruta ya se encuentra registrada', 'Error!')
			}
		})
		
	})
    
    });
</script>
