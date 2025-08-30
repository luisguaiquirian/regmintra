<?
  if(!isset($_SESSION))
    {
      session_start();
    }

  include_once $_SESSION['base_url'].'partials/header.php';

$system->sql="
  SELECT * FROM (
      SELECT sum(m.cantidad_asig) as cantidad,
      sum(m.cantidad_disponible) as disponible,
      pro.codigo,
      pro.descripcion,
      concat(pro.marca,'/',pro.modelo) as marcamodelo,
      concat(ru.descripcion,'/',ru_sub.descripcion) as tiposub,
      pre.descripcion as presentacion

      FROM mov_items as m
      INNER JOIN almacenes as al on al.id = m.destino
      INNER JOIN productos as pro on m.producto = pro.id
      inner JOIN rubros as ru on pro.tipo = ru.id
      INNER JOIN rubros_sub as ru_sub on  pro.subtipo = ru_sub.id and ru.id = ru_sub.id_rubro
      INNER JOIN presentaciones as pre on pro.presentacion = pre.id
      WHERE al.id_user = $_SESSION[user_id]
      GROUP BY m.producto
  ) as t1
";

  $title ="Productos del Inventario";
  $th = ['codigo','Producto','marca/modelo','tipo/subtipo','presentacion','cantidad','disponible'];
  $key_body = ['codigo','descripcion','marcamodelo','tiposub','presentacion','cantidad','disponible'];
  $data = $system->sql();
  $ruta_ver_detalle  = "detalles_producto_almacen.php?key=";
?>



<?
  include_once $_SESSION['base_url'].'partials/footer.php';
?>
