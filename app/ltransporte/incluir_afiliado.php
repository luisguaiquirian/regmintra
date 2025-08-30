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

if(isset($_GET['modificar']))
	{
		// si existe el where de modificar buscamos el registrp
		$system->table = "users";

		$register = $system->find(base64_decode($_GET['modificar']));
		
	}

    $nac = ['V','E','P'];
	foreach ($nac as $row) 
	{
		// llenado del combo de parroquias
		$selected = $register && $register->nacionalidad === $row ? 'selected=""' : '';

		$options_nac .= "<option value='{$row}' {$selected}>{$row}</option>";
	}

?>
<?php
    $system->table = "users";
    $system->where = "cod_linea ='$_SESSION[cod_linea]'";
    $cant_socios = $system->count();
    if($cant_socios == $_SESSION['cant_socios'] and empty($_GET['modificar']))
    {
            
        ?>		
 						<section class="body-error error-inside">
							<div class="center-error">

								<div class="row">
									<div class="col-md-12">
										<div class="main-error mb-xlg">
											<h2 class="error-code text-dark text-center text-semibold m-none"><i class="fa fa-times-circle"></i> </h2>
											<p class="error-explanation text-center">Se ha llegado al límite de <?=$cant_socios ?> socios declarados en su acta constitutiva o DT-10</p>
										</div>
									</div>
								</div>
							</div>
						</section>       
   <? } else { ?>  	
	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
	
            <h4 class="panel-title text-center">Datos del afiliado</h4>
		</header>
        <div class="panel-body">

    			<form action="./operaciones.php" class="form-horizontal" id="form_registrar" method="POST">
		
				<input type="hidden" name="id_modificar" value="<?= $register ? $register->id : '' ?>">
				<input id="estado" type="hidden" name="estado" value="<?= $_SESSION['edo'] ?>">
				<input id="municipio" type="hidden" name="municipio" value="<?= $_SESSION['mun'] ?>">
				<input id="cod_linea" type="hidden" name="cod_linea" value="<?= $_SESSION['cod_linea'] ?>">
				<input id="foto" type="hidden" name="foto" value="fototemp.png">
				<input id="nombre_linea" type="hidden" name="nombre_linea" value="<?= $_SESSION['nom_linea'] ?>">
				<input id="perfil" type="hidden" name="perfil" value="5">
				<input id="activo" type="hidden" name="activo" value="1">
				<input id="password_activo" type="hidden" name="password_activo" value="0">
				<input id="password" type="hidden" name="password" value="<?= password_hash('123456789',PASSWORD_DEFAULT) ?>">
				<input type="hidden" id="action" name="action" value="<?= $register ? 'modificar' : 'grabar' ?>">
                <input type="hidden" name="qr" id="qr" value="<?= $register ? $register->qr : '' ?>">
                
				<fieldset>
					<div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="nacionalidad">Cédula:</label>
                        <div class="col-md-1">                     
                        <select class="form-control" name="nacionalidad" id="nacionalidad" required="">
				            <option value="V">V</option>
				            <option value="E">E</option>
                        </select>
                      </div>                    
                       <div class="col-md-3">                     
                        <input class="form-control" id="cedula" placeholder="123456789" name="cedula" maxlength="8" required="" onkeypress="return valida(event)" value="<?= $register ? $register->cedula : '' ?>">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="usuario">Usuario:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="usuario" readonly placeholder="" name="usuario" value="<?= $register ? $register->usuario : '' ?>">
                      </div>                     </div>
					<div class="form-group">
                       <label class="col-md-2 col-sm-2 control-label" for="nombre">Nombre:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="nombre" readonly placeholder="Nombre del nuevo usuario" name="nombre" required="" onKeyUp="this.value=this.value.toUpperCase();" value="<?= $register ? $register->nombre : '' ?>">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="apellido">Apellido:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="apellido" readonly placeholder="Apellido del nuevo usuario" name="apellido" required="" onKeyUp="this.value=this.value.toUpperCase();" value="<?= $register ? $register->apellido : '' ?>">
                      </div>                  
                    </div>    
 					<div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="email">email:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" id="email" readonly placeholder="e-mail del transportista" name="email" value="<?= $register ? $register->email : '' ?>">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="telefono">Telefono:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" readonly id="telefono" placeholder="Número de Teléfono" maxlength="11" name="telefono" onkeypress="return valida(event)" value="<?= $register ? $register->telefono : '' ?>">
                      </div>
                   </div> 					
                     <div class="form-group">
                      <label class="col-md-2 col-sm-2 control-label" for="email">Serial CP:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" readonly id="serial_cp" placeholder="Serial Carnet de la Patria" maxlength="10" name="serial_cp" value="<?= $register ? $register->serial_cp : '' ?>" onkeypress="return valida(event)" maxlength="11">
                      </div>
                      <label class="col-md-2 col-sm-2 control-label" for="cod_cp">Código CP:</label>
                      <div class="col-md-4">                     
                        <input class="form-control" readonly id="cod_cp" placeholder="Código del Carnet de la Patria" maxlength="10" name="cod_cp" onkeypress="return valida(event)" maxlength="11" value="<?= $register ? $register->cod_cp : '' ?>">
                      </div>
                   </div>                    
                   <div class="form-group">
						<div class="col-md-4 col-sm-4 col-sm-offset-3 col-md-offset-3">
							<button id="boton_enviar" disabled type="submit" class="btn btn-danger btn-block">Guardar&nbsp;<i class="fa fa-send"></i></button>
						</div>
						<div class="col-md-4 col-sm-4">
							<button id="boton_limpiar" disabled type="reset" class="btn btn-info btn-block">Limpiar&nbsp;<i class="fa fa-send"></i></button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</section>

<?
             }	include_once $_SESSION['base_url'].'partials/footer.php';
?>
<script>
    
function Modificar(){
    
var cedula = document.getElementById("cedula").value;
    
    if(cedula.length > 0){
    
					$("#nacionalidad").prop('disabled',true);
					$("#cedula").prop('readonly',true);
					$("#nombre").prop('readonly',false)
					$("#apellido").prop('readonly',false)
                    $("#email").prop('readonly',false)
                    $("#telefono").prop('readonly',false)
                    $("#serial_cp").prop('readonly',false)
                    $("#cod_cp").prop('readonly',false)
                    $("#boton_enviar").prop('disabled',false)
                    $("#boton_limpiar").prop('disabled',false)	    }

}
    window.onload = Modificar;

$(function(){
    
  


    
    $('#fecha_decreto').datepicker({
    format: 'dd/mm/yyyy',
    todayBtn: "linked",
    language: "es",
    autoclose: true,
    todayHighlight: true
});    
/*    $("#cedula").blur(function(){
        
        
        
      var usu = +$("#estado").val()+$("#municipio").val()+$("#cedula").val();
        $("#usuario").val(usu);      
        $("#qr").val("assets/images/Qr/afiliados/"+usu+".png");
    })
*/
    
    $("#cedula").blur(function(){
        
			var ced = $(this).val()
            var naci = document.getElementById("nacionalidad").value;
        
        $.getJSON('./operaciones.php',{ced: ced, naci: naci, action: 'buscar_persona'}, function(data)
			{	
				if(Object.keys(data).length  > 0)
				
				{
                    var usu = +$("#estado").val()+$("#municipio").val()+$("#cedula").val();
        //$("#usuario").val(usu);      
                    $("#qr").val("assets/images/Qr/afiliados/"+usu+".png");
                    if(data[0].count > 0){
					$("#usuario").val(usu+'-'+data[0].count).prop('readonly',true)
                        
                    }else{
                        					
                    $("#usuario").val(usu).prop('readonly',true)

                    }
					$("#nombre").val(data[0].nombre).prop('readonly',true)
					$("#apellido").val(data[0].apellido).prop('readonly',true)
                    $("#email").prop('readonly',false)
                    $("#telefono").prop('readonly',false)
                    $("#serial_cp").prop('readonly',false)
                    $("#cod_cp").prop('readonly',false)
                    $("#boton_enviar").prop('disabled',false)
                    $("#boton_limpiar").prop('disabled',false)

				}
				else
				{
                    var usu = +$("#estado").val()+$("#municipio").val()+$("#cedula").val();
                    
					$("#nombre").prop('readonly',false)
					$("#usuario").val(usu).prop('readonly',true)
					$("#apellido").prop('readonly',false)
                    $("#email").prop('readonly',false)
                    $("#telefono").prop('readonly',false)
                    $("#serial_cp").prop('readonly',false)
                    $("#cod_cp").prop('readonly',false)
                    $("#boton_enviar").prop('disabled',false)
                    $("#boton_limpiar").prop('disabled',false)				}		
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
                    window.location.replace('./incluir_afiliado.php')        
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
                toastr.error('El Usuario ya se encuentra registrado en esta linea de transporte', 'Error!')
			}
		})
		
	})
    
    });
</script>
