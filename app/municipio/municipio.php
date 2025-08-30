<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}

	include_once $_SESSION['base_url'].'partials/header.php';

	$system->sql = "SELECT u.id,u.usuario, m.municipio, u.activo, u.password_activo 
					from users as u
					INNER JOIN municipios as m ON u.municipio = m.id_municipio and m.id_estado = 17
					WHERE u.perfil = 2";

	$title = "Usuarios Municipios";
	$th = ['usuario','municipio','activo','password activo'];
	$key_body = ['usuario','municipio','activo','password_activo'];
	$data = $system->sql();

	echo make_table($title,$th,$key_body,$data,false,false,false,false,false);
?>
<?
	include_once $_SESSION['base_url'].'partials/footer.php';
?>