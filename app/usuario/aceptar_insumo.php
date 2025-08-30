<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
    
	include_once $_SESSION['base_url'].'partials/header.php';

    $ids = base64_decode($_GET["ids"]);    

  
   $system->sql="SELECT
detalles_solicitudes.id_solicitud as idsol,
detalles_solicitudes.id as id_det,
asignaciones_solicitud.cantidad,
unidades.placa,
rubros.descripcion as descrubro,
productos.descripcion
FROM
detalles_solicitudes
INNER JOIN asignaciones_solicitud ON asignaciones_solicitud.id_detalle = detalles_solicitudes.id
INNER JOIN unidades ON unidades.id = detalles_solicitudes.id_unidad
INNER JOIN rubros ON rubros.id = detalles_solicitudes.id_rubro
INNER JOIN asignaciones ON asignaciones_solicitud.id_asignacion = asignaciones.id
INNER JOIN productos ON asignaciones.id_producto = productos.id
WHERE
detalles_solicitudes.estatus = 5 AND
detalles_solicitudes.id_solicitud =".$ids."
GROUP BY
detalles_solicitudes.id_rubro
";

  
	$title ="Listado de Insumos asignados";
                        
	$th = ['Placa',	'Item',	'Descripcion', 'Cantidad'];
	$key_body = ['placa','descrubro','descripcion','cantidad'];
	$data = $system->sql();
	echo make_tabla_aceptar_insumo($title,$th,$key_body,$data,$ids);

?>

<?
	include_once $_SESSION['base_url'].'partials/footer.php';
if ($_SESSION['nivel'] > 3){
	include_once $_SESSION['base_url'].'partials/modal_change_password_2.php';
}else{
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
}?>