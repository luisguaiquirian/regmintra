<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
	include_once $_SESSION['base_url'].'partials/header.php';

	$register = null;

	$options_voc = "<option></option>";
	$options_fuente = "<option></option>";
	$options_sex = "";
	$options_jefe = "";
	$options_carnet = "";

	if(isset($_GET['modificar']))
	{
		// si existe el where de modificar buscamos el registrp
		$system->table = "marcas vehiculos";

		$register = $system->find(base64_decode($_GET['modificar']));
		
	}

?>
	
	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
	
            <h4 class="panel-title text-center">Agregar marcas de vehículos</h4>
		</header>
		<div class="panel-body">
			<form action="./operaciones.php" class="form-horizontal" id="form_registrar" method="POST">
		
				<input type="hidden" name="id_modificar" value="<?= $register ? $register->id : '' ?>">
				<input type="hidden" id="action" name="action" value="<?= $register ? 'modificar' : 'grabar_marca' ?>">
                
				<fieldset>
				<div class="form-group">
                       <label class="col-md-2 col-sm-2 control-label" for="nombre">Marca:</label>
                      <div class="col-md-10">                     
                        <input class="form-control" id="marca" placeholder="Marca del vehículo" name="marca" onKeyUp="this.value=this.value.toUpperCase();" value="<?= $register ? $register->marca : '' ?>">
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
            var mar = document.getElementById("marca").value;
            if (mar.length == 0 || /^\s+$/.test(mar)) {
                toastr.error('Ingrese una marca de vehículo', 'Error!')
                return false;		
            }
        
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

				if(action === "grabar_marca")
				{
					$('#form_registrar')[0].reset()
	                //$('#fields_ocultos').hide()
	                //toastr.success('Decreto guardado con éxito!', 'Éxito!')					
                    window.location.replace('./agregar_marcas.php')        
				}
				if(action === "modificar")
				{
	                //toastr.success('Decreto guardado con éxito!', 'Éxito!')					
                    window.location.replace('./vista_marcas.php')        
				}
	                
			}
			else
			{    
                $('#form_registrar')[0].reset()
                toastr.error('La marca de vehículo ya se encuentra registrada', 'Error!')
			}
		})
		
	})
    
    });
</script>
