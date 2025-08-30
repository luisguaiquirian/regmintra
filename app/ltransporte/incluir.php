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

if(isset($_GET['agregar']))
	{
		// si existe el where de modificar buscamos el registro
		$system->table = "users";
		$regist = $system->find(base64_decode($_GET['agregar']));
	
	}

    if(isset($_GET['modificar']))
	{
		// si existe el where de modificar buscamos el registrp
		$system->table = "unidades";

		$register = $system->find(base64_decode($_GET['modificar']));
		
	}


?>
	
	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
	
            <h4 class="panel-title text-center">Datos de la unidad de transporte</h4>
		</header>
		<div class="panel-body">
			<form action="./operaciones.php" class="form-horizontal" id="form_registrar" method="POST">
		
				<input type="hidden" name="id_modificar" value="<?= $register ? $register->id : '' ?>">
				<input id="estado" type="hidden" name="estado" value="<?= $_SESSION['edo'] ?>">
				<input id="municipio" type="hidden" name="municipio" value="<?= $_SESSION['mun'] ?>">
				<input id="cod_linea" type="hidden" name="cod_linea" value="<?= $_SESSION['cod_linea'] ?>">
				<input id="cod_afiliado" type="hidden" name="cod_afiliado" value="<?= $regist->usuario ?>">
				<input type="hidden" id="action" name="action" value="<?= $register ? 'modificar' : 'grabar_unidad' ?>">

				<fieldset>
					<div class="form-group">            
 						<label for="tipo_unidad" class="control-label col-md-2 col-sm-2">Tipo de Unidad:</label>
						<div class="col-md-4 col-sm-4">
					<select name="tipo_unidad" id="tipo_unidad" required="" class="form-control">
						<option value=""></option>
						<?
							$system->sql = "SELECT * from tipo_unidad";
							foreach ($system->sql() as $rs) 
							{

								if($register->tipo_unidad == $rs->id)
								{

									echo '<option value="'.$rs->id.'" selected>'.$rs->tipo_unidad.'</option>';	
								}
								else
								{
									echo '<option value="'.$rs->id.'">'.$rs->tipo_unidad.'</option>';	
								}

							}
						?>
					</select>
						</div>                       
                      <label class="col-md-2 col-sm-2 control-label" for="cap">Capacidad:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" type="number" id="cap" placeholder="Cantidad de Puestos" maxlength="12" name="cap" onKeypress="if (event.keyCode < 47 || event.keyCode > 57) event.returnValue = false;" value="<?= $register ? $register->cap : '' ?>">
                      </div>                    
                    </div>
					<div class="form-group">              
 						<label for="marca" class="control-label col-md-2 col-sm-2">Marca:</label>
						<div class="col-md-4 col-sm-4">
					<select name="marca" id="marca" required="" class="form-control">
						<option value=""></option>
						<?
							$system->sql = "SELECT * from marcas_vehiculos";
							foreach ($system->sql() as $rs) 
							{

								if($register->marca == $rs->id)
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
 						<label for="modelo" class="control-label col-md-2 col-sm-2">Modelo:</label>
						<div class="col-md-4 col-sm-4">
					<select name="modelo" id="modelo" required="" class="form-control">
						<option value=""></option>
						<?
							$system->sql = "SELECT * from modelos_vehiculos";
							foreach ($system->sql() as $rs) 
							{

								if($register->modelo == $rs->id)
								{

									echo '<option value="'.$rs->id.'" selected>'.$rs->modelo.'</option>';	
								}
								else
								{
									echo '<option value="'.$rs->id.'">'.$rs->modelo.'</option>';	
								}

							}
						?>
					</select>
						</div>                       
                    </div>    
 					<div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="ano">Año:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" type="number" id="ano" placeholder="Año de fabricación del Vehículo" name="ano" value="<?= $register ? $register->ano : '' ?>">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="placa">Placa:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="placa" placeholder="Placa del Vehículo" maxlength="12" name="placa" onKeyUp="this.value=this.value.toUpperCase();" value="<?= $register ? $register->placa : '' ?>">
                      </div>
                   </div> 					
                     <div class="form-group">
 						<label for="tipo_lub" class="control-label col-md-2 col-sm-2">Tipo de Lubricante:</label>
						<div class="col-md-4 col-sm-4">
					<select name="tipo_lub" id="tipo_lub" required="" class="form-control">
						<option value=""></option>
						<?
							$system->sql = "SELECT * from lubricantes";
							foreach ($system->sql() as $rs) 
							{

								if($register->tipo_lub == $rs->id)
								{

									echo '<option value="'.$rs->id.'" selected>'.$rs->lubricante.'</option>';	
								}
								else
								{
									echo '<option value="'.$rs->id.'">'.$rs->lubricante.'</option>';	
								}

							}
						?>
					</select>
						</div>
                      <label class="col-md-2 col-sm-2 control-label" for="cant_lubri">Cantidad de Lubricante:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" type="number" id="cant_lubri" placeholder="Litros de Lubricante que usa" maxlength="12" name="cant_lubri" onKeypress="if (event.keyCode < 47 || event.keyCode > 57) event.returnValue = false;" value="<?= $register ? $register->cant_lubri : '' ?>">
                      </div> 
                   </div>                      
                   <div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="num_neu">Número de neumático:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="num_neu" placeholder="Ejem: 175/70/R13" maxlength="15" name="num_neu" onKeyUp="this.value=this.value.toUpperCase();" value="<?= $register ? $register->num_neu : '' ?>">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="cant_neu">Cantidad de Neumáticos:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" type="number" id="cant_neu" placeholder="Total de Neumáticos que usa la unidad" maxlength="12" name="cant_neu" onKeypress="if (event.keyCode < 47 || event.keyCode > 57) event.returnValue = false;" value="<?= $register ? $register->cant_neu : '' ?>">
                      </div> 
                   </div> 
                     <div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="acumulador">Acumulador:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="acumulador" placeholder="Ejem: 1000 AMP" maxlength="20" name="acumulador" onKeyUp="this.value=this.value.toUpperCase();" value="<?= $register ? $register->acumulador : '' ?>">
                      </div>
                      <label class="col-md-2 control-label" for="radios">¿Se encuentra operativa?:</label>
                      <div class="col-md-4"> 
                        <label class="radio-inline" for="radios-0">
                          <input type="radio" name="activo" id="radios-0" value="1" required="required" <? if($register){ if($register->activo == 1){ echo 'checked'; }} ?>>
                          Si
                        </label> 
                        <label class="radio-inline" for="radios-1">
                          <input type="radio" name="activo" id="radios-1" value="0" required="required" <? if($register){ if($register->activo == 0){ echo 'checked'; }} ?>>
                          No
                        </label>
                      </div>
                   </div>                    
                   <div class="form-group">
						<label for="ruta" class="control-label col-md-2 col-sm-2">Ruta:</label>
						<div class="col-md-4 col-sm-4">
					<select name="ruta" id="ruta" required="" class="form-control">
						<option value=""></option>
						<?
							$system->sql = "SELECT * from rutas";
							foreach ($system->sql() as $rs) 
							{

								if($register->ruta == $rs->id)
								{

									echo '<option value="'.$rs->id.'" selected>'.$rs->ruta.'</option>';	
								}
								else
								{
									echo '<option value="'.$rs->id.'">'.$rs->ruta.'</option>';	
								}

							}
						?>
					</select>
						</div>                      <label class="col-md-2 col-sm-2 control-label" for="obser">Observaciones:</label>
                      <div class="col-md-4">                     
                        <textarea class="form-control" id="obser" placeholder="Observaciones" name="obser" onKeyUp="this.value=this.value.toUpperCase();"><?= $register ? $register->obser : '' ?></textarea>
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

    $('#fecha_decreto').datepicker({
    format: 'dd/mm/yyyy',
    todayBtn: "linked",
    language: "es",
    autoclose: true,
    todayHighlight: true
});    
    $("#cedula").blur(function(){
        
      var usu = +$("#estado").val()+$("#municipio").val()+$("#cedula").val();
        $("#usuario").val(usu);      
        
    })

    
    $("#cedula").blur(function(){
			var ced = $(this).val()
			$.getJSON('./operaciones.php',{ced: ced, action: 'buscar_persona'}, function(data)
			{	
				if(Object.keys(data).length > 0)
				
				{
					var nombre = ''

					$("#nombre").val(data[0].nombre).prop('readonly',true)
					$("#apellido").val(data[0].apellido).prop('readonly',true)

				}
				else
				{
					$("#nombre").prop('readonly',false)
					$("#apellido").prop('readonly',false)
				}		
			})
		})
    
 		$("#marca").change(function(event) {
			
			$("#modelo").empty()
			var marca = $(this).val()
            datos = {
					m: marca,
					action : 'modelo',
				}
			$.getJSON('./operaciones.php',datos, function(data){
				var filas = '<option></option>'

				$.grep(data, function(i,e){
					filas += '<option value="'+i.id+'">'+i.modelo+'</option>'
				})

				$("#modelo").html(filas)
			})
		});    
    
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

				if(action === "grabar_unidad")
				{
					$('#form_registrar')[0].reset()
	                //$('#fields_ocultos').hide()
	                //toastr.success('Decreto guardado con éxito!', 'Éxito!')					
                    window.location.replace('./vista.php')        
				}
				if(action === "modificar")
				{
	                //toastr.success('Decreto guardado con éxito!', 'Éxito!')					
                    window.location.replace('./vista_unidades.php')        
				}
	                
			}
			else
			{    
                $('#form_registrar')[0].reset()
                toastr.error('La unidad ya se encuentra registrada', 'Error!')
			}
		})
		
	})
    
    });
</script>
