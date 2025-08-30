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
      <h1 class="panel-title">Consignar al Estado..</h1>
    </header>
      <div class="panel-body">

        <div class="alert alert-info" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><i class="fa fa-info-circle fa-lg" aria-hidden="true"></i> Puedes ver el <strong>historial de consignaciones</strong> pulsando click en el nombre del estado.</div>
        
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#neu" aria-controls="neu" role="tab" data-toggle="tab">Neumaticos</a></li>
          <li role="presentation"><a href="#lub" aria-controls="lub" role="tab" data-toggle="tab">Lubricantes</a></li>
          <li role="presentation"><a href="#acu" aria-controls="acu" role="tab" data-toggle="tab">Acumuladores</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="neu">
            <table class="table table-bordered table-striped mb-none table-condensed tabla_cuota" id="tabla_neumaticos">
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
                          <td class="text-primary"><a data-tool="tooltip" title="Ver historial de consignaciones del Estado '.$rs->name_estado.'" href="javascript:void(0)" onclick="mostrar_historial_consignaciones('.$rs->estado.','.$rs->id_rubro.')">'.$rs->name_estado.'</a></td>
                          <td><span class="badge">'.$rs->solicitudes.'</span></td>
                          <td><span class="badge">'.$rs->cantidad.'</span></td>
                          <td>&nbsp;<a data-tool="tooltip" title="Ver detalles de solicitudes del estado '.$rs->name_estado.'" class="btn btn-default" href="'.$_SESSION['base_url1']."app/admin/asignaciones/asig_items_estados_detal.php?e=".base64_encode($rs->estado)."&r=".base64_encode($rs->id_rubro).'" role="button"><i role="button" class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;<a data-tool="tooltip" title="Consignar productos al estado '.$rs->name_estado.'" onclick="modal_paso_uno('.$rs->cantidad.','.$rs->id_rubro.','.$rs->estado.',0);" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-check" aria-hidden="true"></i></a></td>
                          </tr>';
                          $x++;
                  }
                  unset($x);
                ?>
              </tbody>
            </table>
          </div>
          
          <div role="tabpanel" class="tab-pane" id="lub">
            
            <table class="table table-bordered table-striped mb-none table-condensed tabla_cuota" id="tabla_neumaticos">
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
                          <td class="text-primary"><a data-tool="tooltip" title="Ver historial de asignación del Estado '.$rs->name_estado.'" href="javascript:void(0)" onclick="mostrar_historial_consignaciones('.$rs->estado.','.$rs->id_rubro.')">'.$rs->name_estado.'</a></td>
                          <td><span class="badge">'.$rs->solicitudes.'</span></td>
                          <td><span class="badge">'.$rs->cantidad.'</span></td>
                          <td>&nbsp;<a data-tool="tooltip" title="Detallar" class="btn btn-default" href="'.$_SESSION['base_url1']."app/admin/asignaciones/asig_items_estados_detal.php?e=".base64_encode($rs->estado)."&r=".base64_encode($rs->id_rubro).'" role="button"><i role="button" class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;<a data-tool="tooltip" title="Asignar items al estado '.$rs->name_estado.'" onclick="modal_paso_uno('.$rs->cantidad.','.$rs->id_rubro.','.$rs->estado.',0);" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-check" aria-hidden="true"></i></a></td>
                          </tr>';
                          $x++;
                  }
                  unset($x);
                ?>
              </tbody>
            </table>

          </div>
          
          <div role="tabpanel" class="tab-pane" id="acu">
            
            <table class="table table-bordered table-striped mb-none table-condensed tabla_cuota" id="tabla_neumaticos">
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
                          <td class="text-primary"><a data-tool="tooltip" title="Ver historial de asignación del Estado '.$rs->name_estado.'" href="javascript:void(0)" onclick="mostrar_historial_consignaciones('.$rs->estado.','.$rs->id_rubro.')">'.$rs->name_estado.'</a></td>
                          <td><span class="badge">'.$rs->solicitudes.'</span></td>
                          <td><span class="badge">'.$rs->cantidad.'</span></td>
                          <td>&nbsp;<a data-tool="tooltip" title="Detallar" class="btn btn-default" href="'.$_SESSION['base_url1']."app/admin/asignaciones/asig_items_estados_detal.php?e=".base64_encode($rs->estado)."&r=".base64_encode($rs->id_rubro).'" role="button"><i role="button" class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;<a data-tool="tooltip" title="Asignar items al estado '.$rs->name_estado.'" onclick="modal_paso_uno('.$rs->cantidad.','.$rs->id_rubro.','.$rs->estado.',0);" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-check" aria-hidden="true"></i></a></td>
                          </tr>';
                          $x++;
                  }
                  unset($x);
                ?>
              </tbody>
            </table>

          </div>
        
        </div>
        <?php
          setlocale(LC_TIME, 'spanish');
          $nombreMes=strftime("%B",mktime(0, 0, 0, date('m'), 1, 2000)); 
        ?>
        <hr>
        <span class="badge bg-danger">Asignaciones del mes <?= $nombreMes;?></span>
        <canvas id="myChart"></canvas>

</div>

      </div>
  </section>
        
<?
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/modal_carga.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/modal_historal_consignaciones.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/modal_paso_uno.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/modal_paso_dos.php';
  include_once $_SESSION['base_url'].'partials/footer.php';
?>
<script src="<?= $_SESSION['base_url1'].'assets/js/asignaciones_admin.js'?>"></script>
<script>
  $('.tabla_cuota').dataTable( {"ordering": false});

   function mostrar_historial_consignaciones(estado,rubro){
    $("modal_carga").modal("show");
    $.getJSON('operaciones2.php',{
      e:btoa(estado),
      r:btoa(rubro),
      p:btoa(0),
      action: 'historial_de_consignaciones'
    }, function(data){ 
    if (data.msg == true) {
      $("#modal_carga").modal('hide');
        $("#name_estado").html(data.r[0].estado);
        let tam = data.r.length;  
        let tab='<table class="table table-bordered table-striped mb-none table-condensed" id="tabla_h_estados"><thead><tr><th>Tipo</th><th>Producto Ref.</th><th>Producto Asignado</th><th>Marca/Modelo</th><th>Fecha Asig.</th><th>Cantidad Asig.</th><th>Estatus</th></tr></thead>';
        let color = '';
        for(let x = 0 ; x < tam; x++){
          tab+='<tr>';
            tab+='<td>'+data.r[x].descripcion+'</td>';
          if(data.r[x].producto_sol==null) {tab+='<td>Libre</td>';}else{tab+='<td>'+data.r[x].producto_sol+'</td>';}
            tab+='<td>'+data.r[x].producto+'</td>';
            tab+='<td>'+data.r[x].marca+'/'+data.r[x].modelo+'</td>';
            tab+='<td>'+data.r[x].fec_reg.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1')+'</td>';
            tab+='<td class="text-danger">'+data.r[x].cantidad_asig+'</td>';
            tab+='<td>'+data.r[x].estatus+'</td>';
          tab+='</tr>';
        }
        tab+='</tbody></table>';
        $("#cont_tabla_history").html(tab);
        $('#tabla_h_estados').dataTable( { "ordering": false,});
        $("#modal_historial_asig_itemsEstadales").modal("show");
        $("#cont_cuota_proc").html(data.c);
    } else{
      toastr.warning(data.msj);
      $("#modal_carga").modal('hide');
    }
    }).fail(function(data) {
      toastr.error('Error! Problemas con el server.');
      $("#modal_carga").modal('hide');
    });
   }


//cargamos las estadisticas
$(document).ready(function(){
  $.getJSON('operaciones2.php',{
      action: 'traer_estadisticas_asigEstados'
    }, function(data){
      let ce = data.e.length;
      let cr = data.r.length;
      let ca = data.a.length;
      let labelA = [];
      let dataA = [];
      let dataB = [];
      let dataC = [];
      for(let x = 0 ; x < ce; x++){
        let passA=0;
        let passB=0;
        let passC=0
        labelA.push(data.e[x].estado);

        for(let j = 0;j < ca; j++){
          if (data.e[x].id_estado == data.a[j].estado && data.a[j].id_rubro == 1) {
            dataA.push(data.a[j].cantidad_asig);
            passA++;
          }
        }
        for(let j = 0;j < ca; j++){
          if (data.e[x].id_estado == data.a[j].estado && data.a[j].id_rubro == 2) {
            dataB.push(data.a[j].cantidad_asig);
            passB++;
          }
        }
        for(let j = 0;j < ca; j++){
          if (data.e[x].id_estado == data.a[j].estado && data.a[j].id_rubro == 3) {
            dataC.push(data.a[j].cantidad_asig);
            passC++;
          }
        }
        if (passA == 0) {dataA.push(0);}
        if (passB == 0) {dataB.push(0);}
        if (passC == 0) {dataC.push(0);}
      }

      armar_estadistica(labelA,dataA,dataB,dataC);
    }).fail(function(data) {
      toastr.error('Error! Problemas con el server.');
      $("#modal_carga").modal('hide');
    });
});


function armar_estadistica(labelA,dataA,dataB,dataC){
  let ctx = document.getElementById('myChart').getContext('2d');
  var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'horizontalBar',

    // The data for our dataset
    data: {
        labels: labelA,
        datasets: [{
            label: 'Neumaticos',
            backgroundColor: 'rgb( 143, 138, 137)',
            borderColor: 'rgb(171, 0, 0,0.5)',
            data: dataA
        },{
            label: 'Lubricantes',
            backgroundColor: 'rgb(16, 218, 178  )',
            borderColor: 'rgb(16, 218, 178  )',
            data: dataB
        },{
            label: 'Baterias',
            backgroundColor: 'rgb( 114, 28, 9   )',
            borderColor: 'rgb( 114, 28, 9   )',
            data: dataC
        }]
    },

    // Configuration options go here
    options: {}
  });
}

</script>