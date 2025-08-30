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
            <h4 class="panel-title text-center"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;Administrar Procedencias</h4>
		</header>
		<div class="panel-body">
			<form action="../operaciones.php" id="form_registrar_procedencia" method="POST">
		
				<input type="hidden"  name="id_procedencia" id="id_procedencia" value="">
				<input type="hidden" id="action" name="action" value="admin_procedencia">
                
				<div class="form-group">
					<div class="col-md-3 col-xs-12">
					    <label for="">Referencia</label>
					    <input type="text" class="form-control" id="referencia" name="referencia" placeholder="Describa una referencia">
					    <p class="help-block">La referencia no es un campo requerido.</p>	
                	</div>

                	<div class="col-md-3 col-xs-12">
					    <label for="">* Procedencia</label>
					    <input type="text" class="form-control" id="procedencia" name="procedencia" placeholder="Nombre de la procedencia">	
                	</div>

					<div class="col-md-4 col-xs-12">
					    <label for="">* Descripción</label>
					    <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripcion de la procedencia">	
                	</div>
                	
                	<div class="col-md-2 col-xs-12">
						<label for="">Estatus</label>
						<select class="form-control" id="estatus" name="estatus">
							<option value="7">Activo</option>
						  	<option value="8">Inactivo</option>
						</select>
	                </div>
	            </div>

                <hr>
					<button type="submit" class="btn btn-danger">Guardar&nbsp;<i class="fa fa-send"></i></button>
					<button id="clean_procedencia" type="reset" class="btn btn-info">Limpiar&nbsp;<i class="fa fa-send"></i></button>
			</form>
		</div>
	</section>
	<span class="label label-default"><i class="fa fa-info-circle"></i>&nbsp;La procedencia hace referencia al lugar o situacion de donde podria provenir algun lote de producto al inventario. Recuerda que los campos con asteriscos(*) son campos obligatorios</span>

	<section class="panel">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
            <h4 class="panel-title text-center"><i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;Listado de las procedencias</h4>
		</header>
		<div class="panel-body">
		<table class="table table-bordered table-striped mb-none table-condensed" id="datatable-editable">
			<thead><tr>
				<th>Referencia</th>
				<th>Procedencia</th>
				<th>Descripción</th>
				<th>Estatus</th>
				<th>Acciones</th>
			</tr></thead>
			<tbody id="cont_tabla_procedencia">
				<?php
					$system->sql="select a.*,b.descripcion as status from procedencias as a inner join estatus as b on (a.estatus=b.id)";
					foreach ($system->sql() as $rs){
						echo '<tr id="fila_'.$rs->id.'"><td>'.$rs->referencia.'</td><td>'.$rs->procedencia.'</td><td>'.$rs->descripcion.'</td><td>'.$rs->status.'</td><td><a data-tool="tooltip" title="Editar" onclick="edit_procedencia('.$rs->id.')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;<a data-tool="tooltip" title="Eliminar" onclick="delete_procedencia('.$rs->id.')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-ban" aria-hidden="true"></i></a>&nbsp;</td></tr>';
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
	$(function(){
		$('#form_registrar_procedencia').submit(function(e) {
		e.preventDefault()
            let des = $("#descripcion").val();
            let pro = $("#procedencia").val();
            if ((des == null || des.length == 0 || /^\s+$/.test(des)) || (pro == null || pro.length == 0 || /^\s+$/.test(pro))) {
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
				$('#form_registrar_procedencia')[0].reset();
				$('#id_procedencia').val('');
				let tam = data.r.length;	
				let tab='';
				for(let x = 0 ; x < tam; x++){
					tab += '<tr id="fila_'+data.r[x].id+'"><td>'+data.r[x].referencia+'</td><td>'+data.r[x].procedencia+'</td><td>'+data.r[x].descripcion+'</td><td>'+data.r[x].status+'</td><td><a data-tool="tooltip" title="Editar" onclick="edit_procedencia('+data.r[x].id+')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-edit" aria-hidden="true"></i></a>&nbsp;<a data-tool="tooltip" title="Eliminar" onclick="delete_procedencia('+data.r[x].id+')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-ban" aria-hidden="true"></i></a>&nbsp;</td></tr>';
				}
				$("#cont_tabla_procedencia").html(tab);
				$("#datatable-editable").DataTable();
			}else{    
                $('#form_registrar_procedencia')[0].reset()
                toastr.error(data.msj, 'Error!');
                $('#id_procedencia').val('');
			}
		})
		
	});
    });




	function edit_procedencia(id){
    	$.getJSON('../operaciones.php',{
			id:id,
			action: 'traer_procedencia'
		}, function(data){	
			if(data.msg){
				toastr.info('Elemento cargado en el formulario para su modificacion');
				$("#descripcion").val(data.r[0].descripcion);
				$("#referencia").val(data.r[0].referencia);
				$("#id_procedencia").val(data.r[0].id);
				$("#procedencia").val(data.r[0].procedencia);
				$("#estatus").val(data.r[0].estatus);
			}else{
				$("#descripcion").val('');
				$("#referencia").val('');
				$("#id_procedencia").val('');
				$("#procedencia").val('');
				$("#estatus").val(data.r[0].estatus);
				toastr.success(data.msj);
			}
		})
	}

	function delete_procedencia(id){
    	if(confirm('¿Esta seguro de eliminar este elemento?')){
  			$.getJSON('../operaciones.php',{
			id:btoa(id),
			action: 'eliminar_procedencia'
		}, function(data){	
			if(data.msg){
				toastr.warning(data.msj);
				$('#form_registrar_procedencia')[0].reset();
    			$("#fila_"+id).remove();//remover fila
			}else{
				toastr.error(data.msj);
				$('#form_registrar_procedencia')[0].reset();
			}
		})
  		}
    }
</script>
