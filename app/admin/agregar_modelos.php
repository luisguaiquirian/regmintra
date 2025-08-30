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
		$system->table = "modelos_vehiculos";

		$register = $system->find(base64_decode($_GET['modificar']));
		
	}

?>
	
	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
	
            <h4 class="panel-title text-center">Agregar modelos de vehículos</h4>
		</header>
		<div class="panel-body">
			<form action="./operaciones.php" class="form-horizontal" id="form_registrar" method="POST">
		
				<input type="hidden" name="id_modificar" value="<?= $register ? $register->id : '' ?>">
				<input type="hidden" id="action" name="action" value="<?= $register ? 'modificar' : 'grabar_modelo' ?>">
                
				<fieldset>
				<div class="form-group">
                       <label class="col-md-2 col-sm-2 control-label" for="marca">Marca:</label>
                      <div class="col-md-10">                     
					<select name="id_marca" id="id_marca" class="form-control">
						<option value=""></option>
						<?
							$system->sql = "SELECT * from marcas_vehiculos";
							foreach ($system->sql() as $rs) 
							{

								if($register->marca == $rs->marca)
								{

									echo '<option value="'.$rs->id.'" selected>'.$rs->marca.'</option>';	
								}
								else
								{
									echo '<option value="'.$rs->id.'">'.$rs->marca.'</option>';	
								}

							}
						?>
					</select>          
                    </div> 
                </div>                  
				<div class="form-group">
                       <label class="col-md-2 col-sm-2 control-label" for="modelo">Modelo:</label>
                      <div class="col-md-10">                     
                        <input class="form-control" id="modelo" placeholder="Modelo del vehículo" name="modelo" onKeyUp="this.value=this.value.toUpperCase();" value="<?= $register ? $register->modelo : '' ?>">
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
           let mod = document.getElementById("modelo").value;
           let mar = document.getElementById("id_marca").value;

            if (mar.length == 0 || /^\s+$/.test(mar)) {
                toastr.error('Selecciones la marca del vehículo', 'Error!')
                return false;		
            }
        
            if (mod.length == 0 || /^\s+$/.test(mar)) {
                toastr.error('Ingrese el modelo del vehículo', 'Error!')
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

				if(action === "grabar_modelo")
				{
					$('#form_registrar')[0].reset()
	                //$('#fields_ocultos').hide()
	                //toastr.success('Decreto guardado con éxito!', 'Éxito!')					
                    window.location.replace('./agregar_modelos.php')        
				}
				if(action === "modificar")
				{
	                //toastr.success('Decreto guardado con éxito!', 'Éxito!')					
                    window.location.replace('./vista_modelos.php')        
				}
	                
			}
			else
			{    
                $('#form_registrar')[0].reset()
                toastr.error('El modelo de vehículo ya se encuentra registrada', 'Error!')
			}
		})
		
	})
    
    });
</script>
