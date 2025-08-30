<?
if(!isset($_SESSION))
{
	session_start();
}


?>

    <div id="modal_cuenta" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="background-color: #C92020; color: white;">
                    <h4 class="modal-title">Cambiar Contraseña por Defecto&nbsp;<i class="fa fa-warning"></i></h4>
                </div>
                <!-- #076DF7 -->
                <form action="" id="form_password_reset">
                    <input type="hidden" name="action" value="change_password_default">
                    <br>
                    <h4 class="text-center">Es importante que reestablezca su contraseña para la seguridad de la cuenta.</h4>
                    <div class="modal-body">
                       	<div class="form-group">
						<label for="" class="control-label col-md-2 col-sm-2">Nacionalidad</label>
						<div class="col-md-4 col-sm-4">
							<select name="nacionalidad" id="nacionalidad" class="form-control" required="">
								<option value="V">V</option>
								<option value="E">E</option>
							</select>
						</div>
						<label for="" class="control-label col-md-2 col-sm-2">Cédula</label>
						<div class="col-md-4 col-sm-4">
							<input type="text" class="form-control" required="" id="cedula" name="cedula">
						</div>
					</div>
					<div class="form-group">
						<label for="" class="control-label col-md-2 col-sm-2">Nombre</label>
						<div class="col-md-4 col-sm-4">
							<input type="text" class="form-control" required="" id="nombre" name="nombre">
						</div>
						<label for="" class="control-label col-md-2 col-sm-2">Apellido</label>
						<div class="col-md-4 col-sm-4">
							<input type="text" class="form-control" required="" id="apellido" name="apellido">
						</div>
					</div>
					<div class="form-group">
						<label for="" class="control-label col-md-2 col-sm-2">Teléfono</label>
						<div class="col-md-4 col-sm-4">
							<input type="text" class="form-control" required="" id="telefono" name="telefono" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" placeholder="Número de Teléfono" maxlength="12">
						</div>
						<label for="" class="control-label col-md-2 col-sm-2">E-mail</label>
						<div class="col-md-4 col-sm-4">
							<input type="email" class="form-control" required="" id="email" name="email" placeholder="Correo electrónico">
						</div>
					</div>
					<div class="form-group">
						<label for="direccion" class="control-label col-md-2 col-sm-2">Dirección</label>
				        <div class="col-md-10 col-sm-10">
                        <textarea class="form-control" id="direccion" placeholder="Dirección de habitación" name="direccion" onKeyUp="this.value=this.value.toUpperCase();"></textarea>
					</div>
                        <div class="form-group">
                            <label for="" class="control-label col-md-3">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required="">
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-md-3">Repita Contraseña</label>
                            <input type="password" class="form-control" id="password_repeat" required="">
                        </div>
                    </div>
                    <!-- fin modal-body -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Guardar&nbsp;<i class="fa fa-send"></i></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
            <!-- fin modal-content -->
        </div>
        <!-- fin modal-dialog -->
    </div>
    <!-- fin modal -->

    <script>
       $("#cedula").blur(function(){
			var ced = $(this).val()
			$.getJSON('../municipio/operaciones.php',{ced: ced, action: 'buscar_persona'}, function(data)
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
        
        let pass = '<?= $_SESSION["pass_activo"] ?>'

        if (pass === '0') {
            $('#modal_cuenta').modal('show')
        }

        $('#form_password_reset').submit(function(e) {
            e.preventDefault()

            let password = $('#password').val(),
                repeat = $('#password_repeat').val()
            if (password !== repeat) {
                toastr.error('Las contraseñas no coinciden', 'Error!')
                return false
            }

            $.ajax({
                    url: '<?= $_SESSION["base_url1"]."app/municipio/operaciones.php" ?>',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $(this).serialize(),
                })
                .done(function(data) {
                    if (data.r) {
                        toastr.success('Sus datos han sido actualizados', 'Éxito!')
                        $('#modal_cuenta').modal('hide')
                        location.reload();
                    } else {
                        toastr.error('Ha ocurrido un error al tratar de ejecutar la operación', 'Error!')
                    }
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                });

        });
    </script>