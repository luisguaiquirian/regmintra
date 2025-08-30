<div id="modalDetallesSolicitud" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 80%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header login-header" style="background-color: black; color: white;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Detalles de la Solicitud</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="idtbody"></div>
                </div>
            </div><!-- fin modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- fin modal-content -->
    </div><!-- fin modal-dialog -->
</div> <!-- fin modal -->

<script type="text/javascript">
  function mostrar_Items(solicitud){
    $.getJSON('./operaciones.php',{
      id:solicitud,
      action: 'mostrar_Items'
    }, function(data){
      if(data.r == true) {
        let c = data[0].length;
        let tbody = ' <table class="table table-bordered table-responsive datatable-default"><thead><tr><th class="text-center">Placa</th><th class="text-center">Item</th><th class="text-center">Descripcion</th><th class="text-center">Cantidad</th><th class="text-center">Fecha Aprobado</th><th class="text-center">Fecha Entrega</th><th class="text-center">Estatus</th></tr></thead><tbody class="text-center" >';
        for(t=0;t<c;t++){
          tbody += '<tr><td>'+data[0][t]['placa']+'</td>'
          switch(data[0][t]['id_rubro']) {
          case '1'://neumatico
            tbody+='<td>Neumatico</td><td>'+data[0][t]['neumatico']+'</td>';
          break;
          case '2'://lubricante
            tbody+='<td>Lubricante</td><td>'+data[0][t]['lubricante']+'</td>';
          break;
          case '3'://bateria
            tbody+='<td>Bateria(Acumulador Electrico)</td><td>'+data[0][t]['acumulador']+'</td>';
          break;
          default:
            alertify.alert('Alerta del Sistema', 'Porfavor seleccione un ítem valido.');
          }
          tbody+= '<td>'+data[0][t]['cantidad']+'</td><td>'+data[0][t]['fec_aprobado']+'</td><td>'+data[0][t]['fec_entrega']+'</td><td><span class="badge bg-default">'+data[0][t]['descripcion']+'</span></td></tr>';
        }
        tbody+='</tbody></table>';
        $('#idtbody').html(tbody);
        $("#modalDetallesSolicitud").modal('show');
      }
    })
  }
</script>