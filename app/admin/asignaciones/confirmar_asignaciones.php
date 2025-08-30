<?
  if(!isset($_SESSION))
    {
      session_start();
    }
    include_once $_SESSION['base_url'].'partials/header.php';

    $system->sql="SELECT
asignaciones.id,
estatus.descripcion AS name_estatus,
estados.estado,
asignaciones.serial,
almacenes.nombre AS acopio,
asignaciones.monto_total,
asignaciones.cantidad_asignada,
asignaciones.fec_reg,
asignaciones.id_rubro,
asignaciones.id_producto,
asignaciones.estatus,
productos.descripcion AS name_producto,
rubros.descripcion AS name_rubro,
presentaciones.descripcion AS name_presentacion,
productos.precio
FROM
asignaciones
INNER JOIN estados ON asignaciones.estado = estados.id_estado
INNER JOIN estatus ON asignaciones.estatus = estatus.id
INNER JOIN mov_items ON asignaciones.id_mov = mov_items.id
INNER JOIN almacenes ON mov_items.destino = almacenes.id
INNER JOIN productos ON asignaciones.id_producto = productos.id
INNER JOIN rubros ON productos.tipo = rubros.id
INNER JOIN presentaciones ON productos.presentacion = presentaciones.id
WHERE
asignaciones.estatus = 2
ORDER BY
asignaciones.fec_reg ASC";
?>
  
  <section class="panel panel-featured panel-featured-success">
    <header class="panel-heading">
      <div class="panel-actions">
        <a href="#" class="fa fa-caret-down"></a>
        <a href="#" class="fa fa-times"></a>
      </div>
      <h1 class="panel-title"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> Verificación de Preasignaciones </h1>
    </header>
      <div class="panel-body">
        <!-- Table -->
                <table class="table table-bordered table-striped mb-none table-condensed" id="tabla_confirmaciones">
                  <thead><tr>
                    <th style="display: none;">ID</th>
                    <th>Serial</th>
                    <th>Estado</th>
                    <th>Acopio</th>
                    <th>Tipo / Producto</th>
                    <th>Asignado</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th style="display: none;">Estatus</th>
                    <th>Accion</th>
                  </tr></thead>

                  <tbody id="cont_tabla_entrada">
                    <?php
                      $x=0;
                      foreach ($system->sql() as $rs) {
                        //cambiar el formato de la fecha 
                        $rs->fec_reg = date("d/m/Y", strtotime($rs->fec_reg));
                        //monto
                        $m = '';
                        if ($rs->monto_total==0) {
                          $m = '<td class="text-center"><a data-tool="tooltip" title="Asignar Precio" onclick="show_asig_precio('.$rs->id.','.$rs->asignado.')" class="btn btn-link btn-xs" href="javascript:void(0)" role="button">Asignar</a></td>';
                        }else{
                          $m = '<td class="text-success"><span data-tool="tooltip" title="Precio Unitario: '.$rs->precio.' Bs.">'.$rs->monto_total.' Bs.</span></td>';
                        }
                        echo '<tr id =fls_'.$x.'>
                              <td style="display:none;">'.$rs->id.'</td>
                              <td>'.$rs->serial.'</td>
                              <td><span class="badge bg-danger">'.$rs->estado.'</span></td>
                              <td class="text-warning">'.$rs->acopio.'</td>
                              <td><span class="text-danger">'.$rs->name_rubro.'</span>&nbsp;/&nbsp;<span class="text-primary">'.$rs->name_producto.'</span></td>
                              <td class="text-center" ><span class="badge">'.$rs->cantidad_asignada.' '.$rs->name_presentacion.'</span></td>
                              '.$m.'
                              <td>'.$rs->fec_reg.'</td>
                              <td style="display: none;"><span class="badge">'.$rs->name_estatus.'</span></td>
                              <td>&nbsp;<a data-tool="tooltip" title="Ver" class="btn btn-success" href="'.$_SESSION['base_url1'].'app/admin/asignaciones/confirmar_asignaciones_ver.php?key='.base64_encode($rs->id).'&key2='.base64_encode($rs->id_rubro).'" role="button"><i role="button" class="fa fa-eye" aria-hidden="true"></i></a></td>
                              </tr>';
                              $x++;
                      }
                      unset($x);
                    ?>
                  </tbody>
                </table>
      </div>
  </section>
        
<?
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/confirmar_asignacion.php';
  include_once $_SESSION['base_url'].'partials/footer.php';
?>

<script type="text/javascript">

  /*cargamos todas */



  $('#tabla_confirmaciones').dataTable( {
      "ordering": false
    });
  
  function show_asig_precio(asi,can){
    $("#modal_sel_precio").modal('show');
    $("#id_asignacion").val(asi);
    $("#cantidad_pro").val(can);
    $("#cantidad_productos").html(can);
  }

  function calcular_precio(){
      let precio = $("#precio").val();
      if (precio=='' && precio==0) {
       toastr.warning('No se puede realizar ningun calculo el precio esta vacio o esta en cero (0)');
       $("#total").val('');
      }else{
        //alert(Math.sign(precio));
        if (Math.sign(precio)!=-1 && Math.sign(precio)!=-0 && Math.sign(precio)!=NaN && isNaN(precio)!=true) {
          let calculo = parseFloat($("#precio").val())*parseFloat($("#cantidad_pro").val());
          $("#total").val(parseFloat(calculo).toFixed(2));
        }else{
          toastr.warning('El valor del precio no es un valor valido');
          $("#precio").val('');  
        }
      }
    }

  function asignar_precio(){
    if ($("#precio").val()!='' && $("#precio").val()!=0 && $("#precio").val()!='' &&$("#precio").val()!=0) {
      $('#modal_sel_item').modal('hide');//cerramos el step_one
      $(".loader").show();//mostramos al usuario de un nuevo proceso

      $.getJSON('operaciones.php',{
        precio:btoa($("#precio").val()),
        asignacion:btoa($("#id_asignacion").val()),
        total:btoa($("#total").val()),
        m:2,
        action: 'step_three'
      }, function(data){  
        if(data.msg){
          $('#modal_sel_precio').modal('hide');
          toastr.success(data.msj);
          /*let url = '';
          $(location).attr('href',url);*/
          $("tbody #"+fls).closest('tr').remove();//removemos la fila
           $(".loader").hide();
        }else{
          toastr.info(data.msj);
          $(".loader").hide();
        }
      });
    }else{
      toastr.warning('El precio esta vacio o su valor es cero (0).');
    }
  }

  function show_asig(id){
    $("#modal_confirmar_asignacion").modal('show');
    $(".loader").show();

    $.getJSON('operaciones.php',{
        id:btoa(id),
        action: 'traer_asignacion'
      }, function(data){  
        if(data.msg){
          toastr.info(data.msj);
          $(".loader").hide();

          $("#show_serial").html(data.a[0].serial);
          $("#show_estado").html(data.a[0].estado);
          $("#show_municipio").html(data.a[0].municipio);
          $("#show_parroquia").html(data.a[0].parroquia);
          $("#show_destino").html('Codigo:&nbsp;'+data.a[0].codigo+'<br>Nombre:&nbsp;'+data.a[0].nombre+'<br>Dirección:&nbsp;'+data.a[0].direccion+'. Estado <span class="text-danger">'+data.a[0].estado+'</span>, Municipio <span class="text-danger">'+data.alm[0].municipio+'</span>.<br>Telefono:&nbsp;'+data.a[0].telefono+'<br>Nivel:&nbsp;'+data.a[0].descripcion);
          let z = data.alm.length;
          let e = '<ul class="list-group"><strong>';
          for(var x = 0; x < z; x++){
            e += '<li class="list-group-item">';
            e += 'Codigo:&nbsp;'+data.alm[x].codigo+'<br>';
            e += 'Nombre:&nbsp;'+data.alm[x].nombre+'<br>';
            e += 'Dirección:&nbsp;'+data.alm[x].direccion+'. Estado <span class="text-danger">'+data.alm[x].estado+'</span>, Municipio <span class="text-danger">'+data.alm[x].municipio+'</span>.<br>';
            e += 'Telefono:&nbsp;'+data.alm[x].telefono+'<br>';
            e += 'Nivel:&nbsp;'+data.alm[x].descripcion;
            e += '</li>';
          }
          e += '</strong></ul>'
          $("#show_almacen").html(e);
          $("#show_precio_monto").html('Precio unitario:&nbsp;<span class="text-success">'+data.a[0].precio+'&nbsp;Bs</span>.<br>Monto Total:&nbsp;<span class="text-success">'+data.a[0].monto_total+'&nbsp;Bs</sapn>.');
          //cargamos las personas beneficiadas
          z = data.p.length;
          e = '<table class="table table-bordered table-striped mb-none table-condensed" id="tabla-beneficiados"><thead><tr><th>Cedula</th><th>Nombre</th><th>Apellido</th><th>Telefono</th><th>Email</th><th>Nombre Linea</th><th>RIF</th></tr></thead><tbody>';
          for(var x = 0; x < z; x++){
            e += '<tr>';
            e += '<td>'+data.p[x].cedula+'</td>';
            e += '<td>'+data.p[x].nombre+'</td>';
            if (typeof data.p[x].apellido == undefined || data.p[x].apellido == null) {e += '<td></td>';}else{e += '<td>'+data.p[x].apellido+'</td>';}
            if (typeof data.p[x].telefono == undefined || data.p[x].telefono == null) {e += '<td></td>';}else{e += '<td>'+data.p[x].telefono+'</td>';}
            if (typeof data.p[x].email == undefined || data.p[x].email == null) {e += '<td></td>';}else{e += '<td>'+data.p[x].email+'</td>';}
            if (typeof data.p[x].nombre_linea == undefined || data.p[x].nombre_linea == null) {e += '<td></td>';}else{e += '<td>'+data.p[x].nombre_linea+'</td>';}
            if (typeof data.p[x].rif == undefined || data.p[x].rif == null) {e += '<td></td>';}else{e += '<td>'+data.p[x].rif+'</td>';}
            e += '</tr>';
          }
          e += '</tbody></table>';
          $("#show_tabla_personas").html(e);
          $('#tabla-beneficiados').dataTable( {
            "ordering": false
          });
        }else{
          toastr.info(data.msj);
          $(".loader").hide();
        }
      });
  }

  function limpiar_three(){
    $("#precio").val('')
    $("#total").val('');
    $("#id_asignacion").val('');
    $("#cantidad_pro").val('');
    $("#cantidad_productos").html('');
  }

</script>