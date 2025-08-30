<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
    
	include_once $_SESSION['base_url'].'partials/header.php';
    
	$system->sql = "SELECT
unidades.placa,
tipo_unidad.tipo_unidad,
CONCAT(users.nacionalidad,'-',users.cedula) AS ced,
CONCAT(users.nombre,' ',users.apellido) as propietario,
users.nombre_linea,
estados.estado,
municipios.municipio
FROM
unidades
INNER JOIN tipo_unidad ON tipo_unidad.id = unidades.tipo_unidad
INNER JOIN users ON users.cod_linea = unidades.cod_linea AND unidades.cod_afiliado = users.usuario
INNER JOIN estados ON estados.id_estado = unidades.estado
INNER JOIN municipios ON municipios.id_municipio = unidades.municipio AND municipios.id_estado = unidades.estado";
  
	$title ="Líneas de transporte a nivel nacional";
                        
	$th = ['estado','municipio','Línea de Transporte','placa','Tipo unidad','Cedula','propietario'];
	$key_body = ['estado','municipio','nombre_linea','placa','tipo_unidad','ced','propietario'];
	$data = $system->sql();
	echo make_table_unidades($title,$th,$key_body,$data);


	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
?>
