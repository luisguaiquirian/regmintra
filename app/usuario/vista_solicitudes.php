<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
    
	include_once $_SESSION['base_url'].'partials/header.php';
  
    $system->sql = "select *,CONCAT(a.nombre, ' ', a.apellido) As nombres,b.id as id_solicitudes from users as a 
inner join solicitudes as b on (a.id=b.id_user)
inner join estados as c  on (a.estado=c.id_estado)
inner join municipios as d on (a.municipio=d.id_municipio and c.id_estado=d.id_estado)
inner join estatus as e on (b.estatus=e.id)
where a.id=$_SESSION[user_id] and a.estado=$_SESSION[edo] and a.municipio=$_SESSION[mun]
    			";

  
	$title ="Listado de Solicitudes Realizadas";
                        
	$th = ['Estado','Municipio','Usuario','Nombre','Nombre de la Linea','Fecha de Solicitud','Estatus'];
	$key_body = ['estado','municipio','usuario','nombres','nombre_linea','fec_solicitud','descripcion'];
	$data = $system->sql();
	echo make_tabla_detalles_solicitud($title,$th,$key_body,$data);

?>

<?
	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'app/usuario/modales/modal_detalles.php';
if ($_SESSION['nivel'] > 3){
	include_once $_SESSION['base_url'].'partials/modal_change_password_2.php';
}else{
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
}?>