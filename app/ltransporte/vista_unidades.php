<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
    
	include_once $_SESSION['base_url'].'partials/header.php';
  
                            $system->sql = "SELECT
unidades.*,
users.cedula,
CONCAT(users.nombre,' ',users.apellido) as propietario,
users.id as id_usuario,
tipo_unidad.tipo_unidad,
marcas_vehiculos.marca,
modelos_vehiculos.modelo,
lubricantes.lubricante,
rutas.ruta
FROM
unidades
INNER JOIN users ON users.usuario = unidades.cod_afiliado
INNER JOIN tipo_unidad ON tipo_unidad.id = unidades.tipo_unidad
INNER JOIN marcas_vehiculos ON marcas_vehiculos.id = unidades.marca
INNER JOIN modelos_vehiculos ON modelos_vehiculos.id = unidades.modelo
INNER JOIN lubricantes ON lubricantes.id = unidades.tipo_lub
INNER JOIN rutas ON rutas.id = unidades.ruta
where unidades.estado =".$_SESSION['edo']." AND unidades.municipio =".$_SESSION['mun']." AND unidades.cod_linea=".$_SESSION['user'];

  
	$title ="Listado de unidades registradas - Por Verificar: ".'<img src="'.$_SESSION['base_url1'].'assets/images/icons/espera.png'.'" width="20px" />'.' / Verificada: '.'<img src="'.$_SESSION['base_url1'].'assets/images/icons/true.png'.'" width="20px" />'.' / Rechazada: '.'<img src="'.$_SESSION['base_url1'].'assets/images/icons/remove.jpg'.'" width="20px" />';  
                        
	$th = ['Tipo','capacidad','marca','modelo','año','placa','lubricante','ruta','propietario','Estatus'];
	$key_body = ['tipo_unidad','cap','marca','modelo','ano','placa','lubricante','ruta','propietario','verf'];
	$data = $system->sql();
	echo make_tableu($title,$th,$key_body,$data);

?>
	
<?
	include_once $_SESSION['base_url'].'partials/footer.php';
if ($_SESSION['nivel'] > 3){
	include_once $_SESSION['base_url'].'partials/modal_change_password_2.php';
}else{
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
}?>