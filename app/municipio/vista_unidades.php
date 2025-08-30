<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
    
	include_once $_SESSION['base_url'].'partials/header.php';
  
    if(isset($_GET['id']) and $_GET['usuario'])
	{

                            $system->sql = "SELECT
unidades.id,
unidades.cap,
unidades.ano,
unidades.placa,
unidades.cant_lubri,
unidades.num_neu,
unidades.cant_neu,
unidades.acumulador,
unidades.obser,
unidades.activo,
users.cedula,
concat(users.nombre,' ',users.apellido) as propietario,
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
WHERE
unidades.cod_linea =".base64_decode($_GET['id'])." AND
unidades.cod_afiliado =".base64_decode($_GET['usuario']);
		$uni = $system->sql();
	} 


  
	$title ="Listado de unidades registradas de ".$uni[0]->propietario;
                        
	$th = ['Tipo','capacidad','marca','modelo','año','placa','lubricante','ruta'];
	$key_body = ['tipo_unidad','cap','marca','modelo','ano','placa','lubricante','ruta'];
	$data = $system->sql();
	echo make_tableuf($title,$th,$key_body,$data);

?>
	
<?
	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
?>