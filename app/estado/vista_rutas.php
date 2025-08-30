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
municipios.municipio as mun,
rutas.*
FROM
municipios
INNER JOIN rutas ON municipios.id_municipio = rutas.municipio AND municipios.id_estado = rutas.estado
WHERE
rutas.estado =".$_SESSION['edo'];
  
	$title ="Rutas de transporte";
                        
	$th = ['Municipio','Ruta','Cantidad de paradas','Kilómetros'];
	$key_body = ['mun','ruta','cant_paradas','kilometros'];
	$data = $system->sql();
	echo make_tableuf($title,$th,$key_body,$data);

?>
	
<?
	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
?>