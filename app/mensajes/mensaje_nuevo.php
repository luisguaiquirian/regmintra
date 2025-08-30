<?php

	if(!isset($_SESSION)){
    	session_start();
  }


	include_once $_SESSION['base_url'].'partials/header.php';
	
	$register = null;
	$option_perfiles = "<option value=''>--Seleccione--</option>";
	$system->table = "perfiles";
	$system->where = "id NOT IN (1,7,$_SESSION[nivel])";

	foreach ($system->get() as $row) {
		$option_perfiles.= "<option value='$row->id'>$row->nombre</option>";
	}

	if(isset($_GET['response'])){
		// si existe el where de modificar buscamos el registrp
		//$system->table = "modelos_vehiculos";

		//$register = $system->find(base64_decode($_GET['modificar']));
	}

?>
	
	<section class="panel panel-dark">
		<header class="panel-heading">
			<div class="panel-actions">
				<a href="#" class="fa fa-caret-down"></a>
				<a href="#" class="fa fa-times"></a>
			</div>
        <h4 class="panel-title text-center">Redactar Mensaje Nuevo</h4>
		</header>
		<div class="panel-body">
			<form class="form-horizontal" id="form_message">
				<input type="hidden" name="action" id="action" value="store">
				<input type="hidden" id="id_singular" name="id_singular" value="">
				<div class="row form-group">
					<div class="col-md-8 col-sm-8 col-md-offset-2 col-sm-offset-2">
						<label class="control-label">Perfil</label>
						<select class="form-control" required="" name="id_perfil" id="id_perfil">
							<?= $option_perfiles ?>
						</select>
					</div>
				</div>
				<div class="row form-group"  id="tipo_mensaje_div" style="display: none">
					<div class="col-md-8 col-sm-8 col-md-offset-2 col-sm-offset-2">
						<label class="control-label">Tipo de Mensaje</label>
						<select class="form-control" required="" name="type_message" id="type_message">
							<option value="">--Seleccione--</option>
							<option value="1">Singular</option>
							<option value="2">Masivo</option>
						</select>
					</div>
				</div>
				<fieldset id="mensaje_div" style="display: none">
					<div class="row">
						<div class="col-md-8 col-sm-8 col-md-offset-2 col-sm-offset-2">
							<br/>
							<button type="button" data-target="#modal_receptores" data-toggle="modal" class="btn btn-block btn-warning">Receptores <i class="fa fa-users"></i></button>
						</div>
					</div>
					<div class="row form-group"  >
						<div class="col-md-12 col-sm-12">
							<label class="control-label">Texto del Mensaje</label>
							<textarea class="form-control" required="" id="mensaje" name="mensaje" rows="5"></textarea>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-4 col-sm-4 col-md-offset-2 col-sm-offset-2">
							<button type="button" id="btn_limpiar" class="btn btn-danger btn-block">Limpiar <i class="fa fa-refresh"></i></button>
						</div>
						<div class="col-md-4 col-sm-4">
							<button type="submit" class="btn btn-primary btn-block">Enviar Mensaje <i class="fa fa-send"></i></button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</section>
	<div class="modal fade" id="modal_receptores">
		<div class="modal-dialog" role="document"  style="width: 80%">
			<div class="modal-content">
				<div class="modal-header" style="background-color: black; color:white;">
					<div class="row">
						<div class="col-md-9 col-sm-9">
							<h4 class="modal-title">Receptores</h4>
						</div>
						<div class="col-md-3 col-sm-3">
							<label class="checkbox-inline" style="font-size: 18px; display: none" id="id_select_all">
								<input type="checkbox" name="all_checking" id="all_checking">
								Seleccionar Todos
							</label>
						</div>
					</div>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<table class="table table-bordered" id="datatable-default">
								<thead>
									<tr>
										<th class="text-center"></th>
										<th class="text-center">Nombre</th>
										<th class="text-center">Cédula</th>
										<th class="text-center">Teléfono</th>
										<th class="text-center">Email</th>
										<th class="text-center">Perfil</th>
									</tr>
								</thead>
								<tbody class="text-center">
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

<?php
	include_once $_SESSION['base_url'].'partials/footer.php';
?>

<script type="text/javascript">
	$(function(){
		
		let not_includes = []

		$('#id_perfil').change(function(e){
			let val = e.target.value,
					type_message = $('#type_message').val()

			if(val){
				$('#tipo_mensaje_div').show()
				if(type_message){
					renderUsersTable()
				}
			}else{
				$('#tipo_mensaje_div').hide()
			}
		})

		let users_message = [],
				users_message_id = [],
				validate_click = false


		$('#type_message').change(function(e){
			renderUsersTable()
		})

		$('#btn_limpiar').click(function(e){
			$('#mensaje').val('')
		})

		$('#all_checking').change(function(e){
				if(e.target.checked){
					users_message_id = users_message
					$('.radio_check_users').prop('checked',true)
					validate_click = true
					not_includes = []
				}else{
					users_message_id = []
					$('.radio_check_users').prop('checked',false)
					not_includes = []
				}
		})

		$('#form_message').submit(function(e){
			e.preventDefault()
			let type_message     = $('#type_message').val(),
					id_singular = $('#id_singular').val()

			if(type_message == 1){
				if(!id_singular){
					toastr.warning('Debe especificar quien va a ser el receptor')
					return false
				}
			}else{
				if(users_message_id.length < 1){
					toastr.warning('Debe especificar quienes va a ser los receptores')
					return false	
				}
			}


			let object = {
				id_singular,
				id_perfil : $('#id_perfil').val(),
				type_message,
				message: $('#mensaje').val(),
				users_message_id,
				action : $('#action').val()
			}

			$.ajax({
				url:"./operaciones.php",
				data: object,
				dataType: "JSON",
				type: "POST",
				success: function(data){
					if(data.r){
						toastr.success('Mensajeria Enviada', 'Éxito!')
						$('#form_message')[0].reset()
						users_message_id = []
						users_message = []
						$('#mensaje_div').hide()
						$('#tipo_mensaje_div').hide()
					}
				}
			})
		})

		function removeOrAdd(id,e){
			if(e.target.checked){
				users_message_id.push(id)
				not_includes = not_includes.filter(i => i !== id)
			}else{
				users_message_id = users_message_id.filter(i => i !== id)
				not_includes.push(id)
			}
		}

		function renderUsersTable(){

			let val = $('#id_perfil').val(),
					type_message = $('#type_message').val(),
					type_field = type_message == 1 ? 'radio' : 'checkbox',
					id_singular_field = document.getElementById('id_singular'),
					all_check = document.getElementById('all_checking')


			if(val){
				$('#mensaje_div').show()
				if(type_message == 1 ){
					$('#id_select_all').hide()
				}else{
					$('#id_select_all').show()
					id_singular_field.value = ""
				}
				let object = {action: "get_users",'id_perfil': val}
				$.getJSON('./operaciones.php',object).then(res => {
					let table  = $('#datatable-default')
					table.DataTable().destroy();
					let all_checked = type_message == 1 ? null : document.getElementById('all_checking')
						if(type_message == 2){
							users_message = res.users.map((e,i) => e.id)
						}else{
							users_message = []
							users_message_id = []
						}

						table.dataTable({
							data: res.users,
							columns: [
								{'data' : 'id',"render":function(id){

									if(type_message == 1){
										return `<button type="button" 
										class="btn btn-danger btn-block"
										data-id="${id}">
										Seleccionar
										</button>`
									}else{

										return `
											<input type="checkbox" name="id_checkbox_user" class="radio_check_users" value="${id}" />
										`;
									}

								}},
								{'data' : {"data":"data"},"render" : function(data){
									if(data.perfil == 2 || data.perfil == 3){
										return data.usuario
									}else{
										return data.nombre
									}
								}},
								{'data' : 'cedula'},
			
								{'data' : 'telefono'},
								{'data' : 'email'},
								{'data' : 'nombre_perfil'},
							],
							"rowCallback": function( row, data ) {
								if(type_message == 1){
									row.children[0].children[0].addEventListener('click',(e) => {
										id_singular_field.value = e.target.dataset.id
										$('#modal_receptores').modal('hide')
									},false)
								}else{
									if(all_check.checked){
										if(not_includes.includes(row.children[0].children[0].value)){
											row.children[0].children[0].checked = false
										}else{
											row.children[0].children[0].checked = true
										}
									}else{	
										if(users_message_id.includes(row.children[0].children[0].value)){

											row.children[0].children[0].checked = true
										}else{
											row.children[0].children[0].checked = false
										}
									}

									row.children[0].children[0].addEventListener('click',(e) => {
											removeOrAdd(data.id,e)
										},false)

								}
						  }
						})
				})
			}else{
				$('#mensaje_div').hide()
			}

		}
	})
</script>
