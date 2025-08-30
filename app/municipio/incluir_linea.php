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
	$options_nac="";
	$options_rif="";

	if(isset($_GET['modificar']))
	{
		// si existe el where de modificar buscamos el registrp
		$system->table = "users";

		$register = $system->find(base64_decode($_GET['modificar']));
		
	}

    $nac = ['V','E'];
	foreach ($nac as $row) 
	{
		// llenado del combo de parroquias
		$selected = $register && $register->nacionalidad === $row ? 'selected=""' : '';

		$options_nac .= "<option value='{$row}' {$selected}>{$row}</option>";
	}

    $ri = ['J','G'];
	foreach ($ri as $row) 
	{
		// llenado del combo de parroquias
		$selected = $register && $register->nac_lin === $row ? 'selected=""' : '';

		$options_rif .= "<option value='{$row}' {$selected}>{$row}</option>";
	}

?>
	
	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
	
            <h4 class="panel-title text-center">Datos de la línea de Transporte</h4>
		</header>
		<div class="panel-body">
			<form action="./operaciones.php" class="form-horizontal" id="form_registrar" method="POST">
		
				<input type="hidden" name="id_modificar" value="<?= $register ? $register->id : '' ?>">
				<input id="foto" type="hidden" name="foto" value="fototemp.png">
				<input id="estado" type="hidden" name="estado" value="<?= $_SESSION['edo'] ?>">
				<input id="municipio" type="hidden" name="municipio" value="<?= $_SESSION['mun'] ?>">
				<input id="perfil" type="hidden" name="perfil" value="4">
				<input id="activo" type="hidden" name="activo" value="1">
				<input id="password_activo" type="hidden" name="password_activo" value="0">
				<input id="password" type="hidden" name="password" value="<?= password_hash('123456789',PASSWORD_DEFAULT) ?>">
				<input type="hidden" id="action" name="action" value="<?= $register ? 'modificar' : 'grabar' ?>">
				<input type="hidden" name="qr" id="qr" value="<?= $register ? $register->qr : '' ?>">

				<fieldset>
					<div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="nac_lin">R.I.F.:</label>
                        <div class="col-md-1">                     
                        <select class="form-control" name="nac_lin" id="nac_lin" required="">
				            <?= $options_rif ?>
                        </select>
                      </div>                    
                       <div class="col-md-3">                     
                        <input class="form-control" id="rif" placeholder="123456789" name="rif" required="" onkeypress="return valida(event)" value="<?= $register ? $register->rif : '' ?>">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="nombre_linea">Nombre de la línea:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="nombre_linea" placeholder="Nombre de la línea de transporte" name="nombre_linea" required="" onKeyUp="this.value=this.value.toUpperCase();" value='<?= $register ? $register->nombre_linea : '' ?>'>
                      </div>
                   </div>  
 					<div class="form-group">
 						<label for="" class="control-label col-md-2 col-sm-2">Tipo de Ruta:</label>
						<div class="col-md-4 col-sm-4">
					<select name="tipo_ruta" id="tipo_ruta" required="" class="form-control">
						<option value=""></option>
						<?
							$system->sql = "SELECT * from tipo_ruta";
							foreach ($system->sql() as $rs) 
							{

								if($register->tipo_ruta == $rs->id_ruta)
								{

									echo '<option value="'.$rs->id_ruta.'" selected>'.$rs->tipo_ruta.'</option>';	
								}
								else
								{
									echo '<option value="'.$rs->id_ruta.'">'.$rs->tipo_ruta.'</option>';	
								}

							}
						?>
					</select>
						</div> 
                      <label class="col-md-2 col-sm-2 control-label" for="gremio">Gremio:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="gremio" placeholder="Gremio al cual pertenece" name="gremio" required="" onKeyUp="this.value=this.value.toUpperCase();" value="<?= $register ? $register->gremio : '' ?>">
                      </div>
                   </div>
                   <div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="observaciones">Observaciones:</label>
                      <div class="col-md-4">                     
                        <textarea class="form-control" id="observaciones" placeholder="Observaciones" name="observaciones" onKeyUp="this.value=this.value.toUpperCase();"><?= $register ? $register->observaciones : '' ?></textarea>
                      </div>                      
                      <label class="col-md-2 col-sm-2 control-label" for="cant_socios">Cant. de Socios:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" type="number" id="cant_socios" placeholder="Cant. de Socios" onkeypress="return valida(event)" name="cant_socios" value="<?= $register ? $register->cant_socios : '' ?>"> 
                      </div>
                   </div>
                    <header class="panel-heading">
                        <div class="panel-actions">
                        </div>

                        <h4 class="panel-title text-center">Datos del Representante legal de la línea de transporte</h4>
                    </header>
                    <br>
					<div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="nacionalidad">Cédula:</label>
                        <div class="col-md-1">                     
                        <select class="form-control" name="nacionalidad" id="nacionalidad" required="">
				            <?= $options_nac ?>
                        </select>
                      </div>                    
                       <div class="col-md-3">                     
                        <input class="form-control" id="cedula" placeholder="123456789" name="cedula" maxlength="8" required="" onkeypress="return valida(event)" value="<?= $register ? $register->cedula : '' ?>">
                      </div>
                     </div>
					<div class="form-group">
                       <label class="col-md-2 col-sm-2 control-label" for="nombre">Nombre:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="nombre" placeholder="Nombre del nuevo usuario" name="nombre" required="" onKeyUp="this.value=this.value.toUpperCase();" value=" <?= $register ? $register->nombre : '' ?>">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="apellido">Apellido:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="apellido" placeholder="Apellido del nuevo usuario" name="apellido" required="" onKeyUp="this.value=this.value.toUpperCase();" value="<?= $register ? $register->apellido : '' ?>">
                      </div>                  
                    </div>    
 					<div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="email">email:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="email" placeholder="e-mail del responsable de la línea" name="email" value="<?= $register ? $register->email : '' ?>">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="telefono">Telefono:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="telefono" placeholder="Número de Teléfono" maxlength="11" name="telefono" onkeypress="return valida(event)" value="<?= $register ? $register->telefono : '' ?>">
                      </div>
                   </div> 					
                    <div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="usuario">Usuario:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="usuario" readonly name="usuario" value="<?= $register ? $register->usuario : '' ?>">
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
    $("#rif").blur(function(){
        var usu = +$("#estado").val()+$("#municipio").val()+$("#rif").val();
        $("#usuario").val(usu);    
        $("#qr").val("assets/images/Qr/ltransporte/"+usu+".png");
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
    
 		$("#municipio").change(function(event) {
			
			$("#parroquia").empty()
			var municipio = $(this).val()
            var	estado = $("#estado").val(),
            datos = {
					action : 'buscar_parroquia',
					municipio: municipio,
					estado : estado
				}
			$.getJSON('operaciones.php',datos, function(data){
				var filas = '<option></option>'

				$.grep(data, function(i,e){
					filas += '<option value="'+i.id_paroquia+'">'+i.parroquia+'</option>'
				})

				$("#parroquia").html(filas)
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

				if(action === "grabar")
				{
					$('#form_registrar')[0].reset()
	                //$('#fields_ocultos').hide()					
                    //window.location.replace('./incluir_linea.php');
                    toastr.success('Linea de transporte Registrada Correctamente!', 'Éxito!', {timeOut: 2000}); 
                    setTimeout("window.location.replace('./incluir_linea.php')",2001);       
				}
				if(action === "modificar")
				{
	                toastr.success('Linea de transporte Actualizada Correctamente!', 'Éxito!', {timeOut: 2000}); 
                    setTimeout("window.location.replace('./vista.php')",2001);					        
				}
			}
			else
			{    
                $('#form_registrar')[0].reset();
                toastr.error('Línea de transporte ya se encuentra registrada', 'Error!');
			}
		})
		
	})
    
    });
</script>
