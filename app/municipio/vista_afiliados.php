<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
    
	include_once $_SESSION['base_url'].'partials/header.php';
  
	if(isset($_GET['id']))
	{

	$system->sql = "SELECT
users.id,
users.usuario,
CONCAT(users.nacionalidad,'-',users.cedula) as ced,
CONCAT(users.nombre,' ',users.apellido) as responsable,
users.telefono,
users.email,
users.nac_lin,
users.rif,
users.ruta,
users.cod_linea
FROM
users
WHERE
users.perfil = 5 and users.estado =".$_SESSION['edo']." AND users.municipio=".$_SESSION['mun']." AND cod_linea =".base64_decode($_GET['id']);
		
	} 



  
	$title ="Listado de afiliados";
                        
	$th = ['usuario','cédula','nombre','telefono','email'];
	$key_body = ['usuario','ced','responsable','telefono','email'];
	$data = $system->sql();
	echo make_table_lin_unidades($title,$th,$key_body,$data);

?>
	
<?
	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
?>