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
users.id,
users.usuario,
CONCAT(nacionalidad,'-',cedula) as ced,
CONCAT(nombre,' ',apellido) as nom_completo,
CONCAT('COD:',cod_cp,' SER:',serial_cp) as cod_ser_cp,
users.telefono,
users.email,
users.nac_lin,
users.rif,
users.ruta,
users.cod_linea
FROM
users
WHERE
users.perfil = 5 and cod_linea=".$_SESSION['user'];
  
	$title ="Listado de afiliados";
                        
	$th = ['nombre','Cédula','usuario','telefono','Carnet de la patria','email'];
	$key_body = ['nom_completo','ced','usuario','telefono','cod_ser_cp','email'];
	$data = $system->sql();
	echo make_tablea($title,$th,$key_body,$data);

?>
	
<?
	include_once $_SESSION['base_url'].'partials/footer.php';
if ($_SESSION['nivel'] > 3){
	include_once $_SESSION['base_url'].'partials/modal_change_password_2.php';
}else{
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
}?>