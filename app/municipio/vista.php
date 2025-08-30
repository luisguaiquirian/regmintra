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
users.nacionalidad,
users.cedula,
concat(users.nombre,' ',users.apellido) as nombre_completo,
users.telefono,
users.nac_lin,
users.rif,
users.nombre_linea,
users.ruta,
users.telefono,
users.gremio,
users.cant_socios,
users.observaciones,
tipo_ruta.tipo_ruta,
users.usuario,
users.id,
(SELECT COUNT(*) from unidades where activo = 0 AND users.usuario = unidades.cod_linea) AS tunides,
(SELECT COUNT(*) from unidades where activo = 1 AND users.usuario = unidades.cod_linea) AS tuniact,
(SELECT COUNT(unidades.id) from unidades where cod_linea = users.usuario) as total_unidades
FROM
users
INNER JOIN tipo_ruta ON users.tipo_ruta = tipo_ruta.id_ruta
where estado =". $_SESSION['edo']." AND municipio =".$_SESSION['mun']." AND perfil = 4";
  
	$title ="Líneas de transporte";
                        
	$th = ['Línea de Transporte','responsable','teléfono','nombre de usuario','gremio','Tipo','total unidades','Unidades Activas','Unidades inactivas','Cant. Socios'];
	$key_body = ['nombre_linea','nombre_completo','telefono','usuario','gremio','tipo_ruta','total_unidades','tuniact','tunides','cant_socios'];
	$data = $system->sql();
	echo make_table_lin_mun($title,$th,$key_body,$data);

?>
	
<?
	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
?>