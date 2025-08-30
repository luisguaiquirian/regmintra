<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
    
	include_once $_SESSION['base_url'].'partials/header.php';
    

  
	$system->sql = "SELECT
users.usuario,
perfiles.nombre AS nomperfil,
users.cedula,
users.nombre,
users.apellido,
users.telefono,
users.email,
estados.estado,
municipios.municipio,
users.id
FROM
users
INNER JOIN perfiles ON perfiles.id = users.perfil
INNER JOIN estados ON estados.id_estado = users.estado
INNER JOIN municipios ON municipios.id_estado = users.estado AND municipios.id_municipio = users.municipio";
  
	$title ="Usuarios del Sistema";
                        
	$th = ['Estado','municipio','nombre de usuario','perfil','cédula','nombre','apellido','teléfono'];
	$key_body = ['estado','municipio','usuario','nomperfil','cedula','nombre','apellido','telefono'];
	$data = $system->sql();
	echo make_table_usuarios($title,$th,$key_body,$data);


	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
?>
