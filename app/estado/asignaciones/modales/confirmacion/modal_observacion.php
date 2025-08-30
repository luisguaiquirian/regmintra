<!-- Modal -->
<div class="modal fade" id="id_modal_observacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" onclick="cancelar_eliminacion_unidad()" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Describa con una observacion la siguiente accion.</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="keydetalle" id="keydetalle">
        <input type="hidden" name="keyasignacion" id="keyasignacion">
        <textarea class="form-control" placeholder="Describa la observacion" rows="5" id="observacion" name="observacion"></textarea> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" onclick="cancelar_eliminacion_unidad()"  id="cancel_step_one" name="cancel_step_one" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" onclick="confirmar_eliminacion_unidad()"  id="cancel_step_one" name="cancel_step_one" data-dismiss="modal">Confirmar</button>
      </div>
    </div>
  </div>
</div>