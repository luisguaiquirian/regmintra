<?
  if(!isset($_SESSION))
    {
      session_start();
    }
  include_once $_SESSION['base_url'].'partials/header.php';

  if (isset($_GET['e']) and isset($_GET['ide']) and isset($_GET['r']) and isset($_GET['i']) and isset($_GET['d'])) {
    
    $estado=base64_decode(trim($_GET['e']));
    $id_estado=base64_decode(trim($_GET['ide']));
    $tipo=base64_decode(trim($_GET['r']));
    $subtipo=base64_decode(trim($_GET['i']));
    $descripcion=base64_decode(trim($_GET['d']));

    unset($_GET['e'],$_GET['r'],$_GET['i'],$_GET['d'],$_GET['ide']);

    switch ($tipo) {
      case '1'://Neumatico
        $system->sql="SELECT e.estado,e.id_estado,m.municipio,m.id_municipio,c.id as id_producto,c.neumatico as subtipo,
          sum(b.cantidad) as cantidad,
          COUNT(*) as solicitudes
          FROM solicitudes as a
          inner join detalles_solicitudes as b on (a.id=b.id_solicitud)
          inner join estados as e on (a.estado=e.id_estado)
          inner join municipios as m on (a.estado=m.id_estado and a.municipio=m.id_municipio)
          inner join unidades as u on (b.id_unidad=u.id)
          inner join cauchos as c on (u.num_neu=c.id) 
          where b.id_rubro=".$tipo." and c.neumatico='".$subtipo."' and a.estado='".$id_estado."' and (a.estatus=1 and b.estatus=1)
          GROUP BY a.municipio";
      break;

      case '2'://Lubricante
        $system->sql="SELECT e.estado,e.id_estado,m.municipio,m.id_municipio,l.id as id_producto,l.lubricante as subtipo,
          SUM(b.cantidad) as cantidad,COUNT(*) as solicitudes
          FROM solicitudes as a
          inner join detalles_solicitudes as b on (a.id=b.id_solicitud)
          inner join estados as e on (a.estado=e.id_estado)
          inner join municipios as m on (a.estado=m.id_estado and a.municipio=m.id_municipio)
          inner join unidades as u on (b.id_unidad=u.id)
          inner join lubricantes as l on (u.tipo_lub=l.id) 
          where b.id_rubro=".$tipo." and l.lubricante='".$subtipo."' and e.id_estado=".$id_estado." and (a.estatus=1 and b.estatus=1)
          GROUP BY(a.municipio)";
      break;

      case '3'://acumulador
        $system->sql="SELECT e.estado,e.id_estado,m.municipio,m.id_municipio,d.id as id_producto,d.acumulador as subtipo,
          SUM(b.cantidad) as cantidad,COUNT(*) as solicitudes
          FROM solicitudes as a
          inner join detalles_solicitudes as b on (a.id=b.id_solicitud)
          inner join estados as e on (a.estado=e.id_estado)
          inner join municipios as m on (a.estado=m.id_estado and a.municipio=m.id_municipio)
          inner join unidades as u on (b.id_unidad=u.id)
          inner join acumuladores as d on (u.acumulador=d.id)
          where b.id_rubro=".$tipo." and d.acumulador='".$subtipo."' and e.id_estado=".$id_estado." and (a.estatus=1 and b.estatus=1)
          GROUP BY(a.municipio)";
      break;
      default:
        echo 'Opción invalida...';
      break;
    }
  }
?>
  
  <section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
      <div class="panel-actions">
        <a href="#" class="fa fa-caret-down"></a>
        <a href="#" class="fa fa-times"></a>
      </div>
      <h1 class="panel-title">Asignaciones del estado <span class="text-danger"><?php echo $estado;?></span> pendientes.</h1>
    </header>
      <div class="panel-body">
        <div class="col-xs-12"><div class="loader" style="margin: 0 auto;margin-top: 10px;margin-bottom: 10px;display: none;"></div></div>
        <!--var estado para entrega-->
        <form id="formHeaderAsignacion">
          <input type="hidden" name="estado_entrega" id="estado_entrega" value="<?= $id_estado?>">
          <input type="hidden" name="municipio_entrega" id="municipio_entrega" value="">
          <input type="hidden" name="linea_entrega" id="linea_entrega" value="0">
        </form>

        <div class="col-xs-12">
          
          <div class="btn-group btn-group-sm" role="group" aria-label="...">

            <a class="btn btn-default btn-sm btn-primary" href="<?= $_SESSION['base_url1'].'app/admin/asignaciones/asig_items.php'?>" role="button"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Volver Selección.</a>

            <a class="btn btn-default btn-sm btn-primary" href="<?= $_SESSION['base_url1'].'app/admin/asignaciones/asig_nacional.php?r='.base64_encode($tipo).'&i='.base64_encode($subtipo).'&d='.base64_encode($descripcion); ?>" role="button"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Nacional.</a>

            <a class="btn btn-default btn-sm btn-primary" href="javascript:void(0)" role="button" disabled="disabled"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> Estado <?= $estado;?>.</a>

          </div>

        </div>
        <div class="col-xs-12">
          <hr>
          <ul class="list-group" style="float: left !important;">
            <li class="list-group-item">
               <strong><span class="glyphicon glyphicon-tag" aria-hidden="true"></span>&nbsp;Tipo : <span class="text-danger"><?= utf8_encode($descripcion)?></span></strong>
            </li>
            <li class="list-group-item">
              <strong><span class="glyphicon glyphicon-tags" aria-hidden="true"></span>&nbsp;Producto : <span class="text-danger"><?= utf8_encode($subtipo)?></span></strong>
            </li>
          </ul>
        </div>

        <div class="col-md-12">
          <div class="panel-body">
                <!-- Table -->
                <table class="table table-bordered table-striped mb-none table-condensed" id="datatable-editable">
                  <thead><tr>
                    <th>Estado</th>
                    <th>Municipio</th>
                    <th><? echo utf8_encode($descripcion); ?></th>
                    <th>Solicitudes</th>
                    <th>Cantidad</th>
                    <th>Asignacion</th>
                  </tr></thead>

                  <tbody id="cont_tabla_entrada">
                    <?php
                      $x=0;
                      foreach ($system->sql() as $rs) {
                        echo '<tr id =fls_'.$x.'>
                              <td>'.$rs->estado.'</td>
                              <td><a data-tool="tooltip" title="Listar asignacion por lineas de transporte del municipio '.$rs->municipio.' estado '.$rs->estado.'" href="'.$_SESSION['base_url1'].'app/admin/asignaciones/asig_municipal.php?e='.base64_encode($rs->estado).'&ide='.base64_encode($rs->id_estado).'&m='.base64_encode($rs->municipio).'&idm='.base64_encode($rs->id_municipio).'&r='.base64_encode($tipo).'&i='.base64_encode($rs->subtipo).'&d='.base64_encode($descripcion).'">'.$rs->municipio.'<a></td>
                              <td class="text-warning">'.$rs->subtipo.'</td>
                              <td><span class="badge bg-danger">'.$rs->solicitudes.'</span></td>
                              <td><span class="badge">'.$rs->cantidad.'</span></td>
                              <td>&nbsp;<a data-tool="tooltip" title="Asignacion" onclick="asignar_municipal('.$rs->id_municipio.','.$tipo.','.$rs->cantidad.',\'fls_'.$x.'\','.$rs->id_producto.')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-check" aria-hidden="true"></i></a>&nbsp;</td>
                              </tr>';
                              $x++;
                      }
                    ?>
                  </tbody>
                </table>
        </div>
      </div>
  </section>
        
<?
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/step_one.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/step_two.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/step_three.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/modal_carga.php';
  include_once $_SESSION['base_url'].'partials/footer.php';
  unset($tipo,$subtipo,$descripcion,$estado,$id_estado,$x);
?>
<script>
  $(document).ready(function(){
    $("#datatable-editable").DataTable();
  });
</script>
<script src="<?= $_SESSION['base_url1'].'assets/js/scripts.js'?>"></script>