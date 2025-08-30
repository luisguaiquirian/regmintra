<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}
    
	include_once $_SESSION['base_url'].'partials/header.php';
    

  
	$system->sql = "SELECT
t1.id,
t1.cantidad_asig,
u6.descripcion,
u6.marca,
u6.codigo,
u6.modelo,
u6.precio,
u4.nombre as almacen_destino,
u4.direccion as direccion_destino,
(SELECT nombre from almacenes where id = u5.almacen) as almacen_origen,
(SELECT direccion from almacenes where id = u5.almacen) as direccion_origen,
u2.cantidad_asignada
FROM
mov_items as t1
INNER JOIN mov_items_almacenes u1 ON t1.id = u1.id_mov
INNER JOIN asignaciones u2 ON u1.id_asignacion = u2.id
INNER JOIN asignaciones_solicitud u3 ON u3.id_asignacion = u2.id
INNER JOIN almacenes u4 ON u4.id = t1.destino
INNER JOIN inventario u5 ON u5.id = t1.inventario_salida
INNER JOIN productos u6 ON u2.id_producto = u6.id
WHERE u1.estatus = 5 
GROUP BY
u1.id_asignacion";
  
	$title ="Despacho del producto";
                        
	$th = ['Almacen de Origen','dirección','Almacen Destino','Dirección Destino','Descripción','Marca','Modeo','Cantidad Total'];
	$key_body = ['almacen_origen','direccion_origen','almacen_destino','direccion_destino','descripcion','marca','modelo','cantidad_asig'];
	$data = $system->sql();
	echo make_table_despacho($title,$th,$key_body,$data);

include_once $_SESSION['base_url'].'app/admin/modales/modal_transporte.php';
	include_once $_SESSION['base_url'].'partials/footer.php';
	include_once $_SESSION['base_url'].'partials/modal_change_password.php';
?>
