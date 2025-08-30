<?
  if(!isset($_SESSION))
    {
      session_start();
    }
  include_once $_SESSION['base_url'].'partials/header.php';

/*seleccioona la solicitudes por items*/
$con1="select b.descripcion,COUNT(*) as cantidad,b.id as rubro from detalles_solicitudes as a
inner join rubros as b on (a.id_rubro=b.id)
where estatus=1 
GROUP by id_rubro";

/*Selecciona el inventario por items*/
$con2="select e.estado,sum(b.cantidad),count(*) as solicitudes
from solicitudes as a 
inner join detalles_solicitudes as b on (a.id=b.id_solicitud)
inner join estados as e on (a.estado=e.id_estado)
where (a.estatus=1 and b.estatus=1)
group by(a.estado)";

?>
  
  <section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
      <div class="panel-actions">
        <a href="#" class="fa fa-caret-down"></a>
        <a href="#" class="fa fa-times"></a>
      </div>
      <h1 class="panel-title">Listado de items por asignar</h1>
    </header>
      <div class="panel-body">

        <a class="btn btn-default" href="<?= $_SESSION['base_url1'].'app/admin/asignaciones/asignacion_especifico.php'?>" role="button"><span class="glyphicon glyphicon-file text-danger" aria-hidden="true"></span>¿Quieres realizar una asignación en especifico?</a>
        <hr>

        <div class="alert alert-info" role="alert">1. Selecciona en la siguiente tabla uno de los tipos de Item por asignar.<br>2. Visualiza la tabla reultante de la pasada seleccion, y puedes seleccionar de la columna subtipo la opcion asignar.</div>

        <div class="col-md-12">
          <div class="panel-body">

            <div class="col-xs-12 col-sm-8">
              <div class="list-group">
                <?php
                  $system->sql = $con1;
                  unset($con1);
                  foreach ($system->sql() as $rs){
                    echo '<a href="javascript:void(0)" onclick="traer('.$rs->rubro.');" class="list-group-item text-primary"><span class="badge bg-primary">'.$rs->cantidad.' Solicitudes Pendientes</span> Tipo&nbsp;'.$rs->descripcion.'</a>';
                  }
                ?>
              </div>
            </div>
            <div class="col-sm-12 col-xs-12">
               <h4><span class="label label-danger" id="total_p"></span></h4>
            </div>
            <div class="col-xs-12 tablas" id="cont_table_1" style="display: none;">
              <!-- Table -->
              <hr>
                <table class="table table-bordered table-striped mb-none table-condensed" id="datatable-editable-1">
                  <thead><tr>
                    <th>Tipo</th>
                    <th>Productos Solicitados</th>
                    <th>Solicitudes Pendiente</th>
                    <th>Unidades</th>
                  </tr></thead>
                  <tbody id="cont_tabla_1"></tbody>
                </table>
            </div>

            <div class="col-xs-12 tablas" id="cont_table_2" style="display: none;">
              <!-- Table -->
              <hr>
                <table class="table table-bordered table-striped mb-none table-condensed" id="datatable-editable-2">
                  <thead><tr>
                    <th>Tipo</th>
                    <th>Producto Solicitados</th>
                    <th>Solicitudes Pendiente</th>
                    <th>Unidades</th>
                  </tr></thead>
                  <tbody id="cont_tabla_2"></tbody>
                </table>
            </div>

            <div class="col-xs-12 tablas" id="cont_table_3" style="display: none;">
              <!-- Table -->
              <hr>
                <table class="table table-bordered table-striped mb-none table-condensed" id="datatable-editable-3">
                  <thead><tr>
                    <th>Tipo</th>
                    <th>Productos Solicitados</th>
                    <th>Solicitudes Pendiente</th>
                    <th>Unidades</th>
                  </tr></thead>
                  <tbody id="cont_tabla_3"></tbody>
                </table>
            </div>

        </div>
      </div>
  </section>
        
<?
  include_once $_SESSION['base_url'].'partials/footer.php';
?>
<script>
  var base = "<?php $_SESSION['base_url'];?>";
  //traer item por tipo de rubro
  function traer(tipo){
    $(".tablas").hide();
    $.getJSON('./operaciones.php',{
      item:tipo,
      action: 'traer_sol_x_item'
    }, function(data){
      if (data.msj) {
        toastr.info(data.msg);
        let tam = data.con.length;  
        let tab='';
        let sum=0;
        for(let x = 0 ; x < tam; x++){
          let url=base+"asig_nacional.php?r="+btoa(data.con[x].rubro)+"&d="+btoa(data.con[x].descripcion)+"&i="+btoa(data.con[x].item);
          tab += '<tr>';
          tab += '<td>'+data.con[x].descripcion+'</td>';
          tab += '<td><a data-tool="tooltip" title="Listar asignaciones nacionales para subtipo '+data.con[x].item+'" href="'+url+'">'+data.con[x].item+'</a></td>';
          tab += '<td>'+data.con[x].solicitudes+'</td>';
          tab += '<td>'+data.con[x].cantidad+'</td>';
          tab += '</tr>';
          sum += parseInt(data.con[x].cantidad);
        }
        $("#cont_tabla_"+tipo).html(tab);
        $("#total_p").html('Total de '+sum+' unidades de '+data.con[0].descripcion+' solicitados a nivel nacional.');
        $("#datatable-editable-"+tipo).DataTable();
        $("#cont_table_"+tipo).show();
      }else{
        $("#total_p").html(0);
        toastr.error(data.msg);
        $(".tablas").hide();
        $("#cont_tabla_items").html('');
      } 

    })
  }

  $('#example').DataTable( {
        "order": [[ 3, "desc" ]]
    } );
</script>