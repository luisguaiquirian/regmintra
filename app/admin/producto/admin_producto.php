<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
	include_once $_SESSION['base_url'].'partials/header.php';

	/*$system->sql="select * from rubros";
  	$elem = $system->sql();*/

?>
	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
            <h4 class="panel-title text-center"><i class="fa fa-list-list" aria-hidden="true"></i>&nbsp;Administrar Productos</h4>
		</header>
		<div class="panel-body">
			<form action="./operaciones.php" id="form_registrar_producto" method="POST">
		 
				<div id="msj-subtipos"  style="display: none" class="alert alert-warning alert-dismissible" role="alert">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <h5><span id="msj_sol"><strong>Este tipo no tiene ningún Sub-Tipo registrado.</strong> Puede resgitrarlo en el siguiente enlace <a href="<?= $_SESSION['base_url1'].'app/admin/producto/admin_tipos_subtipos.php' ?>">Registrar</a> o dirigirse a <strong>Productos > Tablas de Configuración -> Tipos y Subtipos -> Pestaña Sub-Tipos de Producto</strong></span></h5>
				</div>

				<input type="hidden" name="id_producto" id="id_producto" value="">
				<input type="hidden" id="action" name="action" value="admin_producto">
                
				<div class="form-group">
					<div class="col-xs-12 col-md-3">
						<label for=""><span class="text-danger">*</span>Tipo&nbsp;<a data-tool="tooltip" title="Agregar Tipo de Producto" href="<?= $_SESSION['base_url1'].'app/admin/producto/admin_tipos_subtipos.php' ?>"><i class="fa fa-plus-square"></i></a></label>
						<select class="form-control" id="tipo" name="tipo">
								<option value="">Seleccione tipo</option>
								<?php
									$system->sql="select * from rubros";
									foreach ($system->sql() as $rs){
										echo '<option value="'.$rs->id.'">'.$rs->descripcion.'</option>';
									}
								?>
						</select>
					</div>
					<div class="col-md-3 col-xs-12">
						<label for=""><span class="text-danger">*</span>Sub-tipo&nbsp;<a data-tool="tooltip" title="Agregar Sub-Tipo de Producto" href="<?= $_SESSION['base_url1'].'app/admin/producto/admin_tipos_subtipos.php' ?>"><i class="fa fa-plus-square"></i></a></label>
						<select class="form-control" id="subtipo" name="subtipo">
						</select>
	            	</div>
	            	<div class="col-md-3 col-xs-12">
						<label for=""><span class="text-danger">*</span>Presentacion</label>
						<select class="form-control" id="presentacion" name="presentacion">
							<option>Seleccione una presentación</option>
							<?php
								$system->sql="select * from presentaciones where estatus=7";
								foreach ($system->sql() as $rs){
									echo '<option value="'.$rs->id.'">'.$rs->descripcion.'</option>';
								}
							?>
						</select>
	            	</div>
	            	<div class="col-md-3 col-xs-12">
						<label for=""><span class="text-danger">*</span>Estatus</label>
						<select class="form-control" id="estatus" name="estatus">
							<option value="7">Activo</option>
						  	<option value="8">Inactivo</option>
						</select>
	                </div>
				</div>
				<div class="form-group">
					<div class="col-md-4 col-xs-12">
					    <label for=""><span class="text-danger">*</span>Codigo</label>
					    <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Codigo unico">
                	</div>
                	<div class="col-md-8 col-xs-12">
					    <label for=""><span class="text-danger">*</span>Descripción</label>
					    <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripcion del producto">	
                	</div>
				</div>
				<div class="form-group">
					<div class="col-md-4 col-xs-12">
					    <label for="marca"><span class="text-danger">*</span>Marca</label>
					    <input type="text" class="form-control" id="marca" name="marca" placeholder="marca del producto">	
	                </div>

	                <div class="col-md-4 col-xs-12">
					    <label for="modelo"><span class="text-danger">*</span>Modelo</label>
					    <input type="text" class="form-control" id="modelo" name="modelo" placeholder="modelo del producto">	
	                </div>	

	                <div class="col-md-4 col-xs-12">
					    <label for="modelo"><span class="text-danger">*</span>Precio</label>
					    <input type="text" class="form-control" id="precio" name="precio" onkeyup="validar_precio();" placeholder="Precio del producto">
					    <p class="help-block">Utilice punto (.) para identificar los decimales.</p>	
	                </div>	
				</div>

                <hr>
					<button type="submit" class="btn btn-danger">Guardar&nbsp;<i class="fa fa-send"></i></button>
					<button id="clean_producto" type="reset" class="btn btn-info">Limpiar&nbsp;<i class="fa fa-send"></i></button>
			</form>
			<span class="label label-default"><i class="fa fa-info-circle"></i>&nbsp;verifique antes que las tablas de configuracion esten ya definidas ya que todos los campos son obligatorios.</span>
		</div>
	</section>


	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
            <h4 class="panel-title text-center"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;Listado de Productos</h4>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped mb-none table-condensed" id="datatable-editable">
				<thead><tr>
					<th>Tipo/Subtipo</th>
					<th>Codigo</th>
					<th>Descripcion</th>
					<th>Presentacion</th>
					<th>Marca</th>
					<th>Modelo</th>
					<th>Precio</th>
					<th>Estatus</th>
					<th>Acciones</th>
				</tr></thead>
				<tbody id="cont_tabla_producto">
					<?php
						$system->sql = "select a.*,b.descripcion as rubro,c.descripcion as subrubro,d.descripcion as dpresentacion,e.descripcion as destatus from productos as a inner join rubros as b on (a.tipo=b.id) inner join rubros_sub as c on (a.subtipo=c.id) inner join presentaciones as d on (a.presentacion=d.id) inner join estatus as e on (a.estatus=e.id)";
						foreach ($system->sql() as $rs){
							echo '<tr id="fila_'.$rs->id.'"><td>'.$rs->rubro.'/'.$rs->subrubro.'</td><td>'.$rs->codigo.'</td><td>'.$rs->descripcion.'</td><td>'.$rs->dpresentacion.'</td><td>'.$rs->marca.'</td><td>'.$rs->modelo.'</td><td>'.$rs->precio.'</td><td>'.$rs->destatus.'</td><td><a data-tool="tooltip" title="Editar" onclick="edit_producto('.$rs->id.')" class="btn btn-default" href="#" role="button"><i role="button" class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;<a data-tool="tooltip" title="Eliminar" onclick="delete_producto('.$rs->id.')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-ban" aria-hidden="true"></i></a>&nbsp;</td></tr>';
						}
					?>
				</tbody>
			</table>
		</div>
	</section>
<?
	include_once $_SESSION['base_url'].'partials/footer.php';
?>
<script>
	function validar_precio(){
		let precio = $("#precio").val();
        if (Math.sign(precio)!=-1 && Math.sign(precio)!=-0 && Math.sign(precio)!=NaN && isNaN(precio)!=true) {
          let calculo = parseFloat($("#precio").val())*parseFloat($("#cantidad_pro").val());
          $("#total").val(parseFloat(calculo).toFixed(2));
        }else{
          toastr.warning('El valor del precio no es un valor valido');
          $("#precio").val('');  
        }
    }


	$("#datatable-editable").DataTable();
	/***evento para buscar sub_rubro del rubro general***/
	$("#tipo").change(function(){
		if ($("#tipo").val()!='') {
	  		$.getJSON('../operaciones.php',{
			tipo:$("#tipo").val(),
			action: 'buscar_sub_tipo'
			}, function(data){	
				if(data.msg){
					let tam = data.r.length;
					let opt = '<option value="">Seleccione</option>';	
					for(let x = 0 ; x < tam; x++){
						opt += '<option value="'+data.r[x].id+'">'+data.r[x].descripcion+'</option>';
					}
					$("#msj-subtipos").hide();
					$("#subtipo").html(opt);
				}else{
					toastr.error(data.msj);
					$("#subtipo").html('');
					if (data.msj=='No existe subtipos registrados') {$("#msj-subtipos").show();}
				}
			})
  		}else{$("#msj-subtipos").hide();$("#subtipo").html('');}
	});
	/*limpiar detalles*/
	$("#clean_producto").click(function(){$("#msj-subtipos").hide();$("#subtipo").html('');});
	/*registro*/
	$('#form_registrar_producto').submit(function(e) {
		e.preventDefault()
            if (
            	($("#tipo").val() == null || $("#tipo").val().length == 0 || /^\s+$/.test($("#tipo").val())) ||
            	($("#subtipo").val() == null || $("#subtipo").val().length == 0 || /^\s+$/.test($("#subtipo").val())) ||
            	($("#presentacion").val() == null || $("#presentacion").val().length == 0 || /^\s+$/.test($("#presentacion").val())) ||
            	($("#estatus").val() == null || $("#estatus").val().length == 0 || /^\s+$/.test($("#estatus").val())) ||
            	($("#codigo").val() == null || $("#codigo").val().length == 0 || /^\s+$/.test($("#codigo").val())) ||
            	($("#descripcion").val() == null || $("#descripcion").val().length == 0 || /^\s+$/.test($("#descripcion").val())) ||
            	($("#marca").val() == null || $("#marca").val().length == 0 || /^\s+$/.test($("#marca").val())) ||
            	($("#modelo").val() == null || $("#modelo").val().length == 0 || /^\s+$/.test($("#modelo").val()))
            ) {
                toastr.error('Faltan campos por llenar.', 'Error!')
                return false;		
            }
            $.ajax({
			url: '../operaciones.php',
			type: 'POST',
			dataType: 'JSON',
			data: $(this).serialize(),
		})
		.done(function(data) {
			if(data.msg){
				$("#id_producto").val('');
				toastr.success(data.msj);
				$('#form_registrar_producto')[0].reset();
				let tam = data.r.length;	
				let tab='';
				for(let x = 0 ; x < tam; x++){
					tab += '<tr id="fila_'+data.r[x].id+'"><td>'+data.r[x].rubro+'/'+data.r[x].subrubro+'</td><td>'+data.r[x].codigo+'</td><td>'+data.r[x].descripcion+'</td><td>'+data.r[x].dpresentacion+'</td><td>'+data.r[x].marca+'</td><td>'+data.r[x].modelo+'</td><td>'+data.r[x].precio+'</td><td>'+data.r[x].destatus+'</td><td><a data-tool="tooltip" title="Editar" onclick="edit_producto('+data.r[x].id+')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;<a data-tool="tooltip" title="Eliminar" onclick="delete_producto('+data.r[x].id+')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-ban" aria-hidden="true"></i></a>&nbsp;</td></tr>'
				}
				$("#cont_tabla_producto").html(tab);
				$("#datatable-editable").DataTable();
			}else{    
                $("#id_producto").val('');
                $('#form_registrar_')[0].reset()
                toastr.error(data.msj, 'Error!')
			}
		})
		
	});
	function edit_producto(id){
    	$.getJSON('../operaciones.php',{
			id:id,
			action: 'traer_producto'
		}, function(data){	
			if(data.msg){
				toastr.info('Elemento cargado en el formulario para su modificacion');
				$("#id_producto").val(data.r[0].id);
				$("#codigo").val(data.r[0].codigo);
				$("#descripcion").val(data.r[0].descripcion);
				$("#tipo").val(data.r[0].tipo);
				$("#estatus").val(data.r[0].estatus);
				$("#presentacion").val(data.r[0].presentacion);
				let tam;
				let opt;
				/*cargamops municipio*/
					tam = data.s.length;
					if (tam>0) {
						let opt = '<option value="">Seleccione</option>';	
						for(let x = 0 ; x < tam; x++){
							opt += '<option value="'+data.s[x].id+'">'+data.s[x].descripcion+'</option>';
						}
						$("#subtipo").html(opt);
					}else{
						toastr.error('No hay municipios en BD');	
					}
				/*fin de carga*/
				$("#subtipo").val(data.r[0].subtipo);
				$("#modelo").val(data.r[0].modelo);
				$("#marca").val(data.r[0].marca);
				$("#precio").val(data.r[0].precio);
				$("#fec_reg").val(data.r[0].fec_reg);
			}else{
				$("#id_producto").val('');
				$("#codigo").val('');
				$("#descripcion").val('');
				$("#tipo").val('');
				$("#estatus").val('');
				$("#subtipo").val('');
				$("#modelo").val('');
				$("#marca").val('');
				$("#precio").val('');
				$("#fec_reg").val('');
				toastr.success(data.msj);
			}
		})
    }

    function delete_producto(id){
    	if(confirm('¿Esta seguro de eliminar este elemento?')){
  			$.getJSON('../operaciones.php',{
			id:btoa(id),
			action: 'eliminar_producto'
		}, function(data){	
			if(data.msg){
				toastr.warning(data.msj);
				$('#form_registrar_producto')[0].reset();
				$("#fila_"+id).remove();//remover fila
			}else{
				toastr.error(data.msj);
				$('#form_registrar_producto')[0].reset();
			}
		})
  		}
    }
</script>
