<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
    
	include_once $_SESSION['base_url'].'partials/header.php';
    
 
		// si existe el where de modificar buscamos el registro
        
        $system->sql = "SELECT * from municipios WHERE id_estado =".$_SESSION['edo'];
		$est = $system->sql();

   
	$system->sql = "SELECT
municipios.municipio,
municipios.id_municipio,
municipios.id_estado,
(SELECT COUNT(unidades.id) from unidades where activo = 0 AND unidades.estado = users.estado AND unidades.municipio = users.municipio) AS tunides,
(SELECT COUNT(unidades.id) from unidades where activo = 1 AND unidades.estado = users.estado AND unidades.municipio = users.municipio) AS tuniact,
(SELECT COUNT(users.usuario) FROM users where users.estado = municipios.id_estado AND users.perfil = 4 AND users.municipio = municipios.id_municipio) AS tlineas,
(SELECT COUNT(rutas.id) FROM rutas where rutas.estado = users.estado AND rutas.municipio = users.municipio) AS trutas
FROM
users
INNER JOIN municipios ON municipios.id_municipio = users.municipio AND municipios.id_estado = users.estado
WHERE
users.estado = ".$_SESSION['edo']."
GROUP BY
users.municipio";

  $lin = $system->sql();
	$title ="Líneas de transporte del estado ".$est[0]->municipio;
                        
	$th = ['Municipio','cantidad de rutas que cubre','unidades operativas','unidades inactivas'];
	$key_body = ['municipio','trutas','tuniact','tunides'];
	$data = $system->sql();
	echo make_table_esta_1($title,$th,$key_body,$data);


	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
?>
