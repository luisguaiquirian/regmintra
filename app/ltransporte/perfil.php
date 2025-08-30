<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}

	include_once $_SESSION['base_url'].'partials/header.php';
    
		$system->sql = "SELECT
users.*,
perfiles.nombre as perfil
FROM
users
INNER JOIN perfiles ON perfiles.id = users.perfil
WHERE
users.id = ".$_SESSION['user_id'];
		
		$register = $system->sql();

?>

					<div class="row">
						<div class="col-md-4 col-lg-4">

							<section class="panel">
								<div class="panel-body">
									<div class="thumb-info mb-md text-center">
										<img src="<?= $_SESSION['base_url1'].'assets/images/fotos/'.$_SESSION['foto'] ?>" class="rounded img-responsive" alt="John Doe">
										<div class="thumb-info-title">
											<span class="thumb-info-inner"><?= $register[0]->nombre." ".$register[0]->apellido ?></span>
											<span class="thumb-info-type"><?= $register[0]->perfil ?></span>
										</div>
									</div>


									<div class="widget-toggle-expand mb-md">
										<div class="widget-header">
											<h6>Cargar Foto de perfil</h6>
											<div class="widget-toggle">+</div>
										</div>
<!--
										<div class="widget-content-collapsed">
											<div class="progress progress-xs light">
												<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
													60%
												</div>
											</div>
										</div>
-->
										<div class="widget-content-expanded">
                                        <form name="foto_perfil" method="post" action="./operacion_foto.php" enctype="multipart/form-data">
                                        <input type="hidden" id="action" name="action" value="<?= $register ? 'update_foto' : '' ?>">
                                        <input type="hidden" name="foto" value="<?= $register[0]->usuario ?>">
                                        <input type="hidden" name="id_modificar" value="<?= $register ? $register[0]->id : '' ?>">
                                        
                                           <div class="form-group">
                                            <input class="input-file" type="file" name="tempo" required>
                                            </div>
                                           <div class="form-group">
                                            <div class="col-md-12 col-sm-12">
                                                <button type="submit" class="btn btn-info btn-block">Cargar&nbsp;<i class="fa fa-send"></i></button>
                                            </div>                                            
                                            </div>
                                        </form>   

										</div>
									</div>
<!--
									<h6 class="text-muted">About</h6>
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam quis vulputate quam. Interdum et malesuada</p>
-->



								</div>
							</section>
						</div>
						<div class="col-md-8 col-lg-8">

							<div class="tabs">
								<ul class="nav nav-tabs tabs-primary">
									<li class="active">
										<a href="#edit" data-toggle="tab">Editar datos del usuario</a>
									</li>
                                    <?php if($_SESSION['nivel']==5){?>
									<li>
										<a href="#carnet" data-toggle="tab">Carnet del usuario</a>
									</li>
                                    <?php }?>
									<li>
										<a href="#change_password" data-toggle="tab">Cambio de clave</a>
									</li>
								</ul>
								<div class="tab-content">
									<div id="edit" class="tab-pane active">

										<form name="perfil" class="form-horizontal" method="post" action="./operaciones.php">
            		                    <input type="hidden" name="action" value="update_datos_usuario">
                                        <input type="hidden" name="id_modificar" value="<?= $register ? $register[0]->id : '' ?>">

											<h4 class="mb-xlg">Información Personal</h4>
											<fieldset>
												<div class="form-group">
													<label class="col-md-3 control-label" for="nombre">Nombre</label>
													<div class="col-md-8">
														<input type="text" name="nombre" class="form-control" id="nombre" value="<?= $register[0]->nombre ?>" onKeyUp="this.value=this.value.toUpperCase();">
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label" for="apellido">Apellido</label>
													<div class="col-md-8">
														<input type="text" class="form-control" name="apellido" id="apellido" value="<?= $register[0]->apellido ?>" onKeyUp="this.value=this.value.toUpperCase();">
													</div>
												</div>
													<div class="form-group">
													<label class="col-md-3 control-label" for="telefono">Teléfono</label>
													<div class="col-md-8">
														<input type="text" class="form-control" name="telefono" id="telefono" value="<?= $register[0]->telefono ?>" placeholder="Número de Teléfono" maxlength="11" name="telefono" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
													</div>
												</div>
													<div class="form-group">
													<label class="col-md-3 control-label" for="email">email</label>
													<div class="col-md-8">
														<input type="text" class="form-control" name="email" id="email" value="<?= $register[0]->email ?>" onKeyUp="this.value=this.value.toLowerCase();">
													</div>
												</div>
													<div class="form-group">
													<label class="col-md-3 control-label" for="direccion">Dirección</label>
													<div class="col-md-8">
														<input type="text" class="form-control" name="direccion" id="direccion" value="<?= $register[0]->direccion ?>" onKeyUp="this.value=this.value.toUpperCase();">
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label" for="nombre_linea">Línea de transporte</label>
													<div class="col-md-8">
														<input type="text" class="form-control" id="nombre_linea" name="nombre_linea" value="<?= $register[0]->nombre_linea ?>" disabled>
													</div>
												</div>
											</fieldset>
<!--											<hr class="dotted tall">

											<h4 class="mb-xlg">About Yourself</h4>
											<fieldset>
												<div class="form-group">
													<label class="col-md-3 control-label" for="profileBio">Biographical Info</label>
													<div class="col-md-8">
														<textarea class="form-control" rows="3" id="profileBio"></textarea>
													</div>
												</div>
												<div class="form-group">
													<label class="col-xs-3 control-label mt-xs pt-none">Public</label>
													<div class="col-md-8">
														<div class="checkbox-custom checkbox-default checkbox-inline mt-xs">
															<input type="checkbox" checked="" id="profilePublic">
															<label for="profilePublic"></label>
														</div>
													</div>
												</div>
											</fieldset>
											<hr class="dotted tall">
-->
                							<div class="panel-footer">
												<div class="row">
													<div class="col-md-9 col-md-offset-5">
														<button type="submit" class="btn btn-primary">Enviar</button>
<!--														<button type="reset" class="btn btn-default">Reset</button>-->
													</div>
												</div>
											</div>

										</form>

									</div>	
                                    <?php if($_SESSION['nivel']==5){?>
									<div id="carnet" class="tab-pane">
										<h4 class="mb-md">Carnet de Usuario</h4>
                                        <a class="btn btn-primary btn-lg text-center" href="<?php echo $_SESSION['base_url1'].'app/gen_carnet.php?u='.base64_encode($_SESSION['user_id'])?>"  target="_blank" role="button">Generar Carnet&nbsp;<i class="fa fa-file"></i></a>

<!--
										<section class="simple-compose-box mb-xlg">
											<form method="get" action="/">
												<textarea name="message-text" data-plugin-textarea-autosize placeholder="What's on your mind?" rows="1"></textarea>
											</form>
											<div class="compose-box-footer">
												<ul class="compose-toolbar">
													<li>
														<a href="#"><i class="fa fa-camera"></i></a>
													</li>
													<li>
														<a href="#"><i class="fa fa-map-marker"></i></a>
													</li>
												</ul>
												<ul class="compose-btn">
													<li>
														<a class="btn btn-primary btn-xs">Post</a>
													</li>
												</ul>
											</div>
										</section>
-->
									</div>
                                    <?php }?>
									<div id="change_password" class="tab-pane">
										<h4 class="mb-md">Cambio de clave del usuario</h4>
                                        <form action="./operaciones" id="form_password_reset" method="post" name="form_password_reset">
                                            <input type="hidden" name="action" value="change_password">
                                            <br>
                                               <div class="form-group">
                                                    <label for="" class="control-label col-md-3">Contraseña</label>
                                                    <input type="password" class="form-control" id="password" name="password" required="">
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="control-label col-md-3">Repita Contraseña</label>
                                                    <input type="password" class="form-control" id="password_repeat" required="">
                                                </div>
                							<div class="panel-footer">
												<div class="row">
													<div class="col-md-9 col-md-offset-5">
														<button type="submit" class="btn btn-primary">Enviar</button>
<!--														<button type="reset" class="btn btn-default">Reset</button>-->
													</div>
												</div>
											</div>
                                        </form>
									</div>
								</div>
							</div>
						</div>
					</div>


<?
	include_once $_SESSION['base_url'].'partials/footer.php';
?>
<script>
        $('#form_password_reset').submit(function(e) {
            e.preventDefault()

            let password = $('#password').val(),
                repeat = $('#password_repeat').val()
           
            if (password !== repeat) {
                toastr.error('Las contraseñas no coinciden', 'Error!')
                return false
            }

            $.ajax({
                    url: 'operaciones.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $(this).serialize(),
                })
                .done(function(data) {
                    if (data.r) {
                        toastr.success('Sus datos han sido actualizados', 'Éxito!')
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

    $('#perfil').submit(function(e) {
            e.preventDefault()

            $.ajax({
                    url: 'ltransporte/operaciones.php',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $(this).serialize(),
                })
                .done(function(data) {
                    if (data.r) {
                        toastr.success('Sus datos han sido actualizados', 'Éxito!')
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
    
    $(function(){
    
     $(function(){
        $("input[name='tempo']").on("change", function(){
            var formData = new FormData($("#foto_perfil")[0]);
            var ruta = "usuario/operacion_foto.php";
            $.ajax({
                url: ruta,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(datos)
                {
                    $("#respuesta").html(datos);
                }
            });
        });
     });
 
 /*   $('#perfil').submit(function(e) {
		e.preventDefault()

		$.ajax({
			url: 'usuario/operaciones.php',
			type: 'POST',
			dataType: 'JSON',
			data: $(this).serialize(),
		})
		.done(function(data) {
			if(data.r)
			{
				let action = $('#action').val()

				if(action === "update_datos_usuario")
				{
	                toastr.success('Datos Actualizados!', 'Éxito!')					
                    window.location.replace('../perfil.php')        
				}
	                
			}
		})
		
	})   */     
    
    });
</script>