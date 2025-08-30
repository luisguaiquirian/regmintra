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
	if(isset($_GET['modificar_conduc']))
	{
		// si existe el where de modificar buscamos el registrp
		$system->table = "avances";

		$register = $system->find(base64_decode($_GET['modificar_conduc']));
		
	}

    $nac = ['V','E','P'];
	foreach ($nac as $row) 
	{
		// llenado del combo de parroquias
		$selected = $register && $register->nac_avan === $row ? 'selected=""' : '';

		$options_nac .= "<option value='{$row}' {$selected}>{$row}</option>";
	}

?>
	
	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
	
            <h4 class="panel-title text-center">Datos del conductor o avance</h4>
		</header>
		<div class="panel-body">
			<form action="./operaciones.php" class="form-horizontal" id="form_registrar" method="POST">
		
				<input type="hidden" name="id_modificar" value="<?= $register ? $register->id : '' ?>">
				<input id="estado" type="hidden" name="estado" value="<?= $_SESSION['edo'] ?>">
				<input id="municipio" type="hidden" name="municipio" value="<?= $_SESSION['mun'] ?>">
				<input id="id_afiliado" type="hidden" name="id_afiliado" value="<?= $_SESSION['user'] ?>">
				<input id="activo" type="hidden" name="activo" value="1">
				<input type="hidden" id="action" name="action" value="<?= $register ? 'modificar_conduc' : 'grabar_conduc' ?>">

				<fieldset>
					<div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="nacionalidad">Cédula:</label>
                        <div class="col-md-1">                     
                        <select class="form-control" name="nac_avan" id="nac_avan" required="">
				            <?= $options_nac ?>
                        </select>
                      </div>                    
                       <div class="col-md-3">                     
                        <input class="form-control" id="ced_avan" placeholder="123456789" name="ced_avan" maxlength="8" required="" onKeypress="if (event.keyCode < 47 || event.keyCode > 57) event.returnValue = false;" value="<?= $register ? $register->ced_avan : '' ?>">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="id_unidad">Unidad asignada:</label>
						<div class="col-md-4 col-sm-4">
					<select name="id_unidad" id="id_unidad" class="form-control">
						<option value=""></option>
						<?
	if(isset($_GET['modificar_conduc']))
	{
        $system->sql = "SELECT * FROM unidades WHERE cod_afiliado =".$_SESSION['user'];
							foreach ($system->sql() as $rs) 
							{

								if($register->id_unidad == $rs->id)
								{

									echo '<option value="'.$rs->id.'" selected>'.$rs->placa.'</option>';	
								}
								else
								{
									echo '<option value="'.$rs->id.'">'.$rs->placa.'</option>';	
								}

							}
    }else{
						$system->sql = "SELECT * FROM unidades WHERE NOT EXISTS (SELECT * FROM avances WHERE unidades.id = avances.id_unidad) and cod_afiliado =".$_SESSION['user'];
							foreach ($system->sql() as $rs) 
							{

								if($register->id_unidad == $rs->id)
								{

									echo '<option value="'.$rs->id.'" selected>'.$rs->placa.'</option>';	
								}
								else
								{
									echo '<option value="'.$rs->id.'">'.$rs->placa.'</option>';	
								}

							}
    }					
                        ?>
					</select>
						</div>                    
                    </div>
					<div class="form-group">
                       <label class="col-md-2 col-sm-2 control-label" for="nom_avan">Nombre:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="nom_avan" placeholder="Nombre del nuevo usuario" name="nom_avan" required="" onKeyUp="this.value=this.value.toUpperCase();" value=" <?= $register ? $register->nom_avan : '' ?>">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="ape_avan">Apellido:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="ape_avan" placeholder="Apellido del nuevo usuario" name="ape_avan" required="" onKeyUp="this.value=this.value.toUpperCase();" value="<?= $register ? $register->ape_avan : '' ?>">
                      </div>                  
                    </div>    
 					<div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="email_avan">email:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="email_avan" placeholder="e-mail del responsable de la línea" name="email_avan" value="<?= $register ? $register->email_avan : '' ?>">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="telf_avan">Telefono:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="telf_avan" placeholder="Número de Teléfono" maxlength="12" name="telf_avan" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="<?= $register ? $register->telf_avan : '' ?>">
                      </div>
                   </div> 					
                     <div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="sercp_avan">Serial CP:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="sercp_avan" placeholder="Serial Carnet de la Patria" maxlength="10" name="sercp_avan" value="<?= $register ? $register->sercp_avan : '' ?>" onKeypress="if (event.keyCode < 47 || event.keyCode > 57) event.returnValue = false;">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="codcp_avan">Código CP:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="codcp_avan" placeholder="Código del Carnet de la Patria" maxlength="10" name="codcp_avan" onKeypress="if (event.keyCode < 47 || event.keyCode > 57) event.returnValue = false;" value="<?= $register ? $register->codcp_avan : '' ?>">
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

    
    $("#ced_avan").blur(function(){
			var ced = $(this).val()
			$.getJSON('./operaciones.php',{ced: ced, action: 'buscar_persona'}, function(data)
			{	
				if(Object.keys(data).length > 0)
				
				{
					var nombre = ''

					$("#nom_avan").val(data[0].nombre).prop('readonly',true)
					$("#ape_avan").val(data[0].apellido).prop('readonly',true)

				}
				else
				{
					$("#nom_avan").prop('readonly',false)
					$("#ape_avan").prop('readonly',false)
				}		
			})
		})
    
    
    
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

				if(action === "grabar_conduc")
				{
					$('#form_registrar')[0].reset()
	                //$('#fields_ocultos').hide()
	                //toastr.success('Decreto guardado con éxito!', 'Éxito!')					
                    window.location.replace('./incluir_conduc.php')        
				}
				if(action === "modificar_conduc")
				{
	                //toastr.success('Decreto guardado con éxito!', 'Éxito!')					
                    window.location.replace('./vista_conductores.php')        
				}
	                
			}
			else if(data.x)
			{    
                $('#form_registrar')[0].reset()
                toastr.error('La unidad ya se encuentra asignada a otro conductor', 'Error!')
			}
            else
			{    
                $('#form_registrar')[0].reset()
                toastr.error('El conductor ya se encuentra registrado', 'Error!')
			}
		})
		
	})
    
    });
</script>
