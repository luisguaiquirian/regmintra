var almacenesd ;
 function modal_paso_uno(cantidad,tipo,estado,id_producto_sol){
    $("#modal_carga").modal('show');
    $.getJSON('operaciones2.php',{
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

     $.getJSON('operaciones2.php',{
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
    $('input:checkbox').removeAttr('checked');
    $("#"+idcheck).prop("checked", true);
    let a = $("#"+idcheck).data('disponible');
    //let b = $("#contador_disponibilidad").val();
    let c=0;
    if($("#"+idcheck).is(':checked')) {
      let cant = prompt("Ingrese la cantidad a descontar");
      if(cant > parseInt($("#env_cantidad_sol").val())){toastr.warning('La cantidad ingresada es mayor a la cantidad solicitada.');$("#"+idcheck).prop("checked", false);$("#contador_disponibilidad").val('');return;}
      if(cant == null || cant == false){$("#"+idcheck).prop("checked", false);$("#contador_disponibilidad").val('');return;}
      if (!/^([0-9])*$/.test(cant)){toastr.warning('El valor ingresado parace ser diferente al tipo permitido. Porfavor ingrese un valor numerico.');$("#"+idcheck).prop("checked", false);$("#contador_disponibilidad").val('');return;}
      if (cant > a) {toastr.warning('cantidad a descontar es mayor a lo disponible.');$("#"+idcheck).prop("checked", false);$("#contador_disponibilidad").val('');return;}
      if(cant == 0 || cant == ''){toastr.warning('La cantidad a descontar que ingreso es cero(0) o esta vacia, verifique sus datos ingresados.');$("#"+idcheck).prop("checked", false);$("#contador_disponibilidad").val('');return;}
      $("#"+idcheck).val(cant);
      //c = parseInt(b)+parseInt(cant);
      c = parseInt(cant);
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
    if($("#contador_disponibilidad").val() > $("#env_cantidad_sol").val()){toastr.warning('La cantidad asignada es mayor a la cantidad solicitada.');$("#modal_carga").modal('hide');return;}
   
    let x = 0;
    let datosalmacenes = [];
    $("ul li input[type=checkbox]:checked").each(function(){
      datosalmacenes[x] = [$("#alm"+x).data('inventario'),$("#alm"+x).val()];
      x++;
    });

     $.getJSON('operaciones2.php',{
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
          toastr.success(data.msj);
        }else{
          toastr.warning(data.msj);
          $("#modal_carga").modal('hide');
          $('#id_modal_paso_dos').modal('hide');
          limpiar_modal_dos();
        }
      }).fail(function(data) {
        toastr.error('Error! Problemas con el server.');
        $("#modal_carga").modal('hide');
      });
  }

  function mostrar_historial_consignaciones(estado,rubro,producto){
    $("modal_carga").modal("show");
    $.getJSON('operaciones2.php',{
      e:btoa(estado),
      r:btoa(rubro),
      p:btoa(producto),
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