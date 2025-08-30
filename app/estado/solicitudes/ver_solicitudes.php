<?
  if(!isset($_SESSION))
  {
      session_start();
  }
  include_once $_SESSION['base_url'].'partials/header.php';

  $sql="select a.*,e.estado as nomestado,m.municipio as nommunicipio,c.descripcion as nomestatus,u.cedula,u.nombre,u.apellido,u.nombre_linea
        from solicitudes as a
        inner join estados as e on (a.estado=e.id_estado)
        inner join municipios as m on (a.municipio=m.id_municipio and e.id_estado=m.id_estado)
        inner join estatus as c on (a.estatus=c.id)
        inner join users as u on (a.id_user=u.id)
        WHERE a.estado = ".$_SESSION['edo']."
        order by fec_solicitud DESC";

  /*if (isset($_POST['estado'])) {
    if (trim($_POST['estado'])!='all') {
      $sql="";
    }
  }*/

?>
  
  <section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
      <div class="panel-actions">
        <a href="#" class="fa fa-caret-down"></a>
        <a href="#" class="fa fa-times"></a>
      </div>
      <h1 class="panel-title">Listados de Solicitudes</h1>
    </header>
      <div class="panel-body">
          
            <form id="form_filtro_sol" action="" method="POST">
              
              <div class="col-xs-1"><h4 class="text-danger"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> Filtros</h4></div>
              <input type="hidden" name="estado" id="estado" value="<?= $_SESSION['edo']?>">

              <div class="col-sm-12 col-md-2">
                <select class="form-control" id="municipio" name="municipio">
                  <option value="all">Todos los municipios</option>
                    <?php
                      $system->sql="select * from municipios where id_estado = ".$_SESSION['edo'];
                      foreach ($system->sql() as $rs){
                        echo '<option value="'.$rs->id_municipio.'">Municipio '.$rs->municipio.'</option>';
                      }
                    ?>
                </select>
              </div>

              <div class="col-sm-12 col-md-2">
                <select class="form-control" id="estatus" name="estatus">
                  <option value="all">Todos los estatus</option>
                    <?php
                      $system->sql="select * from estatus where id <> 7 and id <> 8";
                      foreach ($system->sql() as $rs){
                        echo '<option value="'.$rs->id.'">Estatus: '.$rs->descripcion.'</option>';
                      }
                    ?>
                </select>
              </div>

              <div class="col-xs-12 col-sm-2"><button  id="filtrar" name="filtrar" type="button" class="btn btn-danger"><span class="glyphicon glyphicon-glass" aria-hidden="true"></span> Filtrar</button></div>

            </form>

            <div class="col-xs-12">

        <hr>
      	<!-- Table -->
        <div class="col-xs-12" id="cont_tabla_filtro">
                <table class="table table-bordered table-striped mb-none table-condensed tablita" id="datatable-editable">
                  <thead><tr>
                    <th>Estado</th>
                    <th>Municipio</th>
                    <th>Cedula</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Linea</th>
                    <th>Fec. Solicitud</th>
                    <th>Estatus</th>
                    <th>Accion</th>
                  </tr></thead>

                  <tbody id="cont_tabla_sol">
                    <?php
                      $system->sql = $sql;
                      unset($sql);
                      $total=0;
                      foreach ($system->sql() as $rs) {
                        $total++;
                        echo '<tr>
                              <td>'.$rs->nomestado.'</td>
                              <td>'.$rs->nommunicipio.'</td>
                              <td>'.$rs->cedula.'</td>
                              <td>'.$rs->nombre.'</td>
                              <td>'.$rs->apellido.'</td>
                              <td>'.$rs->nombre_linea.'</td>
                              <td>'.date("d-m-Y",strtotime($rs->fec_solicitud)).'</td>
                              <td>'.$rs->nomestatus.'</td>
                              <td>&nbsp;<a data-tool="tooltip" title="Ver detalles de la solicitud del usuario con cedula N° '.$rs->cedula.'" class="btn btn-default" onclick="mostrar_detalles('.$rs->id.');" href="javascript:void(0)" role="button"><i role="button" class="fa fa-windows" aria-hidden="true"></i></a>&nbsp;</td>
                              </tr>';
                      }
                    ?>
                  </tbody>
                </table>
            </div>
                </div>
      </div>
      <h4><span class="label label-danger">Total de solicitudes <span id="t_sol"><?= $total;?></span></span></h4> 
  </section>


<!--Modal-->
<!-- Modal -->
<div class="modal fade" id="modal_detalles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detalles</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
        
<?
  include_once $_SESSION['base_url'].'partials/footer.php';
?>
<script>
  $('#datatable-editable').dataTable( {
    "ordering": false
  });
  $("#estado").change(function(){

    if($("#estado").val()!='all'){

      $.getJSON('../operaciones.php',{
      estado:$("#estado").val(),
      action: 'buscar_municipios'
    }, function(data){  
      if(data.msg){
        $("#con_municipio").show();
        let tam = data.r.length;
        if (tam>0) {
          let opt = '<option value="all">Todos los municipios</option>'; 
          for(let x = 0 ; x < tam; x++){
            opt += '<option value="'+data.r[x].id_municipio+'">'+data.r[x].municipio+'</option>';
          }
          $("#municipio").html(opt);
        }else{
          toastr.success(data.msj); 
        }
      }else{
        toastr.success(data.msj);
      }
    })

    }else{
      $("#con_municipio").hide();
    }

  });

  $("#filtrar").click(function(){

    $.getJSON('../operaciones.php',{
      estado:$("#estado").val(),
      municipio:$("#municipio").val(),
      estatus:$("#estatus").val(),
      action: 'filtrar_solicitudes'
    }, function(data){  
      if(data.msg){
        toastr.success(data.msj);
        let tam = data.r.length;
        let t = '<table class="table table-bordered table-striped mb-none table-condensed tablita" id="datatable-editable_2"><thead><tr><th>Estado</th><th>Municipio</th><th>Cedula</th><th>Nombre</th><th>Apellido</th><th>Linea</th><th>Fec. Solicitud</th><th>Estatus</th><th>Accion</th></tr></thead><tbody>';
        let to=0 ;
        for(x=0;x<tam;x++){
          t += '<tr>';
          t += '<td>'+data.r[x].nomestado+'</td>';
          t += '<td>'+data.r[x].nommunicipio+'</td>';
          t += '<td>'+data.r[x].cedula+'</td>';
          t += '<td>'+data.r[x].nombre+'</td>';
          t += '<td>'+data.r[x].apellido+'</td>';
          t += '<td>'+data.r[x].nombre_linea+'</td>';
          t += '<td>'+data.r[x].fec_solicitud+'</td>';
          t += '<td>'+data.r[x].nomestatus+'</td>';
          t += '<td>&nbsp;<a data-tool="tooltip" title="Ver detalles de la solicitud del usuario con cedula N° '+data.r[x].cedula+'" class="btn btn-default" onclick="mostrar_detalles('+data.r[x].id+');" href="javascript:void(0)" role="button"><i role="button" class="fa fa-windows" aria-hidden="true"></i></a>&nbsp;</td>';
          t += '</tr>';
          to++;
        }
        t += '</tbody></table>';
        $("#cont_tabla_filtro").html(t);
        $("#t_sol").html(to);
        $('#datatable-editable_2').dataTable( {
          "ordering": false
        });
      }else{
        toastr.info(data.msj);
        $("#cont_tabla_filtro").html('<div class="alert alert-warning" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No hay datos disponibles.</div>');
        $("#t_sol").html(0);
      }
    })
  });

  function mostrar_detalles(id){
    $.getJSON('../operaciones.php',{
      id:id,
      action: 'mostrar_detalles_filtrado'
    }, function(data){  
      if(data.msg){

        let tam = data.r.length;
        let list = '<ul class="list-group">';
        let color = '';
       for(x=0;x<tam;x++){
          if(data.r[x].id_estatus==1){color='default';}
          if(data.r[x].id_estatus==2){color='info';}
          if(data.r[x].id_estatus==4){color='danger';}
          if(data.r[x].id_estatus==5){color='success';}
          if(data.r[x].id_estatus==6){color='primary';}
          
          if (data.r[x].id_rubro == 1) {

            list += '<li class="list-group-item"><i class="fa fa-tags fa-lg text-'+color+'" aria-hidden="true">'+data.r[x].estatus+'</i><br> <span class="text-danger">'+data.r[x].cantidad+'</span> <span class="text-danger">'+data.r[x].descripcion+'</span> tipo <span class="text-danger">'+data.r[x].neumatico+'</span> para el vehiculo con placa :<span class="text-danger">'+data.r[x].placa+'</span></li>';
          }
          if (data.r[x].id_rubro == 2) {
            list += '<li class="list-group-item"><i class="fa fa-tags fa-lg text-'+color+'" aria-hidden="true">'+data.r[x].estatus+'</i><br> <span class="text-danger">'+data.r[x].cantidad+'</span> <span class="text-danger">'+data.r[x].descripcion+'</span> tipo <span class="text-danger">'+data.r[x].lubricante+'</span> para el vehiculo con placa :<span class="text-danger">'+data.r[x].placa+'</span></li>';
          }
          if (data.r[x].id_rubro == 3) {
            list += '<li class="list-group-item"><i class="fa fa-tags fa-lg text-'+color+'" aria-hidden="true">'+data.r[x].estatus+'</i><br> <span class="text-danger">'+data.r[x].cantidad+'</span> <span class="text-danger">'+data.r[x].descripcion+'</span> tipo <span class="text-danger">'+data.r[x].acumulador+'</span> para el vehiculo con placa :<span class="text-danger">'+data.r[x].placa+'</span></li>';
          }
        }
        list += '</ul>';
        $(".modal-body").html(list);

        $('#modal_detalles').modal('show');
      }else{
        toastr.info(data.msj);
      }
    })
  }
</script>