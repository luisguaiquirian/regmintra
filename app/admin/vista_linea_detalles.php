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
    $options_nac = "";
    $options_rif = "";
	if(isset($_GET['id']))
	{
		// si existe el where de modificar buscamos el registrp
		$system->table = "users";

		$register = $system->find(base64_decode($_GET['id']));
		
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
	
            <h4 class="panel-title text-center"><?= $register->nombre_linea ?></h4>
		</header>
		<div class="panel-body">
			<form action="" class="form-horizontal" id="form_registrar" method="POST">
		
				<fieldset>
					<div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="nac_lin">R.I.F.:</label>
                        <div class="col-md-1">                     
                        <select class="form-control" readonly name="nac_lin" id="nac_lin" required="">
				            <?= $options_rif ?>
                        </select>
                      </div>                    
                       <div class="col-md-3">                     
                        <input class="form-control" id="rif" readonly placeholder="123456789" name="rif" required="" onKeypress="if (event.keyCode < 47 || event.keyCode > 57) event.returnValue = false;" value="<?= $register ? $register->rif : '' ?>">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="nombre_linea">Nombre de la línea:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="nombre_linea" readonly placeholder="Nombre de la línea de transporte" name="nombre_linea" required="" onKeyUp="this.value=this.value.toUpperCase();" value="<?= $register ? $register->nombre_linea : '' ?>">
                      </div>
                   </div>  
 					<div class="form-group">
 						<label for="" class="control-label col-md-2 col-sm-2">Tipo de Ruta:</label>
						<div class="col-md-4 col-sm-4">
					<select name="tipo_ruta" id="tipo_ruta" readonly required="" class="form-control">
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
                        <input class="form-control" readonly id="gremio" placeholder="Gremio al cual pertenece" name="gremio" required="" onKeyUp="this.value=this.value.toUpperCase();" value="<?= $register ? $register->gremio : '' ?>">
                      </div>
                   </div>
                   <div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="observaciones">Observaciones:</label>
                      <div class="col-md-10">                     
                        <textarea class="form-control" readonly id="observaciones" placeholder="Observaciones" name="observaciones" onKeyUp="this.value=this.value.toUpperCase();"><?= $register ? $register->observaciones : '' ?></textarea>
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
                        <select class="form-control" readonly name="nacionalidad" id="nacionalidad" required="">
				            <?= $options_nac ?>
                        </select>
                      </div>                    
                       <div class="col-md-3">                     
                        <input class="form-control" readonly id="cedula" placeholder="123456789" name="cedula" maxlength="8" required="" onKeypress="if (event.keyCode < 47 || event.keyCode > 57) event.returnValue = false;" value="<?= $register ? $register->cedula : '' ?>">
                      </div>
                     </div>
					<div class="form-group">
                       <label class="col-md-2 col-sm-2 control-label" for="nombre">Nombre:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" readonly id="nombre" placeholder="Nombre del nuevo usuario" name="nombre" required="" onKeyUp="this.value=this.value.toUpperCase();" value=" <?= $register ? $register->nombre : '' ?>">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="apellido">Apellido:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" readonly id="apellido" placeholder="Apellido del nuevo usuario" name="apellido" required="" onKeyUp="this.value=this.value.toUpperCase();" value="<?= $register ? $register->apellido : '' ?>">
                      </div>                  
                    </div>    
 					<div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="email">email:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" readonly id="email" placeholder="e-mail del responsable de la línea" name="email" value="<?= $register ? $register->email : '' ?>">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="telefono">Telefono:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" readonly id="telefono" placeholder="Número de Teléfono" maxlength="12" name="telefono" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="<?= $register ? $register->telefono : '' ?>">
                      </div>
                   </div> 					
                     <div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="usuario">Usuario:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" readonly id="usuario" placeholder="" name="usuario" value="<?= $register ? $register->usuario : '' ?>">
                      </div>
                   </div>                    
                   <div class="form-group">
						<div class="col-md-4 col-sm-4 col-md-offset-4">
                            <a href="vista_lineas.php?id=<?= base64_encode($register->estado) ?>&id_mun=<?= base64_encode($register->municipio) ?>"><button type="button" class="btn btn-info btn-block">Volver&nbsp;<i class="fa fa-send"></i></button></a>
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
	                //toastr.success('Decreto guardado con éxito!', 'Éxito!')					
                    window.location.replace('./incluir_linea.php')        
				}
				if(action === "modificar")
				{
	                //toastr.success('Decreto guardado con éxito!', 'Éxito!')					
                    window.location.replace('./vista.php')        
				}
	                
			}
			else
			{    
                $('#form_registrar')[0].reset()
                toastr.error('Línea de transporte ya se encuentra registrada', 'Error!')
			}
		})
		
	})
    
    });
</script>
