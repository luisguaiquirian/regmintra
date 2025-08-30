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

	if(isset($_GET['modificar_carga']))
	{
		// si existe el where de modificar buscamos el registrp
		$system->table = "carga_familiar";

		$register = $system->find(base64_decode($_GET['modificar_carga']));
		
	}
$options_nac = "";
$options_sexo = "";
    $nac = ['V','E','P'];
	foreach ($nac as $row) 
	{
		// llenado del combo de parroquias
		$selected = $register && $register->nac_carga === $row ? 'selected=""' : '';

		$options_nac .= "<option value='{$row}' {$selected}>{$row}</option>";
	}
    $sexo = ['','Masculino','Femenino'];
	foreach ($sexo as $row) 
	{
		// llenado del combo de parroquias
		$selected = $register && $register->sexo === $row ? 'selected=""' : '';

		$options_sexo .= "<option value='{$row}' {$selected}>{$row}</option>";
	}

?>
	
	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
	
            <h4 class="panel-title text-center">Datos de la carga familiar</h4>
		</header>
		<div class="panel-body">
			<form action="./operaciones.php" class="form-horizontal" id="form_registrar" method="POST">
		
				<input type="hidden" name="id_modificar" value="<?= $register ? $register->id : '' ?>">
				<input id="estado" type="hidden" name="estado" value="<?= $_SESSION['edo'] ?>">
				<input id="id_usuario" type="hidden" name="id_usuario" value="<?= $_SESSION['user_id'] ?>">
				<input id="municipio" type="hidden" name="municipio" value="<?= $_SESSION['mun'] ?>">
				<input type="hidden" id="action" name="action" value="<?= $register ? 'modificar_carga' : 'grabar_carga_familiar' ?>">

				<fieldset>
					<div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="ced_carga">Cédula:</label>
                        <div class="col-md-1">                     
                        <select class="form-control" name="nac_carga" id="nac_carga" required="">
				            <?= $options_nac ?>
                        </select>
                      </div>                    
                       <div class="col-md-3">                     
                        <input class="form-control" id="ced_carga" placeholder="123456789" name="ced_carga" maxlength="8" required="" onkeypress="return valida(event)" value="<?= $register ? $register->ced_carga : '' ?>">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="fecha_nac">Fecha de nacimiento:</label>
                      <div class="col-md-4">                     
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <input type="text" id="fecha_nac" name="fecha_nac" value="<?= $register ? $register->fecha_nac : '' ?>" class="form-control" required="">
                        </div>
                      </div>                  
                    </div>
					<div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="ape_avan">Nombre:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="nom_carga" placeholder="Nombre de la carga familiar" name="nom_carga" required="" onKeyUp="this.value=this.value.toUpperCase();" value="<?= $register ? $register->nom_carga : '' ?>">
                      </div>                  
                      <label class="col-md-2 col-sm-2 control-label" for="ape_avan">Apellido:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="ape_carga" placeholder="Apellido de la carga familiar" name="ape_carga" required="" onKeyUp="this.value=this.value.toUpperCase();" value="<?= $register ? $register->ape_carga : '' ?>">
                      </div>                  
                    </div>    
 					<div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="email_carga">email:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="email_carga" placeholder="correo electrónico" name="email_carga" value="<?= $register ? $register->email_carga : '' ?>">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="telf_carga">Telefono:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="telf_carga" placeholder="Número de Teléfono" maxlength="12" name="telf_carga" onkeypress="return valida(event)" value="<?= $register ? $register->telf_carga : '' ?>">
                      </div>
                   </div> 					
 					<div class="form-group">
                       <label class="col-md-2 col-sm-2 control-label" for="ced_carga">Sexo:</label>
                        <div class="col-md-4">                     
                        <select class="form-control" name="sexo" id="sexo" required="">
				            <?= $options_sexo ?>
                        </select>
                      </div>                    
						<label for="parentesco" class="control-label col-md-2 col-sm-2">Parentesco:</label>
						<div class="col-md-4 col-sm-4">
					<select name="parentesco" id="parentesco" required="" class="form-control">
						<option value=""></option>
						<?
							$system->sql = "SELECT * from parentesco";
							foreach ($system->sql() as $rs) 
							{

								if($register->parentesco == $rs->id)
								{

									echo '<option value="'.$rs->id.'" selected>'.$rs->parentesco.'</option>';	
								}
								else
								{
									echo '<option value="'.$rs->id.'">'.$rs->parentesco.'</option>';	
								}

							}
						?>
					</select>
						</div>
                    </div>
                <div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="sercp_carga">Serial CP:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="sercp_carga" placeholder="Serial Carnet de la Patria" maxlength="10" name="sercp_carga" value="<?= $register ? $register->sercp_carga : '' ?>" onkeypress="return valida(event)">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="codcp_carga">Código CP:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="codcp_carga" placeholder="Código del Carnet de la Patria" maxlength="10" name="codcp_carga" onkeypress="return valida(event)" value="<?= $register ? $register->codcp_carga : '' ?>">
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

    $('#fecha_nac').datepicker({
        format: 'yyyy-mm-dd',
        orientation: "auto left",
        forceParse: false,
        autoclose: true,
        todayHighlight: true,
        toggleActive: true,
        endDate: '0',
        todayBtn: true,
        language: 'es',
    });
    
    $("#ced_carga").blur(function(){
			var ced = $(this).val()
			$.getJSON('./operaciones.php',{ced: ced, action: 'buscar_persona'}, function(data)
			{	
				if(Object.keys(data).length > 0)
				
				{
					var nombre = ''

					$("#nom_carga").val(data[0].nombre).prop('readonly',true)
					$("#ape_carga").val(data[0].apellido).prop('readonly',true)

				}
				else
				{
					$("#nom_carga").prop('readonly',false)
					$("#ape_carga").prop('readonly',false)
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

				if(action === "grabar_carga_familiar")
				{
					$('#form_registrar')[0].reset()
	                //$('#fields_ocultos').hide()
	                //toastr.success('Decreto guardado con éxito!', 'Éxito!')					
                    window.location.replace('./incluir_carga_familiar.php')        
				}
				if(action === "modificar_carga")
				{
	                //toastr.success('Decreto guardado con éxito!', 'Éxito!')					
                    window.location.replace('./vista_carga.php')        
				}
	                
			}
			else
			{    
                $('#form_registrar')[0].reset()
                toastr.error('Carga familiar ya se encuentra registrada', 'Error!')
			}

		})
		
	})
    
    });
</script>
