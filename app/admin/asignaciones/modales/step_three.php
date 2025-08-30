<!-- Modal -->
<div data-backdrop="static" data-keyboard="false" class="modal fade" id="modal_sel_precio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="limpiar_three();"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Asignación de precio.</h4>
      </div>
      <div class="modal-body">
        
        <h4><span class="badge bg-danger">4</span> Coloca el precio de cada uno de los productos para esta asignación.</h4>

        <h4><span class="label label-info"><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span><span id="cantidad_productos"></span> productos asignados.</span></h4>

        <input type="hidden" class="form-control" id="id_asignacion" name="id_asignacion" disabled>
        <input type="hidden" class="form-control" id="cantidad_pro" name="cantidad_pro" disabled>

        <div class="form-group">
          <label for="precio"><strong>Precio</strong></label>
          <input type="text" class="form-control" id="precio" name="precio" value="" placeholder="Ingresa el precio del producto" onkeyup="calcular_precio();" >
          <p class="help-block">Utilice punto (.) para identificar los decimales.</p>
        </div>

        <div class="form-group">
          <label for="total"><strong>Total a cobrar por esta asignacion</strong></label>
          <input type="text" class="form-control" id="total" name="total" value="" placeholder="(beneficiados * precio)" disabled>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="cancel_step_three" name="cancel_step_three" data-dismiss="modal" onclick="limpiar_three();">Cancelar</button>
        <button type="button" class="btn btn-danger" onclick="asignar_precio();"><span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span> Guardar Precio</button>
      </div>
    </div>
  </div>
</div>