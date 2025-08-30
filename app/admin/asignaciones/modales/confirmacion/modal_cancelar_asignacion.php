<!-- Modal -->
<div class="modal fade" id="id_modal_observacion_cancelar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" onclick="cancelar_c_preasignacion()" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Cancelar Asignacion.</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-info" role="alert">Al cancelar la preasignacion estara en un estado de "Rechazado", el estado debere realizar las respectivas modificaciones a la preasignacion.</div>
        <input type="hidden" name="keyasignacioncancelar" id="keyasignacioncancelar">
        <textarea class="form-control" placeholder="Describa la observacion" rows="5" id="observacioncancelar" name="observacioncancelar"></textarea> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" onclick="cancelar_c_preasignacion()"  id="cancel_step_one" name="cancel_step_one" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" onclick="confirmar_cancelar_preasignacion()"  id="cancel_step_one" name="cancel_step_one" data-dismiss="modal">Confirmar</button>
      </div>
    </div>
  </div>
</div>