<!-- Modal -->
<div class="modal fade" id="id_modal_cambio_precio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" onclick="cancelar_cambio_precio()" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Asignar precio unitario al producto.</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-info" role="alert">El precio unitario que asignara para este producto solo tendra efecto para esta asignacion.</div>
        <input type="hidden" name="keyasigpro" id="keyasigpro">
        <div class="form-group">
          <label for="precionew">Nuevo Precio</label>
          <input type="text" class="form-control" onkeyup="validar_precio();" name="precionew" id="precionew">
           <p class="help-block">Utilice punto (.) para identificar los decimales.</p>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" onclick="cancelar_cambio_precio()"  id="cancel_step_one" name="cancel_step_one" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" onclick="confirmar_cambio_precio()"  id="cancel_step_one" name="cancel_step_one" data-dismiss="modal">Guardar</button>
      </div>
    </div>
  </div>
</div>