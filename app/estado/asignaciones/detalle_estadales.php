<?
  if(!isset($_SESSION))
    {
      session_start();
    }

  include_once $_SESSION['base_url'].'partials/header.php';
  
  $register = isset($_GET['register']) ? base64_decode($_GET['register']) : null;
  $todos    = isset($_GET['all']) ? base64_decode($_GET['all']) : null;
  
  if(!$register && !$todos){
    header('Location: '.$_SESSION['base_url1'].'app/estado/asignaciones/estadales.php');
  }

  $where = $register ? "id_asignacion = ".$register : "asignacion.id_estado = $_SESSION[edo]";

  $system->sql = "SELECT t1.id,cantidad,asignacion.descripcion as asignacion,
                  (SELECT item from inventario where id = t1.id_inventario) as item,
                  date_format(t1.created_at, '%d-%m-%Y %H:%i:%s') as fecha_creacion,
                  date_format(t1.updated_at, '%d-%m-%Y %H:%i:%s') as fecha_modificacion
                  FROM asignacion_detalle as t1
                  INNER JOIN asignacion ON asignacion.id = t1.id_asignacion 
                  WHERE $where
                  ORDER BY cantidad desc";

  $data = $system->sql();
?>
  <div class="row" data-appear-animation="fadeInRightBig">
    <div class="col-md-6 col-xl-12">
      <section class="panel">
        <div class="panel-body bg-primary">
          <div class="widget-summary">
            <div class="widget-summary-col widget-summary-col-icon">
              <div class="summary-icon">
                <i class="fa fa-life-ring"></i>
              </div>
            </div>
            <div class="widget-summary-col">
              <div class="summary">
                <h4 class="title">Item con mayor Cantidad: <br/><?= $data[0]->item ?></h4>
                <div class="info">
                  <strong class="amount"><?= $data[0]->cantidad ?></strong>
                </div>
              </div>
              <div class="summary-footer">
                <a class="text-uppercase" href="<?= $_SESSION['base_url1'].'app/estado/asignaciones/estadales.php' ?>">(Volver a Asignaciones)</a>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
<?if(count($data) > 1){?>
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
                <h4 class="title">Segundo Item con mayor Cantidad: <br/><?= $data[1]->item ?></h4>
                <div class="info">
                  <strong class="amount"><?= $data[1]->cantidad ?></strong>
                </div>
              </div>
              <div class="summary-footer">
                <a class="text-uppercase" href="<?= $_SESSION['base_url1'].'app/estado/asignaciones/estadales.php' ?>">(Volver a Asignaciones)</a>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
<? } ?>
  </div>
<?

  $title = "Detalle de Asignaciones Estadales";
  $th = ['Asignación','Item','Cantidad','Fecha Creación','Fecha Modificación'];
  $key_body = ['asignacion','item','cantidad','fecha_creacion','fecha_modificacion'];
  echo make_table_crud($title,$th,$key_body,$data,false,false,false,false,null,null,null);
?>
<?
  include_once $_SESSION['base_url'].'partials/footer.php';
?>