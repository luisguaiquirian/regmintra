<?
  if(!isset($_SESSION))
    {
      session_start();
    }

  include_once $_SESSION['base_url'].'partials/header.php';

  /*$system->sql = "SELECT item,cantidad,id,activo,
                  date_format(created_at, '%d-%m-%Y %H:%i:%s') as fecha_creacion,
                  date_format(updated_at, '%d-%m-%Y %H:%i:%s') as fecha_modificacion
                  FROM inventario";

  $data = $system->sql();

  $title = "Inventario General";
  $th = ['Item','Cantidad','Fecha Ingreso','Fecha Modificación','Activo'];
  $key_body = ['item','cantidad','fecha_creacion','fecha_modificacion','activo'];
  $ruta_crear = "./add_inventario.php";
  $ruta_modificar = "./add_inventario.php";
  $ruta_eliminar  = "../operaciones.php?action=eliminar_inventario&id=";
  echo make_table_crud($title,$th,$key_body,$data,true,true,true,false,$ruta_crear,$ruta_modificar,$ruta_eliminar);*/

  $system->sql="SELECT a.producto as pro,b.codigo,b.descripcion,concat(b.marca,'/',b.modelo) as marcamodelo,concat(c.descripcion,'/',d.descripcion) as tiposub,e.descripcion as presentacion,
SUM(a.cantidad) as cantidad,SUM(a.disponible) as disponible,SUM(a.comprometido) as comprometido
FROM inventario as a 
inner JOIN productos as b on (a.producto=b.id)
inner JOIN rubros as c on (b.tipo=c.id)
INNER JOIN rubros_sub as d on (b.subtipo=d.id and c.id=d.id_rubro)
INNER JOIN presentaciones as e on (b.presentacion=e.id)
GROUP BY a.producto";

$title ="Inventario General";                        
  $th = ['codigo','Producto','marca/modelo','tipo/subtipo','presentacion','cantidad','disponible','comprometido'];
  $key_body = ['codigo','descripcion','marcamodelo','tiposub','presentacion','cantidad','disponible','comprometido'];
  $data = $system->sql();
  $ruta_ver_detalle  = "detalles_producto_almacen.php?key=";
  echo make_inventario_general($title,$th,$key_body,$data,$ruta_ver_detalle);
?>
<?
  include_once $_SESSION['base_url'].'partials/footer.php';
?>