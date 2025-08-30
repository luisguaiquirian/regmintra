<?
  if(!isset($_SESSION))
    {
      session_start();
    }
  include_once $_SESSION['base_url'].'partials/header.php';
  $e = base64_decode($_GET['e']);
  $r = base64_decode($_GET['r']);
  $sel = "";
  $sel2 = "";
  $inner = "";
  $group = "";
  switch ($r) {
    case 1:
      $sel = "cauchos.neumatico as producto,";
      $sel2 = " cauchos.id AS id_producto ";
      $inner = " INNER JOIN cauchos ON unidades.num_neu = cauchos.id ";
      $group = " cauchos.id ";
    break;
    case 2:
      $sel = "lubricantes.lubricante as producto, ";
      $sel2 = " lubricantes.id AS id_producto ";
      $inner = " INNER JOIN lubricantes ON unidades.tipo_lub = lubricantes.id ";
      $group = "lubricantes.id";
    break;
    case 3:
      $sel = "acumuladores.acumulador as producto, ";
      $sel2 = " acumuladores.id AS id_producto ";
      $inner = " INNER JOIN acumuladores ON unidades.acumulador = acumuladores.id ";
      $group = "acumuladores.id";
    break;    
    default:
      for ($i=0; $i < 50; $i++) { 
        echo 'Luis G. Error';
      }
    break;
  }

  $system->sql = "SELECT estados.estado,detalles_solicitudes.id_rubro,unidades.placa,detalles_solicitudes.cantidad,unidades.num_neu,".$sel."
Count(detalles_solicitudes.id) AS solicitudes,
Sum(detalles_solicitudes.cantidad) AS cantidad,
estados.id_estado,
rubros.descripcion,
".$sel2."
FROM
detalles_solicitudes
INNER JOIN solicitudes ON detalles_solicitudes.id_solicitud = solicitudes.id
INNER JOIN estados ON solicitudes.estado = estados.id_estado
INNER JOIN unidades ON detalles_solicitudes.id_unidad = unidades.id
INNER JOIN rubros ON detalles_solicitudes.id_rubro = rubros.id
".$inner."
WHERE
estados.id_estado = ".$e." AND
detalles_solicitudes.estatus = 1 AND
detalles_solicitudes.id_rubro = ".$r."
GROUP BY ".$group;

?>
  
  <section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
      <div class="panel-actions">
        <a href="#" class="fa fa-caret-down"></a>
        <a href="#" class="fa fa-times"></a>
      </div>
      <h1 class="panel-title">Consignar al Estado.</h1>
    </header>
      <div class="panel-body">
        <a class="btn btn-default" href="<?php echo $_SESSION['base_url1']."app/admin/asignaciones/asig_items_estados.php"?>" role="button"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Volver</a>
        <hr>

        <div class="alert alert-info" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Consignar producto al estado para responder solicitudes especificas.</div>
       
            <table class="table table-bordered table-striped mb-none table-condensed" id="tabla_detallados">
              <thead><tr>
                <th>Estado</th>
                <th>Tipo</th>
                <th>Producto</th>
                <th>Solicitudes</th>
                <th>Cantidad</th>
                <th>Accion</th>
              </tr></thead>
              <tbody id="cont_tabla_entrada">
                <?php
                  $x=0;
                  foreach ($system->sql() as $rs) {
                    echo '<tr id ='.$x.'>
                          <td class="text-danger"><a data-tool="tooltip" title="Ver historial de consignación del Estado '.$rs->estado.' para el producto '.$rs->producto.'" href="javascript:void(0)" onclick="mostrar_historial_consignaciones('.$e.','.$rs->id_rubro.','.$rs->id_producto.')">'.$rs->estado.'</a></td>
                          <td>'.$rs->descripcion.'</td>
                          <td class="text-primary">'.$rs->producto.'</td>
                          <td><span class="badge">'.$rs->solicitudes.'</span></td>
                          <td><span class="badge">'.$rs->cantidad.'</span></td>
                          <td>&nbsp;<a data-tool="tooltip" title="Consignar producto al estado para la solicitud de '.$rs->producto.'" onclick="modal_paso_uno('.$rs->cantidad.','.$rs->id_rubro.','.$e.','.$rs->id_producto.');" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-check" aria-hidden="true"></i></a>&nbsp;</td>
                          </tr>';
                          $x++;
                  }
                  unset($x);
                ?>
              </tbody>
            </table>
          
        
        </div>

</div>

      </div>
  </section>
        
<?
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/modal_carga.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/modal_paso_uno.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/modal_paso_dos.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/modal_historal_consignaciones.php';
  include_once $_SESSION['base_url'].'partials/footer.php';
?>
<script src="<?= $_SESSION['base_url1'].'assets/js/asignaciones_admin.js'?>"></script>
<script>
   $(document).ready(function(){$("#tabla_detallados").DataTable();});
</script>