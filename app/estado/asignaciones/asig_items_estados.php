<?
  if(!isset($_SESSION))
    {
      session_start();
    }
  include_once $_SESSION['base_url'].'partials/header.php';
?>
  
  <section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
      <div class="panel-actions">
        <a href="#" class="fa fa-caret-down"></a>
        <a href="#" class="fa fa-times"></a>
      </div>
      <h1 class="panel-title">Asignacion de items a los Estados.</h1>
    </header>
      <div class="panel-body">
        
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#neu" aria-controls="neu" role="tab" data-toggle="tab">Neumaticos</a></li>
          <li role="presentation"><a href="#lub" aria-controls="lub" role="tab" data-toggle="tab">Lubricantes</a></li>
          <li role="presentation"><a href="#acu" aria-controls="acu" role="tab" data-toggle="tab">Acumuladores</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="neu">
            <table class="table table-bordered table-striped mb-none table-condensed" id="tabla_neumaticos">
              <thead><tr>
                <th>Estado</th>
                <th>Solicitudes</th>
                <th>Cantidad</th>
                <th>Accion</th>
              </tr></thead>
              <tbody id="cont_tabla_entrada">
                <?php
                  $x=0;
                  $system->sql="SELECT
                  solicitudes.estado,
                  solicitudes.municipio,
                  solicitudes.estatus,
                  estados.estado AS name_estado,
                  Sum(detalles_solicitudes.cantidad) AS cantidad,
                  Count(*) AS solicitudes,
                  detalles_solicitudes.id_rubro
                  FROM
                  solicitudes
                  INNER JOIN estados ON solicitudes.estado = estados.id_estado
                  INNER JOIN detalles_solicitudes ON detalles_solicitudes.id_solicitud = solicitudes.id
                  WHERE
                  detalles_solicitudes.estatus = 1 AND
                  detalles_solicitudes.id_rubro = 1
                  GROUP BY
                  solicitudes.estado
                  ORDER BY
                  solicitudes.estado ASC";
                  foreach ($system->sql() as $rs) {
                    echo '<tr id ='.$x.'>
                          <td class="text-primary"><a data-tool="tooltip" title="Ver historial de asignación del Estado '.$rs->name_estado.'" href="javascript:void(0)" onclick="show_historyAsignacion_estado('.$rs->estado.','.$rs->id_rubro.')">'.$rs->name_estado.'</a></td>
                          <td><span class="badge">'.$rs->solicitudes.'</span></td>
                          <td><span class="badge">'.$rs->cantidad.'</span></td>
                          <td>&nbsp;<a data-tool="tooltip" title="Detallar" class="btn btn-default" href="'.$_SESSION['base_url1']."app/admin/asignaciones/asig_items_estados_detal.php?e=".base64_encode($rs->estado)."&r=".base64_encode($rs->id_rubro).'" role="button"><i role="button" class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;</td>
                          </tr>';
                          $x++;
                  }
                  unset($x);
                ?>
              </tbody>
            </table>
          </div>
          
          <div role="tabpanel" class="tab-pane" id="lub">
            
            <table class="table table-bordered table-striped mb-none table-condensed" id="tabla_neumaticos">
              <thead><tr>
                <th>Estado</th>
                <th>Solicitudes</th>
                <th>Cantidad</th>
                <th>Accion</th>
              </tr></thead>
              <tbody id="cont_tabla_entrada">
                <?php
                  $x=0;
                  $system->sql="SELECT
                  solicitudes.estado,
                  solicitudes.municipio,
                  solicitudes.estatus,
                  estados.estado AS name_estado,
                  Sum(detalles_solicitudes.cantidad) AS cantidad,
                  Count(*) AS solicitudes,
                  detalles_solicitudes.id_rubro
                  FROM
                  solicitudes
                  INNER JOIN estados ON solicitudes.estado = estados.id_estado
                  INNER JOIN detalles_solicitudes ON detalles_solicitudes.id_solicitud = solicitudes.id
                  WHERE
                  detalles_solicitudes.estatus = 1 AND
                  detalles_solicitudes.id_rubro = 2
                  GROUP BY
                  solicitudes.estado
                  ORDER BY
                  solicitudes.estado ASC";
                  foreach ($system->sql() as $rs) {
                    echo '<tr id ='.$x.'>
                          <td class="text-primary"><a data-tool="tooltip" title="Ver historial de asignación del Estado '.$rs->name_estado.'" href="javascript:void(0)" onclick="show_historyAsignacion_estado('.$rs->estado.','.$rs->id_rubro.')">'.$rs->name_estado.'</a></td>
                          <td><span class="badge">'.$rs->solicitudes.'</span></td>
                          <td><span class="badge">'.$rs->cantidad.'</span></td>
                          <td>&nbsp;<a data-tool="tooltip" title="Detallar" class="btn btn-default" href="'.$_SESSION['base_url1']."app/admin/asignaciones/asig_items_estados_detal.php?e=".base64_encode($rs->estado)."&r=".base64_encode($rs->id_rubro).'" role="button"><i role="button" class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;</td>
                          </tr>';
                          $x++;
                  }
                  unset($x);
                ?>
              </tbody>
            </table>

          </div>
          
          <div role="tabpanel" class="tab-pane" id="acu">
            
            <table class="table table-bordered table-striped mb-none table-condensed" id="tabla_neumaticos">
              <thead><tr>
                <th>Estado</th>
                <th>Solicitudes</th>
                <th>Cantidad</th>
                <th>Accion</th>
              </tr></thead>
              <tbody id="cont_tabla_entrada">
                <?php
                  $x=0;
                  $system->sql="SELECT
                  solicitudes.estado,
                  solicitudes.municipio,
                  solicitudes.estatus,
                  estados.estado AS name_estado,
                  Sum(detalles_solicitudes.cantidad) AS cantidad,
                  Count(*) AS solicitudes,
                  detalles_solicitudes.id_rubro
                  FROM
                  solicitudes
                  INNER JOIN estados ON solicitudes.estado = estados.id_estado
                  INNER JOIN detalles_solicitudes ON detalles_solicitudes.id_solicitud = solicitudes.id
                  WHERE
                  detalles_solicitudes.estatus = 1 AND
                  detalles_solicitudes.id_rubro = 3
                  GROUP BY
                  solicitudes.estado
                  ORDER BY
                  solicitudes.estado ASC";
                  foreach ($system->sql() as $rs) {
                    echo '<tr id ='.$x.'>
                          <td class="text-primary"><a data-tool="tooltip" title="Ver historial de asignación del Estado '.$rs->name_estado.'" href="javascript:void(0)" onclick="show_historyAsignacion_estado('.$rs->estado.','.$rs->id_rubro.')">'.$rs->name_estado.'</a></td>
                          <td><span class="badge">'.$rs->solicitudes.'</span></td>
                          <td><span class="badge">'.$rs->cantidad.'</span></td>
                          <td>&nbsp;<a data-tool="tooltip" title="Detallar" class="btn btn-default" href="'.$_SESSION['base_url1']."app/admin/asignaciones/asig_items_estados_detal.php?e=".base64_encode($rs->estado)."&r=".base64_encode($rs->id_rubro).'" role="button"><i role="button" class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;</td>
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

      </div>
  </section>
        
<?
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/modal_carga.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/h_asignaciones_items_estadales.php';
  include_once $_SESSION['base_url'].'partials/footer.php';
?>
<script>
   $(document).ready(function(){
    $("#datatable-editable").DataTable();
  });

   function show_historyAsignacion_estado(estado,rubro){
    $("modal_carga").modal("show");
    $.getJSON('operaciones.php',{
      e:btoa(estado),
      r:btoa(rubro),
      action: 'historial_deAsignaciones'
    }, function(data){ 
    if (data.msg == true) {
      $("#modal_carga").modal('hide');
        $("#name_estado").html(data.r[0].estado);
        let tam = data.r.length;  
        let tab='<table class="table table-bordered table-striped mb-none table-condensed" id="tabla_h_estados"><thead><tr><th>Tipo</th><th>Producto Ref.</th><th>Producto Enviado</th><th>Marca/Modelo</th><th>Fecha Asig.</th><th>Cantidad Asig.</th><th>Estatus</th></tr></thead>';
        let color = '';
        for(let x = 0 ; x < tam; x++){
          tab+='<tr>';
            tab+='<td>'+data.r[x].descripcion+'</td>';
            tab+='<td>'+data.r[x].producto_sol+'</td>';
            tab+='<td>'+data.r[x].producto+'</td>';
            tab+='<td>'+data.r[x].marca+'/'+data.r[x].modelo+'</td>';
            tab+='<td>'+data.r[x].fec_reg+'</td>';
            tab+='<td class="text-danger">'+data.r[x].cantidad_asig+'</td>';
            tab+='<td>'+data.r[x].estatus+'</td>';
          tab+='</tr>';
        }
        tab+='</tbody></table>';
        $("#cont_tabla_history").html(tab);
        $('#tabla_h_estados').dataTable( { "ordering": false,});
        $("#modal_historial_asig_itemsEstadales").modal("show");
    } else{
      toastr.warning(data.msj);
      $("#modal_carga").modal('hide');
    }
      
    }).fail(function(data) {
      toastr.error('Error! Problemas con el server.');
      $("#modal_carga").modal('hide');
    });
   }
</script>