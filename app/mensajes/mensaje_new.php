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
				<input type="checkbox" name="select_all" id="select_all" style="display: none">
									
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
							<label class="form-control-label">Receptores</label>	
							<input class="form-control" id="receptores" name="receptores" required="">
						</div>
					</div>
					<div class="row form-group"  >
						<div class="col-md-12 col-sm-12">
							<label class="control-label">Texto del Mensaje</label>
							<textarea class="form-control" required="" id="mensaje" name="mensaje" rows="5"></textarea>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-4 col-sm-4">
							<button type="button" id="btn_limpiar" class="btn btn-danger btn-block">Limpiar <i class="fa fa-refresh"></i></button>
						</div>
						<div class="col-md-4 col-sm-4">
							<button type="submit" class="btn btn-primary btn-block">Enviar Mensaje <i class="fa fa-send"></i></button>
						</div>
						<div class="col-md-4 col-sm-4">
							<button type="button" id="select_all_button" class="btn btn-block btn-warning">Seleccionar Todos <i class="fa fa-users"></i></button>
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
								<input type="checkbox" name="all_checking1" id="all_checking1">
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

		let all_users = []

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

		$('#select_all_button').click((e) => {
			let check = document.getElementById('select_all')
			
			if(check.checked){
				check.checked = false
				$('#receptores').select2('data',[])

				//$('#receptores').prop('selected', false).change();
				$("#select_all_button").html('Seleccionar Todos <i class="fa fa-users"></i>')
			}else{
				check.checked = true

				$('#receptores').select2('data', all_users);
				
				$("#select_all_button").html('Remover todos <i class="fa fa-users"></i>')
			}

		})

		$('#form_message').submit(function(e){
			e.preventDefault()
			let type_message     = $('#type_message').val(),
					id_singular = $('#id_singular').val()


			let object = {
				id_singular,
				id_perfil : $('#id_perfil').val(),
				type_message,
				message: $('#mensaje').val(),
				users_message_id : $('#receptores').val(),
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
						$('#mensaje_div').hide()
						$('#tipo_mensaje_div').hide()
					}
				}
			})
		})

		function removeOrAdd(id,e){
			validate_click = false
			if(e.target.checked){
				users_message_id.push(id)
			}else{
				users_message_id = users_message_id.filter(i => i !== id)
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
					$('#select_all_button').prop('disabled',true)
				}else{
					$('#id_select_all').show()
					id_singular_field.value = ""
					$('#select_all_button').prop('disabled',false)
				}
				let object = {action: "get_users",'id_perfil': val}
				$.getJSON('./operaciones.php',object).then(res => {
					all_users = res.users
					$('#receptores').select2('destroy').select2({
				    data: res.users,
				    placeholder: 'buscar',
				    multiple: true,
				    // query with pagination
				    query: function(q) {
				      var pageSize,
				        results,
				        that = this;
				      pageSize = 20; // or whatever pagesize
				      results = [];
				      if (q.term && q.term !== '') {
				        // HEADS UP; for the _.filter function i use underscore (actually lo-dash) here
				        results = that.data.filter(function(e) {
				          return e.text.toUpperCase().indexOf(q.term.toUpperCase()) >= 0;
				        });
				      } else if (q.term === '') {
				        results = that.data;
				      }
				      
				      q.callback({
				        results: results.slice((q.page - 1) * pageSize, q.page * pageSize),
				        more: results.length >= q.page * pageSize,
				      });
				    },
				  });
					
					/*let nombre = ""
					let options = ""
					
					res.users.forEach((v,i) => {

						if(v.perfil == 2 || v.perfil == 3){
							nombre = v.usuario
						}else{
							nombre = v.nombre
						}

						options+= "<option value='"+v.id+"'>"+nombre+"</option>"
					})
					$("#receptores").select2('destroy')

					$("#receptores").prop("multiple", type_message != 1 ? "multiple" : "");

					$('#receptores').html(options)
					$("#receptores").select2()*/
				})
			}else{
				$('#mensaje_div').hide()
			}

		}

	})
</script>
