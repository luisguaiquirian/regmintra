<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
	include_once $_SESSION['base_url'].'partials/header.php';

	$ri = ['J','G'];
	$options_rif = '';
	foreach ($ri as $row) 
	{$options_rif .= '<option value="'.$row.'" >'.$row.'</option>';}

?>
	
	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
            <h4 class="panel-title text-center"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;Administrar Almacen</h4>
		</header>
		<div class="panel-body">
			<form action="./operaciones.php" id="form_registrar_almacen" method="POST">
		
				<input type="hidden" name="id_almacen" id="id_almacen" value="">
				<input type="hidden" id="action" name="action" value="admin_almacen">
                
				<div class="form-group">
					<div class="col-md-2 col-xs-12">
						<label for="">Nivel</label>
						<select class="form-control" id="nivel" name="nivel">
							<option value="">Seleccione</option>
							<?php
								$system->sql="select * from almacenes_nivel where estatus=7";
								foreach ($system->sql() as $rs){
									echo '<option value="'.$rs->id.'">'.$rs->descripcion.'</option>';
								}
							?>
						</select>
	                </div>

	                <div class="col-md-2 col-xs-12">
						<label for="">R.I.F.</label>
						<select class="form-control" id="referencia" name="referencia">
							<?php
								foreach ($options_rif as $rs){
									echo $options_rif;
								}
								echo $options_rif;
							?>
						</select>
	                </div>

					<div class="col-md-3 col-xs-12">
					    <label for="">*</label>
					    <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Ingrese el RIF"  onkeyup="mayus(this);" maxlength="150">	
                	</div>

                	<div class="col-md-5 col-xs-12">
					    <label for="">*Nombre</label>
					    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del almacen"  onkeyup="mayus(this);">	
                	</div>
                </div>
                <div class="form-group">
					<div class="col-md-12 col-xs-12">
					    <label for="">*Direccion</label>
					    <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Direccion del almacen"  onkeyup="mayus(this);">	
                	</div>
	            </div>
	            <div class="form-group">
					<div class="col-md-4 col-xs-12">
						<label for="">Estado</label>
						<select class="form-control" id="estado" name="estado">
							<option value="">Seleccione</option>
							<?php
								$system->sql="select * from estados";
								foreach ($system->sql() as $rs){
									echo '<option value="'.$rs->id_estado.'">'.$rs->estado.'</option>';
								}
							?>
						</select>
	                </div>
	                <div class="col-md-4 col-xs-12">
						<label for="">Municipio</label>
						<select class="form-control" id="municipio" name="municipio">
							<option value="">Seleccione</option>
						</select>
	                </div>
	                <div class="col-md-4 col-xs-12">
						<label for="">Parroquia</label>
						<select class="form-control" id="parroquia" name="parroquia">
							<option value="">Seleccione</option>
						</select>
	                </div>
	            </div>
	            <div class="form-group">
					<div class="col-md-3 col-xs-12">
					    <label for="">*Telefono Almacen</label>
					    <input type="text" minlength="11" maxlength="11" class="form-control" id="telefono" name="telefono" pattern="[0-9]{11}" placeholder="Telefono del Almacen">	
                	</div>
                	<div class="col-md-3 col-xs-12">
					    <label for="">Telefono Contacto</label>
					    <input type="text" minlength="11" maxlength="11" class="form-control" id="tel_contac" name="tel_contac" pattern="[0-9]{11}" placeholder="Numero del encargado del almacen">	
                	</div>
                	<div class="col-md-3 col-xs-12">
						<label for="">*Estatus</label>
						<select class="form-control" id="estatus" name="estatus">
							<option value="7">Activo</option>
						  	<option value="8">Inactivo</option>
						</select>
	                </div>
	                <div class="col-xs-12 col-md-3">
						<label for=""><span class="text-danger">*</span>Tipo&nbsp;</label>
						<select class="form-control" id="id_rubro" name="id_rubro">
								<option value="">Seleccione tipo</option>
								<?php
									$system->sql="select * from rubros";
									foreach ($system->sql() as $rs){
										echo '<option value="'.$rs->id.'">'.$rs->descripcion.'</option>';
									}
								?>
						</select>
						<p class="help-block">Seleccione el tipo de producto que albergara este almacen comunmente.</p>
					</div>
	            </div>

	            

                <hr>
					<button type="submit" class="btn btn-danger">Guardar&nbsp;<i class="fa fa-send"></i></button>
					<button type="reset" class="btn btn-info">Limpiar&nbsp;<i class="fa fa-send"></i></button>
			</form>
		</div>
	</section>
	<span class="label label-default"><i class="fa fa-info-circle"></i>&nbsp;En la actualizacion no se toma en cuenta el codigo, para ello tendra que eliminarlo y registrarlo de nuevo</span>

	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
		<h3>Listado de Almacenes</h3>
		</header>
		<div class="panel-body">
			<table class="table table-bordered table-striped mb-none table-condensed" id="datatable-editable">
			<thead><tr>
				<th>Nivel</th>
				<th>RIF</th>
				<th>Nombre</th>
				<th>Estado</th>
				<th>Municipio</th>
				<th>Parroquia</th>
				<th>Estatus</th>
				<th>Acciones</th>
			</tr></thead>
			<tbody id="cont_tabla_almacen">
				<?php
					$system->sql="select a.*,b.estado as destado,c.municipio as dmunicipio,d.parroquia as dparroquia,n.descripcion as dnivel,e.descripcion as destatus from almacenes as a inner join estados as b on (a.estado=b.id_estado) inner join municipios as c on (a.estado=c.id_estado and a.municipio=c.id_municipio) inner join parroquias as d on (a.estado=d.id_estado and a.municipio=d.id_municipio and a.parroquia=d.id_parroquia) inner join almacenes_nivel as n on (a.nivel=n.id) inner join estatus as e on (a.estatus=e.id)";
					foreach ($system->sql() as $rs){
						echo '<tr id="fila_'.$rs->id.'"><td>'.$rs->dnivel.'</td><td>'.$rs->referencia.$rs->codigo.'</td><td>'.$rs->nombre.'</td><td>'.$rs->destado.'</td><td>'.$rs->dmunicipio.'</td><td>'.$rs->dparroquia.'</td><td>'.$rs->destatus.'</td><td><a data-tool="tooltip" title="Editar" onclick="edit_almacen('.$rs->id.')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;<a data-tool="tooltip" title="Eliminar" onclick="delete_almacen('.$rs->id.')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-ban" aria-hidden="true"></i></a>&nbsp;</td></tr>';
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
	$("#datatable-editable").DataTable();
	$("#estado").change(function(){
  		$.getJSON('../operaciones.php',{
			estado:$("#estado").val(),
			action: 'buscar_municipios'
		}, function(data){	
			if(data.msg){
				let tam = data.r.length;
				if (tam>0) {
					let opt = '<option value="">Seleccione</option>';	
					for(let x = 0 ; x < tam; x++){
						opt += '<option value="'+data.r[x].id_municipio+'">'+data.r[x].municipio+'</option>';
					}
					$("#municipio").html(opt);
				}else{
					toastr.success(data.msj);	
				}
			}else{
				toastr.success(data.msj);
			}
		})
	});$("#municipio").change(function(){
  			$.getJSON('../operaciones.php',{
			estado:$("#estado").val(),
			municipio:$("#municipio").val(),
			action: 'buscar_parroquias'
		}, function(data){	
			if(data.msg){
				let tam = data.r.length;
				if (tam>0) {
					let opt = '<option value="">Seleccione</option>';	
					for(let x = 0 ; x < tam; x++){
						opt += '<option value="'+data.r[x].id_parroquia+'">'+data.r[x].parroquia+'</option>';
					}
					$("#parroquia").html(opt);
				}else{
					toastr.success(data.msj);	
				}
			}else{
				toastr.success(data.msj);
			}
		})
	});
	$('#form_registrar_almacen').submit(function(e) {
		e.preventDefault()
            if (($("#nivel").val() == null || $("#nivel").val().length == 0 || /^\s+$/.test($("#nivel").val())) || ($("#codigo").val() == null || $("#codigo").val().length == 0 || /^\s+$/.test($("#codigo").val())) || ($("#nombre").val() == null || $("#nombre").val().length == 0 || /^\s+$/.test($("#nombre").val())) || ($("#direccion").val() == null || $("#direccion").val().length == 0 || /^\s+$/.test($("#direccion").val())) || ($("#estado").val() == null || $("#estado").val().length == 0 || /^\s+$/.test($("#estado").val())) || ($("#municipio").val() == null || $("#municipio").val().length == 0 || /^\s+$/.test($("#municipio").val())) || ($("#parroquia").val() == null || $("#parroquia").val().length == 0 || /^\s+$/.test($("#parroquia").val())) || ($("#telefono").val() == null || $("#telefono").val().length == 0 || /^\s+$/.test($("#telefono").val()))
            	|| ($("#referencia").val() == null || $("#referencia").val().length == 0 || /^\s+$/.test($("#referencia").val())) || ($("#id_rubro").val() == null || $("#id_rubro").val().length == 0 || /^\s+$/.test($("#id_rubro").val()))
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
				toastr.success(data.msj);
				$('#form_registrar_almacen')[0].reset();
				$("#id_almacen").val('');
				let tam = data.r.length;	
				let tab='';
				for(let x = 0 ; x < tam; x++){
					tab += '<tr id="fila_'+data.r[x].id+'"><td>'+data.r[x].dnivel+'</td><td>'+data.r[x].referencia+data.r[x].codigo+'</td><td>'+data.r[x].nombre+'</td><td>'+data.r[x].destado+'</td><td>'+data.r[x].dmunicipio+'</td><td>'+data.r[x].dparroquia+'</td><td>'+data.r[x].destatus+'</td><td><a data-tool="tooltip" title="Editar" onclick="edit_almacen('+data.r[x].id+')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;<a data-tool="tooltip" title="Eliminar" onclick="delete_almacen('+data.r[x].id+')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-ban" aria-hidden="true"></i></a>&nbsp;</td></tr>';
				}
				$("#cont_tabla_almacen").html(tab);
				$("#datatable-editable").DataTable();
			}else{    
				$("#id_almacen").val('');
                $('#form_registrar_almacen')[0].reset()
                toastr.error(data.msj, 'Error!')
			}
		})
		
	});
	function edit_almacen(id){
    	$.getJSON('../operaciones.php',{
			id:id,
			action: 'traer_almacen'
		}, function(data){	
			if(data.msg){
				toastr.info('Elemento cargado en el formulario para su modificacion');
				$("#id_almacen").val(data.r[0].id);
				$("#referencia").val(data.r[0].referencia);
				$("#codigo").val(data.r[0].codigo);
				$("#nombre").val(data.r[0].nombre);
				$("#direccion").val(data.r[0].direccion);
				$("#estado").val(data.r[0].estado);
				$("#referencia").val(data.r[0].referencia);
				$("#id_rubro").val(data.r[0].id_rubro);
				let tam;
				let opt;
				/*cargamops municipio*/
					tam = data.m.length;
					if (tam>0) {
						let opt = '<option value="">Seleccione</option>';	
						for(let x = 0 ; x < tam; x++){
							opt += '<option value="'+data.m[x].id_municipio+'">'+data.m[x].municipio+'</option>';
						}
						$("#municipio").html(opt);
					}else{
						toastr.error('No hay municipios en BD');	
					}
				/*fin de carga*/
				/*carga de parroquias*/
					tam = data.p.length;
					if (tam>0) {
						let opt = '<option value="">Seleccione</option>';	
						for(let x = 0 ; x < tam; x++){
							opt += '<option value="'+data.p[x].id_parroquia+'">'+data.p[x].parroquia+'</option>';
						}
						$("#parroquia").html(opt);
					}else{
						toastr.success(data.msj);	
					}
				/*fin de carga*/
				$("#municipio").val(data.r[0].municipio);
				$("#parroquia").val(data.r[0].parroquia);
				$("#telefono").val(data.r[0].telefono);
				$("#tel_contac").val(data.r[0].tel_contac);
				$("#estatus").val(data.r[0].estatus);
				$("#fec_reg").val(data.r[0].fec_reg);
				$("#nivel").val(data.r[0].nivel);
			}else{
				$("#id_almacen").val('');
				$("#referencia").val('');
				$("#codigo").val('');
				$("#nombre").val('');
				$("#direccion").val('');
				$("#estado").val('');
				$("#municipio").val('');
				$("#parroquia").val('');
				$("#telefono").val('');
				$("#tel_contac").val('');
				$("#estatus").val('');
				$("#fec_reg").val('');
				$("#nivel").val('');
				toastr.success(data.msj);
			}
		})
    }

    function delete_almacen(id){
    	if(confirm('¿Esta seguro de eliminar este elemento?')){
  			$.getJSON('../operaciones.php',{
			id:btoa(id),
			action: 'eliminar_almacen'
		}, function(data){	
			if(data.msg){
				toastr.warning(data.msj);
				$('#form_registrar_almacen')[0].reset();
				$("#fila_"+id).remove();//remover fila
			}else{
				toastr.error(data.msj);
				$('#form_registrar_almacen')[0].reset();
			}
		})
  		}
    }
</script>
