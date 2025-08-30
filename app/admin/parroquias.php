<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}

	include_once $_SESSION['base_url'].'partials/header.php';


	if($_SESSION['nivel'] == 1)
                                    {
											$system->sql = "SELECT	
					u.id,u.usuario,u.perfil,m.municipio,p.parroquia,u.activo,u.password_activo,centro_votaciones.nombre_centro
					FROM
					users AS u INNER JOIN municipios AS m ON u.municipio = m.id_municipio AND m.id_estado = 17
					INNER JOIN parroquias AS p ON p.id_parroquia = u.parroquia AND u.municipio = p.id_municipio AND p.id_estado = 17
					INNER JOIN centro_votaciones ON centro_votaciones.ctro_prop = u.usuario
					WHERE u.perfil = 3 and centro_votaciones.estado = 17";

	$title = "Usuarios Comités";
	$th = ['usuario','Centro','municipio','parroquia','activo','password activo'];
	$key_body = ['usuario','nombre_centro','municipio','parroquia','activo','password_activo'];
	$data = $system->sql();
        
									}
	else
                                    {
											$system->sql = "SELECT	
					u.id,u.usuario,m.municipio,p.parroquia,u.activo,u.password_activo,centro_votaciones.nombre_centro
					FROM
		users AS u INNER JOIN municipios AS m ON u.municipio = m.id_municipio AND m.id_estado = 17 AND u.municipio = $_SESSION[municipio]
					INNER JOIN parroquias AS p ON p.id_parroquia = u.parroquia AND u.municipio = p.id_municipio AND p.id_estado = 17
					INNER JOIN centro_votaciones ON centro_votaciones.ctro_prop = u.usuario
					WHERE u.perfil = 3 and centro_votaciones.estado = 17";

	$title = "Usuarios Comités";
	$th = ['usuario','Centro','municipio','parroquia','activo','password activo'];
	$key_body = ['usuario','nombre_centro','municipio','parroquia','activo','password_activo'];
	$data = $system->sql();
									}
	echo make_table($title,$th,$key_body,$data,false,false,false,false,false);
?>
<?
	include_once $_SESSION['base_url'].'partials/footer.php';
?>