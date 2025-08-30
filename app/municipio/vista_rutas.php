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
rutas.ruta,
users.nombre_linea,
CONCAT(users.nombre,' ',users.apellido) as responsable,
users.telefono,
tipo_ruta.tipo_ruta,
municipios.municipio
FROM
rutas
INNER JOIN estados ON estados.id_estado = rutas.estado
INNER JOIN municipios ON municipios.id_estado = rutas.estado AND municipios.id_municipio = rutas.municipio
INNER JOIN users ON users.usuario = rutas.cod_linea
INNER JOIN tipo_ruta ON tipo_ruta.id_ruta = users.tipo_ruta
where rutas.estado =".$_SESSION[edo]." AND rutas.municipio =".$_SESSION['mun'];
  $mun = $system->sql();
	$title ="Rutas de transporte municipio ".$mun[0]->municipio;
                        
	$th = ['Nombre de la Ruta','Línea de transporte','Tipo de Ruta','responsable','teléfono'];
	$key_body = ['ruta','nombre_linea','tipo_ruta','responsable','telefono'];
	$data = $system->sql();
	echo make_tableuf($title,$th,$key_body,$data,$linea);

?>
	
<?
	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
?>