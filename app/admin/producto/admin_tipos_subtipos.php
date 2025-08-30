<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
	include_once $_SESSION['base_url'].'partials/header.php';

?>
	
	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
            <h4 class="panel-title text-center"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;Administra Tipos y Sub-Tipos de Productos</h4>
		</header>
		<div class="panel-body">

			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-asterisk"></i>&nbsp;Tipos de Productos</a></li>
			    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-asterisk"></i>&nbsp;Sub-Tipos de Productos</a></li>
			</ul>

  			<!-- Tab panes -->
  			<div class="tab-content">
		    <div role="tabpanel" class="tab-pane active" id="home">
		    	<form action="../operaciones.php" id="form_add_tipo" method="POST">
		    		<input type="hidden"  name="id_tipo" id="id_tipo" value="">
					<input type="hidden" id="action" name="action" value="add_tipo">
					<div class="form-group">
						<div class="col-md-4 col-xs-12">
					    	<label for="">Descripción</label>
					    	<input type="text" class="form-control" id="descripcion_tipo" name="descripcion_tipo" placeholder="Agregue nombre del nuevo tipo">	
                		</div>
                		<div class="col-md-4 col-xs-12">
					    	<label for="">Margen de Peticion</label>
					    	<input type="number" class="form-control" id="dias_no_habil" name="dias_no_habil" placeholder="Representelo en numero">	
					    	<p class="help-block">Este campo representa los el margen de dias que un usuario puede solicitar este tipo de producto desde su ultima petición.</p>
                		</div>
					</div>
					<hr>
					<button type="submit"  class="btn btn-danger">Guardar&nbsp;<i class="fa fa-send"></i></button>&nbsp;
					<button type="reset" class="btn btn-info">Limpiar&nbsp;<i class="fa fa-send"></i></button>&nbsp;
				</form>
				<hr>
				<!---------TABLA DE TIPOS---------->
				<div>
					<h3>Listado de los tipos de productos</h3>
					<table class="table table-bordered table-striped mb-none table-condensed" id="datatable-editable">
						<thead><tr>
							<th>Descripcion</th>
							<th>Dias no habiles</th>
							<th>Acciones</th>
						</tr></thead>
						<tbody id="cont_tabla_tipo">
							<?php
								$system->sql="select * from rubros";
								foreach ($system->sql() as $rs){
									echo '<tr id="fila_'.$rs->id.'"><td>'.$rs->descripcion.'</td><td>'.$rs->dias_no_habil.'</td><td><a data-tool="tooltip" title="Editar" onclick="edit_tipo('.$rs->id.')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;<a data-tool="tooltip" title="Eliminar" onclick="delete_tipo('.$rs->id.')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-ban" aria-hidden="true"></i></a>&nbsp;</td></tr>';
								}
							?>
						</tbody>
					</table>
				</div>
		    </div>
    		<div role="tabpanel" class="tab-pane" id="profile">
    			<form action="../operaciones.php" id="form_add_subtipo" method="POST">
    				<input type="hidden" name="id_subtipo" id="id_subtipo" value="">
					<input type="hidden" id="action" name="action" value="add_subtipo">
					<div class="form-group">
						<div class="col-xs-12 col-md-3">
							<label for="">Tipo</label>
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
						<div class="col-md-4 col-xs-12">
					    	<label for="">Descripción</label>
					    	<input type="text" class="form-control" id="descripcion_subtipo" name="descripcion_subtipo" placeholder="Ingrese descripcion del subtipo">	
                		</div>
					</div>
					<hr>
					<button type="submit"  class="btn btn-danger">Guardar&nbsp;<i class="fa fa-send"></i></button>
					<button type="reset" class="btn btn-info">Limpiar&nbsp;<i class="fa fa-send"></i></button>
				</form>
				<span class="label label-default"><i class="fa fa-info-circle"></i>&nbsp;Selecciona el tipo asociado a el subtipo</span>
				<hr>
				<h3>Listado de los subtipos de productos</h3>
				<table class="table table-bordered table-striped mb-none table-condensed" id="datatable-editable">
						<thead><tr>
							<th>Descripcion del Sub-Tipo</th>
							<th>Dependencia</th>
							<th>Acciones</th>
						</tr></thead>
						<tbody id="cont_tabla_subtipo">
							<?php
								$system->sql="select a.*,b.descripcion as des_tipo from rubros_sub as a  inner join rubros as b on (a.id_rubro=b.id) where status=7";
								foreach ($system->sql() as $rs){
									echo '<tr id="fila_'.$rs->id.'"><td>'.$rs->descripcion.'</td><td>'.$rs->des_tipo.'</td><td><a data-tool="tooltip" title="Editar" onclick="edit_subtipo('.$rs->id.')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;<a data-tool="tooltip" title="Eliminar" onclick="delete_subtipo('.$rs->id.',this)" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-ban" aria-hidden="true"></i></a>&nbsp;</td></tr>';
								}
							?>
						</tbody>
					</table>
    		</div>
  			</div>
			
		</div>
	</section>
<?
	include_once $_SESSION['base_url'].'partials/footer.php';
?>
<script>
	$("#datatable-editable").DataTable();
	$(function(){

    $('#form_add_tipo').submit(function(e) {
		e.preventDefault()
            let tipo = $("#descripcion_tipo").val();
            let dias = $("#dias_no_habil").val();
            if ((tipo == null || tipo.length == 0 || /^\s+$/.test(tipo)) && (dias == null || dias.length == 0 || /^\s+$/.test(dias))) {
                toastr.error('Ingrese una descripción para el nuevo tipo a registrar.', 'Error!')
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
				$('#form_add_tipo')[0].reset();
				let tam = data.rubros.length;
				let opt = '<option value="">Seleccione una opcion</option>';	
				let tab='';
				for(let x = 0 ; x < tam; x++){
					opt += '<option value="'+data.rubros[x].id+'">'+data.rubros[x].descripcion+'</option>';
					tab += '<tr id="fila_'+data.rubros[x].id+'"><td>'+data.rubros[x].descripcion+'</td><td>'+data.rubros[x].dias_no_habil+'</td><td><a data-tool="tooltip" title="Editar" onclick="edit_tipo('+data.rubros[x].id+')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;<a data-tool="tooltip" title="Eliminar" onclick="delete_tipo('+data.rubros[x].id+')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-ban" aria-hidden="true"></i></a>&nbsp;</td></tr>';
				}
				$("#tipo").html(opt);
				$("#cont_tabla_tipo").html(tab);
				$('table#tabla_tipo').DataTable();
			}else{    
                $('#form_add_tipo')[0].reset()
                toastr.error(data.msj, 'Error!')
			}
		})
		
	})
    });

    $('#form_add_subtipo').submit(function(e) {
		e.preventDefault()
            let subtipo = $("#descripcion_subtipo").val();
            let tipo =$("#tipo").val();
            if (tipo == null || tipo.length == 0 || /^\s+$/.test(tipo)) {
                toastr.error('Ingrese una descripción para el nuevo subtipo a registrar.', 'Error!')
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
				$('#form_add_subtipo')[0].reset();
				let tam = data.rubros_sub.length;
				let tab='';
				for(let x = 0 ; x < tam; x++){
					tab += '<tr id="fila_'+data.rubros_sub[x].id+'" ><td>'+data.rubros_sub[x].descripcion+'</td><td>'+data.rubros_sub[x].des_tipo+'</td></td><td><a data-tool="tooltip" title="Editar" onclick="edit_subtipo('+data.rubros_sub[x].id+')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;<a data-tool="tooltip" title="Eliminar" onclick="delete_subtipo('+data.rubros_sub[x].id+')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-ban" aria-hidden="true"></i></a>&nbsp;</td></tr>';
				}
				$("#cont_tabla_subtipo").html(tab);
				$('table#tabla_tipo').DataTable();
			}else{    
                $('#form_add_subtipo')[0].reset()
                toastr.error(data.msj, 'Error!')
			}
		})
		
	});

    function edit_tipo(id){
    	$.getJSON('../operaciones.php',{
			tipo:id,
			action: 'traer_tipo'
		}, function(data){	
			if(data.msg){
				toastr.info('Elemento cargado en el formulario para su modificacion');
				$("#descripcion_tipo").val(data.r[0].descripcion);
				$("#dias_no_habil").val(data.r[0].dias_no_habil);
				$("#id_tipo").val(data.r[0].id);
			}else{
				$("#descripcion_tipo").val('');
				$("#dias_no_habil").val('');
				$("#id_tipo").val('');
				toastr.success(data.msj);
			}
		})
    }

    function edit_subtipo(id){
    	$.getJSON('../operaciones.php',{
			tipo:id,
			action: 'traer_subtipo'
		}, function(data){	
			if(data.msg){
				toastr.info('Elemento cargado en el formulario para su modificacion');
				$("#descripcion_subtipo").val(data.r[0].descripcion);
				$("#tipo").val(data.r[0].id_rubro);
				$("#id_subtipo").val(data.r[0].id);
			}else{
				$("#descripcion_subtipo").val('');
				$("#tipo").val('');
				$("#id_subtipo").val('');
				toastr.success(data.msj);
			}
		})
    }

    function delete_subtipo(id){
    	if(confirm('¿Esta seguro de eliminar este elemento?')){
  			$.getJSON('../operaciones.php',{
			id:btoa(id),
			action: 'eliminar_subtipo'
		}, function(data){	
			if(data.msg){
				toastr.warning(data.msj);
				$('#form_add_subtipo')[0].reset();
    			$("#fila_"+id).remove();//remover fila
			}else{
				toastr.error(data.msj);
				$('#form_add_subtipo')[0].reset();
			}
		})
  		}
    }

    function delete_tipo(id){
    	if(confirm('¿Esta seguro de eliminar este elemento?')){
  			$.getJSON('../operaciones.php',{
			id:btoa(id),
			action: 'eliminar_tipo'
		}, function(data){	
			if(data.msg){
				toastr.warning(data.msj);
				$('#form_add_tipo')[0].reset();
				$("#fila_"+id).remove();//remover fila
				let tam = data.rubros_sub.length;
				let tab='';
				for(let x = 0 ; x < tam; x++){
					tab += '<tr id="fila_'+data.rubros_sub[x].id+'" ><td>'+data.rubros_sub[x].descripcion+'</td><td>'+data.rubros_sub[x].des_tipo+'</td></td><td><a data-tool="tooltip" title="Editar" onclick="edit_subtipo('+data.rubros_sub[x].id+')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;<a data-tool="tooltip" title="Eliminar" onclick="delete_subtipo('+data.rubros_sub[x].id+')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-ban" aria-hidden="true"></i></a>&nbsp;</td></tr>';
				}
				$("#cont_tabla_subtipo").html(tab);
				$('table#tabla_tipo').DataTable();
			}else{
				toastr.error(data.msj);
				$('#form_add_tipo')[0].reset();
			}
		})
  		}
    }

</script>
