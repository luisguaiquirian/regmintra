<?
  if(!isset($_SESSION))
    {
      session_start();
    }

  include_once $_SESSION['base_url'].'partials/header.php';

  $year = date('Y');

  $system->sql = "SELECT SUM(cantidad) as total from asignacion_detalle
                  INNER JOIN asignacion ON asignacion.id = asignacion_detalle.id_asignacion
                  WHERE asignacion.id_estado = $_SESSION[edo] and YEAR(asignacion_detalle.updated_at) = '$year'";
  $res = $system->sql();

  echo '<link rel="stylesheet" href="'.$_SESSION["base_url1"].'assets/vendor/morris/morris.css"/>';
?>
  <div class="row" data-appear-animation="fadeInDown">
    <div class="col-md-6 col-xl-12">
      <section class="panel">
        <div class="panel-body bg-secondary">
          <div class="widget-summary">
            <div class="widget-summary-col widget-summary-col-icon">
              <div class="summary-icon">
                <i class="fa fa-life-ring"></i>
              </div>
            </div>
            <div class="widget-summary-col">
              <div class="summary">
                <h4 class="title">Cantidad Inventario Disponible</h4>
                <div class="info">
                  <strong class="amount"><?= $res[0]->total ?></strong>
                </div>
              </div>
              <div class="summary-footer">
                <a class="text-uppercase" href="<?= $_SESSION['base_url1'].'app/estado/asignaciones/detalle_estadales.php?all='.base64_encode('true') ?>">(Ver todos)</a>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <div class="col-md-6 col-sm-6">
      <section class="panel">
        <header class="panel-heading">
          <div class="panel-actions">
            <a href="#" class="fa fa-caret-down"></a>
            <a href="#" class="fa fa-times"></a>
          </div>
  
          <h2 class="panel-title">Inventario asignado por mes.</h2>
        </header>
        <div class="panel-body">
          <div class="chart chart-md" id="morrisLine"  style="height: 150px;"></div>
        </div>
      </section>
    </div>
  </div>
    
<?
  $system->sql = "SELECT id,descripcion,
                  (SELECT COUNT(*) from asignacion_detalle where id_asignacion = asignacion.id) as detalles,
                  date_format(created_at, '%d-%m-%Y %H:%i:%s') as fecha_creacion,
                  date_format(updated_at, '%d-%m-%Y %H:%i:%s') as fecha_modificacion
                  FROM asignacion WHERE type = 1 and id_estado =  $_SESSION[edo]";

  $data = $system->sql();

  $title = "Asignaciones Estadales";
  $th = ['Descripcion','Items Asignados','Fecha Creación','Fecha Modificación'];
  $key_body = ['descripcion','detalles','fecha_creacion','fecha_modificacion'];
  $title_ver = "Ver detalles";
  $ruta_ver  = "./detalle_estadales.php";
  echo make_table_crud($title,$th,$key_body,$data,false,false,false,true,null,null,null,$ruta_ver,$title_ver);
?>
<?
  include_once $_SESSION['base_url'].'partials/footer.php';
  echo '<script src="'.$_SESSION["base_url1"].'assets/vendor/morris/morris.js"></script>';
  echo '<script src="'.$_SESSION["base_url1"].'assets/vendor/raphael/raphael.js"></script>';
?>

<script>

  var meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

  var chartData = []

  <? 
    $system->sql = "SELECT SUM(t1.cantidad) as total, MONTH(t1.created_at) as month 
                  from asignacion_detalle as t1
                  INNER JOIN asignacion as t2 ON t2.id = t1.id_asignacion
                  WHERE t2.id_estado = $_SESSION[edo] and YEAR(t1.updated_at) = '$year'
                  GROUP BY month";

    foreach ($system->sql() as $row) { ?>
        chartData.push({y: meses['<?= $row->month - 1 ?>'], a: '<?= $row->total ?>'})
    <?}?>

  Morris.Line({
    resize: true,
    element: 'morrisLine',
    data: chartData,
    xkey: 'y',
    ykeys: ['a'],
    labels: ['Productos'],
    hideHover: true,
    lineColors: ['#0088cc'],
  });

</script>