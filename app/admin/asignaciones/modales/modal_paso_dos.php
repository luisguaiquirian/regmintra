<div id="id_modal_paso_dos" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      
      <div class="modal-header">
        <button  type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Listado de beneficiados.</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="env_cantidad_sol" id="env_cantidad_sol">
        <input type="hidden" name="env_tipo" id="env_tipo">
        <input type="hidden" name="env_id_producto_asi" id="env_id_producto_asi">
        <input type="hidden" name="env_estado" id="env_estado">
        <input type="hidden" name="env_id_producto_sol" id="env_id_producto_sol">
        <span class="badge bg-danger"><h5>Items : <span id="show_cantidad"></span> solicitados</h5></span>
        <!-------agregando la disponibilidad------->
        <h4><span class="badge bg-danger">1</span> Agrega los almacenes de los cuales dispondras para la asignación.</h4>

        <!--lista de almacenes-->
        <p class="help-block"><strong>Seleccione un o varios almacenes con disponibilidad para la asignacion:</strong> </p>
        <ul class="list-group"><li class="list-group-item" id="lista_almacen"></li></ul>

        <div class="form-group">
          <label for="contador_disponibilidad"><strong>cantidad total para asignar</strong></label>
          <input type="number" class="form-control" id="contador_disponibilidad" name="contador_disponibilidad" value="0" disabled>
        </div>

        <!-------SELECCIONANDO EL ALMACEN DE ACOPIO------->
        <hr>
        <h4><span class="badge bg-danger">2</span> Selecciona el almacen de acopio para la entrega de las asignaciones.</h4>
        <div class="form-group">
          <select class="form-control" id="centro" name="centro" searchable="Search here.."></select>
        </div>
  
        
      </div>
      <div class="modal-footer">
        <span id="bta_2"></span>
        <button type="button" onclick="limpiar_modal_dos();" class="btn btn-default" id="cancel_two" name="cancel_two" data-dismiss="modal"><span class="glyphicon glyphicon-remove-circle text-danger" aria-hidden="true"></span>&nbsp;Cancelar</button>
        <button type="button" class="btn btn-danger" onclick="asignar_items_estado();"><span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>&nbsp;Consignar</button>
      </div>
    </div>
  </div>
</div>