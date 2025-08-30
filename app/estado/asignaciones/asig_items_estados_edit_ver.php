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
      <h1 class="panel-title">Asignacion de items a los Estados.</h1>
    </header>
      <div class="panel-body">
        <a class="btn btn-default" href="<?php echo $_SESSION['base_url1']."app/admin/asignaciones/asig_items_estados.php"?>" role="button"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Volver</a>
       
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
                          <td class="text-danger">'.$rs->estado.'</td>
                          <td>'.$rs->descripcion.'</td>
                          <td class="text-primary">'.$rs->producto.'</td>
                          <td><span class="badge">'.$rs->solicitudes.'</span></td>
                          <td><span class="badge">'.$rs->cantidad.'</span></td>
                          <td>&nbsp;<a data-tool="tooltip" title="Asignar items al estado" onclick="modal_paso_uno('.$rs->cantidad.','.$rs->id_rubro.','.$e.','.$rs->id_producto.');" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-check" aria-hidden="true"></i></a>&nbsp;</td>
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
  include_once $_SESSION['base_url'].'partials/footer.php';
?>
<script>
  var almacenesd ;

   $(document).ready(function(){
    $("#tabla_detallados").DataTable();
  });

  function modal_paso_uno(cantidad,tipo,estado,id_producto_sol){
    $("#modal_carga").modal('show');
    $.getJSON('operaciones.php',{
      tipo:btoa(tipo),
      action: 'step_one'
    }, function(data){  
      if(data.msg == true){
        let tam = data.r.length;  
        let tab='<table class="table table-bordered table-striped mb-none table-condensed" id="tabla_step_one"><thead><tr><th>Codigo</th><th>Producto</th><th>Marca/Modelo</th><th>Cantidad Disponible</th><th>Accion</th></tr></thead>';
        let color = '';
        for(let x = 0 ; x < tam; x++){
          tab+='<tr>';
            tab+='<td>'+data.r[x].cod_almacen+'</td>';
            tab+='<td class="text-primary">'+data.r[x].descripcion+'</td>';
            tab+='<td class="text-info">'+data.r[x].marca+'/'+data.r[x].modelo+'</td>';
            if (cantidad > data.r[x].total_disponible) {color='danger';}else{color='success';}
            tab+='<td class="text-center"><span class="label label-'+color+'">'+data.r[x].total_disponible+'</span></td>';
            tab+='<td class="text-center"><button onclick="modal_paso_dos('+cantidad+','+tipo+','+data.r[x].id_producto+','+estado+','+id_producto_sol+')" type="button" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span></button></td><tbody>';
          tab+='</tr>';
        }
        tab+='</tbody></table>';
        $("#modal_carga").modal('hide');//cerrmos el loader
        $("#show_cant_sol").html(cantidad);
        $("#cont_tabla_rubros").html(tab);
        //convertimos la tabla sin orden impuesto
        $('#tabla_step_one').dataTable( { "ordering": false,});
        $("#id_modal_paso_uno").modal('show');
      }else{
        toastr.info(data.msj);
        $("#modal_carga").modal('hide');//cerrmos el loader
        $("#id_modal_paso_uno").modal('hide');
      }
    }).fail(function(data) {
      toastr.error('Error! Problemas con el server.');
      $("#modal_carga").modal('hide');
      $("#id_modal_paso_uno").modal('hide');
    });

  }

  function modal_paso_dos(cantidad,tipo,id_producto_asi,estado,id_producto_sol){
    $("#modal_carga").modal('show');
    $("#id_modal_paso_uno").modal('hide');

     $.getJSON('operaciones.php',{
      producto:btoa(id_producto_asi),
      estado:btoa(estado),
      action: 'traer_alm_disponibles'
    }, function(data){  
      if(data.msg){
        $("#show_cantidad").html(cantidad);
        $("#env_cantidad_sol").val(cantidad);
        $("#env_tipo").val(tipo);
        $("#env_id_producto_asi").val(id_producto_asi);
        $("#env_estado").val(estado);
        $("#env_id_producto_sol").val(id_producto_sol);

        /*******cargamos almacenes destino********/
        let sel = '<option value="">Seleccione Almacen de destino</option>';
        tam = data.a.length;
        if (tam==0) {toastr.warning('No existe registro disponible de almacen en el estado.');}
        sel+='<optgroup label="'+data.a[0].rubro+'">';
        let s = data.a[0].rubro;
        for(let x = 0 ; x < tam; x++){
          if (data.a[x].rubro == s) {sel+='<option value="'+data.a[x].id+'">Nivel: '+data.a[x].descripcion+'  | <strong>Nombre:</strong> '+data.a[x].nombre+'</option> ';}
          else{
            sel+='</optgroup>';
            s = data.a[x].rubro;
            sel+='<optgroup label="'+data.a[x].rubro+'">';
            sel+='<option value="'+data.a[x].id+'">Nivel: '+data.a[x].descripcion+'  | <strong>Nombre:</strong> '+data.a[x].nombre+'</option> ';
          }
        }
        sel+='</optgroup>';
        $("#centro").html(sel);
        /******cargando los almacenes con disposicion*****/
        almacenesd = data.r;
        let listalmacen = '';
        tam = almacenesd.length;
        for(let x = 0; x <tam; x++){
          listalmacen += '<div class="checkbox list-group-item"><label><input data-disponible="'+almacenesd[x].disponible+'" data-inventario="'+almacenesd[x].id_inventario+'" type="checkbox" value="" id="alm'+x+'" name="almacenes[]" onclick="checkalmacen(\'alm'+x+'\');"> <strong>'+almacenesd[x].nombre+'</strong> <span class="text-danger">/</span> <strong>Nivel del Almacen:'+almacenesd[x].niveld+'</strong> <span class="text-danger">/</span> <strong>Estado:'+almacenesd[x].estadod+'</strong>. <span class="badge">'+almacenesd[x].disponible+' disponibles</span></label></div>';
        }
        $("#lista_almacen").html(listalmacen);

        
        $("#modal_carga").modal('hide');
        $('#id_modal_paso_dos').modal('show');
      }else{
        toastr.info(data.msj);
        $("#modal_carga").modal('hide');
        $('#id_modal_paso_dos').modal('hide');
      }
    }).fail(function(data) {
      toastr.error('Error! Problemas con el server.');
      $("#modal_carga").modal('hide');
      $('#id_modal_paso_dos').modal('hide');
      limpiar_two();
    });

  }

  function checkalmacen(idcheck){
    let a = $("#"+idcheck).data('disponible');
    let b = $("#contador_disponibilidad").val();
    let c=0;
    if($("#"+idcheck).is(':checked')) {
      let cant = prompt("Ingrese la cantidad a descontar");
      if(cant == null || cant == false){$("#"+idcheck).prop("checked", false);return;}
      if (!/^([0-9])*$/.test(cant)){toastr.warning('El valor ingresado parace ser diferente al tipo permitido. Porfavor ingrese un valor numerico.');$("#"+idcheck).prop("checked", false);return;}
      if (cant > a) {toastr.warning('cantidad a descontar es mayor a lo disponible.');$("#"+idcheck).prop("checked", false);return;}
      if(cant == 0 || cant == ''){toastr.warning('La cantidad a descontar que ingreso es cero(0) o esta vacia, verifique sus datos ingresados.');$("#"+idcheck).prop("checked", false);return;}
      $("#"+idcheck).val(cant);
      c = parseInt(b)+parseInt(cant);
    }else{c = parseInt(b)-parseInt($("#"+idcheck).val());$("#"+idcheck).val(0);}
    $("#contador_disponibilidad").val(c);
  }

  function limpiar_modal_dos(){
    $("#show_cantidad").html('');
    $("#show_cantidad").html('');
    $("#env_cantidad_sol").val('');
    $("#env_tipo").val('');
    $("#env_id_producto_asi").val('');
    $("#env_estado").val('');
    $("#env_id_producto_sol").val('');
    $("#contador_disponibilidad").val(0);
  }

  function asignar_items_estado(){
    $("#modal_carga").modal('show');
    if ($("#contador_disponibilidad").val() == 0 || $("#contador_disponibilidad").val() == 0) {toastr.warning('No tiene ninguna cantidad para asignar.');$("#modal_carga").modal('hide');return;}
    if($("#centro").val() == ''){toastr.warning('No tien seleccionado ningun almacen de destino.');$("#modal_carga").modal('hide');return;}

    let x = 0;
    let datosalmacenes = [];
    $("ul li input[type=checkbox]:checked").each(function(){
      datosalmacenes[x] = [$("#alm"+x).data('inventario'),$("#alm"+x).val()];
      x++;
    });

     $.getJSON('operaciones.php',{
      destino:btoa($("#centro").val()),
      producto_sol:btoa($("#env_id_producto_sol").val()),
      producto_asig:btoa($("#env_id_producto_asi").val()),
      rubro:btoa($("#env_tipo").val()),
      cantidad_asig:btoa($("#contador_disponibilidad").val()),
      cantidad_sol:btoa($("#env_cantidad_sol").val()),
      estado:btoa($("#env_estado").val()),
      almacenes:datosalmacenes,
      action: 'asignar_items_estado',
              }, function(data){  
                if(data.msg){
                  $("#modal_carga").modal('hide');
                  $('#id_modal_paso_dos').modal('hide');
                  limpiar_modal_dos();
                  toastr.info(data.msj);
                }else{
                  toastr.info(data.msj);
                  $("#modal_carga").modal('hide');
                  $('#id_modal_paso_dos').modal('hide');
                  limpiar_modal_dos();
                }
              }).fail(function(data) {
                toastr.error('Error! Problemas con el server.');
                $("#modal_carga").modal('hide');
              });
  }

</script>
