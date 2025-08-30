<?
  if(!isset($_SESSION))
    {
      session_start();
    }
    include_once $_SESSION['base_url'].'partials/header.php';

    $system->sql="SELECT
solicitudes.id_user,
solicitudes.cod_linea,
solicitudes.cod_afiliado,
solicitudes.fec_solicitud,
solicitudes.estado AS id_estado,
solicitudes.municipio AS id_municipio,
solicitudes.estatus,
solicitudes.id,
users.apellido,
users.nombre,
users.cedula,
users.telefono,
users.foto,
users.nombre_linea,
estados.estado,
municipios.id_estado,
estatus.descripcion,
municipios.municipio
FROM
solicitudes
INNER JOIN users ON solicitudes.id_user = users.id
INNER JOIN estados ON solicitudes.estado = estados.id_estado
INNER JOIN municipios ON solicitudes.municipio = municipios.id_municipio AND estados.id_estado = municipios.id_estado
INNER JOIN estatus ON solicitudes.estatus = estatus.id
WHERE
solicitudes.estatus = 1
ORDER BY
solicitudes.fec_solicitud ASC
";
?>
  
  <section class="panel panel-featured panel-featured-primary">
    <header class="panel-heading">
      <div class="panel-actions">
        <a href="#" class="fa fa-caret-down"></a>
        <a href="#" class="fa fa-times"></a>
      </div>
      <h1 class="panel-title"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> Listado de solicitudes:</h1>
    </header>
      <div class="panel-body">
        <form id="formHeaderAsignacion">
          <input type="hidden" name="estado_entrega" id="estado_entrega" value="">
          <input type="hidden" name="municipio_entrega" id="municipio_entrega" value="0">
          <input type="hidden" name="linea_entrega" id="linea_entrega" value="0">
        </form>

        <!-- Table -->
                <table class="table table-bordered .table-hover table-striped mb-none table-condensed" id="tabla_sol">
                  <thead><tr>
                    <th>Foto</th>
                    <th>Cedula</th>
                    <th>Nombres</th>
                    <th>Telf.</th>
                    <th>Estado</th>
                    <th>Municipio</th>
                    <th>Linea</th>
                    <th>Fec. Solicitud</th>
                    <th>Estatus</th>
                    <th>Accion</th>
                  </tr></thead>

                  <tbody id="cont_tabla_entrada">
                    <?php
                      $x=0;
                      foreach ($system->sql() as $rs) {
                        //cambiar el formato de la fecha 
                        $rs->fec_solicitud = date("d/m/Y", strtotime($rs->fec_solicitud));
                        
                        echo '<tr id =fls_'.$x.'>
                              <td><img src="'.$_SESSION['base_url1'].'assets/images/fotos/'.$rs->foto.'" width="50px" height="50px" class="img-responsive" alt="Responsive image"></td>
                              <td><span class="badge bg-danger">'.$rs->cedula.'</span></td>
                              <td>'.$rs->nombre.' '.$rs->apellido.'</td>
                              <td>'.$rs->telefono.'</td>
                              <td>'.$rs->estado.'</td>
                              <td>'.$rs->municipio.'</td>
                              <td>'.$rs->nombre_linea.'</td>
                              <td><span class="badge bg-info">'.$rs->fec_solicitud.'</span></td>
                              <td>'.$rs->descripcion.'</td>
                              <td>&nbsp;<a data-tool="tooltip" title="Ver detalles de la solicitud del usuario con cedula N° '.$rs->cedula.'" class="btn btn-default" onclick="mostrar_detalles('.$rs->id.');" href="javascript:void(0)" role="button"><i role="button" class="fa fa-windows" aria-hidden="true"></i></a>&nbsp;</td>
                              </tr>';
                              $x++;
                      }
                      unset($x);
                    ?>
                  </tbody>
                </table>
      </div>
  </section>

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
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/step_one.php';
  /*include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/step_one.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/step_two.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/step_three.php';
   include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/modal_carga.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/confirmar_asignacion.php';*/
  include_once $_SESSION['base_url'].'partials/footer.php';
?>

<script type="text/javascript">

  $('#tabla_sol').dataTable( {
      "ordering": false
    });
  
  function mostrar_detalles(id){
    $.getJSON('../operaciones.php',{
      id:id,
      action: 'mostrar_detalles_filtrado'
    }, function(data){  
      if(data.msg){
        let tam = data.r.length;
        let tabla = '<div class="table-responsive"><table class="table table-bordered table-hover"><thead><tr><th>Tipo</th><th>Producto</th><th>Cantidad</th><th>Placa</th><th>Estatus</th><th>Accion</th></tr></thead><tbody>';
        for(let x = 0; x<tam; x++){
          let producto;
          let id_producto;
          let accion;
          tabla += '<tr>';
            tabla += '<td>'+data.r[x].descripcion+'</td>';
            
            if (data.r[x].id_rubro == 1) {producto=data.r[x].neumatico;id_producto=data.r[x].id_neumatico}
            if (data.r[x].id_rubro == 2) {producto=data.r[x].lubricante;id_producto=data.r[x].id_lubricante}
            if (data.r[x].id_rubro == 3) {producto=data.r[x].acumulador;id_producto=data.r[x].id_acumulador}
            tabla += '<td>'+producto+'</td>';
            tabla += '<td>'+data.r[x].cantidad+'</td>';
            tabla += '<td>'+data.r[x].placa+'</td>';
            tabla += '<td>'+data.r[x].estatus+'</td>';
            
            if (data.r[x].id_estatus == 1) {accion = '&nbsp;<a data-tool="tooltip" title="Asignacion" onclick="asignar_especifico('+data.r[x].id+','+data.r[x].id_rubro+','+data.r[x].cantidad+',0,'+id_producto+','+data.r[x].estado+')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-check" aria-hidden="true"></i></a>&nbsp;';}else{accion = '<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>';}
            tabla += '<td>'+accion+'</td>';
          tabla += '</tr>';
        }
        tabla += '</tbody></table></div>';
        $(".modal-body").html(tabla);
      $('#modal_detalles').modal('show');
      }else{
        toastr.info(data.msj);
      }
    })
  }

  function asignar_especifico(solicitud,tipo,cantidad,fl,producto_sol,estado){
    $("#modal_detalles").modal('hide');
    $("#estado_entrega").val(estado);
    step_one(solicitud,tipo,cantidad,0,producto_sol);
  }

</script>
<script src="<?= $_SESSION['base_url1'].'assets/js/scripts.js'?>"></script>