<!-- Modal -->
<div class="modal fade" id="id_modal_agregar_unidad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" onclick="cancelar_agregar_unidad()" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Complete los datos para agregar esta unidad a la lista de preasignacion.</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="keydetalleplus" id="keydetalleplus">
        <input type="hidden" name="keyrubroplus" id="keyrubroplus">
        <input type="hidden" name="keyasignacionplus" id="keyasignacionplus">
        <input type="hidden" name="keycantidad" id="keycantidad">

        <div class="form-group">
          <label for="cantidadsumar">Cantidad a entregar</label>
          <input type="text" class="form-control" name="cantidadsumar" id="cantidadsumar" disabled>
        </div>
        <div class="form-group">
          <textarea class="form-control" placeholder="Describa una observacion para esta accion" rows="5" id="observacionagregar" name="observacionagregar"></textarea> 
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" onclick="cancelar_agregar_unidad()"  id="cancel_step_one" name="cancel_step_one" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" onclick="confirmar_agregar_unidad()"  id="cancel_step_one" name="cancel_step_one" data-dismiss="modal">Confirmar</button>
      </div>
    </div>
  </div>
</div>