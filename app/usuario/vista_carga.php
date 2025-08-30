<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
    
	include_once $_SESSION['base_url'].'partials/header.php';
  
    $system->sql = "SELECT  carga_familiar.id,
                            carga_familiar.id_usuario,
                            CONCAT(nac_carga,'-',ced_carga) as cedula,
                            CONCAT(nom_carga,' ',ape_carga) as nombre_completo,
                            sexo,
                            fecha_nac,
                            parentesco.parentesco,
                            email_carga,
                            telf_carga,
                            CONCAT('serial:',sercp_carga,'código:',codcp_carga) as carnet
                            
                            
                                    FROM
                                    carga_familiar
                                    INNER JOIN users ON users.id = carga_familiar.id_usuario
                                    INNER JOIN parentesco ON parentesco.id = carga_familiar.parentesco
                                    WHERE carga_familiar.id_usuario =".$_SESSION['user_id'];

  
	$title ="Carga familiar";
                        
	$th = ['Cédula','nombre','Fecha de Nacimiento','Sexo','parentesco','e-mail','Teléfono','Carnet de la Patria'];
	$key_body = ['cedula','nombre_completo','fecha_nac','sexo','parentesco','email_carga','telf_carga','carnet'];
	$data = $system->sql();
	echo make_table_carga_familiar($title,$th,$key_body,$data);

?>
	
<?
	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'partials/footer.php';
if ($_SESSION['nivel'] > 3){
	include_once $_SESSION['base_url'].'partials/modal_change_password_2.php';
}else{
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
}?>