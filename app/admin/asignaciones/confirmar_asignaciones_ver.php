<?
  if(!isset($_SESSION))
    {
      session_start();
    }
    include_once $_SESSION['base_url'].'partials/header.php';

    $rubro = base64_decode($_GET['key2']);
    $selec="";
    $inner="";
    switch ($rubro) {
      case '1'://neu
        $selec = "cauchos.neumatico AS name_producto_sol";
        $inner = "INNER JOIN cauchos ON asignaciones.id_producto_solicitado = cauchos.id";
      break;
      case '2'://lub
        $selec = "lubricantes.lubricante AS name_producto_sol";
        $inner = "INNER JOIN lubricantes ON asignaciones.id_producto_solicitado = lubricantes.id";
      break;
      case '3'://acu
        $selec = "acumuladores.acumulador AS name_producto_sol";
        $inner = "INNER JOIN acumuladores ON asignaciones.id_producto_solicitado = acumuladores.id";
      break;
      
      default:
        echo 'Opcion invalida....';
      break;
    }

    $system->sql="SELECT
asignaciones.id,
asignaciones.estado as id_estado,
asignaciones.id_producto_solicitado,
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
productos.precio,
asignaciones.beneficiados,
almacenes.id AS id_acopio,
mov_items.id AS id_mov,
asignaciones.cantidad_solicitud,
".$selec."
FROM
asignaciones
INNER JOIN estados ON asignaciones.estado = estados.id_estado
INNER JOIN estatus ON asignaciones.estatus = estatus.id
INNER JOIN mov_items ON asignaciones.id_mov = mov_items.id
INNER JOIN almacenes ON mov_items.destino = almacenes.id
INNER JOIN productos ON asignaciones.id_producto = productos.id
INNER JOIN rubros ON productos.tipo = rubros.id
INNER JOIN presentaciones ON productos.presentacion = presentaciones.id
".$inner."
WHERE
asignaciones.estatus = 2 AND
asignaciones.id = ".base64_decode($_GET['key'])."
ORDER BY
asignaciones.fec_reg ASC
";
$r = $system->sql();
if(count($r)>0):
?>
  
  <section class="panel panel-featured panel-featured-success">
    <header class="panel-heading">
      <div class="panel-actions">
        <a href="#" class="fa fa-caret-down"></a>
        <a href="#" class="fa fa-times"></a>
      </div>
      <h1 class="panel-title"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span> Serial: <?= $r[0]->serial?></h1>
    </header>
      <div class="panel-body">
        <h5><span class="badge bg-info">Datos de las Solicitudes:</span></h5>
        <h5>Estado:<strong><?= $r[0]->estado;?></strong></h5>
        <h5>Tipo de Producto:<strong><?= $r[0]->name_rubro;?></strong></h5>
        <h5>Producto Solicitado:<strong><?= $r[0]->cantidad_solicitud." ".$r[0]->name_presentacion;?> de <?= $r[0]->name_producto_sol;?></strong></h5>
        <hr>
        <h5><span class="badge bg-info">Datos de la asignacion:</span></h5>
        <h5>Fecha:<strong><?= date("d/m/Y", strtotime($r[0]->fec_reg));?></strong></h5>
        <h5>Producto asignado:<strong><?= $r[0]->name_producto;?></strong>&nbsp;<a onclick="ver_producto(<?= $r[0]->id_producto;?>)" href="javascript:void(0)"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>Ver</a></h5>
        <h5>Cantidad asignada:<strong id="ver_cantidadasignada"><?= $r[0]->cantidad_asignada?></strong><strong>&nbsp;<?=$r[0]->name_presentacion;?></strong></h5>
        <h5>Precio unitario:<strong id="ver_precio"><?= $r[0]->precio;?></strong>&nbsp;<a onclick="cambiar_precio(<?= $r[0]->id;?>,'<?= $r[0]->name_producto;?>')" href="javascript:void(0)"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>Cambiar precio</a></h5>
        <h5>Monto total:<strong id="ver_montototal"><?= $r[0]->monto_total;?></strong></h5>
        <h5>Centro de acopio destinado:<strong> <?= $r[0]->acopio;?>&nbsp;<a onclick="ver_acopio(<?= $r[0]->id_acopio;?>)" href="javascript:void(0)"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>Ver</a></strong></h5>
        <h5>Cantidad de unidades preasignadas:<strong id="ver_beneficiados"><?= $r[0]->beneficiados;?></strong></h5>
        <h5>Estatus: <strong><span class="badge"><?= $r[0]->name_estatus;?></span></strong> </h5>

        <ol class="breadcrumb">
          <li><a href="#"><a onclick="ver_almacenes(<?= $r[0]->id_mov;?>)" href="javascript:void(0)"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Ver distribucion de descuento de almacenes</a></a></li>
          <li><a href="#"><a onclick="ver_cuota(<?= $r[0]->id_mov;?>)" href="javascript:void(0)"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Ver cuota asignada</a></a></li>
          <li><a onclick="ver_observaciones(<?= $r[0]->id;?>)" href="javascript:void(0)"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Ver Observaciones</a></li>
        </ol>
        <hr>
        <h5><span class="badge bg-info">Unidades Preasignadas:</span></h5>
          <div id="con_tabla_lista_unidades"></div>
        <h5><span class="badge bg-warning">Unidades sin preasignar:</span></h5>
        <p><strong>Las unidades en la siguiente lista pertenecen a las solicitudes del producto solicitado primordialmente que no fueron incluidos en la preasignacion por parte del estado.</strong></p>
          <div id="con_tabla_lista_unidades_noagregados"></div>
      </div>
      <hr>
      <div class="btn-group" role="group" aria-label="...">
        <a data-tool="tooltip" title="Volver a la lista de solicitudes por confirmar" class="btn btn-default" href="<?= $_SESSION['base_url1'].'app/admin/asignaciones/confirmar_asignaciones.php' ?>" role="button">Volver</a>
        <button onclick="cancelar_preasignacion(<?= $r[0]->id;?>);" data-tool="tooltip" title="Cancelar la asignacion, para que el estado realice alguna modificacion" type="button" class="btn btn-default">Cancelar</button>
        <button onclick="confirmar_preasignacion(<?= $r[0]->id;?>);" data-tool="tooltip" title="Confirmar asignacion para el proceso de translado y despacho del producto" type="button" class="btn btn-default">Confirmar</button>
      </div>
  </section>
        
<?
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/modal_carga.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/confirmacion/modal_observacion.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/confirmacion/modal_agregar_unidad.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/confirmacion/modal_info.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/confirmacion/modal_cambio_precio.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/confirmacion/modal_cancelar_asignacion.php';
  include_once $_SESSION['base_url'].'partials/footer.php';
?>

<script type="text/javascript">
  $('#datatable-editable').dataTable( {
      "ordering": false
    });
  lista_unidades();

  function lista_unidades(){
    let key = '<?= base64_encode($r[0]->id); ?>';
    let key2 = '<?= base64_encode($r[0]->id_producto_solicitado); ?>';
    let key3 = '<?= base64_encode($rubro); ?>';
    let key4 = '<?= base64_encode($r[0]->id_estado); ?>';
    $("#modal_carga").modal('show');
    $.getJSON('operaciones2.php',{
      key:key,
      key2:key2,
      key3:key3,
      key4:key4,
      action: 'listar_unidades_asignacion'
    }, function(data){  
        if(data.msg){
          $("#modal_carga").modal('hide');
          $('#modal_sel_precio').modal('hide');
          toastr.info(data.msj);
          let l = data.r.length;
          let tab = '<table class="table table-bordered table-striped mb-none table-condensed" id="tabla_lista_unidades"><thead><tr><th>Placa</th><th>Cedula</th><th>Nombres</th><th>Linea</th><th>Telf.</th><th>Fec. Solicitud</th><th>Cant. Solicitada</th><th>Cant. Asignada</th><th>Accion</th></tr></thead><tbody>';
          for(let x = 0; x<l; x++){
            tab += '<tr>';
              tab += '<td>'+data.r[x].placa+'</td>';
              tab += '<td><a>'+data.r[x].cedula+'</a></td>';
              tab += '<td>'+data.r[x].nombre+' '+data.r[x].apellido+'</td>';
              tab += '<td>'+data.r[x].nombre_linea+'</td>';
              tab += '<td>'+data.r[x].telefono+'</td>';
              tab += '<td>'+data.r[x].fec_solicitud.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1')+'</td>';
              tab += '<td>'+data.r[x].cant_solicitada+'</td>';
              tab += '<td>'+data.r[x].cant_asignada+'</td>';
              tab += '<td>'
                tab += '<a onclick="historial_unidad('+data.r[x].id_user+','+data.r[x].id_rubro+')" class="tool" data-tool="tooltip" title="Ver Historial de '+data.r[x].placa+'" href="javascript:void(0)"><i class="fa fa-info-circle fa-lg" aria-hidden="true"></i></a>&nbsp;';
                tab += '<a onclick="eliminar_unidad_lista('+data.r[x].id_asignacion+','+data.r[x].id_detalle+',\''+data.r[x].placa+'\')" class="tool" data-tool="tooltip" title="Eliminar de la lista" href="javascript:void(0)"><i class="fa fa-times-circle fa-lg" aria-hidden="true"></i></a>';
              tab += '</td>';
            tab += '</tr>';
          }
          tab += '</tbody></table>';
          $("#con_tabla_lista_unidades").html(tab);
          $("#tabla_lista_unidades").dataTable({"ordering": false});
          /***Lista no agregados**/
          l = data.n.length;
          tab = '<table class="table table-bordered table-striped mb-none table-condensed" id="tabla_lista_unidades_noagregados"><thead><tr><th>Placa</th><th>Cedula</th><th>Nombres</th><th>Linea</th><th>Telf.</th><th>Fec. Solicitud</th><th>Cant. Solicitada</th><th>Accion</th></tr></thead><tbody>';
          for(let y = 0; y<l; y++){
            tab += '<tr>';
              tab += '<td>'+data.n[y].placa+'</td>';
              tab += '<td><a>'+data.n[y].cedula+'</a></td>';
              tab += '<td>'+data.n[y].nombre+' '+data.n[y].apellido+'</td>';
              tab += '<td>'+data.n[y].nombre_linea+'</td>';
              tab += '<td>'+data.n[y].telefono+'</td>';
              tab += '<td>'+data.n[y].fec_solicitud.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1')+'</td>';
              tab += '<td>'+data.n[y].cant_solicitada+'</td>';
              tab += '<td>'
                tab += '<a onclick="historial_unidad('+data.n[y].id_user+','+data.n[y].id_rubro+')" class="tool" data-tool="tooltip" title="Ver Historial de '+data.n[y].placa+'" href="javascript:void(0)"><i class="fa fa-info-circle fa-lg" aria-hidden="true"></i></a>&nbsp;';
                tab += '<a onclick="agregar_unidad_lista('+data.n[y].id_rubro+','+data.n[y].id_detalle+','+data.a+','+data.n[y].cantidad+',\''+data.n[y].placa+'\')" class="tool" data-tool="tooltip" title="Agregar a la lista" href="javascript:void(0)"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></a>';
              tab += '</td>';
            tab += '</tr>';
          }
          tab += '</tbody></table>';
          $("#con_tabla_lista_unidades_noagregados").html(tab);
          $("#tabla_lista_unidades_noagregados").dataTable({"ordering": false});
          $(".tool").tooltip();
        }else{
          $("#modal_carga").modal('hide');
          toastr.warning(data.msj);
        }
      });
  }

  function ver_producto(producto){
    $("#modal_carga").modal('show');
    $.getJSON('operaciones2.php',{
        key:btoa(producto),
        action: 'ver_producto'
      }, function(data){  
          if(data.msg){
            $("#id_modal_info").modal('show');
            $("#modal_carga").modal('hide');
            let info = 'Serial: '+data.r[0].codigo+'.<br>';
            info += 'Nombre: '+data.r[0].descripcion+'.<br>';
            info += 'Marca: '+data.r[0].marca+'.<br>';
            info += 'Modelo: '+data.r[0].modelo+'.<br>';
            info += 'Precio unitario: '+data.r[0].precio+'.<br>';
            info += 'Tipo de Producto: '+data.r[0].tipo+'.<br>';
            $("#cont_info").html(info);
            toastr.info(data.msj);
          }else{
            $("#modal_carga").modal('hide');
            toastr.warning(data.msj);
          }
        });
  }
  function ver_acopio(id){
    $("#modal_carga").modal('show');
    $.getJSON('operaciones2.php',{
        key:btoa(id),
        action: 'ver_acopio'
      }, function(data){  
          if(data.msg){
            $("#id_modal_info").modal('show');
            $("#modal_carga").modal('hide');
            let info = 'Nivel: '+data.r[0].name_nivel+'.<br>';
            info += 'Estado: '+data.r[0].name_estado+'.<br>';
            info += 'Referencia: '+data.r[0].referencia+'-'+data.r[0].codigo+'.<br>';
            info += 'Nombre del almacen: '+data.r[0].nombre+'.<br>';
            info += 'Direccion: '+data.r[0].direccion+'.<br>';
            info += 'Telefono: '+data.r[0].telefono+'.<br>';
            info += 'Telefono de contacto: '+data.r[0].tel_contac+'.<br>';
            info += 'Destinado para: '+data.r[0].name_rubro+'.<br>';
            $("#cont_info").html(info);
            toastr.info(data.msj);
          }else{
            $("#modal_carga").modal('hide');
            toastr.warning(data.msj);
          }
        });
  }
  function ver_almacenes(id){
    $.getJSON('operaciones2.php',{
        key:btoa(id),
        action: 'ver_almacenes_descuentos'
      }, function(data){  
          if(data.msg){
            $("#id_modal_info").modal('show');
            $("#modal_carga").modal('hide');
            let l = data.r.length;
            let lista ='<ul class="list-group">';
              for(let i = 0;i<l ; i++){
                lista +='<li class="list-group-item">';
                lista +='Estado'+'<br>';
                lista +='Almacen: ('+data.r[i].referencia+data.r[i].codigo+')'+data.r[i].nombre+'<br>';
                lista +='Nivel: '+data.r[i].name_nivel+'<br>';
                lista +='Direccion: '+data.r[i].direccion+'<br>';
                lista +='Telefono: '+data.r[i].telefono+'<br>';
                lista +='Telefono de contacto: '+data.r[i].tel_contac+'<br>';
                lista +='Rubro: '+data.r[i].name_rubro+'<br>';
                lista +='Cantidad a despachar: <span class="text-danger">'+data.r[i].cantidad+'</span><br>';
                lista += '</li>';
              }
            lista += '</ul>';
            $("#cont_info").html(lista);
            toastr.info(data.msj);
          }else{
            $("#modal_carga").modal('hide');
            toastr.warning(data.msj);
          }
        });
  }
  function ver_cuota(id){
    $("#modal_carga").modal('show');
    $.getJSON('operaciones2.php',{
        key:btoa(id),
        action: 'ver_cuota'
      }, function(data){  
          if(data.msg){
            $("#id_modal_info").modal('show');
            $("#modal_carga").modal('hide');
            let info = 'Nº: '+data.r[0].id+'.<br>';
            info += 'Estado: '+data.r[0].estado+'.<br>';
            info += 'Producto asignado: '+data.r[0].name_producto+'.<br>';
            info += 'Cantidad asignada: '+data.r[0].cantidad_asig+'.<br>';
            info += 'Cantidad de disponible: '+data.r[0].cantidad_disponible+'.<br>';
            info += 'Almacen destino: '+data.r[0].name_destino+'.<br>';
            info += 'Fecha de asignación: '+data.r[0].fec_reg.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1')+'.<br>';
            info += 'Estatus: '+data.r[0].name_estatus+'.<br>';
            $("#cont_info").html(info);
            toastr.info(data.msj);
          }else{
            $("#modal_carga").modal('hide');
            toastr.warning(data.msj);
          }
        });
  }
  function ver_observaciones(id){
    $.getJSON('operaciones2.php',{
        key:btoa(id),
        action: 'ver_observaciones'
      }, function(data){  
          if(data.msg){
            $("#id_modal_info").modal('show');
            $("#modal_carga").modal('hide');
            let l = data.r.length;
            let lista ='<ul class="list-group">';
              for(let i = 0;i<l ; i++){
                lista +='<a href="#" class="list-group-item">';
                lista +='<h4 class="list-group-item-heading">('+data.r[i].desde+')'+data.r[i].accion+'</h4>';
                lista += '<p class="list-group-item-text">'+data.r[i].observacion+'</p></a>';
              }
            lista += '</ul>';
            $("#cont_info").html(lista);
            toastr.info(data.msj);
          }else{
            $("#modal_carga").modal('hide');
            toastr.warning(data.msj);
          }
        });
  }
  function cambiar_precio(id,producto){
    alertify.confirm('Regmintra','¿Seguro quiere cambiar precio del producto <span class="text-danger">'+producto+'</span> para esta asignacón?',function(){ 
      $("#keyasigpro").val(id);
      $("#id_modal_cambio_precio").modal('show');
    },function(){ 
      toastr.info('Cancelado');
    }).set('labels', {ok:'SI', cancel:'CANCELAR'});
  }
  function confirmar_cambio_precio(){
    $("#modal_carga").modal('show');
    $.getJSON('operaciones2.php',{
        key:btoa($("#keyasigpro").val()),
        precio:btoa($("#precionew").val()),
        action: 'cambio_precio_asignacion'
      }, function(data){  
          if(data.msg){
            $("#modal_carga").modal('hide');
            toastr.info(data.msj);
            cancelar_cambio_precio();
            $("#ver_precio").html(data.precio);
            $("#ver_montototal").html(data.total);
          }else{
            $("#modal_carga").modal('hide');
            toastr.warning(data.msj);
            cancelar_cambio_precio();
          }
        });
  }
  function cancelar_cambio_precio(){
    $("#id_modal_cambio_precio").modal('hide');
    $("#precionew").val('');
    $("#keyasigpro").val('');
  }
  function validar_precio(){
    let precio = $("#precionew").val();
        if (Math.sign(precio)!=-1 && Math.sign(precio)!=-0 && Math.sign(precio)!=NaN && isNaN(precio)!=true) {
        }else{
          toastr.warning('El valor del precio no es un valor valido');
          $("#precionew").val('');  
        }
    }

  function historial_unidad(iduser,idrubro){
    $("#modal_carga").modal('show');
    $.getJSON('operaciones2.php',{
        keyru:btoa(idrubro),
        key:btoa(iduser),
        action: 'historial_asignaciones_unidades'
      }, function(data){  
          if(data.msg){
            
          }else{
            $("#modal_carga").modal('hide');
            toastr.warning(data.msj);
          }
        });
  }
  
  function eliminar_unidad_lista(idasignacion,iddetalle,placa){
    alertify.confirm('Regmintra','¿Seguro quiere eliminar a la unidad con placa <span class="text-danger">'+placa+'</span> de esta lista de preasignación?',function(){ 
      $("#id_modal_observacion").modal('show');
      $("#keyasignacion").val(idasignacion);
      $("#keydetalle").val(iddetalle);
    },function(){ 
      toastr.info('Cancelado');
    }).set('labels', {ok:'SI', cancel:'CANCELAR'});
  }
  function confirmar_eliminacion_unidad(){
    if($("#observacion").val()!=''){
      $("#modal_carga").modal('show');
      $.getJSON('operaciones2.php',{
        keyas:btoa($("#keyasignacion").val()),
        key:btoa($("#keydetalle").val()),
        obser:btoa($("#observacion").val()),
        action: 'eliminar_unidad_lista'
      }, function(data){  
          if(data.msg){
            $("#modal_carga").modal('hide');
            toastr.success(data.msj);
            cancelar_eliminacion_unidad();
            lista_unidades();
            $("#ver_beneficiados").html(data.r[0].beneficiados);
            $("#ver_cantidadasignada").html(data.r[0].cantidad_asignada);
            $("#ver_montototal").html(data.r[0].monto_total);
          }else{
            $("#modal_carga").modal('hide');
            toastr.warning(data.msj);
            cancelar_eliminacion_unidad();
          }
        });
    }else{toastr.warning('Debe describir la observacion, Porfavor');}
  }
  function cancelar_eliminacion_unidad(){
    $("#id_modal_observacion").modal('hide');
    $("#keydetalle").val('');
    $("#keyasignacion").val('');
    $("#observacion").val('');
  }

  function agregar_unidad_lista(idrubro,iddetalle,idasignacion,cantidad,placa){
    alertify.confirm('Regmintra','¿Seguro quiere agregar a la unidad con placa <span class="text-danger">'+placa+'</span> a esta lista de preasignación?',function(){ 
      $("#id_modal_agregar_unidad").modal('show');
      $("#keyasignacionplus").val(idasignacion);
      $("#keydetalleplus").val(iddetalle);
      $("#keycantidad").val(cantidad);
      $("#cantidadsumar").val(cantidad);
      $("#keyrubroplus").val(idrubro);
      if (idrubro == 1) {$("#cantidadsumar").prop('disabled', false);}else{$("#cantidadsumar").prop('disabled', true);}
    },function(){ 
      toastr.info('Cancelado');
    }).set('labels', {ok:'SI', cancel:'CANCELAR'});
  }
  function confirmar_agregar_unidad(){
    if ($("#cantidadsumar").val()=='' || $("#cantidadsumar").val()==0) {toastr.warning('Cantidad a entregar es vacia o esta en cero(0).');return false;}
    if ($("#cantidadsumar").val() > $("#keycantidad").val()) {toastr.warning('La cantidad asignada es mayor a la cantidad solicitada.');return false;}
    if($("#observacionagregar").val()!=''){
      $("#modal_carga").modal('show');
      $.getJSON('operaciones2.php',{
        keyru:btoa($("#keyrubroplus").val()),
        keyas:btoa($("#keyasignacionplus").val()),
        key:btoa($("#keydetalleplus").val()),
        keycan:btoa($("#cantidadsumar").val()),
        obser:btoa($("#observacionagregar").val()),
        action: 'agregar_unidad_lista'
      }, function(data){  
          if(data.msg){
            $("#modal_carga").modal('hide');
            toastr.success(data.msj);
            cancelar_agregar_unidad();
            lista_unidades();
            $("#ver_beneficiados").html(data.r[0].beneficiados);
            $("#ver_cantidadasignada").html(data.r[0].cantidad_asignada);
            $("#ver_montototal").html(data.r[0].monto_total);
          }else{
            $("#modal_carga").modal('hide');
            toastr.warning(data.msj);
            cancelar_agregar_unidad();
          }
        });
    }else{toastr.warning('Debe describir la observacion, Porfavor');}
  }
  function cancelar_agregar_unidad(){
    $("#id_modal_observacion").modal('hide');
    $("#keydetalleplus").val('');
    $("#keyasignacionplus").val('');
    $("#cantidadsumar").val('');
    $("#keycantidad").val('');
    $("#observacionagregar").val('');
    $("#keyrubroplus").val('');
    $("#cantidadsumar").prop('disabled', true);
  }

  function confirmar_preasignacion(id){
    alertify.confirm('Regmintra','¿Seguro quiere confirmar esta preasignación?',function(){
      
      $.getJSON('operaciones2.php',{
        key:btoa(id),
        action: 'confirmar_preasignacion'
      }, function(data){  
          if(data.msg){
            $("#modal_carga").modal('hide');
            toastr.success(data.msj);
            window.location.href = data.url;
            cancelar_c_preasignacion()
          }else{
            $("#modal_carga").modal('hide');
            toastr.warning(data.msj);
            cancelar_c_preasignacion()
          }
      });

    },function(){ 
      toastr.info('Cancelado');
    }).set('labels', {ok:'SI', cancel:'CANCELAR'});
  }

  function cancelar_preasignacion(id){
    alertify.confirm('Regmintra','¿Seguro quiere cancelar el proceso de esta asignación?',function(){
      $("#keyasignacioncancelar").val(id);
      $("#id_modal_observacion_cancelar").modal('show');
    },function(){ 
      toastr.info('Cancelado');
    }).set('labels', {ok:'SI', cancel:'CANCELAR'});
  }
  function confirmar_cancelar_preasignacion(){
    $.getJSON('operaciones2.php',{
        key:btoa($("#keyasignacioncancelar").val()),
        observacion:btoa($("#observacioncancelar").val()),
        action: 'cancelar_preasignacion'
      }, function(data){  
          if(data.msg){
            $("#modal_carga").modal('hide');
            toastr.success(data.msj);
            window.location.href = data.url;
            cancelar_c_preasignacion()
          }else{
            $("#modal_carga").modal('hide');
            toastr.warning(data.msj);
            cancelar_c_preasignacion()
          }
        });
  }
  function cancelar_c_preasignacion(){
    $("#id_modal_observacion_cancelar").modal('hide');
    $("#observacioncancelar").val('');
  }

  function limpiar_info(){
    $("#cont_info").html('');
  }
</script>

<?
else:?>
  <div class="alert alert-warning" role="alert">
  No disponible ya!
</div>
  <a data-tool="tooltip" title="Volver a la lista de solicitudes por confirmar" class="btn btn-default" href="<?= $_SESSION['base_url1'].'app/admin/asignaciones/confirmar_asignaciones.php' ?>" role="button">Volver</a>
<?endif;
?>