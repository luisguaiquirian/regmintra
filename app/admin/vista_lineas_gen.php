<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
    
	include_once $_SESSION['base_url'].'partials/header.php';
    
	$system->sql = "SELECT
users.nacionalidad,
users.cedula,
users.nombre,
CONCAT(users.nombre,' ',users.apellido) AS responsable,
users.nac_lin,
users.rif,
users.nombre_linea,
users.ruta,
users.telefono,
users.gremio,
users.observaciones,
tipo_ruta.tipo_ruta,
users.usuario,
users.id,
estados.estado,
municipios.municipio
FROM
users
INNER JOIN tipo_ruta ON users.tipo_ruta = tipo_ruta.id_ruta
INNER JOIN estados ON estados.id_estado = users.estado
INNER JOIN municipios ON municipios.id_estado = users.estado AND municipios.id_municipio = users.municipio";
  
	$title ="Líneas de transporte a nivel nacional";
                        
	$th = ['estado','municipio','Línea de Transporte','gremio','Tipo','responsable','teléfono','usuario'];
	$key_body = ['estado','municipio','nombre_linea','gremio','tipo_ruta','responsable','telefono','usuario'];
	$data = $system->sql();
	echo make_tableuf($title,$th,$key_body,$data);


	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
?>
