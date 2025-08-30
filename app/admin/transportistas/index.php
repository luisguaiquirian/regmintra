<?
  if(!isset($_SESSION))
    {
      session_start();
    }

  include_once $_SESSION['base_url'].'partials/header.php';

  $system->sql = "SELECT id,
                  (SELECT estado from estados where id = t1.estado) as estado,
                  (SELECT municipio from municipios where id_municipio = t1.municipio and id_estado = t1.estado) as municipio,
                  (SELECT COUNT(*) FROM unidades WHERE t1.usuario = unidades.cod_afiliado) as unidades,
                  concat(t1.nombre,' ',t1.apellido) as nombre_completo,
                  concat(t1.nacionalidad,'-',t1.cedula) as ced,
                  t1.telefono,
                  t1.nombre_linea as linea
                  FROM users as t1 where perfil = 5";

  $data = $system->sql();

?>
  <div class="row" data-appear-animation="fadeInRightBig">
    <div class="col-md-6 col-xl-12">
      <section class="panel">
        <div class="panel-body bg-primary">
          <div class="widget-summary">
            <div class="widget-summary-col widget-summary-col-icon">
              <div class="summary-icon">
                <i class="fa fa-car"></i>
              </div>
            </div>
            <div class="widget-summary-col">
              <div class="summary">
                <h4 class="title">Cantidad de Transportistas</h4>
                <div class="info">
                  <strong class="amount"><?= count($data) ?></strong>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
<?
  $title = "Listado de Transportistas";
  $th = ['Estado','Municipio','Cédula','Datos Personales','Cant. Vehículos','telefono','Línea de transporte'];
  $key_body = ['estado','municipio','ced','nombre_completo','unidades','telefono','linea'];
  $ruta_eliminar  = "../operaciones.php?action=eliminar_transportista&id=";
  $ruta_ver = "./detalle_transportistas.php";
  echo make_table_crud($title,$th,$key_body,$data,false,false,false,true,null,null,null,$ruta_ver,"Ver Unidades");
?>
<?
  include_once $_SESSION['base_url'].'partials/footer.php';
?>