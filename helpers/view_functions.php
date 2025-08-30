<?

function make_table_editable($title,$th,$key_body,$data,$clp)
	{
		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>

			<h2 class="panel-title">'.$title.'</h2>
			</header>
			<div class="panel-body">

		<div class="clearfix"></div>
		<table class="table table-bordered table-striped mb-none table-condensed"
			id="datatable-editable"
		>';

		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}


		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {

			$clase = '';
			switch ($con) {
				case 0:
					$clase = 'gradeX';
				break;

				case 1:
					$clase = 'gradeC';
				break;

				default:
					$clase = 'gradeA';
				break;
			}

			$body_html.='<tr class="'.$clase.'">';

			foreach ($key_body as $row1)
			{
				$body_html.= '<td>'.$row->{$row1}.'</td>';
			}

			$body_html.= '<td class="actions">
							<a href="#" class="hidden on-editing save-row" data-tool="tooltip" title="Guardar">
								<img src="'.$_SESSION['base_url1'].'assets/images/icons/save.jpg'.'" width="20px"/>
							</a>
							<a href="#" class="hidden on-editing cancel-row" data-tool="tooltip" title="Cancelar">
								<img src="'.$_SESSION['base_url1'].'assets/images/icons/cancel.jpg'.'" width="20px"/>
							</a>
							<a href="#" class="on-default edit-row" data-tool="tooltip" title="Modificar">
								<img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.jpg'.'" width="20px"/>
							</a>
							<a href="#" class="on-default remove-row" data-tool="tooltip" title="Eliminar">
								<img src="'.$_SESSION['base_url1'].'assets/images/icons/remove.jpg'.'" width="20px"/>
							</a>							</a>
							<a href="#" class="on-default edit-row" data-tool="tooltip" title="Aprobar">
								<img src="'.$_SESSION['base_url1'].'assets/images/icons/true.png'.'" width="20px"/>
							</a>
						</td>';

			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		$html.= '<div id="dialog" class="modal-block mfp-hide">
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">¿Está Seguro?</h2>
						</header>
						<div class="panel-body">
							<div class="modal-wrapper">
								<div class="modal-text">
									<p>¿Está seguro de querer eliminar este Registro?</p>
								</div>
							</div>
						</div>
						<footer class="panel-footer">
							<div class="row">
								<div class="col-md-12 text-right">
									<button id="dialogConfirm" class="btn btn-primary">Confirmar</button>
									<button id="dialogCancel" class="btn btn-default">Cancelar</button>
								</div>
							</div>
						</footer>
					</section>
				</div>';

		return $html;
	}

	function make_table($title,$th,$key_body,$data)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar




		$fila_th = '';

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">';

        echo'<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$agg.'
						</div>
					</div>
				</div>';
			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {

			$clase = '';
			switch ($con) {
				case 0:
					$clase = 'gradeX';
				break;

				case 1:
					$clase = 'gradeC';
				break;

				default:
					$clase = 'gradeA';
				break;
			}

			$body_html.='<tr class="'.$clase.'">';

			foreach ($key_body as $row1)
			{
				$image = '';

                //$row1->{$row1} = number_format($row->{$row1},'2', ',','.');

                if($row->{$row1} === '0')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/desactivado.jpg'.'" width="20px" data-tool="tooltip" title="desactivado"/>';
				}

				if($row->{$row1} === '1')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/true.png'.'" width="20px" data-tool="tooltip" title="Activado"/>';
				}

				if(empty($image))
				{

					$body_html.= '<td>'.$row->{$row1}.'</td>';
				}
				else
				{
					$body_html.= '<td>'.$image.'</td>';
				}

			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

function make_table_de_ee($title,$th,$key_body,$data)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">';

        echo'<div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';
			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {

			$clase = '';
			switch ($con) {
				case 0:
					$clase = 'gradeX';
				break;

				case 1:
					$clase = 'gradeC';
				break;

				default:
					$clase = 'gradeA';
				break;
			}

			$body_html.='<tr class="'.$clase.'">';

			foreach ($key_body as $row1)
			{
				$image = '';

				if($row->{$row1} === '0')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/desactivado.jpg'.'" width="20px" data-tool="tooltip" title="desactivado"/>';
				}

				if($row->{$row1} === '1')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/true.png'.'" width="20px" data-tool="tooltip" title="Activado"/>';
				}

				if(empty($image))
				{

					$body_html.= '<td>'.$row->{$row1}.'</td>';
				}
				else
				{
					$body_html.= '<td>'.$image.'</td>';
				}

			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}


	function make_table_crud($title,$th,$key_body,$data,$crear = true,$editar = true,$eliminar = true,$ver = true,$ruta_crear= null,$ruta_modificar = null, $ruta_eliminar = null,$ruta_ver = null,$title_ver = "ver detalles")
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';

		if($editar === true || $eliminar === true || $ver === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}


		if(!$ruta_crear){
				$ruta_crear = './add.php';
		}

		$html_crear = '<h1 class="panel-title">'.$title.'</h1><div class="clearfix"></div><br>        ';
		if($crear){
			$html_crear = '<div class="row" data-appear-animation="fadeInRightBig">
						<div class="col-md-12 text-center">
							<a href="'.$ruta_crear.'" class="btn btn-success">Crear Registro&nbsp;<i class="fa fa-plus"></i></a>
						<div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<h1 class="panel-title pull-left">'.$title.'</h1><div class="clearfix"></div><br>
						</div>
					</div>';
		}


		$html = '
		<section class="panel-featured">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				'.$html_crear.'
			</header>
			<div class="panel-body">
    		<div class="col-md-12">
					<table class="table table-bordered table-striped table-condensed table-responsive"
					id="datatable-default">';



		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {

			$clase = '';
			switch ($con) {
				case 0:
					$clase = 'gradeX';
				break;

				case 1:
					$clase = 'gradeC';
				break;

				default:
					$clase = 'gradeA';
				break;
			}

			$body_html.='<tr class="'.$clase.'">';

			foreach ($key_body as $row1)
			{
				$image = '';

				if($row->{$row1} === "desactivado_validador")
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/desactivado.jpg'.'" width="20px" data-tool="tooltip" title="desactivado"/>';
				}

				if($row->{$row1} === "activado_validador")
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/true.png'.'" width="20px" data-tool="tooltip" title="Activado"/>';
				}

				if(empty($image))
				{

					$body_html.= '<td>'.$row->{$row1}.'</td>';
				}
				else
				{
					$body_html.= '<td>'.$image.'</td>';
				}

			}

			$modificar_ruta = "";
			if(!$ruta_modificar){
				$modificar_ruta = './add.php?modificar='.base64_encode($row->id);
			}else{
				$modificar_ruta = $ruta_modificar."?modificar=".base64_encode($row->id);
			}

			$eliminar_ruta = "";

			if(!$ruta_eliminar){
				$eliminar_ruta = './operaciones.php?eliminar='.base64_encode($row->id);
			}else{
				$eliminar_ruta = $ruta_eliminar.base64_encode($row->id);
			}

			$ver_ruta = "";
			if(!$ruta_ver){
				$ver_ruta = './detalles.php?register='.base64_encode($row->id);
			}else{
				$ver_ruta = $ruta_ver."?register=".base64_encode($row->id);
			}

			$modificar = $editar ? '<a href="'.$modificar_ruta.'" data-tool="tooltip" title="Editar Registro"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.jpg'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="'.$eliminar_ruta.'" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Eliminar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/remove.jpg'.'"width="20px"/></a>' : '';


       $ver   = $ver ? '<a href="'.$ver_ruta.'" data-tool="tooltip" title="'.$title_ver.'"><img src="'.$_SESSION['base_url1'].'assets/images/icons/ver.png'.'"width="20px"/></a>' : '';

			if(!empty($modificar) || !empty($remover) || !empty($ver))
			{

				$body_html.= '<td class="">
								'.$modificar.'
								'.$remover.'
								'.$ver.'
							</td>';
			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}


	function make_tablev($title,$th,$key_body,$data,$editar = true,$eliminar = true,$ver = true)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';

		if($editar === true || $eliminar === true || $ver === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>
      </header>
      <div class="panel-body">
        <div col-md-12">
					<table class="table table-bordered table-striped table-condensed table-responsive"
					id="datatable-default">';

        if($_SESSION['nivel'] == 3){
				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$agg.'
						</div>
					</div>
				</div>';
			}


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {

			$clase = '';
			switch ($con) {
				case 0:
					$clase = 'gradeX';
				break;

				case 1:
					$clase = 'gradeC';
				break;

				default:
					$clase = 'gradeA';
				break;
			}

			$body_html.='<tr class="'.$clase.'">';

			foreach ($key_body as $row1)
			{
				$image = '';

				if($row->{$row1} === '0')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/desactivado.jpg'.'" width="20px" data-tool="tooltip" title="desactivado"/>';
				}

				if($row->{$row1} === '1')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/true.png'.'" width="20px" data-tool="tooltip" title="Activado"/>';
				}

				if(empty($image))
				{

					$body_html.= '<td>'.$row->{$row1}.'</td>';
				}
				else
				{
					$body_html.= '<td>'.$image.'</td>';
				}

			}

			$modificar = $editar ? '<a href="./incluir_linea.php?modificar='.base64_encode($row->id).'" data-tool="tooltip" title="Editar Registro"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.jpg'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="./operaciones.php?eliminar='.base64_encode($row->id).'&action=remover_linea" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Eliminar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/remove.jpg'.'"width="20px"/></a>' : '';


            $ver   = $mas ? '<a href="./partidas_add.php?agregar='.base64_encode($row->id).'" class="add_helper" data-tool="tooltip" id="add" title="Ver Afiliados"><img src="'.$_SESSION['base_url1'].'assets/images/icons/ver.png'.'"width="20px"/></a>' : '';

			if(!empty($modificar) || !empty($remover) || !empty($ver))
			{

				$body_html.= '<td class="">
								'.$modificar.'
								'.$remover.'
								'.$ver.'
							</td>';
			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></div></section>";

		return $html;

	}


	function make_table_conduc($title,$th,$key_body,$data,$editar = true,$eliminar = true,$add = true)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';

		if($editar === true || $eliminar === true || $add === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

        if($_SESSION['nivel'] == 3){
				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$agg.'
						</div>
					</div>
				</div>';
                }
			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {

			$clase = '';
			switch ($con) {
				case 0:
					$clase = 'gradeX';
				break;

				case 1:
					$clase = 'gradeC';
				break;

				default:
					$clase = 'gradeA';
				break;
			}

			$body_html.='<tr class="'.$clase.'">';

			foreach ($key_body as $row1)
			{
				$image = '';

				if($row->{$row1} === '0')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/desactivado.jpg'.'" width="20px" data-tool="tooltip" title="desactivado"/>';
				}

				if($row->{$row1} === '1')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/true.png'.'" width="20px" data-tool="tooltip" title="Activado"/>';
				}

				if(empty($image))
				{

					$body_html.= '<td>'.$row->{$row1}.'</td>';
				}
				else
				{
					$body_html.= '<td>'.$image.'</td>';
				}

			}

			$modificar = $editar ? '<a href="./incluir_conduc.php?modificar_conduc='.base64_encode($row->id_avance).'" data-tool="tooltip" title="Editar Registro"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.jpg'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="./operaciones.php?eliminar='.base64_encode($row->id_avance).'&action=remover_conduc" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Eliminar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/remove.jpg'.'"width="20px"/></a>' : '';


            $pluss   = $add ? '<a href="./agregar_carga.php?agregar='.base64_encode($row->id_avance).'" class="add_helper" data-tool="tooltip" id="add" title="Agregar Carga Familiar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/pluss.png'.'"width="20px"/></a>' : '';

			if(!empty($modificar) || !empty($remover) || !empty($ver))
			{

				$body_html.= '<td class="">
								'.$modificar.'
								'.$remover.'
								'.$pluss.'
							</td>';
			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

	function make_table_carga_familiar($title,$th,$key_body,$data,$editar = true,$eliminar = true)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';

		if($editar === true || $eliminar === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

        if($_SESSION['nivel'] == 3){
				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$agg.'
						</div>
					</div>
				</div>';
                }
			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';


		foreach ($data as $row) {


			$body_html.='<tr>';

			foreach ($key_body as $row1)
			{
				$image = '';

				if($row->{$row1} === '0')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/desactivado.jpg'.'" width="20px" data-tool="tooltip" title="desactivado"/>';
				}

				if($row->{$row1} === '1')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/true.png'.'" width="20px" data-tool="tooltip" title="Activado"/>';
				}

				if(empty($image))
				{

					$body_html.= '<td>'.$row->{$row1}.'</td>';
				}
				else
				{
					$body_html.= '<td>'.$image.'</td>';
				}

			}

			$modificar = $editar ? '<a href="./incluir_carga_familiar.php?modificar_carga='.base64_encode($row->id).'" data-tool="tooltip" title="Editar Registro"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.jpg'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="./operaciones.php?eliminar='.base64_encode($row->id).'&action=eliminar_carga" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Eliminar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/remove.jpg'.'"width="20px"/></a>' : '';


			if(!empty($modificar) || !empty($remover))
			{

				$body_html.= '<td class="">
								'.$modificar.'
								'.$remover.'
							</td>';
			}


			$body_html.= '</tr>';

		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}


	function make_table_mun_gen($title,$th,$key_body,$data)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';



		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

        if($_SESSION['nivel'] == 3){
				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$agg.'
						</div>
					</div>
				</div>';
                }
			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {

			$clase = '';
			switch ($con) {
				case 0:
					$clase = 'gradeX';
				break;

				case 1:
					$clase = 'gradeC';
				break;

				default:
					$clase = 'gradeA';
				break;
			}

			$body_html.='<tr class="'.$clase.'">';

			foreach ($key_body as $row1)
			{
				$image = '';

				if($row->{$row1} === '0')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/desactivado.jpg'.'" width="20px" data-tool="tooltip" title="desactivado"/>';
				}

				if($row->{$row1} === '1')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/true.png'.'" width="20px" data-tool="tooltip" title="Activado"/>';
				}

				if(empty($image))
				{

					$body_html.= '<td>'.$row->{$row1}.'</td>';
				}
				else
				{
					$body_html.= '<td>'.$image.'</td>';
				}

			}

			$modificar = $editar ? '<a href="./incluir_linea.php?modificar='.base64_encode($row->id).'" data-tool="tooltip" title="Editar Registro"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.jpg'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="./operaciones.php?eliminar='.base64_encode($row->id).'&action=remover_linea" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Eliminar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/remove.jpg'.'"width="20px"/></a>' : '';


            $add   = $agregar ? '<a href="./partidas_add.php?agregar='.base64_encode($row->id).'" class="add_helper" data-tool="tooltip" id="add" title="Agregar Partidas"><img src="'.$_SESSION['base_url1'].'assets/images/icons/pluss.png'.'"width="20px"/></a>' : '';

			if(!empty($modificar) || !empty($remover) || !empty($add))
			{

				$body_html.= '<td class="">

							</td>';
			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

	function make_tablea($title,$th,$key_body,$data,$editar = true,$eliminar = true,$ver = true)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';

		if($editar === true || $eliminar === true || $ver === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

        if($_SESSION['nivel'] == 3){
				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$agg.'
						</div>
					</div>
				</div>';
                }
			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {

			$clase = '';
			switch ($con) {
				case 0:
					$clase = 'gradeX';
				break;

				case 1:
					$clase = 'gradeC';
				break;

				default:
					$clase = 'gradeA';
				break;
			}

			$body_html.='<tr class="'.$clase.'">';

			foreach ($key_body as $row1)
			{
				$image = '';

				if($row->{$row1} === '0')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/desactivado.jpg'.'" width="20px" data-tool="tooltip" title="desactivado"/>';
				}

				if($row->{$row1} === '1')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/true.png'.'" width="20px" data-tool="tooltip" title="Activado"/>';
				}

				if(empty($image))
				{

					$body_html.= '<td>'.$row->{$row1}.'</td>';
				}
				else
				{
					$body_html.= '<td>'.$image.'</td>';
				}

			}

			$modificar = $editar ? '<a href="./incluir_afiliado.php?modificar='.base64_encode($row->id).'" data-tool="tooltip" title="Editar Registro"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.jpg'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="./operaciones.php?eliminar='.base64_encode($row->id).'&usuario='.base64_encode($row->usuario).'&action=remover_afiliado" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Eliminar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/remove.jpg'.'"width="20px"/></a>' : '';


            $mostrar   = $ver ? '<a href="./incluir_unidad.php?agregar='.base64_encode($row->id).'" class="add_helper" data-tool="tooltip" id="add" title="Agregar Unidad"><img src="'.$_SESSION['base_url1'].'assets/images/icons/pluss.png'.'"width="20px"/></a>' : '';

			if(!empty($modificar) || !empty($remover) || !empty($add))
			{

				$body_html.= '<td class="">
								'.$modificar.'
								'.$remover.'
								'.$mostrar.'
							</td>';
			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

		function make_table_lin_mun($title,$th,$key_body,$data,$excel = true,$editar = true,$eliminar = true,$ver = true, $imprimir = true, $lista = true)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar

        //$archivo = $excel ? '<a href="./form.php" class="btn btn-danger">Exportar a Excel <i class="fa fa-file-excel-o"></i></a>' : '';
        $archivo = $excel ? '<form name="exportar_csv" method="post" class="form" action="./reportes/list_lineas.php">
        <button type="submit" class="btn btn-danger" name="exportar">Exportar a Excel&nbsp;<i class="fa fa-file-excel-o"></i></button>
        </form>' : '';


		$fila_th = '';

		if($editar === true || $eliminar === true || $ver === true || $imprimir === true || $lista === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

        if($_SESSION['nivel'] == 3){
				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$archivo.'
						</div>
					</div>
				</div>';
                }
			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {

			$clase = '';
			switch ($con) {
				case 0:
					$clase = 'gradeX';
				break;

				case 1:
					$clase = 'gradeC';
				break;

				default:
					$clase = 'gradeA';
				break;
			}

			$body_html.='<tr class="'.$clase.'">';

			foreach ($key_body as $row1)
			{
				$image = '';

				if($row->{$row1} === '0' && $row1 !== "total_unidades" && $row1 !== "tuniact" && $row1 !== "tunides")
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/desactivado.jpg'.'" width="20px" data-tool="tooltip" title="desactivado"/>';
				}


				if(empty($image))
				{

					$body_html.= '<td>'.$row->{$row1}.'</td>';
				}
				else
				{
					$body_html.= '<td>'.$image.'</td>';
				}

			}

			$modificar = $editar ? '<a href="./incluir_linea.php?modificar='.base64_encode($row->id).'" data-tool="tooltip" title="Editar Registro"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.jpg'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="./operaciones.php?eliminar='.base64_encode($row->id).'&action=remover_linea" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Eliminar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/remove.jpg'.'"width="20px"/></a>' : '';

            $mostrar   = $ver ? '<a href="./vista_afiliados.php?id='.base64_encode($row->usuario).'" class="add_helper" data-tool="tooltip" id="add" title="Ver Asociados"><img src="'.$_SESSION['base_url1'].'assets/images/icons/ver.png'.'"width="20px"/></a>' : '';

            $reporte   = $imprimir ? '<a href="./reportes/list_unidades_linea.php?id='.base64_encode($row->usuario).'" target="_blank" class="add_helper" data-tool="tooltip" id="print" title="Imprimir lista de unidades"><img src="'.$_SESSION['base_url1'].'assets/images/icons/impresora.png'.'"width="20px"/></a>' : '';

            $unidades = $lista ? '<a href="./vista_unidades_linea.php?id='.base64_encode($row->usuario).'" class="add_helper" data-tool="tooltip" id="print" title="Listado de Unidades"><img src="'.$_SESSION['base_url1'].'assets/images/icons/bus.png'.'"width="20px"/></a>' : '';

			if(!empty($modificar) || !empty($remover) || !empty($mostrar) || !empty($reporte) || !empty($unidades))
			{

				$body_html.= '<td class="">
								'.$modificar.'
								'.$remover.'
								'.$mostrar.'
								'.$reporte.'
								'.$unidades.'
							</td>';
			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

		function make_table_unidades_linea($title,$th,$key_body,$data,$aprobar = true,$rechazar = true)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar



		$fila_th = '';

		if($aprobar === true || $rechazar === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {

			$clase = '';
			switch ($con) {
				case 0:
					$clase = 'gradeX';
				break;

				case 1:
					$clase = 'gradeC';
				break;

				default:
					$clase = 'gradeA';
				break;
			}

			$body_html.='<tr class="'.$clase.'">';

			foreach ($key_body as $row1)
			{
				$image = '';

				if($row->{$row1} === '0' && $row1 !== "total_unidades" && $row1 !== "tuniact" && $row1 !== "tunides")
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/desactivado.jpg'.'" width="20px" data-tool="tooltip" title="desactivado"/>';
				}


				if(empty($image))
				{

					$body_html.= '<td>'.$row->{$row1}.'</td>';
				}
				else
				{
					$body_html.= '<td>'.$image.'</td>';
				}

			}
            if ($row->verf == 0){
			$modificar = $aprobar ? '<a href="./operaciones.php?id='.base64_encode($row->id).'&linea='.base64_encode($row->cod_linea).'&action=aprobar" data-tool="tooltip" title="Validar unidad"><img src="'.$_SESSION['base_url1'].'assets/images/icons/espera.png'.'" width="20px"/></a>' : '';

            $remover   = $rechazar ? '<a data-target="#modal_rechazar" data-id="'.base64_encode($row->id).'" data-toggle="modal" data-tool="tooltip" title="Rechazar" ><img src="'.$_SESSION['base_url1'].'assets/images/icons/rechazar.png'.'"width="20px"/></a>' : '';

            }if ($row->verf == 1){

            $modificar = $aprobar ? '<spam data-tool="tooltip" title="Unidad Verificada"><img src="'.$_SESSION['base_url1'].'assets/images/icons/verificacion.png'.'" width="20px"/></spam>' : '';
            $remover   = $rechazar ? '':'';
            }

            if ($row->verf == 2){

            $modificar = $aprobar ? '' : '';
            $remover   = $rechazar ? '<spam data-tool="tooltip" title="'.$row->rechazo.'"><img src="'.$_SESSION['base_url1'].'assets/images/icons/cancel.jpg'.'" width="20px"/></spam>' : '';
            }

			if(!empty($modificar) || !empty($remover))
			{

				$body_html.= '<td class="">
								'.$modificar.'
								'.$remover.'
							</td>';
			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

	function make_tabla_detalles_solicitud($title,$th,$key_body,$data,$editar = true,$eliminar = false,$ver = true){
		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar

		$fila_th = '';

		if($editar === true || $eliminar === true || $ver === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

        if($_SESSION['nivel'] == 3){
				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$agg.'
						</div>
					</div>
				</div>';
                }
		$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='</tr></thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {
			$body_html.='<tr class="'/*.$clase*/.'">';
			foreach ($key_body as $row1)
			{
				$body_html.= '<td>'.$row->{$row1}.'</td>';
			}

			$modificar = $editar ? '<a href="./incluir_afiliado.php?modificar='.base64_encode($row->id).'" data-tool="tooltip" title="Editar Registro"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.png'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="./operaciones.php?id='.base64_encode($row->id).'&action=reset_clave" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Resetear Password"><img src="'.$_SESSION['base_url1'].'assets/images/icons/cancel.jpg'.'"width="20px"/></a>' : '';


            $mostrar   = $ver ? '<a href="javascript:void(0);" onclick="mostrar_Items('.$row->id_solicitudes.');" class="add_helper" data-tool="tooltip" id="add" title="Ver detalles"><img src="'.$_SESSION['base_url1'].'assets/images/icons/ver.png'.'"width="20px"/></a>' : '';

			if(!empty($modificar) || !empty($remover) || !empty($add)){
				$body_html.= '<td class="">'.$mostrar.'</td>';
			}

			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}
	function make_tabler($title,$th,$key_body,$data,$editar = true,$eliminar = true)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';

		if($editar === true || $eliminar === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {

			$clase = '';
			switch ($con) {
				case 0:
					$clase = 'gradeX';
				break;

				case 1:
					$clase = 'gradeC';
				break;

				default:
					$clase = 'gradeA';
				break;
			}

			$body_html.='<tr class="'.$clase.'">';

			foreach ($key_body as $row1)
			{
				$image = '';

/*				if($row->{$row1} === '0')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/desactivado.jpg'.'" width="20px" data-tool="tooltip" title="desactivado"/>';
				}

				if($row->{$row1} === '1')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/true.png'.'" width="20px" data-tool="tooltip" title="Activado"/>';
				}
*/
				if(empty($image))
				{

					$body_html.= '<td>'.$row->{$row1}.'</td>';
				}
				else
				{
					$body_html.= '<td>'.$image.'</td>';
				}

			}

			$modificar = $editar ? '<a href="./incluir_rutas.php?modificar='.base64_encode($row->id).'" data-tool="tooltip" title="Editar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.jpg'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="./operaciones.php?eliminar='.base64_encode($row->id).'&action=remover_ruta" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Eliminar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/remove.jpg'.'"width="20px"/></a>' : '';


			if(!empty($modificar) || !empty($remover))
			{

				$body_html.= '<td class="">
								'.$modificar.'
								'.$remover.'
							</td>';
			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}


	function make_tabler_mun($title,$th,$key_body,$data,$editar = true,$eliminar = true)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';

		if($editar === true || $eliminar === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {

			$clase = '';
			switch ($con) {
				case 0:
					$clase = 'gradeX';
				break;

				case 1:
					$clase = 'gradeC';
				break;

				default:
					$clase = 'gradeA';
				break;
			}

			$body_html.='<tr class="'.$clase.'">';

			foreach ($key_body as $row1)
			{
				$image = '';

/*				if($row->{$row1} === '0')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/desactivado.jpg'.'" width="20px" data-tool="tooltip" title="desactivado"/>';
				}

				if($row->{$row1} === '1')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/true.png'.'" width="20px" data-tool="tooltip" title="Activado"/>';
				}
*/
				if(empty($image))
				{

					$body_html.= '<td>'.$row->{$row1}.'</td>';
				}
				else
				{
					$body_html.= '<td>'.$image.'</td>';
				}

			}

			$modificar = $editar ? '<a href="./incluir_rutas.php?modificar_ruta='.base64_encode($row->id).'" data-tool="tooltip" title="Editar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.jpg'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="./operaciones.php?eliminar='.base64_encode($row->id).'&action=remover_ruta" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Eliminar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/remove.jpg'.'"width="20px"/></a>' : '';


			if(!empty($modificar) || !empty($remover))
			{

				$body_html.= '<td class="">
								'.$modificar.'
								'.$remover.'
							</td>';
			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

	function make_tableu($title,$th,$key_body,$data,$editar = true, $eliminar = true)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';

		if($editar === true || $eliminar === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

        if($_SESSION['nivel'] == 3){
				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$agg.'
						</div>
					</div>
				</div>';
                }
			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {

			$clase = '';
			switch ($con) {
				case 0:
					$clase = 'gradeX';
				break;

				case 1:
					$clase = 'gradeC';
				break;

				default:
					$clase = 'gradeA';
				break;
			}

			$body_html.='<tr class="'.$clase.'">';

			foreach ($key_body as $row1)
			{
				$image = '';

				if($row->{$row1} === '0')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/espera.png'.'" width="20px" data-tool="tooltip" title="En espera de Verificación"/>';
				}

				if($row->{$row1} === '1')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/true.png'.'" width="20px" data-tool="tooltip" title="Aprobado"/>';
				}

				if($row->{$row1} === '2')
				{
					$image= '<img src="'.$_SESSION['base_url1'].'assets/images/icons/remove.jpg'.'" width="20px" data-tool="tooltip" title="Rechazado"/>';
				}

				if(empty($image))
				{

					$body_html.= '<td>'.$row->{$row1}.'</td>';
				}
				else
				{
					$body_html.= '<td>'.$image.'</td>';
				}

			}

			$modificar = $editar ? '<a href="./incluir_unidad.php?id_usuario='.base64_encode($row->id_usuario).'&modificar_unidad='.base64_encode($row->id).'" data-tool="tooltip" title="Editar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.jpg'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="./operaciones.php?eliminar='.base64_encode($row->id).'&action=remover_unidad" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Eliminar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/remove.jpg'.'"width="20px"/></a>' : '';


 /*           $mostrar   = $ver ? '<a href="./vista_unidades.php?agregar='.base64_encode($row->id).'" class="add_helper" data-tool="tooltip" id="add" title="Ver Unidades"><img src="'.$_SESSION['base_url1'].'assets/images/icons/bus.png'.'"width="20px"/></a>' : '';*/

			if(!empty($modificar) || !empty($remover))
			{

				$body_html.= '<td class="">
								'.$modificar.'
								'.$remover.'
							</td>';
			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

	function make_table_esta($title,$th,$key_body,$data,$linea = null,$editar = true,$eliminar = true,$ver = true)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';

		if($editar === true || $eliminar === true || $ver === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel animated bounceInUp slow">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

        if($_SESSION['nivel'] == 3){
				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$agg.'
						</div>
					</div>
				</div>';
                }
			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {



			$clase = '';
			switch ($con) {
				case 0:
					$clase = 'gradeX';
				break;

				case 1:
					$clase = 'gradeC';
				break;

				default:
					$clase = 'gradeA';
				break;
			}

			$body_html.='<tr class="'.$clase.'">';

			foreach ($key_body as $row1)
			{
				$body_html.= '<td>'.$row->{$row1}.'</td>';


			}

			$modificar = $editar ? '<a href="./incluir_afiliado.php?modificar='.base64_encode($row->id).'" data-tool="tooltip" title="Editar Registro"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.jpg'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="./operaciones.php?eliminar='.base64_encode($row->id).'&action=remover_linea" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Eliminar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/remove.jpg'.'"width="20px"/></a>' : '';


      $mostrar   = $ver ? '<a href="./vista_muni.php?id='.base64_encode($row->id_estado).'" class="add_helper" data-tool="tooltip" id="add" title="Ver detalles"><img src="'.$_SESSION['base_url1'].'assets/images/icons/ver.png'.'"width="20px"/></a>' : '';

			if(!empty($modificar) || !empty($remover) || !empty($add))
			{

				$body_html.= '<td class="">

								'.$mostrar.'
							</td>';
			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

	function make_table_esta_1($title,$th,$key_body,$data,$ver = true)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';

		if($ver === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel animated bounceInUp delay-4s slow">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

        if($_SESSION['nivel'] == 3){
				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$agg.'
						</div>
					</div>
				</div>';
                }
			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {



			$body_html.='<tr>';

			foreach ($key_body as $row1)
			{
				$body_html.= '<td>'.$row->{$row1}.'</td>';


			}

/*			$modificar = $editar ? '<a href="./incluir_afiliado.php?modificar='.base64_encode($row->id).'" data-tool="tooltip" title="Editar Registro"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.jpg'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="./operaciones.php?eliminar='.base64_encode($row->id).'&action=remover_linea" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Eliminar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/remove.jpg'.'"width="20px"/></a>' : '';	*/


            $mostrar   = $ver ? '<a href="./vista_lineas_gen.php?id='.base64_encode($row->id_estado).'&id_mun='.base64_encode($row->id_municipio).'" class="add_helper" data-tool="tooltip" id="add" title="Ver detalles"><img src="'.$_SESSION['base_url1'].'assets/images/icons/ver.png'.'"width="20px"/></a>' : '';

			if(!empty($modificar) || !empty($remover) || !empty($mostrar))
			{

				$body_html.= '<td class="">

								'.$mostrar.'
							</td>';
			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

	function make_table_lin($title,$th,$key_body,$data,$editar = true,$eliminar = true,$ver = true)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';

		if($editar === true || $eliminar === true || $ver === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

        if($_SESSION['nivel'] == 3){
				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$agg.'
						</div>
					</div>
				</div>';
                }
			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {



			$body_html.='<tr>';

			foreach ($key_body as $row1)
			{
				$body_html.= '<td>'.$row->{$row1}.'</td>';


			}

			$modificar = $editar ? '<a href="./incluir_afiliado.php?modificar='.base64_encode($row->id).'" data-tool="tooltip" title="Editar Registro"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.jpg'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="./operaciones.php?eliminar='.base64_encode($row->id).'&action=remover_linea" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Eliminar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/remove.jpg'.'"width="20px"/></a>' : '';


            $mostrar   = $ver ? '<a href="./vista_linea_detalles.php?id='.base64_encode($row->id).'" class="add_helper" data-tool="tooltip" id="add" title="Ver detalles"><img src="'.$_SESSION['base_url1'].'assets/images/icons/ver.png'.'"width="20px"/></a>' : '';

			if(!empty($modificar) || !empty($remover) || !empty($add))
			{

				$body_html.= '<td class="">

								'.$mostrar.'
							</td>';
			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

	function make_table_lin_unidades($title,$th,$key_body,$data,$editar = true,$eliminar = true,$ver = true)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';

		if($editar === true || $eliminar === true || $ver === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {



			$body_html.='<tr>';

			foreach ($key_body as $row1)
			{
				$body_html.= '<td>'.$row->{$row1}.'</td>';


			}

			$modificar = $editar ? '<a href="./incluir_afiliado.php?modificar='.base64_encode($row->id).'" data-tool="tooltip" title="Editar Registro"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.jpg'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="./operaciones.php?eliminar='.base64_encode($row->id).'&action=remover_linea" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Eliminar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/remove.jpg'.'"width="20px"/></a>' : '';


            $mostrar   = $ver ? '<a href="./vista_unidades.php?id='.base64_encode($row->cod_linea).'&usuario='.base64_encode($row->usuario).'" class="add_helper" data-tool="tooltip" id="add" title="Ver Unidades"><img src="'.$_SESSION['base_url1'].'assets/images/icons/ver.png'.'"width="20px"/></a>' : '';

			if(!empty($modificar) || !empty($remover) || !empty($add))
			{

				$body_html.= '<td class="">

								'.$mostrar.'
							</td>';
			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

function make_tableuf($title,$th,$key_body,$data)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';



		$html = '
		<section class="panel animated bounceInUp slow">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {



			$body_html.='<tr>';

			foreach ($key_body as $row1)
			{
				$body_html.= '<td>'.$row->{$row1}.'</td>';


			}




			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

function make_table_unidades($title,$th,$key_body,$data)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';



		$html = '
		<section class="panel animated bounceInUp slow">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {



			$body_html.='<tr>';

			foreach ($key_body as $row1)
			{
				$body_html.= '<td>'.$row->{$row1}.'</td>';


			}




			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

function make_table_muni($title,$th,$key_body,$data,$editar = true,$eliminar = true,$ver = true)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';

		if($editar === true || $eliminar === true || $ver === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

        if($_SESSION['nivel'] == 3){
				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$agg.'
						</div>
					</div>
				</div>';
                }
			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {



			$body_html.='<tr>';

			foreach ($key_body as $row1)
			{
				$body_html.= '<td>'.$row->{$row1}.'</td>';


			}

/*			$modificar = $editar ? '<a href="./incluir_afiliado.php?modificar='.base64_encode($row->id).'" data-tool="tooltip" title="Editar Registro"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.jpg'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="./operaciones.php?eliminar='.base64_encode($row->id).'&action=remover_linea" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Eliminar"><img src="'.$_SESSION['base_url1'].'assets/images/icons/remove.jpg'.'"width="20px"/></a>' : '';	*/


            $mostrar   = $ver ? '<a href="./vista_lineas.php?id='.base64_encode($row->id_estado).'&id_mun='.base64_encode($row->id_municipio).'" class="add_helper" data-tool="tooltip" id="add" title="Ver detalles"><img src="'.$_SESSION['base_url1'].'assets/images/icons/ver.png'.'"width="20px"/></a>' : '';

			if(!empty($modificar) || !empty($remover) || !empty($mostrar))
			{

				$body_html.= '<td class="">

								'.$mostrar.'
							</td>';
			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

function make_table_usuarios($title,$th,$key_body,$data,$editar = true,$eliminar = true,$ver = true)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';

		if($editar === true || $eliminar === true || $ver === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

        if($_SESSION['nivel'] == 3){
				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$agg.'
						</div>
					</div>
				</div>';
                }
			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {



			$body_html.='<tr>';

			foreach ($key_body as $row1)
			{
				$body_html.= '<td>'.$row->{$row1}.'</td>';


			}

			$modificar = $editar ? '<a href="./incluir_afiliado.php?modificar='.base64_encode($row->id).'" data-tool="tooltip" title="Editar Registro"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.jpg'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="./operaciones.php?id='.base64_encode($row->id).'&action=reset_clave" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Resetear Password"><img src="'.$_SESSION['base_url1'].'assets/images/icons/cancel.jpg'.'"width="20px"/></a>' : '';




			if(!empty($modificar) || !empty($remover) || !empty($mostrar))
			{

				$body_html.= '<td class="">

								'.$remover.'
							</td>';
			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

	/*etapa v1 efrain*/

	function make_inventario_general($title,$th,$key_body,$data,$ver,$details = true)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';

		if($details === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

        if($_SESSION['nivel'] == 3){
				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$agg.'
						</div>
					</div>
				</div>';
                }
			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {



			$body_html.='<tr>';

			foreach ($key_body as $row1)
			{
				$body_html.= '<td>'.$row->{$row1}.'</td>';


			}

			$details ? $detalle = $ver ? '<a href="'.$ver.base64_encode($row->pro).'&d='.base64_encode($row->descripcion).'" data-tool="tooltip" title="Ver detalle por almacenes"><i class="fa fa-folder"></i></a>' : '' : '';




			if($details)
			{

				$body_html.= '<td class="">

								'.$detalle.'
							</td>';
			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

	function make_inventario_detalle_producto($title,$th,$key_body,$data)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';

		/*if($details === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}*/

		$html = '
		
    <section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">

        <div class="col-xs-12">
          <div class="btn-group btn-group-sm" role="group" aria-label="...">
            <a class="btn btn-default btn-sm btn-primary" href="'.$_SESSION['base_url1'].'app/admin/inventario/ver_inventario.php'.'" role="button"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Volver a Inventario.</a>
            <a class="btn btn-default btn-sm btn-primary" href="javascript:void(0)" role="button" disabled="disabled"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Detalles de almacenes.</a>
          </div>
    	</div>

    	<hr>

		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

        if($_SESSION['nivel'] == 3){
				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$agg.'
						</div>
					</div>
				</div>';
                }
			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {



			$body_html.='<tr>';

			foreach ($key_body as $row1)
			{
				$body_html.= '<td>'.$row->{$row1}.'</td>';


			}

			/*$detalle = $ver ? '<a href="'.$ver.base64_encode($row->pro).'&d='.$row->descripcion.'" data-tool="tooltip" title="Editar Registro"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.jpg'.'" width="20px"/></a>' : '';




			if(!empty($detalle))
			{

				$body_html.= '<td class="">

								'.$detalle.'
							</td>';
			}*/


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}
	function make_tabla_aceptar_insumo($title,$th,$key_body,$data,$ids,$editar = true,$eliminar = true,$ver = true){
		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar

		$fila_th = '';

		if($editar === true || $eliminar === true || $ver === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

        if($_SESSION['nivel'] == 3){
				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$agg.'
						</div>
					</div>
				</div>';
                }
		$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='</tr></thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {
			$body_html.='<tr class="'/*.$clase*/.'">';
			foreach ($key_body as $row1)
			{
				$body_html.= '<td>'.$row->{$row1}.'</td>';
			}

			$modificar = $editar ? '<a href="./incluir_afiliado.php?modificar=" data-tool="tooltip" title="Editar Registro"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.png'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="./operaciones.php?id_det_sol=&action=aceptar_insumo" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Resetear Password"><img src="'.$_SESSION['base_url1'].'assets/images/icons/cancel.jpg'.'"width="20px"/></a>' : '';


            $mostrar   = $ver ? '<a href="./operaciones.php?id_det_sol='.base64_encode($row->id_det).'&idsol='.base64_encode($row->idsol).'&action=aceptar_insumo" class="add_helper" data-tool="tooltip" id="add" title="Aceptar Insumo"><img src="'.$_SESSION['base_url1'].'assets/images/icons/verificacion.png'.'"width="20px"/></a>' : '';

			if(!empty($modificar) || !empty($remover) || !empty($add)){
				$body_html.= '<td class="">'.$mostrar.'</td>';
			}

			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

	function make_tabla_vista_asig($title,$th,$key_body,$data,$editar = true,$eliminar = true,$ver = true){
		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar

		$fila_th = '';

		if($editar === true || $eliminar === true || $ver === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

        if($_SESSION['nivel'] == 3){
				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$agg.'
						</div>
					</div>
				</div>';
                }
		$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='</tr></thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {
			$body_html.='<tr class="'/*.$clase*/.'">';
			foreach ($key_body as $row1)
			{
				$body_html.= '<td>'.$row->{$row1}.'</td>';
			}

			$modificar = $editar ? '<a href="./incluir_afiliado.php?modificar=" data-tool="tooltip" title="Editar Registro"><img src="'.$_SESSION['base_url1'].'assets/images/icons/edit.png'.'" width="20px"/></a>' : '';

			$remover   = $eliminar ? '<a href="./operaciones.php?id_det_sol=&action=aceptar_insumo" class="remover_helper" data-tool="tooltip" id="btn_eliminar" title="Resetear Password"><img src="'.$_SESSION['base_url1'].'assets/images/icons/cancel.jpg'.'"width="20px"/></a>' : '';


            $mostrar   = $ver ? '<a href="'.$_SESSION['base_url1'].'app/usuario/preview_asig.php?'.'idasig='.base64_encode($row->id_det).'" class="add_helper" data-tool="tooltip" id="add" title="Imprimir Nota de retiro"><i class="fa fa-print" aria-hidden="true"></i></a>' : '';

			if(!empty($modificar) || !empty($remover) || !empty($add)){
				$body_html.= '<td class="">'.$mostrar.'</td>';
			}

			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

function make_table_despacho($title,$th,$key_body,$data,$editar = true,$eliminar = true,$ver = true)
	{

		// title = Título de la caja
		// th = array de los th de la tabla
		// key_body = aray de los campos a imprimir
		// data = arreglo q viene de la bd o manual con los datos
		// crear = si es true aparece el botón de guardar
		// modificar = si es true aparece el botón de modificar
		// eliminar = si es true aparece el botón de eliminar


		$fila_th = '';

		if($editar === true || $eliminar === true || $ver === true)
		{
			$fila_th = '<th class="text-center text-primary">Acciones</th>';
		}

		$html = '
		<section class="panel">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div>
				<h1 class="panel-title">'.$title.'</h1><br>

        <div col-md-10">
		<div class="panel panel-default">
        <div class="panel-body">
		<table class="table table-bordered table-striped table-condensed table-responsive"
			id="datatable-default">';

        if($_SESSION['nivel'] == 3){
				$html .='<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="mb-md pull-right">
							'.$agg.'
						</div>
					</div>
				</div>';
                }
			$html .='</header>';


		$th_html = "<thead><tr>";

		foreach ($th as $row)
		{
			$th_html.= '<th class="text-primary text-center">'.ucwords($row).'</th>';
		}

		$th_html.= $fila_th;

		$th_html.='
					</tr>
				</thead>';

		$body_html ='<tbody class="text-center">';

		$con = 0;

		foreach ($data as $row) {



			$body_html.='<tr>';

			foreach ($key_body as $row1)
			{
				$body_html.= '<td>'.$row->{$row1}.'</td>';


			}

/*			$modificar = $editar ? '<a href="./incluir_afiliado.php?modificar='.base64_encode($row->almacen_destino).'" data-tool="tooltip" class="btn btn default" role="button" title="Ver detalles"><img src="'.$_SESSION['base_url1'].'assets/images/icons/ver.png'.'" width="20px"/></a>' : '';
*/
			$remover   = $eliminar ? '<a target="_blank" href="./carta_despacho.php?asig='.base64_encode($row->id).'" id="20" data-toggle="modal" class="btn btn-default" data-tool="tooltip" title="Emitir Nota de entrega"><i role="button" class="fa fa-check" aria-hidden="true"></i></a>' : '';




			if(!empty($modificar) || !empty($remover) || !empty($mostrar))
			{

				$body_html.= '<td class="">

								'.$remover.'

							</td>';
			}


			$body_html.= '</tr>';

			$con++;
		}

		$body_html.= '</tbody>';

		$html.= $th_html.$body_html."</table></div></section>";

		return $html;
	}

?>
