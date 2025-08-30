<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
	include_once $_SESSION['base_url'].'partials/header.php';

	$system->sql="select id,procedencia,descripcion from procedencias where estatus=7";
  	$proc = $system->sql();
  	$system->sql="select id,descripcion from productos where estatus=7";
  	$prod = $system->sql();
  	$system->sql="select a.id,a.nombre,b.descripcion from almacenes as a inner join almacenes_nivel as b on (a.nivel=b.id) where a.estatus=7";
  	$alma = $system->sql();

?>
	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
            <h4 class="panel-title text-center"><i class="fa fa-list-list" aria-hidden="true"></i>&nbsp;Administrar Entradas Productos</h4>
		</header>
		<div class="panel-body">
			<form action="./operaciones.php" id="form_registrar_entrada" method="POST">

				<?php if(count($proc)==0){?>
					<div id="msj-procedencia" class="alert alert-warning alert-dismissible text-justify" role="alert">
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					  <h5><span>El sistema no tiene registro alguno aun de ningun <strong>procedencia</strong>, para registrar una entrada de producto debe tener estos datos configurados. Para configurar estos datos debe ir a el menu<strong> Productos -> Tablas de Configuración -> Procedencias.</strong> Tambien puede ir a la configuración con el siguiente enlace: <a href="<?= $_SESSION['base_url1'].'app/admin/producto/admin_procedencias.php' ?>">Registrar</a></h5>
					</div>
				<?php } ?>
		 
				<?php if(count($prod)==0){?>
					<div id="msj-producto" class="alert alert-warning alert-dismissible text-justify" role="alert">
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					  <h5><span>El sistema no tiene registro alguno aun de ningun <strong>producto</strong>, para registrar una entrada de producto debe tener estos datos configurados. Para configurar estos datos debe ir a el menu<strong> Productos -> Registrar Producto.</strong> Tambien puede ir a la configuración con el siguiente enlace: <a href="<?= $_SESSION['base_url1'].'app/admin/producto/admin_producto.php' ?>">Registrar</a></h5>
					</div>
				<?php } ?>		

				<?php if(count($alma)==0){?>
					<div id="msj-almacen" class="alert alert-warning alert-dismissible text-justify" role="alert">
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					  <h5><span>El sistema no tiene registro alguno aun de ninguna <strong>almacen</strong>, para registrar una entrada de producto debe tener estos datos configurados. Para configurar estos datos debe ir a el menu<strong> Almacenes -> Administrar Almacenes.</strong> Tambien puede ir a la configuración con el siguiente enlace: <a href="<?= $_SESSION['base_url1'].'app/admin/producto/admin_almacenes.php' ?>">Registrar</a></h5>
					</div>
				<?php } ?>	

				<div id="msj-pre-mod" style="display: none" class="alert alert-warning alert-dismissible text-justify" role="alert">
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					  <h5><span><strong>Advertencia</strong>, las modificaciones y eliminaciones de estos registros deben ser tomados con mucho cuidado, podrian estar poniendo en riesgo la integridad de los datos. Para modificar producto, almacen, Nº de documento o cantidad dentro de los registro de entrada debe proceder primero a eliminarlo, luego crear uno nuevo registro</a></h5>
				</div>
				<div id="msj-ale-com" style="display: none" class="alert alert-danger alert-dismissible text-justify" role="alert">
					  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					  <h5><span><strong>Error</strong>, no se puede realizar dicha acción porque existen asignaciones que comprometen la integridad del inventario,PORFAVOR verifique bien los datos, en caso de ser correcto debe antes deshacer  las asignaciones que realizaron del producto en el almacen.</a></h5>
				</div>

				<input type="hidden" name="id_entrada" id="id_entrada" value="">
				<input type="hidden" id="action" name="action" value="admin_entrada">
                
				<div class="form-group">
					<div class="col-xs-12 col-md-4">
						<label for=""><span class="text-danger">*</span>Procedencia&nbsp;<a data-tool="tooltip" title="Agregar Procedencia" href="<?= $_SESSION['base_url1'].'app/admin/producto/admin_procedencias.php' ?>"><i class="fa fa-plus-square"></i></a></label>
						<select class="form-control" id="procedencia" name="procedencia">
								<option value="">Seleccione tipo</option>
								<?php
									foreach ($proc as $rs){
										echo '<option value="'.$rs->id.'">'.$rs->procedencia.' / '.$rs->descripcion.'</option>';
									}
								?>
						</select>
					</div>

					<div class="col-xs-12 col-md-6">
						<label for=""><span class="text-danger">*</span>Producto&nbsp;<a data-tool="tooltip" title="Agregar Producto" href="<?= $_SESSION['base_url1'].'app/admin/producto/admin_producto.php' ?>"><i class="fa fa-plus-square"></i></a></label>
						<select class="form-control" id="producto" name="producto">
								<option value="">Seleccione tipo</option>
								<?php
									foreach ($prod as $rs){
										echo '<option value="'.$rs->id.'">'.$rs->descripcion.'</option>';
									}
								?>
						</select>
						<p class="help-block"><a id="lista_productos" name="lista_productos" href="#modalProductoSelect" data-toggle="modal" class="text-primary text-uppercase"><i role="button" class="fa fa-eye" aria-hidden="true"></i>&nbsp;Ver Productos</a></p>
					</div>

					<div class="col-md-2 col-xs-12">
					    <label for=""><span class="text-danger">*</span>cantidad</label>
					    <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="Cantidad">	
                	</div>

					
				</div>

				<div class="form-group">

					<div class="col-xs-12 col-md-6">
						<label for=""><span class="text-danger">*</span>Almacen&nbsp;<a data-tool="tooltip" title="Agregar Almacen" href="<?= $_SESSION['base_url1'].'app/admin/producto/admin_almacenes.php' ?>"><i class="fa fa-plus-square"></i></a></label>
						<select class="form-control" id="almacen" name="almacen">
								<option value="">Seleccione tipo</option>
								<?php
									foreach ($alma as $rs){
										echo '<option value="'.$rs->id.'">'.$rs->descripcion.' / '.$rs->nombre.'</option>';
									}
									unset($proc,$prod,$alma);//eliminamos variables suras
								?>
						</select>
					</div> 
                	
                	<div class="col-md-3 col-xs-12">
					    <label for="marca"><span class="text-danger">*</span>Fecha (DOT)</label>
					    <input type="date" class="form-control" id="dot_fecha" name="dot_fecha" placeholder="Fecha de Creacion">	
	                </div>

                	<div class="col-md-3 col-xs-12">
					    <label for="modelo"><span class="text-danger">*</span>Lote (DOT)</label>
					    <input type="text" class="form-control" id="dot_lote" name="dot_lote" placeholder="Lote del Producto">	
	                </div>
                	
				</div>
				<div class="form-group">
					<div class="col-md-4 col-xs-12">
					    <label for=""><span class="text-danger">*</span>N° de Documento</label>
					    <input type="text" class="form-control" id="nro_documento" name="nro_documento" placeholder="Numero de Documento">
                	</div>
					<div class="col-md-3 col-xs-12">
						<label for="fec_reg"><span class="text-danger">*</span>Fecha de registro</label>
						<input type="date" class="form-control" id="fec_reg" name="fec_reg">	
					</div>
				</div>

                <hr>
					<button type="submit" class="btn btn-danger">Guardar&nbsp;<i class="fa fa-send"></i></button>
					<button id="clean_entrada" type="reset" class="btn btn-info">Limpiar&nbsp;<i class="fa fa-send"></i></button>
			</form>
			<span class="label label-default"><i class="fa fa-info-circle"></i>&nbsp;Esta entrada permite registrar las adquisiciones de los productos al inventario.</span>
		</div>
	</section>


	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
            <h4 class="panel-title text-center"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;Listado de Entradas a Inventario</h4>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped mb-none table-condensed tablita_entrada" id="datatable-editable">
				<thead><tr>
					<th>Procedencia</th>
					<th>Producto</th>
					<th>Cantidad</th>
					<th>Almacen</th>
					<th>(DOT) Fecha</th>
					<th>(DOT) Lote</th>
					<th>N° Doc.</th>
					<th>Fec. Registro</th>
					<th>Acciones</th>
				</tr></thead>
				<tbody id="cont_tabla_entrada">
					<?php
						$system->sql = "select a.*,b.descripcion,c.procedencia,c.descripcion as pdes,d.nombre,e.descripcion as ndes from productos_entrada as a inner join productos as b on (a.producto=b.id) inner join procedencias as c on (a.procedencia=c.id) inner join almacenes as d on (a.almacen=d.id) inner join almacenes_nivel as e on (d.nivel=e.id)";
						foreach ($system->sql() as $rs){
							echo '<tr id="fila_'.$rs->id.'"><td>'.$rs->procedencia.'/'.$rs->pdes.'</td><td>'.$rs->descripcion.'</td><td>'.$rs->cantidad.'</td><td>'.$rs->ndes.'/'.$rs->nombre.'</td><td>'.$rs->dot_fecha.'</td><td>'.$rs->dot_lote.'</td><td>'.$rs->nro_documento.'</td><td>'.$rs->fec_reg.'</td><td>&nbsp;<a data-tool="tooltip" title="Eliminar" onclick="delete_entrada('.$rs->id.')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-ban" aria-hidden="true"></i></a>&nbsp;</td></tr>';
						}
					?>
				</tbody>
			</table>
		</div>
	</section>
<?

	include_once $_SESSION['base_url'].'app/admin/producto/modales/modal_producto.php';
	include_once $_SESSION['base_url'].'partials/footer.php';
?>
<script>
	$(document).ready(function(){
		$("#datatable-editable").DataTable();
		//$("#datatable-editable_2").DataTable();
	});
	$("#clean_entrada").click(function(){$("#msj-pre-mod").hide();$("#msj-ale-com").hide();});
	
	$("#lista_productos").click(function(){
		$.getJSON('../operaciones.php',{
			action: 'traer_lista_producto'
		}, function(data){	
			if(data.msg){
				let tam = data.r.length;	
				let tab='';
				for(let x = 0 ; x < tam; x++){
					tab += '<tr id="fila_'+data.r[x].id+'"><td>'+data.r[x].rubro+'/'+data.r[x].subrubro+'</td><td>'+data.r[x].codigo+'</td><td>'+data.r[x].descripcion+'</td><td>'+data.r[x].dpresentacion+'</td><td>'+data.r[x].marca+'</td><td>'+data.r[x].modelo+'</td><td>'+data.r[x].destatus+'</td><td><a data-tool="tooltip" title="Seleccionar" onclick="select_producto('+data.r[x].id+')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-check-square" aria-hidden="true"></i></a>&nbsp;</td></tr>';
				}
				$("#lista_producto").html(tab);
				$("#datatable-editable_2").DataTable();
			}else{
				toastr.success(data.msj);
			}
		})
	});
	function select_producto(id){
		$("#producto").val(id);
		toastr.info('Elemento Seleccionado');
		$('#modalProductoSelect').modal('hide');
		$("#datatable-editable").DataTable();
	}
	$('#form_registrar_entrada').submit(function(e) {
		e.preventDefault()
            if (
            	($("#procedencia").val() == null || $("#procedencia").val().length == 0 || /^\s+$/.test($("#procedencia").val())) ||
            	($("#producto").val() == null || $("#producto").val().length == 0 || /^\s+$/.test($("#producto").val())) ||
            	($("#almacen").val() == null || $("#almacen").val().length == 0 || /^\s+$/.test($("#almacen").val())) ||
            	($("#nro_documento").val() == null || $("#nro_documento").val().length == 0 || /^\s+$/.test($("#nro_documento").val())) ||
            	($("#cantidad").val() == null || $("#cantidad").val().length == 0 || /^\s+$/.test($("#cantidad").val())) ||
            	($("#dot_fecha").val() == null || $("#dot_fecha").val().length == 0 || /^\s+$/.test($("#dot_fecha").val())) ||
            	($("#dot_lote").val() == null || $("#dot_lote").val().length == 0 || /^\s+$/.test($("#dot_lote").val())) ||
            	($("#fec_reg").val() == null || $("#fec_reg").val().length == 0 || /^\s+$/.test($("#fec_reg").val()))
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
				$("#msj-pre-mod").hide();
				$("#msj-ale-com").hide();
				if (data.msj=="modificar") {
					$("#fila_"+data.ide).remove();//remover fila
					toastr.success(data.msj2);	
					$("#action").val('admin_entrada');
				}else{
					toastr.success(data.msj);
				}
				$('#form_registrar_entrada')[0].reset();

				var t = $('.tablita_entrada').DataTable();
			    t.row.add( [
		            data.r[0].procedencia+'/'+data.r[0].pdes,
		            data.r[0].descripcion,
		            data.r[0].cantidad,
		            data.r[0].ndes+'/'+data.r[0].nombre,
		            data.r[0].dot_fecha,
		            data.r[0].dot_lote,
		            data.r[0].nro_documento,
		            data.r[0].fec_reg,
		            '&nbsp;<a data-tool="tooltip" title="Eliminar" onclick="delete_entrada('+data.r[0].id+')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-ban" aria-hidden="true"></i></a>&nbsp;',
		        ] ).draw( false );


				$( "#producto" ).prop( "disabled", false );
				$( "#almacen" ).prop( "disabled", false );
				$( "#nro_documento" ).prop( "disabled", false );
				$( "#cantidad" ).prop( "disabled", false );
			}else{
				$( "#producto" ).prop( "disabled", false );
				$( "#almacen" ).prop( "disabled", false );
				$( "#nro_documento" ).prop( "disabled", false );
				$( "#cantidad" ).prop( "disabled", false );
				$("#msj-pre-mod").hide();  
                $('#form_registrar_entrada')[0].reset()
                $("#action").val('admin_entrada');
                if (data.msj=="comprometido") {
   					$("#msj-ale-com").show();
                	toastr.error(data.msj2, 'Error!');
                	$('#form_registrar_entrada')[0].reset();	
                }else{toastr.error(data.msj, 'Error!');$("#msj-ale-com").hide(); }
			}
		})
		
	});
	function edit_entrada(id){
    	$.getJSON('../operaciones.php',{
			id:id,
			action: 'traer_entrada'
		}, function(data){	
			if(data.msg){
				$("#action").val('modificar_entrada');
				toastr.info('Elemento cargado en el formulario para su modificacion');
				$("#id_entrada").val(data.r[0].id);
				$("#procedencia").val(data.r[0].procedencia);
				$("#producto").val(data.r[0].producto);
					$( "#producto" ).prop( "disabled", true );
				$("#almacen").val(data.r[0].almacen);
					$( "#almacen" ).prop( "disabled", true );
				$("#nro_documento").val(data.r[0].nro_documento);
					$( "#nro_documento" ).prop( "disabled", true );
				$("#cantidad").val(data.r[0].cantidad);
					$( "#cantidad" ).prop( "disabled", true );
				$("#dot_lote").val(data.r[0].dot_lote);
				$("#dot_fecha").val(data.r[0].dot_fecha);
				$("#fec_reg").val(data.r[0].fec_reg);
				$("#msj-pre-mod").show();
				$("#msj-ale-com").hide();
			}else{
				$( "#producto" ).prop( "disabled", false );
				$( "#almacen" ).prop( "disabled", false );
				$( "#nro_documento" ).prop( "disabled", false );
				$( "#cantidad" ).prop( "disabled", false );
				$("#action").val('admin_entrada');
				$("#msj-pre-mod").hide();
				$('#form_registrar_entrada')[0].reset();
				$("#id_entrada").val('');
				$("#procedencia").val('');
				$("#producto").val('');
				$("#almacen").val('');
				$("#nro_documento").val('');
				$("#cantidad").val('');
				$("#dot_lote").val('');
				$("#dot_fecha").val('');
				$("#fec_reg").val('');
				toastr.success(data.msj);
				$("#msj-ale-com").hide();
			}
		})
    }

    function delete_entrada(id){
    	if(confirm('Alerta! verifique antes sus acciones puede comprometer la integridad de los datos. ¿Esta seguro de eliminar este elemento?')){
  			$.getJSON('../operaciones.php',{
			id:btoa(id),
			action: 'eliminar_entrada'
		}, function(data){	
			if(data.msg){
				toastr.warning(data.msj);
				$('#form_registrar_entrada')[0].reset();
				$("#fila_"+id).remove();//remover fila
			}else{
				if (data.msj='comprometido') {
					toastr.error(data.msj2);
					$("#msj-ale-com").show();
				}else{
					toastr.error(data.msj);
					$("#msj-ale-com").hide();
				}
				$('#form_registrar_entrada')[0].reset();
			}
		})
  		}
    }
</script>
