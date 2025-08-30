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
      <h1 class="panel-title">Listado de consignaciones para el estado <?= $_SESSION['estado']?></h1>
    </header>
      <div class="panel-body">

         <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#home" onclick="get_listado_cuotas(1);" aria-controls="home" role="tab" data-toggle="tab">Neumaticos</a></li>
          <li role="presentation"><a href="#profile" onclick="get_listado_cuotas(2);" aria-controls="profile" role="tab" data-toggle="tab">Lubricante</a></li>
          <li role="presentation"><a href="#messages" onclick="get_listado_cuotas(3);" aria-controls="messages" role="tab" data-toggle="tab">Acumuladores</a></li>
        </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home"><div id="cont_lista_neu"></div><div style="display: none;" class="alert alert-warning" id="alert_nodata1" role="alert">No hay datos</div></div>
    <div role="tabpanel" class="tab-pane" id="profile"><div id="cont_lista_lub"></div><div style="display: none;" class="alert alert-warning" id="alert_nodata2" role="alert">No hay datos</div></div>
    <div role="tabpanel" class="tab-pane" id="messages"><div id="cont_lista_acu"></div><div style="display: none;" class="alert alert-warning" id="alert_nodata3" role="alert">No hay datos</div></div>
  </div>

</div>



          <div id="cont_lista_asignado"></div>
        </div>
      </div>
  </section>
        
<?
  include_once $_SESSION['base_url'].'app/estado/asignaciones/modales/modal_carga.php';
  include_once $_SESSION['base_url'].'app/estado/asignaciones/modales/mod_preasignacion_uno.php';
  include_once $_SESSION['base_url'].'app/estado/asignaciones/modales/mod_preasignacion_dos.php';
  include_once $_SESSION['base_url'].'partials/footer.php';
?>
<script type="text/javascript">
  var Es = '<?php echo base64_encode($_SESSION['edo']);?>';
  var lista;
  var personas = [];
  var regex = /(\d+)/g;//para algo importante
  var fls;//guardamos la fila a tocar xD
  get_listado_cuotas(1);
  function get_listado_cuotas(ru){
    $("#modal_carga").modal('show');
    $.getJSON('../operaciones.php',{
      e:Es,
      r:btoa(ru),
      action: 'traer_lista_cuotas',
      }, function(data){  
        if(data.msg){
          $("#modal_carga").modal('hide');
          toastr.info(data.msj);
          let tam = data.r.length;  
          let tab='<table class="table table-bordered table-striped mb-none table-condensed content_lista" id="idtabla'+ru+'"  ><thead><tr><th>Tipo</th><th>Producto Ref.</th><th>Producto Asignado</th><th>Marca/Modelo</th><th>Fecha Asig.</th><th>Disponibilidad.</th><th>Estatus</th></tr></thead>';
        for(let x = 0 ; x < tam; x++){
          tab+='<tr id =fls_'+x+'>';
          tab+='<td>'+data.r[x].descripcion+'</td>';
          let acc = 0;
          if(data.r[x].producto_sol==null) {tab+='<td>Libre</td>';acc = 1;}else{tab+='<td>'+data.r[x].producto_sol+'</td>';acc = 2;}
          tab+='<td>'+data.r[x].producto+'</td>';
          tab+='<td>'+data.r[x].marca+' / '+data.r[x].modelo+'</td>';
          tab+='<td>'+data.r[x].fec_reg.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1')+'</td>';
          tab+='<td class="text-danger">'+data.r[x].cantidad_disponible+'</td>';
          tab+='<td><a data-tool="tooltip" title="Preasignar" onclick="paso_accion(\'fls_'+x+'\',\''+data.r[x].descripcion+'\',\''+data.r[x].producto+'\','+data.r[x].id_mov+','+data.r[x].id_rubro+','+data.r[x].cantidad_disponible+','+data.r[x].id_producto+','+data.r[x].id_producto_sol+',\''+data.r[x].producto_sol+'\','+data.r[x].cantidad_solicitudes+')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-check" aria-hidden="true"></i></a>&nbsp;</td>';
          tab+='</tr>';
        }
        tab+='</tbody></table>';
        let tid;
        if (ru == 1) {$("#cont_lista_neu").html(tab);tid = 'idtabla'+ru;$("#alert_nodata1").hide();}
        if (ru == 2) {$("#cont_lista_lub").html(tab);tid = 'idtabla'+ru;$("#alert_nodata2").hide();}
        if (ru == 3) {$("#cont_lista_acu").html(tab);tid = 'idtabla'+ru;$("#alert_nodata3").hide();}
        $(tid).dataTable( { "ordering": false,});
        $("#alert_nodata").hide();
        }else{
          toastr.warning(data.msj);
          $("#modal_carga").modal('hide');
          if (ru == 1) {$("#alert_nodata1").show();}
          if (ru == 2) {$("#alert_nodata2").show();}
          if (ru == 3) {$("#alert_nodata3").show();}
        }
      }).fail(function(data) {
        toastr.error('Error! Problemas con el server.');
        $("#modal_carga").modal('hide');
      });
  }

  function paso_accion(fl,nameRubro,nameProductoAsig,idmov,idrubro,cantidadAsig,productoAsig,productoSol,nameProductoSol,cantidadSol){
    $(".namerubro").html(nameRubro);
    $(".descripcion_asig").html(nameProductoAsig);
    $(".descripcion_asig_can").html(cantidadAsig);
    fls = fl;
    if (productoSol == null) {
      paso_uno(idmov,idrubro,cantidadAsig,productoAsig);
    }else{
      paso_dos(idmov,idrubro,cantidadAsig,productoAsig,productoSol,nameProductoSol,cantidadSol);
    }
  }

  function paso_uno(idmov,idrubro,cantidadAsig,productoAsig){
    $("#modal_carga").modal('show');
    $.getJSON('../operaciones.php',{
      e:Es,
      r:btoa(idrubro),
      action: 'traer_solicitudes_act',
      }, function(data){  
        if(data.msg){
          $("#modal_carga").modal('hide');
          toastr.info(data.msj);
          $("#modal_preasignacion_uno").modal('show');
          let tam = data.r.length;  
          let tab='<table class="table table-bordered table-striped mb-none table-condensed " id="tabla_lista_sol"><thead><tr><th>Producto</th><th>Solicitudes</th><th>Cantidad de productos</th><th>Accion</th></tr></thead>';
        for(let x = 0 ; x < tam; x++){
          tab+='<tr>';
          tab+='<td>'+data.r[x].producto_sol+'</td>';
          tab+='<td>'+data.r[x].cantidad_sol+'</td>';
          tab+='<td>'+data.r[x].cantidad_producto+'</td>';
          tab+='<td><a data-tool="tooltip" title="Preasignar" onclick="paso_dos('+idmov+','+idrubro+','+cantidadAsig+','+productoAsig+','+data.r[x].id_producto_sol+',\''+data.r[x].producto_sol+'\','+data.r[x].cantidad_sol+')" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-check" aria-hidden="true"></i></a>&nbsp;</td>';
          tab+='</tr>';
        }
        tab+='</tbody></table>';
        $("#cont_tabla_sol").html(tab);
        $("#tabla_lista_sol").dataTable( { "ordering": false,});
        //$("#descripcion_asignacion").html(cantidadAsig+' '+nameProductoAsig);
        }else{
          toastr.warning(data.msj);
          $("#modal_carga").modal('hide');
        }
      }).fail(function(data) {
        toastr.error('Error! Problemas con el server.');
        $("#modal_carga").modal('hide');
      });
  }

  function paso_dos(idmov,idrubro,cantidadAsig,productoAsig,productoSol,nameProductoSol,cantidadSol){
    $("#modal_preasignacion_uno").modal('hide');
    $("#modal_carga").modal('show');

    $.getJSON('../operaciones.php',{
        e:Es,
        r:btoa(idrubro),
        i:btoa(productoSol),
        action: 'traer_solicitudes_act_esp',
      }, function(data){  
        if(data.msg){
          //habilitar los campos dependiento del evento
          if (idrubro==1) {$("#act_cincuenta").show();$("#add_cantidad").show();}
          else if(idrubro == 2){$("#act_cincuenta").hide();$("#add_cantidad").hide();}
          else{$("#act_cincuenta").hide();$("#add_cantidad").hide();}


          $("#modal_carga").modal('hide');
          $("#modal_preasignacion_dos").modal('show');
          $(".descripcion_productoref").html(nameProductoSol);
          /*add valores esp*/
          $("#env_tipo_asig").val(1);
          $("#env_idmov").val(idmov);
          $("#env_idrubro").val(idrubro);
          $("#env_cantsol").val(cantidadSol);
          $("#env_cantasig").val(cantidadAsig);
          $("#env_productoAsig").val(productoAsig);
          $("#env_productoSol").val(productoSol);
          $("#contador_solicitud").val(cantidadSol);
          disponibilidad();

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
          $("#env_idmov").val('');
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

  function preasignar(){
    //validamos los parametros

          if (parseInt($("#contador_solicitud").val())!=0) {

            if (parseFloat($("#env_cantasig").val())>=parseFloat($("#contador_solicitud").val())) {
            
            /************GUARDAMOS LAS PERSONAS BENEFICIADAS***********/
              let datospersonas = [];
              let can = personas.length;
              let z = 0;
              //let sum = 0;
              for(let x = 0; x<can;x++){
                if (personas[x][2] == 1) {//validamos que estuviera activo
                  datospersonas[z] =[personas[x][0],personas[x][1]];
                  //sum += parseInt(personas[x][1]);
                  z++;
                }
              }
              /*if(parseInt(sum)==parseInt($("#contador_solicitud").val())){
                toastr.error('Lo sentimos, pero su preasdignacion no esta completa. Porfavor Ajuste la preasignacion.'); 
                return;
              }*/
              $("#modal_carga").modal('show');//mostramos al usuario de un nuevo proceso

              /*************AJAX SAVE***********/
              $.getJSON('../operaciones.php',{
                id_mov:btoa($("#env_idmov").val()),
                cantidad_asignada:btoa($("#contador_solicitud").val()),
                personas:datospersonas,
                id_producto_solicitado:btoa($("#env_productoSol").val()),
                cantidad_solicitada:btoa($("#env_cantsol").val()),
                tipo_asignacion:btoa($("#env_tipo_asig").val()),
                entrega_general:btoa($("#cantidad_entrega").val()),
                action: 'preasignar',
              }, function(data){  
                if(data.msg){
                  $("#modal_carga").modal('hide');
                  toastr.success(data.msj);
                  $("#modal_preasignacion_dos").modal('hide');
                  if (data.st == 2) {get_listado_cuotas(data.r);}else{$("tbody #"+fls).closest('tr').remove();}
                  clean_dos();
                }else{
                  toastr.error(data.msj);
                  $("#modal_carga").modal('hide');
                }
              }).fail(function(data) {
                toastr.error('Error! Problemas con el server.');
                $("#modal_carga").modal('hide');
                $("#modal_preasignacion_dos").modal('hide');
                clean_dos();
              });

            }else{
              toastr.error('Lo sentimos, pero la cantidad preasignada supera la consignada. Porfavor Ajuste la asignacion.');  
            }
          }else{
            toastr.error('Lo sentimos, pero la cantidad de solicitud no puede ser cero (0).');  
          }

      }

</script>