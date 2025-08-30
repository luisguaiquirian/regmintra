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
avances.id as id_avance,
avances.id_afiliado,
avances.id_unidad,
avances.estado,
avances.municipio,
concat(avances.nac_avan,'-',avances.ced_avan) as cedula,
concat(avances.nom_avan,' ',avances.ape_avan) as nombre_avance,
avances.email_avan,
avances.telf_avan,
avances.sercp_avan,
avances.codcp_avan,
avances.activo,
unidades.id,
unidades.placa
FROM
avances
INNER JOIN unidades ON avances.id_unidad = unidades.id
WHERE unidades.cod_afiliado =".$_SESSION['user'];

  
	$title ="Listado de Conductores";
                        
	$th = ['Cédula.','nombre','teléfono','e-mail','Unidad asignada'];
	$key_body = ['cedula','nombre_avance','telf_avan','email_avan','placa'];
	$data = $system->sql();
	echo make_table_conduc($title,$th,$key_body,$data);

?>
	
<?
	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'partials/footer.php';
if ($_SESSION['nivel'] > 3){
	include_once $_SESSION['base_url'].'partials/modal_change_password_2.php';
}else{
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
}?>