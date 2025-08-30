<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
	include_once $_SESSION['base_url'].'partials/header.php';

	$register = null;

	$options_municipio = "<option></option>";
	$options_parroquia = "<option></option>";

	if(isset($_GET['modificar']))
	{
		// si existe el where de modificar buscamos el registrp
		$system->table = "users";
		$register = $system->find(base64_decode($_GET['modificar']));
	}

	// where para el combo de municipio
	$where_municipio = $_SESSION['nivel'] >= 2 ? 'AND id_municipio = '.$_SESSION['municipio'] : '';



	$system->sql = "SELECT id_municipio, municipio from municipios where id_estado = 17 $where_municipio";

	foreach ($system->sql() as $row) 
	{
		// llenado del combo de municipios
		$selected = $register && $register->municipio === $row->id_municipio ? 'selected=""' : '';

		$options_municipio .= "<option value='{$row->id_municipio}' {$selected}>{$row->municipio}</option>";
	
	}

	// where para el combo de parroquias

	$where_parroquia = $_SESSION['nivel'] === '3' ? 'AND id_municipio = '.$_SESSION['municipio'].' AND id_parroquia = '.$_SESSION['parroquia'] : '';

	$system->sql = "SELECT id_municipio, id_parroquia, parroquia from parroquias where id_estado = 17 $where_parroquia";
	

	foreach ($system->sql() as $row) 
	{
		// llenado del combo de parroquias
		$selected = $register && $register->parroquia === $row->id_parroquia && $register->municipio === $row->id_municipio ? 'selected=""' : '';

		$options_parroquia .= "<option value='{$row->id_parroquia}' {$selected}>{$row->parroquia}</option>";
	}
?>
	<section class="panel">
		<header class="panel-heading">
			<h3 class="text-center panel-title">Crear Centro</h3>
		</header>
		<div class="panel-body">
			<form action="./operaciones.php" class="form-horizontal" id="form_registrar" method="POST">
				
				<input type="hidden" name="password_activo" value="0">
				<input type="hidden" name="action" value="<?= $register ? 'modificar' : 'registrar' ?>">
				<input type="hidden" name="id_modificar" value="<?= $register ? $register->id : '' ?>">

				<div class="form-group">
					<label for="" class="control-label col-md-2 col-sm-2">Usuario</label>
					<div class="col-md-4 col-sm-4">
						<input type="text" id="Usuario" name="usuario" class="form-control" required="" value="<?= $register ? $register->usuario : '' ?>">
					</div>
					<label for="" class="control-label col-md-2 col-sm-2">Perfil</label>
					<div class="col-md-4 col-sm-4">
						<select name="perfil" id="perfil" class="form-control">
							<option value="4">Centro</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="control-label col-md-2 col-sm-2">Activo</label>
					<div class="col-md-4 col-sm-4">
						<select name="activo" id="activo" class="form-control">
							<option value="1" <? $register && $register->activo === '1' ? 'selected' : '' ?> >Activo</option>
							<option value="0" <? $register && $register->activo === '0' ? 'selected' : '' ?>>Desactivado</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="" class="control-label col-md-2 col-sm-2">Municipio</label>
					<div class="col-md-4 col-sm-4">
						<select name="municipio" id="municipio" class="form-control" required="">
							<?= $options_municipio; ?>
						</select>
					</div>
					<label for="" class="control-label col-md-2 col-sm-2">Parroquia</label>
					<div class="col-md-4 col-sm-4">
						<select name="parroquia" id="parroquia" class="form-control" required="">
							<?= $register || $_SESSION['nivel'] === '3' ? $options_parroquia : ''; ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-4 col-md-4 col-sm-offset-4 col-sm-4">
						<button class="btn btn-block btn-success">Guardar&nbsp;<i class="fa fa-save"></i></button>
					</div>
				</div>
			</form>
		</div>
<?
	include_once $_SESSION['base_url'].'partials/footer.php';
?>

<script>
	
	$('select').select2()

	$('#municipio').change(function(e) {
		$.ajax({
			url: './operaciones.php',
			type: 'GET',
			dataType: 'JSON',
			data: {action: 'municipio', mun: e.target.value},
		})
		.done(function(data) {
			
			let options = data.length > 0 ? '<option>Seleccione</option>' : '<option>No hay Parroquias</option>'
			
			$.grep(data,function(i,e){
				options+= "<option value='"+i.id_parroquia+"'>"+i.parroquia+"</option>"
			})

			$('#parroquia').html(options)
		})
	});

	$('#form_registrar').submit(function(e) {
		/* Act on the event */
		e.preventDefault()

		$.ajax({
			url: './operaciones.php',
			type: 'POST',
			dataType: 'JSON',
			data: $(this).serialize(),
		})
		.done(function(data) {
			
			if(data.r)
			{
				setTimeout(function(){
					window.location.href = "./index.php";
				})
			}
			else
			{
				toastr.error('Usuario repetido!','Error!')
			}	
		})
	});

</script>