/******************VARIABLES GLOBALES****************/
  var lista;//beneficiarios inmodificables
  var personas = [];
  //var personas = new Object();//beneficiarios modificables
  var almacenesd;//variable para alojar los almacenes
  var fls;//guardamos la fila a tocar xD
  var totalPro;
  var regex = /(\d+)/g;//para algo importante
  
 /**********************PRIMER PASO*************************/
  function asignar_estadal(solicitud,tipo,cantidad,fl,producto_sol){
    $("#estado_entrega").val(solicitud);//esta solicitud es id del estado
    solicitud = 0;
    step_one(solicitud,tipo,cantidad,fl,producto_sol);
  }

  function asignar_municipal(solicitud,tipo,cantidad,fl,producto_sol){
    $("#municipio_entrega").val(solicitud);//esta solicitud es el id municipio
    solicitud = 0;
    step_one(solicitud,tipo,cantidad,fl,producto_sol);
  }

  function asignar_linea(solicitud,tipo,cantidad,fl,producto_sol){
    $("#linea_entrega").val(solicitud);//estasolicitud es el id linea
    solicitud = 0;
    step_one(solicitud,tipo,cantidad,fl,producto_sol);
  }

   function step_one(solicitud,tipo,cantidad,fl,producto_sol){//item es el id de la solicitud
    $("#modal_carga").modal('show');
    $.getJSON('operaciones.php',{
      tipo:btoa(tipo),
      action: 'step_one'
    }, function(data){  
      if(data.msg){
        fls = fl;
        let tam = data.r.length;  
        let tab='<table class="table table-bordered table-striped mb-none table-condensed" id="tabla_step_one"><thead><tr><th>Codigo</th><th>Producto</th><th>Marca/Modelo</th><th>Cantidad Disponible</th><th>Accion</th></tr></thead>';
        let color = '';
        for(let x = 0 ; x < tam; x++){
          tab+='<tr>';
            tab+='<td>'+data.r[x].codigo+'</td>';
            tab+='<td class="text-primary">'+data.r[x].descripcion+'</td>';
            tab+='<td class="text-info">'+data.r[x].marca+'/'+data.r[x].modelo+'</td>';
            if (cantidad > data.r[x].total_disponible) {color='danger';}else{color='success';}
            tab+='<td class="text-center"><span class="label label-'+color+'">'+data.r[x].total_disponible+'</span></td>';
            tab+='<td class="text-center"><button onclick="step_two_unico('+data.r[x].id_producto+','+data.r[x].id_inventario+','+data.r[x].total_disponible+','+solicitud+','+cantidad+','+tipo+','+producto_sol+')" type="button" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span></button></td><tbody>';
          tab+='</tr>';
        }
        tab+='</tbody></table>';
        $("#modal_carga").modal('hide');//cerrmos el loader
        $("#show_cant_sol").html(cantidad);
        $("#cont_tabla_step_one").html(tab);
        //convertimos la tabla sin orden impuesto
        $('#tabla_step_one').dataTable( { "ordering": false,});
        $('#modal_sel_item').modal('show');
      }else{
        toastr.info(data.msj);
        $("#modal_carga").modal('hide');//cerrmos el loader
        $('#modal_sel_item').modal('hide');
        limpiar_one();
      }
    }).fail(function(data) {
      toastr.error('Error! Problemas con el server.');
      $("#modal_carga").modal('hide');
      $('#modal_sel_item').modal('hide');
      limpiar_one();
    });
  }

  function limpiar_one(){
    $("#cont_tabla_step_one").html('');
  }

  /********************SEGUNDA PARTE*********************/

  function step_two_unico(producto,id_inventario,total_disponible,solicitud,cantidad,tipo,producto_sol){
    $('#modal_sel_item').modal('hide');//cerramos el step_one
    $("#modal_carga").modal('show');//mostramos al usuario de un nuevo proceso

    //habilitar los campos dependiento del evento
    if (tipo==1) {$("#act_cincuenta").show();$("#add_cantidad").show();}
    else if(tipo == 2){$("#act_cincuenta").hide();$("#add_cantidad").hide();}
    else{$("#act_cincuenta").hide();$("#add_cantidad").hide();}

    let estado=0;
    let municipio=0;
    let linea=0;
    if (solicitud==0) {estado = $("#estado_entrega").val(); municipio = $("#municipio_entrega").val(); linea = $("#linea_entrega").val();}

    $.getJSON('operaciones.php',{
      solicitud:btoa(solicitud),
      producto:btoa(producto),
      tipo:btoa(tipo),
      producto_sol:btoa(producto_sol),
      estado:btoa(estado),
      municipio:btoa(municipio),
      linea:btoa(linea),
      action: 'step_two_unico'
    }, function(data){  
      if(data.msg){
        $("#producto").val(producto);
        $("#producto_sol").val(producto_sol);
        $("#cantidad_solicitada").val(cantidad);

        lista = data.r;//agregamos la lista de la personas posibles beneficiarios a una variable global
        
        let c = lista.length;
        for (var i = 0 ; i < c; i++) {
          personas[i] = [lista[i].id_detalle , lista[i].cantidad , lista[i].activo];
        }

        convert_tabla_two(1,lista,personas);//convertimos la tabla el valor 1 indica primer registro sin modificaciones
        
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
        almacenesd = data.rsa;
        let listalmacen = '';
        tam = almacenesd.length;
        for(let x = 0; x <tam; x++){
          listalmacen += '<div class="checkbox list-group-item"><label><input data-disponible="'+almacenesd[x].disponible+'" data-inventario="'+almacenesd[x].id_inventario+'" type="checkbox" value="'+almacenesd[x].id_inventario+'" id="alm'+x+'" name="almacenes[]" onclick="checkalmacen(\'alm'+x+'\');"> <strong>'+almacenesd[x].nombre+'</strong> <span class="text-danger">/</span> <strong>Nivel del Almacen:'+almacenesd[x].niveld+'</strong> <span class="text-danger">/</span> <strong>Estado:'+almacenesd[x].estadod+'</strong>. <span class="badge">'+almacenesd[x].disponible+' disponibles</span></label></div>';
        }
        $("#lista_almacen").html(listalmacen);

        $("#bta_2").html('<button type="button" class="btn btn-default" onclick="atras_step_one('+solicitud+','+tipo+','+cantidad+',\''+fls+'\','+producto_sol+');" id="atras_two" name="atras_two" data-dismiss="modal"><span class="glyphicon glyphicon-chevron-left text-danger" aria-hidden="true"></span>&nbsp;Atras</button>');
        $("#modal_carga").modal('hide');
        $('#modal_sel_almacen').modal('show');
      }else{
        toastr.info(data.msj);
        $("#modal_carga").modal('hide');
        $('#modal_sel_almacen').modal('hide');
      }
    }).fail(function(data) {
      toastr.error('Error! Problemas con el server.');
      $("#modal_carga").modal('hide');
      $('#modal_sel_almacen').modal('hide');
      limpiar_two();
    });
  }

  /*Opciones de limpieza*/
    function limpiar_two(){
      $("#producto").val('');
      $("#producto_sol").val('');
      $("#cantidad_solicitada").val(0);
      $("#lista_almacen").html('');
      $("#contador_disponibilidad").val(0);
      $("#centro").html('');
      $("#contador_solicitud").val(0);
      $("#add_cantidad").hide();
      $("#add_cincuenta").hide();
      $("#id_cantidad_entrega").hide();
      $("#cantidad_entrega").prop( "disabled", false );
      $("#cantidad_entrega").val('');
      $("#suger").html('');
      $("#cont_tabla_step_two").html('');
      $('#modal_sel_almacen').modal('hide');
      limpiar_one();
      //$("input[type=checkbox]").prop('checked', false);
      //reiniciamos el formulario
      $("#formHeaderAsignacion")[0].reset();
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

  //funcion para agregar la disponibilidad del almacen a la suma
  function checkalmacen(idcheck){
    let a = $("#"+idcheck).data('disponible');
    let b = $("#contador_disponibilidad").val();
    let c=0;
    if($("#"+idcheck).is(':checked')) {
      c = parseInt(b)+parseInt(a);
    }else{
      c = parseInt(b)-parseInt(a);
    }
    $("#contador_disponibilidad").val(c);
    disponibilidad(); 
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
        //alert(c)
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

  //funcion para promediar una entrega del 50%
  function cincuenta(){
    convert_tabla_two(2,lista,personas);
    $("#id_cantidad_entrega").hide();
  }

  //converimos las tabla depende de las opciones
  function convert_tabla_two(opc,objeto,personas){
    let tam = objeto.length;//capturamos el tamaño de los objetos
    let sum_cantidad=0;//inicializamos un contador en cero para la cantidad de producto en la solicitud
    //convertimos la tabla
    let tab = '<table class="table table-bordered table-striped mb-none table-condensed tabla_beneficiarios" id="tabla_step_two"><thead><tr><th>Selección</th><th style="display:none">ID_solicitud</th><th style="display:none">ID_user</th><th>Cedula</th><th>Nombres</th><th>Placa</th><th>Estado</th><th>Municipio</th><th>Fec. Solicitud</th><th>Cantidad</th></tr></thead><tbody>';
    switch(opc) {
      case 1://creamos por primera vez la tabla de los beneficiarios
          for(let x = 0 ; x < tam; x++){
          tab+='<tr id="ptr'+x+'">';
          tab+='<td><label><input type="checkbox" onclick="check_persona(\'pcheck'+x+'\',\'ptr'+x+'\');" id="pcheck'+x+'" class="pcheck" name="pcheck'+x+'" checked></label></td>';
          tab+='<td style="display:none">'+objeto[x].id_detalle+'</td>';
          tab+='<td style="display:none">'+objeto[x].id_user+'</td>';
          tab+='<td class="text-primary">'+objeto[x].cedula+'</td>';
          tab+='<td class="text-primary">'+objeto[x].nombre+' '+objeto[x].apellido+'</td>';
          tab+='<td class="text-warning">'+objeto[x].placa+'</td>';
          tab+='<td>'+objeto[x].estado+'</td>';
          tab+='<td>'+objeto[x].municipio+'</td>';
          tab+='<td>'+objeto[x].fec_solicitud+'</td>';
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
            tab+='<td style="display:none">'+objeto[x].id_detalle+'</td>';
            tab+='<td style="display:none">'+objeto[x].id_user+'</td>';
            tab+='<td class="text-primary">'+objeto[x].cedula+'</td>';
            tab+='<td class="text-primary">'+objeto[x].nombre+' '+objeto[x].apellido+'</td>';
            tab+='<td class="text-warning">'+objeto[x].placa+'</td>';
            tab+='<td>'+objeto[x].estado+'</td>';
            tab+='<td>'+objeto[x].municipio+'</td>';
            tab+='<td>'+objeto[x].fec_solicitud+'</td>';
            tab+='<td>'+newcan+'</td>';
            tab+='</tr>';
            personas[x][1] = newcan;
            sum_cantidad+=newcan;
          }
          disponibilidad();
          toastr.info('50% de asignacion activado!');

        }else if($("#tipo_de_asignacion").val()==2 || $("#tipo_de_asignacion").val()==1){//desactivamos el 50% 

          for(x=0;x<tam;x++){
            tab+='<tr id="ptr'+x+'">';
            tab+='<td><label><input type="checkbox" onclick="check_persona(\'pcheck'+x+'\',\'ptr'+x+'\');" id="pcheck'+x+'" class="pcheck" name="pcheck'+x+'" checked></label></td>';
            tab+='<td style="display:none">'+objeto[x].id_detalle+'</td>';
            tab+='<td style="display:none">'+objeto[x].id_user+'</td>';
            tab+='<td class="text-primary">'+objeto[x].cedula+'</td>';
            tab+='<td class="text-primary">'+objeto[x].nombre+' '+objeto[x].apellido+'</td>';
            tab+='<td class="text-warning">'+objeto[x].placa+'</td>';
            tab+='<td>'+objeto[x].estado+'</td>';
            tab+='<td>'+objeto[x].municipio+'</td>';
            tab+='<td>'+objeto[x].fec_solicitud+'</td>';
            tab+='<td>'+objeto[x].cantidad+'</td>';
            tab+='</tr>';
            personas[x][1] = objeto[x].cantidad;
            sum_cantidad+=lista[x].cantidad;
          }
          disponibilidad();
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
    $("#contador_solicitud").val(sum_cantidad);
    $("#cont_tabla_step_two").html(tab);
    $('.tabla_beneficiarios').dataTable( {"ordering": false});
  }

//devuelve la modal step_two a la anterior modal step_one
  function atras_step_one(solicitud,tipo,cantidad,fl,producto_sol){
    let estado=$("#estado_entrega").val();
    let municipio=$("#municipio_entrega").val();
    let linea=$("#linea_entrega").val();
    limpiar_two();
    $("#estado_entrega").val(estado);
    $("#municipio_entrega").val(municipio);
    $("#linea_entrega").val(linea);
    $('#modal_sel_almacen').modal('hide');
    if (solicitud == 0) {step_one(0,tipo,cantidad,fl,producto_sol);}else{step_one(solicitud,tipo,cantidad,fl,producto_sol);}
  }

  //verifica si la disponibilidad es viable
  function disponibilidad(){
    let a = $("#contador_disponibilidad").val();
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
    console.log(personas[tupla][2]);
  }

  /*****************ASIGNACION GUARDANDO LOS DATOS***********************/

    function asignar(){
      //validamos los parametros
      if (parseInt($("#contador_disponibilidad").val())!=0) {

        if ($("#centro").val()!='') {

          if (parseInt($("#contador_solicitud").val())!=0) {

            if (parseFloat($("#contador_disponibilidad").val())>=parseFloat($("#contador_solicitud").val())) {

              $('#modal_sel_almacen').modal('hide');//cerramos el step_one
              $("#modal_carga").modal('show');//mostramos al usuario de un nuevo proceso

              /************CAPTURANDO VALORES DE LOS ALMACENES***********/
              let x = 0;
              let datosalmacenes = [];
              $("ul li input[type=checkbox]:checked").each(function(){
                datosalmacenes.push($("#alm"+x).data('inventario'));
                //alert(datosalmacenes[x])
                x++;
              });

              /************GUARDAMOS LAS PERSONAS BENEFICIADAS***********/
              let datospersonas = [];
              let can = personas.length;
              for(let x = 0; x<can;x++){
                if (personas[x][2].activo = 1) {
                  datospersonas[x] =[personas[x][0],personas[x][1]];
                }
              }

              /*************AJAX SAVE***********/
              $.getJSON('operaciones.php',{
                inventarios:datosalmacenes,
                deposito:$("#centro").val(),
                solicitud:$("#contador_solicitud").val(),
                entregar:$("#cantidad_entrega").val(),
                personas:datospersonas,
                producto:$("#producto").val(),
                producto_solicitado:$("#producto_sol").val(),
                cantidad_solicitada:$("#cantidad_solicitada").val(),
                action: 'asignar',
              }, function(data){  
                if(data.msg){
                  toastr.success(data.msj);
                  $("#modal_carga").modal('hide');
                  $('#modal_sel_precio').modal('show');//cerramos el step_one
                  $("#cantidad_productos").html($("#contador_solicitud").val());
                  $("#cantidad_pro").val($("#contador_solicitud").val());
                  $("#id_asignacion").val(data.asignacion);
                  totalPro = $("#contador_solicitud").val();
                  $("tbody #"+fls).closest('tr').remove();//removemos la fila
                  limpiar_two();
                }else{
                  toastr.error(data.msj);
                  $("#modal_carga").modal('hide');
                }
              }).fail(function(data) {
                toastr.error('Error! Problemas con el server.');
                $("#modal_carga").modal('hide');
                limpiar_two();
              });

            }else{
              toastr.error('Lo sentimos, pero la disponibilidad del producto es menor a la cantidad total solicitada. Porfavor Ajuste la asignacion.');  
            }

          }else{
            toastr.error('Lo sentimos, pero la cantidad de solicitud no puede ser cero (0).');  
          }

        }else{
          toastr.error('Lo sentimos, pero debe seleccionar un almacen de acopio para la entregar las asignaciones.');
        }

      }else{
        toastr.error('Lo sentimos, pero la disponibilidad esta en cero (0). Talvez aun no ha seleccionado uno de los inventarios disponibles.');
      }
      
    }

    function calcular_precio(){
      let precio = $("#precio").val();
    	if (precio=='' && precio==0) {
    	 toastr.warning('No se puede realizar ningun calculo el precio esta vacio o esta en cero (0)');
    	 $("#total").val('');
      }else{
        //alert(Math.sign(precio));
        if (Math.sign(precio)!=-1 && Math.sign(precio)!=-0 && Math.sign(precio)!=NaN && isNaN(precio)!=true) {
          let calculo = parseFloat($("#precio").val())*parseFloat(totalPro);
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
      $("#modal_carga").modal('show');//mostramos al usuario de un nuevo proceso

      $.getJSON('operaciones.php',{
        precio:btoa($("#precio").val()),
        asignacion:btoa($("#id_asignacion").val()),
        total:btoa($("#total").val()),
        m:1,
        action: 'step_three'
      }, function(data){  
        if(data.msg){
          $('#modal_sel_precio').modal('hide');
          toastr.success(data.msj);
          $("#modal_carga").modal('hide');
        }else{
          toastr.info(data.msj);
          $("#modal_carga").modal('hide');
        }
      }).fail(function(data) {
                toastr.error('Error! Problemas con el server.');
                $("#modal_carga").modal('hide');
                limpiar_two();
              });
    }else{
      toastr.warning('El precio esta vacio o su valor es cero (0).');
    }
  }

    function limpiar_three(){
      $("#cantidad_productos").val(0);
      $("#id_asignacion").val('');
      $("#cantidad_pro").val('');
      $("#precio").val(0)
      //$("#beneficiados").val(0);
      $("#total").val('');
      $("#modal_carga").modal('hide');
    }