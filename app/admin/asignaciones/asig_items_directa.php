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
/*solicitudes.fec_solicitud ASC*/
solicitudes.id ASC
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
          <input type="hidden" name="asignacionId" id="asignacionId" value="">
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

        
<?
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/modal_carga.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/modal_detalles_sol.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/modal_paso_uno.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/modal_paso_dos_directo.php';
  include_once $_SESSION['base_url'].'partials/footer.php';
?>

<script type="text/javascript">
  var lista;
  var personas = [];
  var regex = /(\d+)/g;//para algo importante
  var fls;//guardamos la fila a tocar xD
  $('#tabla_sol').dataTable( {"ordering": false});
  
  function mostrar_detalles(id){
    $("#asignacionId").val(id);
    $("#modal_carga").modal('show');
    $.getJSON('../operaciones.php',{
      id:id,
      action: 'mostrar_detalles_filtrado'
    }, function(data){  
      if(data.msg){
        $("#modal_carga").modal('hide');
        let tam = data.r.length;
        let tabla = '<div class="table-responsive"><table class="table table-bordered table-hover" id="tabla_detalle_sol"><thead><tr><th>Tipo</th><th>Producto</th><th>Cantidad</th><th>Placa</th><th>Estatus</th><th>Accion</th></tr></thead><tbody>';
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
            
            if (data.r[x].id_estatus == 1) {accion = '&nbsp;<a data-tool="tooltip" title="Asignar para esta solicitud" onclick="modal_paso_uno('+data.r[x].id+','+data.r[x].cantidad+','+data.r[x].id_rubro+','+data.r[x].estado+','+id_producto+')" class="btn btn-default toltip" href="javascript:void(0)" role="button"><i role="button" class="fa fa-check" aria-hidden="true"></i></a>&nbsp;';}else{accion = '<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>';}
            tabla += '<td>'+accion+'</td>';
          tabla += '</tr>';
        }
        tabla += '</tbody></table></div>';
      $("#cont_modal_detalles_sol").html(tabla);
      $('#modal_detalles_sol').modal('show');
      $('#tabla_detalle_sol').dataTable( {"ordering": false});
      $(".toltip").tooltip();
      }else{
        toastr.info(data.msj);
      }
    })
  }

  function modal_paso_uno(id_detalle,cantidad,tipo,estado,id_producto_sol){
    $("#modal_carga").modal('show');
    $.getJSON('operaciones2.php',{
      tipo:btoa(tipo),
      action: 'step_one_directo'
    }, function(data){  
      if(data.msg == true){
        let tam = data.r.length;  
        let tab='<table class="table table-bordered table-striped mb-none table-condensed" id="tabla_step_one"><thead><tr><th>Codigo</th><th>Codigo</th><th>Producto</th><th>Marca/Modelo</th><th>Cantidad Disponible</th><th>Accion</th></tr></thead>';
        let color = '';
        for(let x = 0 ; x < tam; x++){
          tab+='<tr>';
            tab+='<td>'+data.r[x].estado+'</td>';
            tab+='<td>'+data.r[x].nombre+'</td>';
            tab+='<td class="text-primary">'+data.r[x].descripcion+'</td>';
            tab+='<td class="text-info">'+data.r[x].marca+'/'+data.r[x].modelo+'</td>';
            if (cantidad > data.r[x].total_disponible) {color='danger';}else{color='success';}
            tab+='<td class="text-center"><span class="label label-'+color+'">'+data.r[x].total_disponible+'</span></td>';
            tab+='<td class="text-center"><button data-tool="tooltip" title="Seleccionar este producto para asignar" onclick="paso_dos_directo('+cantidad+','+tipo+','+data.r[x].id_producto+','+estado+','+id_producto_sol+',0,'+id_detalle+',\''+data.r[x].nombre+'\','+data.r[x].total_disponible+','+data.r[x].id_inventario+')" type="button" class="btn btn-primary btn-xs toltip"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span></button></td><tbody>';
          tab+='</tr>';
        }
        tab+='</tbody></table>';
        $("#modal_carga").modal('hide');//cerrmos el loader
        $("#show_cant_sol").html(cantidad);
        $("#cont_tabla_rubros").html(tab);
        $(".toltip").tooltip();
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

  function paso_dos_directo(cantidadSol,idrubro,productoAsig,estado,productoSol,idmov,iddetalle,almacen,almacendisponible,id_inventario){
    $("#id_modal_paso_uno").modal('hide');
    $("#modal_carga").modal('show');

    $.getJSON('operaciones2.php',{
        e:btoa(estado),
        r:btoa(idrubro),
        i:btoa(iddetalle),
        action: 'traer_solicitudes_act_directo',
      }, function(data){  
        if(data.msg){
          //habilitar los campos dependiento del evento
          if (idrubro==1) {$("#act_cincuenta").show();$("#add_cantidad").show();}
          else if(idrubro == 2){$("#act_cincuenta").hide();$("#add_cantidad").hide();}
          else{$("#act_cincuenta").hide();$("#add_cantidad").hide();}

          traer_almacenes_dis();

          $("#modal_carga").modal('hide');
          $("#modal_paso_dos_directo").modal('show');
          $(".descripcion_asig").html(almacen);
          $(".descripcion_asig_can").html(almacendisponible);
          /*add valores esp*/
          $("#env_tipo_asig").val(2);
          $("#env_detalle").val(iddetalle);
          $("#env_idrubro").val(idrubro);
          $("#env_cantsol").val(cantidadSol);
          $("#env_cantasig").val(almacendisponible);
          $("#env_productoAsig").val(productoAsig);
          $("#env_productoSol").val(productoSol);
          $("#env_inventario").val(id_inventario);
          $("#env_estado").val(estado);
          $("#contador_solicitud").val(cantidadSol);
          disponibilidad();

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

          //cargamos personas
          lista = data.r;//agregamos la lista de la personas posibles beneficiarios a una variable global
        
          let c = lista.length;
          let ben = 0;
          for (var i = 0 ; i < c; i++) {
            //personas[i] = [lista[i].id_detalle , lista[i].cantidad , lista[i].activo];
            personas[i] = [lista[i].id , lista[i].cantidad, 1];
          }
          convert_tabla_two(1,lista,personas);

        }else{
          toastr.warning(data.msj);
          $("#modal_carga").modal('hide');
        }
      }).fail(function(data) {
        toastr.error('Error! Problemas con el server.');
        $("#modal_carga").modal('hide');
      });

  }
  function traer_almacenes_dis(){

  }



  function des_add_cantidad(){
    $("#id_cantidad_entrega").hide();
    $("#cantidad_entrega").prop( "disabled", true );
    $("#cantidad_entrega").val('');
    convert_tabla_two(2,lista,personas);
  }
  function act_add_cantidad(){
    $("#id_cantidad_entrega").show();
    $("#cantidad_entrega").prop( "disabled", false );
    convert_tabla_two(2,lista,personas);
  }
  function act_50(){
    $("#id_cantidad_entrega").hide();
    $("#cantidad_entrega").prop( "disabled", true );
    $("#cantidad_entrega").val('');
  }

   //funcion para promediar una entrega del 50%
  function cincuenta(){
    convert_tabla_two(2,lista,personas);
    $("#id_cantidad_entrega").hide();
  }

  //calculo y evento para al seleccionar una cantidad de entrega
  function calcular_entrega(){
    let entrega = $("#cantidad_entrega").val();
    //validamos que no sea negativo 
    if (parseInt(entrega) > parseInt($("#env_cantsol").val())) {toastr.warning('El valor agregado es mayor al solicitado para la unidad.');$("#cantidad_entrega").val('');return;}
    if (Math.sign(entrega)!=-1 && Math.sign(entrega)!=-0 && Math.sign(entrega)!=NaN && isNaN(entrega)!=true) {
      if (entrega!=0 && entrega!='') {
        let a = entrega;
        let c = 0;//creamos variable para guardar la cantidad
        let cal = 0;
        $("table tbody tr td input[type=checkbox]:checked").each(function(){
          c++;
        });
        cal = parseInt(a)*parseInt(c);
        $("#contador_solicitud").val(cal);
        disponibilidad();
      }else{
        let tam = lista.length;
        let sum=0;
        for(x=0;x<tam;x++){
          sum+=lista[x].cantidad;
        }
        $("#contador_solicitud").val(sum);
        $("#cantidad_entrega").val('');
        disponibilidad();
      }
    }else{
      toastr.error('Valor incorrecto para el campo de "Cantidad a entregar".');
      $("#cantidad_entrega").val('');
    }

  }

  //converimos las tabla depende de las opciones
  function convert_tabla_two(opc,objeto,personas){
    let tam = objeto.length;//capturamos el tamaño de los objetos
    let sum_cantidad=0;//inicializamos un contador en cero para la cantidad de producto en la solicitud
    //convertimos la tabla
    let tab = '<table class="table table-bordered table-striped mb-none table-condensed tabla_beneficiarios" id="tabla_step_two"><thead><tr><th>Selección</th><th style="display:none">ID_solicitud</th><th style="display:none">ID_user</th><th>Cedula</th><th>Nombres</th><th>Placa</th><th>Municipio</th><th>Linea</th><th>Fec. Solicitud</th><th>Cantidad</th></tr></thead><tbody>';
    switch(opc) {
      case 1://creamos por primera vez la tabla de los beneficiarios
          for(let x = 0 ; x < tam; x++){
          tab+='<tr id="ptr'+x+'">';
          tab+='<td><label><input type="checkbox" onclick="check_persona(\'pcheck'+x+'\',\'ptr'+x+'\');" id="pcheck'+x+'" class="pcheck" name="pcheck'+x+'" checked></label></td>';
          tab+='<td style="display:none">'+objeto[x].id+'</td>';
          tab+='<td style="display:none">'+objeto[x].id_user+'</td>';
          tab+='<td class="text-primary">'+objeto[x].cedula+'</td>';
          tab+='<td class="text-primary">'+objeto[x].nombre+' '+objeto[x].apellido+'</td>';
          tab+='<td class="text-warning">'+objeto[x].placa+'</td>';
          tab+='<td>'+objeto[x].municipio+'</td>';
          tab+='<td>'+objeto[x].nombre_linea+'</td>';
          tab+='<td>'+objeto[x].fec_solicitud.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1')+'</td>';
          tab+='<td>'+objeto[x].cantidad+'</td>';
          tab+='</tr>';
          sum_cantidad+=parseInt(objeto[x].cantidad);
        }
      break;
      
      case 2://activamos o desactivamos el 50% a os beneficiados.
        
        if($("#tipo_de_asignacion").val()==3){//activamos el 50%

          for(x=0;x<tam;x++){
            let newcan = objeto[x].cantidad/2;
            if (newcan%1!=0) {
              newcan = Math.round(newcan);
            }
            tab+='<tr id="ptr'+x+'">';
          tab+='<td><label><input type="checkbox" onclick="check_persona(\'pcheck'+x+'\',\'ptr'+x+'\');" id="pcheck'+x+'" class="pcheck" name="pcheck'+x+'" checked></label></td>';
          tab+='<td style="display:none">'+objeto[x].id+'</td>';
          tab+='<td style="display:none">'+objeto[x].id_user+'</td>';
          tab+='<td class="text-primary">'+objeto[x].cedula+'</td>';
          tab+='<td class="text-primary">'+objeto[x].nombre+' '+objeto[x].apellido+'</td>';
          tab+='<td class="text-warning">'+objeto[x].placa+'</td>';
          tab+='<td>'+objeto[x].municipio+'</td>';
          tab+='<td>'+objeto[x].nombre_linea+'</td>';
          tab+='<td>'+objeto[x].fec_solicitud.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1')+'</td>';
          tab+='<td>'+newcan+'</td>';
          tab+='</tr>';
            personas[x][1] = newcan;
            sum_cantidad+=parseInt(newcan);
          }
          //disponibilidad();
          toastr.info('50% de asignacion activado!');

        }else if($("#tipo_de_asignacion").val()==2 || $("#tipo_de_asignacion").val()==1){//desactivamos el 50% 

          for(x=0;x<tam;x++){
            tab+='<tr id="ptr'+x+'">';
          tab+='<td><label><input type="checkbox" onclick="check_persona(\'pcheck'+x+'\',\'ptr'+x+'\');" id="pcheck'+x+'" class="pcheck" name="pcheck'+x+'" checked></label></td>';
          tab+='<td style="display:none">'+objeto[x].id+'</td>';
          tab+='<td style="display:none">'+objeto[x].id_user+'</td>';
          tab+='<td class="text-primary">'+objeto[x].cedula+'</td>';
          tab+='<td class="text-primary">'+objeto[x].nombre+' '+objeto[x].apellido+'</td>';
          tab+='<td class="text-warning">'+objeto[x].placa+'</td>';
          tab+='<td>'+objeto[x].municipio+'</td>';
          tab+='<td>'+objeto[x].nombre_linea+'</td>';
          tab+='<td>'+objeto[x].fec_solicitud.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1')+'</td>';
          tab+='<td>'+objeto[x].cantidad+'</td>';
          tab+='</tr>';
            personas[x][1] = objeto[x].cantidad;
            sum_cantidad+=parseInt(lista[x].cantidad);
          }
          //disponibilidad();
          if($("#tipo_de_asignacion").val()==2){toastr.info('Asignacion por defecto activado!');}
          if($("#tipo_de_asignacion").val()==3){toastr.info('Asignacion general activado!');}

        }else{//error 
          console.log('ErrorJs!, Error al encontrar opcion para activar o desactivar el 50%');
        }

      break;

      default:
        console.log('EroorJS!, no hjay opcuion valida para convertir tabla de beneficiados');
    }

    tab+='</tbody></table>';
    $("#cont_tabla_lista_personas").html(tab);
    $("#contador_solicitud").val(sum_cantidad);
    $('.tabla_beneficiarios').dataTable( {"ordering": false});
    disponibilidad();
  }

    //verifica si la disponibilidad es viable
  function disponibilidad(){
    let a = $("#env_cantasig").val();
    let b = $("#contador_solicitud").val();
    if (parseInt(b)!=0 && parseInt(b)>parseInt(a)) {
      $("#suger").html('<span class="label label-danger"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> La disponibilidad actual no es suficiente para cubrir una asignacion con la actual numero de solicitud.</span>');
    }else{
      if (parseInt(b)==0) {
        $("#suger").html('<span class="label label-warning"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> la cantidad de productos solicitados no puede ser cero (0).</span>');
      }else{
        $("#suger").html('<span class="label label-success"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Disponibilidad para abarcar esta asignacion.</span>');
      }
    }
  }

  //evento al seleccionar o deselecionar una persona de la lista de beneficio
  function check_persona(id,idtr){
    if ($("#tipo_de_asignacion").val()==2) {
        if($("#"+id).is(':checked')) {
        let a = parseInt($("#contador_solicitud").val());
        let b = parseInt($("#cantidad_entrega").val());
        let c = parseInt(a)+parseInt(b);
        $("#contador_solicitud").val(c);
      }else{
        let a = parseInt($("#contador_solicitud").val());
        let b = parseInt($("#cantidad_entrega").val());
        let c = parseInt(a)-parseInt(b);
        $("#contador_solicitud").val(c);
      }
    }else{

    let tupla = idtr.match(regex);//coger el numero del ide de la fila
    if($("#"+id).is(':checked')) {
      let a = parseInt($("#contador_solicitud").val());  
      let b = parseInt($("tr#"+idtr+" td:eq(9)").text());
      let suma = parseInt(a)+parseInt(b);
      $("#contador_solicitud").val(suma);
      toastr.info(b+' sumados a la solicitud');
      disponibilidad();
      personas[tupla][2] = 1;
    } else {  
      let a = parseInt($("#contador_solicitud").val());  
      let b = parseInt($("tr#"+idtr+" td:eq(9)").text());
      let resta = parseInt(a)-parseInt(b);
      $("#contador_solicitud").val(resta);
      toastr.info(b+' descontados de la solicitud');
      disponibilidad();
      personas[tupla][2] = 0;
    }
    console.log(personas[tupla][0]+personas[tupla][1]+personas[tupla][2]);
    }
  }

  function clean_dos(){
    $(".descripcion_productoref").html('');
          /*add valores esp*/
          $("#env_tipo_asig").val(0);
          $("#env_detalle").val('');
          $("#env_inventario").val('');
          $("#env_idrubro").val('');
          $("#env_cantsol").val('');
          $("#env_cantasig").val('');
          $("#env_productoAsig").val('');
          $("#env_productoSol").val('');
          $("#contador_solicitud").val('');
          $("#cantidad_entrega").val('');
          $("#cont_tabla_lista_personas").val('');
          $("#tipo_de_asignacion").val(1);  
          $("#id_cantidad_entrega").hide();  
          $("#modal_carga").modal('hide');      
  }

  function asignar(){
    //validamos los parametros
    if($("#centro").val()=='') {toastr.warning('Debe seleccionar un centro de acopio para la asignacion.');return false;}
    if (parseInt($("#contador_solicitud").val())==0) {toastr.warning('La cantidad asignada no puede ser cero(0) o vacia.');return false;}
    if(parseFloat($("#env_cantasig").val())<parseFloat($("#contador_solicitud").val())) {toastr.warning('La cantidad asignada excede la disponibilidad.');return false;}
    $("#modal_carga").modal('show');//mostramos al usuario de un nuevo proceso

    /************GUARDAMOS LAS PERSONAS BENEFICIADAS***********/
    /*let datospersonas = [];
    let can = personas.length;
    let z = 0;
    for(let x = 0; x<can;x++){
      if (personas[x][2] == 1) {//validamos que estuviera activo
        datospersonas[z] =[personas[x][0],personas[x][1]];
        z++;
      }
    }*/

    /*************AJAX SAVE***********/
    $.getJSON('operaciones2.php',{
      detalle:btoa($("#env_detalle").val()),
      centro:btoa($("#centro").val()),
      id_producto_solicitado:btoa($("#env_productoSol").val()),
      id_producto_asig:btoa($("#env_productoAsig").val()),
      cantidad_asignada:btoa($("#contador_solicitud").val()),
      inventario:btoa($("#env_inventario").val()),
      estado:btoa($("#env_estado").val()),
      //personas:datospersonas,/
      action: 'asignar_directo',
    }, function(data){  
      if(data.msg){
        $("#modal_carga").modal('hide');
        toastr.success(data.msj);
        $("#modal_paso_dos_directo").modal('hide');
        mostrar_detalles($("#asignacionId").val());
        //if (data.st == 2) {get_listado_cuotas(data.r);}else{$("tbody #"+fls).closest('tr').remove();}
        clean_dos();
      }else{
        toastr.error(data.msj);
        $("#modal_carga").modal('hide');
        $("#modal_paso_dos_directo").modal('hide');
      }
    }).fail(function(data) {
      toastr.error('Error! Problemas con el server.');
      $("#modal_carga").modal('hide');
      $("#modal_preasignacion_dos").modal('hide');
      clean_dos();
    });
  }

</script>
