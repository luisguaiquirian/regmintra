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

<div class="row">
  <div class="col-md-6 col-lg-6 col-xl-12 animated bounceInRight slow">
    <section class="panel panel-featured-left panel-featured-tertiary">
      <div class="panel-body">
        <div class="widget-summary">
          <div class="widget-summary-col widget-summary-col-icon">
            <div class="summary-icon bg-tertiary">
              <i style="margin-top: 20%" class="fa fa-shopping-cart"></i>
            </div>
          </div>
          <div class="widget-summary-col">
            <div class="summary">
              <h4 class="title">Total Productos</h4>
              <div class="info">
                <strong class="amount"><?= count($data); ?></strong>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<?
  echo make_inventario_general($title,$th,$key_body,$data,$ruta_ver_detalle,false);
?>


<?
  include_once $_SESSION['base_url'].'partials/footer.php';
?>
