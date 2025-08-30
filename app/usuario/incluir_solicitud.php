<?php
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
  	/*Cabecera de  la pag*/
	include_once $_SESSION['base_url'].'partials/header.php';
	include_once $_SESSION['base_url'].'partials/header.php';
  	$system->sql="select a.*,b.lubricante,c.neumatico,d.tipo_unidad from unidades as a
left join lubricantes as b on (a.tipo_lub=b.id)
left join cauchos as c on (a.num_neu=c.id) left join tipo_unidad as d on (a.tipo_unidad=d.id) where cod_linea=".$_SESSION['cod_linea_2']." and cod_afiliado=".$_SESSION['cod_afiliado'];
  	$elem = $system->sql();
?>
<!--cuerpo de la pag-->
<section class="panel">
	<header class="panel-heading">
		<div class="panel-actions">
			<a href="#" class="fa fa-caret-down"></a>
			<a href="#" class="fa fa-times"></a>
		</div>
		<h4 class="panel-title text-center">Datos de Solicitud.</h4>
	</header>
	<div class="panel-body">
		<div class="alert alert-warning alert-dismissible" role="alert">
		  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		  <h4><span id="msj_sol"><strong>Porfavor!</strong> Selecciona una de tus unidades.</span></h4>
		</div>
		<form action="./operaciones.php" class="form-horizontal" id="form_registrar" method="POST">
			<input id="id_user" type="hidden" name="id_user" value="<?= base64_encode($_SESSION['user_id']) ?>">
			<input id="id_user" type="hidden" name="id_user" value="<?= $_SESSION['user_id'] ?>">
			<input id="estado" type="hidden" name="estado" value="<?= $_SESSION['edo'] ?>">
			<input id="municipio" type="hidden" name="municipio" value="<?= $_SESSION['mun'] ?>">
			<input id="id_afiliado" type="hidden" name="id_afiliado" value="<?= $_SESSION['cod_afiliado'] ?>">
			<input id="id_linea" type="hidden" name="id_linea" value="<?= $_SESSION['cod_linea_2'] ?>">
			
			<div class="form-group">
			<div class="col-md-3 col-sm-4">
		    	<label for="id_unidad">Unidad</label>
		    	<select name="id_unidad" id="id_unidad" class="form-control">
					<option value=""></option>
					<?php
						//foreach ($system->sql() as $rs){
						$cont = 0;
						foreach ($elem as $rs){
							if($rs->verf == 1){
								echo '<option value="'.$rs->id.'">'.$rs->placa.'</option>';
								$cont++;
							}
						}
					?>
				</select>
		  	</div>
		  	<div id="cont-datos" style="display: none">
		  	<div class="col-md-3 col-sm-4">
		    	<label for="id_rubro">Item</label>
		    	<select name="id_rubro" id="id_rubro" class="form-control">
					<option value=""></option>
					<?php
						$system->sql="select * from rubros";
						foreach ($system->sql() as $rs){
							echo '<option value="'.$rs->id.'">'.$rs->descripcion.'</option>';
						}
					?>
				</select>
		  	</div>
		  	<div class="col-md-3 col-sm-4">
		    	<label for="des">Descripción del Item</label>
		    	<input type="text" class="form-control" id="des" name="des" disabled>
		  	</div>
		  	<div class="col-md-3 col-sm-4">
		    	<label for="can">Cantidad de Item</label>
		    	<input type="number" class="form-control" id="can" name="can" disabled>
		    	<p class="help-block">Maximo: <span id="maximo"></span></p>
		  	</div>
		  	</div><!-- fin cont-datos-->
		  	</div><!--fin form-group-->
		  	<div class="form-group">
			  	<div class="col-md-4 col-sm-4 col-xs-12">
					<button type="button" id="add_elemento" name="add_elemento" class="btn btn-danger btn-block" disabled>Agregar Item&nbsp;<i class="fa fa-clipboard"></i></button>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12">
					<button type="reset" id="clean_elemento" name="clean_elemento" class="btn btn-info btn-block">Limpiar&nbsp;<i class="fa fa-eraser"></i></button>
				</div>
			</div>
			<br><br>
			<div id="seccion-detalle" style="display: none;">
				<!--tabla Detalles-->
				<div class="panel panel-danger col-xs-12 col-sm-8">
					<div class="panel-heading bg-danger text-center"><h4><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>&nbsp;Detalles de la Solicitud</h4></div>
					<div class="panel-body">
					    <table id="list_sol" class="table table-hover">
						  <thead>
						    <th class="ta-hide">id_unidad</th>
						    <th>Placa</th>
						    <th class="ta-hide">id_item</th>
						    <th>item</th>
						    <th>Descripcion</th>
						    <th>Cantidad</th>
						    <th>-</th>
						  </thead>
						</table>
					</div>
					<div class="panel-footer">Linea: <?php echo $_SESSION['cod_linea_2'];?> <span class="text-danger">|</span> Afiliado: <?php echo $_SESSION['cod_afiliado'];?> <span class="text-danger">|</span> Fecha : <?php echo date('d/m/Y');?></div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<button type="button" id="guardar_solicitud" class="btn btn-danger btn-block">Guardar&nbsp;<i class="fa fa-send"></i></button>
					<button type="button" class="btn btn-info btn-block" onclick="eliminaFilas();">Cancelar&nbsp;<i class="fa fa-send"></i></button>
				</div>
			</div>
		</form>
	</div><!--cierre panel body-->
</section><!--cierre del panel-->


<section class="panel">
	<header class="panel-heading">
		<div class="panel-actions">
			<a href="#" class="fa fa-caret-down"></a>
			<a href="#" class="fa fa-times"></a>
		</div>
		<h5 class="text-center">Tus Unidades</h5>
	</header>
	<div class="panel-body">
		<ul class="list-group">
			<?php
				foreach ($elem as $rs){
					if($rs->verf == 1){
						echo '<a class="list-group-item"><span class="badge bg-success">Verificado</span>'.$rs->placa.'</a>';
					}else{
						echo '<a class="list-group-item"><span class="badge bg-danger">No Verificado</span>'.$rs->placa.'</a>';
					}
				}
				?>
		</ul>
	</div>
</section>


<?php include_once $_SESSION['base_url'].'partials/footer.php';?>
<script type="text/javascript">
	//variables globales
	var unidad;
	var can;
   	var unidades = JSON.parse('<?php echo json_encode($elem)?>');
   	//eventos al seleccionar unidades y item
   	$("#id_unidad").change(function(){
		if ($("#id_unidad").val()!='') {
			$("#cont-datos").show();
			$("#msj_sol").html('<strong>Ahora!</strong> Selecciona un Item a solicitar para tu unidad.</span>');
			$("#maximo").html('');
			mostrar_datos();
		}else{
			$("#cont-datos").hide();
			$("#can").val('');
			$("#des").val('');
			$("#msj_sol").html('<strong>Porfavor!</strong> Selecciona una de tus unidades.</span>');
			$("#maximo").html('');
			$("#add_elemento").prop('disabled', true);
			limpiar_datos_add();
		}
	});

	$("#id_rubro").change(function(){
		if ($("#id_rubro").val()!='') {
			$("#msj_sol").html('<strong>Excelente!</strong> Puede agregar este detalle a la lista, antes puedes chequear la cantidad del ítem seleccionado y cambiarla si la opción esta habilitada.</span>');
			$("#add_elemento").prop('disabled', false);
			mostrar_datos();
		}else{
			$("#can").val('');
			$("#des").val('');
			$("#msj_sol").html('<strong>Ahora!</strong> Selecciona un Item a solicitar para tu unidad.</span>');
			$("#add_elemento").prop('disabled', true);
			$("#maximo").html('');
		}
	});
	//mostramos informacion del item dependiendo de los eventos
	function mostrar_datos(){
		if ($("#id_rubro").val() != '') {
            for (var i = 0; i < unidades.length; i++) {
            	if(unidades[i].id == $("#id_unidad").val()){unidad=i;}
            }
            switch($("#id_rubro").val()) {
			    case '1'://neumatico
			        $("#des").val(unidades[unidad].neumatico);
			        $("#can").val(unidades[unidad].cant_neu).prop('disabled', false);
			        can = unidades[unidad].cant_neu;
			        $("#maximo").html(unidades[unidad].cant_neu);
			    break;
			    case '2'://lubricante
			        $("#des").val(unidades[unidad].lubricante);
			        $("#can").val(unidades[unidad].cant_lubri).prop('disabled', true);
			        can = unidades[unidad].cant_lubri;
			        $("#maximo").html(unidades[unidad].cant_lubri);
			    break;
			    case '3'://bateria
			        $("#des").val(unidades[unidad].acumulador);
			        $("#can").val('1').prop('disabled', true);//valor default 1, bateria para el tipo de unidades en el sistema.
			        can = 1;
			        $("#maximo").html(1);
			    break;
			    default:
			        alertify.alert('Alerta del Sistema', 'Porfavor seleccione un ítem valido.');
			}
		}
	}
	//podemos limpiar los datos de la seleccion de los item
	$("#clean_elemento").click(function(){limpiar_datos_add();});
	function limpiar_datos_add(){
		$("#cont-datos").hide();
		$("#maximo").html('');
		$("#msj_sol").html('<strong>Porfavor!</strong> Selecciona una de tus unidades.</span>');
		$("#add_elemento").prop('disabled', true);
	}
	//se agrega el item a la lista de detalles de la solicitud
	$("#add_elemento").click(function(){
		if($("#can").val()>0) {
			if(parseInt(can) >= parseInt($("#can").val())){
				//alert(repeated());
				if(repeated() == false){
					validar_item();
					$("#msj_sol").html('<strong>Muy Bien!</strong> Selecciona una de tus unidades.</span>');
				}else{
					alertify.alert('Alerta del Sistema', '<strong>Este item para la unidad "'+$("#id_unidad option:selected").text()+'" se encuentra agregado en los detalles de la lista, puede eliminar el Item de la lista si necesita realizar alguna modificación y volver agregarlo.</strong>');
				}
			}else{
				alertify.alert('Alerta del Sistema', '<strong>Porfavor, verifique la cantidad solicitada con la cantidad maxima disponible o registrada para esta unidad. Modifique el valor de acuerdo con lo permitido, para su caso su Maximo es '+can+'.</strong>');
			}
		}else{
			alertify.alert('Alerta del Sistema', '<strong>Porfavor, la cantidad del Item no puede estar en cero(0). Modifique el valor de acuerdo con lo permitido.</strong>');
		}
	});
	//verificamos que el item no este en la lista
	function repeated(){
		if($("#list_sol tbody tr").size()>0){
			let c=1;
			let r=0;
			$("#list_sol tbody tr").each(function(){
				if ($("tr:eq("+c+") td:eq(0)").text() == $("#id_unidad").val() && $("tr:eq("+c+") td:eq(2)").text() == $("#id_rubro").val()) {
					c++;r++;
				}else{
					c++;
				}
 			});
 			if (r==0) {return false;}else{return true;}
		}else{return false;}
	}
	//realizamos una consulta a la BD para asegurar que puede solicitar el item y su cantidad
	function validar_item(){
		$.getJSON('./operaciones.php',{
			id_user:$("#id_user").val(),
			municipio:$("#municipio").val(),
			estado:$("#estado").val(),
			id_unidad:$("#id_unidad").val(),
			id_rubro:$("#id_rubro").val(),
			cant_sol:$("#can").val(),
			cant_max:can,
			action: 'validar_item'
		}, function(data){
			if (data.r == true) {
				switch(data.case) {
			    case 1:
			    	toastr.success(data.msg, "Enhorabuena");
			    	if ($("#list_sol tr").size()==1){$("#seccion-detalle").show();}//mostrar seccion de detalles
					let detalle = '<tr class="delta"><td class="ta-hide">'+$("#id_unidad").val()+'</td><td>'+$("#id_unidad option:selected").text()+'</td><td class="ta-hide">'+$("#id_rubro").val()+'</td><td>'+$("#id_rubro option:selected").text()+'</td><td>'+$("#des").val()+'</td><td>'+$("#can").val()+'</td><td><a href="#"><span class="badge bg-danger borrar"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></span></a></td></tr>'
					$("#list_sol").append(detalle);
					/*ocultamos por estetica*/
					$(".ta-hide").hide();
			    break;
			    case 2:
			    	toastr.info(data.msg, "Notificación");
			    	$("#can").val(data.dis);
			    break;
			    case 3:
			    	toastr.warning(data.msg, "Atención");
			    break;
			    default:
					toastr.error('No se puede establecer la conexón.', "Error")
				}
			}else{
				toastr.error(data.msg, "Error");
			}
		});
		//return c;
	}
	//evento para eliminar un detalle de la list
	$(document).on('click', '.borrar', function (event) {
		event.preventDefault();
    	$(this).closest('tr').remove();
		if ($("#list_sol tr").size()==1){$("#seccion-detalle").hide();}//ocualtar seccion de detalles
		toastr.warning('Eliminista el item de la lista de detalles.', "Atención");
	})
	$("#guardar_solicitud").click(function(){
		var detalles = crearArrayDatos();
		$.getJSON('./operaciones.php',{
			id_user:$("#id_user").val(),
			cod_linea:$("#id_linea").val(),
			cod_afiliado:$("#id_afiliado").val(),
			estado:$("#estado").val(),
			municipio:$("#municipio").val(),
			detalles:detalles,
			action: 'guardar_solicitud'
		}, function(data){	
			toastr.success(data.msg, "Tu solicitud fue registrada con exito.");
			eliminaFilas();
			limpiar_datos_add();
			document.getElementById("form_registrar").reset();
		})
	});
	function crearArrayDatos(){
		var datostabla = [];
		var i = 1;
		var j = 0;
		//alert($("#list_sol tbody tr").size())
		$("#list_sol tbody tr").each(function(){
			datostabla[j] = [
			$("tr:eq("+i+") td:eq(0)").text(),//id_unidad
			$("tr:eq("+i+") td:eq(2)").text(),//id_rubro
			$("tr:eq("+i+") td:eq(5)").text(),//cantidad*/
			];
			i++;j++;
 		});
 		return datostabla;
	}
	function eliminaFilas(){
	$("#list_sol tbody tr").each(function(){
		$(this).closest('tr').remove();
		$("#seccion-detalle").hide();
 	});
	}
</script>