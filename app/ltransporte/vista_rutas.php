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
rutas.id,
rutas.ruta,
municipios.municipio,
estados.estado
FROM
rutas
INNER JOIN municipios ON municipios.id_municipio = rutas.municipio AND municipios.id_estado = rutas.estado
INNER JOIN estados ON estados.id_estado = municipios.id_estado
where rutas.estado =".$_SESSION[edo]." AND rutas.municipio =".$_SESSION['mun']." AND rutas.cod_linea=".$_SESSION['user'];

  
	$title ="Rutas de transporte";
                        
	$th = ['Estado','Municipio','Ruta'];
	$key_body = ['estado','municipio','ruta'];
	$data = $system->sql();
	echo make_tabler($title,$th,$key_body,$data,$linea);

?>
	
<?
	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
?>