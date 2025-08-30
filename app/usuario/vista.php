<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
    
	include_once $_SESSION['base_url'].'partials/header.php';
  
    $system->sql = "select * from users where estado = $_SESSION[edo] AND municipio = $_SESSION[mun] AND perfil = 4";
    
                foreach ($system->sql() as $row) 
					{	
                            $id = $row->id;

                            
                        }
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
WHERE unidades.cod_afiliado =".$_SESSION['user'];

  
	$title ="Listado de unidades registradas";
                        
	$th = ['Tipo','capacidad','marca','modelo','año','placa','lubricante','ruta','propietario'];
	$key_body = ['tipo_unidad','cap','marca','modelo','ano','placa','lubricante','ruta','propietario'];
	$data = $system->sql();
	echo make_tableuf($title,$th,$key_body,$data);

?>
	
<?
	include_once $_SESSION['base_url'].'partials/footer.php';
if ($_SESSION['nivel'] > 3){
	include_once $_SESSION['base_url'].'partials/modal_change_password_2.php';
}else{
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
}?>