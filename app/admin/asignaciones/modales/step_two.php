<div id="modal_sel_almacen" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      
      <div class="modal-header">
        <button onclick="limpiar_two();" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Listado de beneficiados.</h4>
      </div>
      <div class="modal-body">

        <input type="hidden" class="form-control" id="producto" name="producto" placeholder="producto">
        <input type="hidden" class="form-control" id="producto_sol" name="producto_sol" placeholder="producto_solicitado">
        <input type="hidden" class="form-control" id="cantidad_solicitada" name="cantidad_solicitada" placeholder="cantidad_solicitado" value="0">

        <!-------agregando la disponibilidad------->
        <h4><span class="badge bg-danger">1</span> Agrega los almacenes de los cuales dispondras para la asignación.</h4>

        <!--lista de almacenes-->
        <p class="help-block"><strong>Seleccione un o varios almacenes con disponibilidad para la asignacion:</strong> </p>
        <ul class="list-group"><li class="list-group-item" id="lista_almacen"></li></ul>

        <div class="form-group">
          <label for="contador_disponibilidad"><strong>Disponible</strong></label>
          <input type="number" class="form-control" id="contador_disponibilidad" name="contador_disponibilidad" value="0" disabled>
        </div>

        <!-------SELECCIONANDO EL ALMACEN DE ACOPIO------->
        <hr>
        <h4><span class="badge bg-danger">2</span> Selecciona el almacen de acopio para la entrega de las asignaciones.</h4>
        <div class="form-group">
          <select class="form-control" id="centro" name="centro" searchable="Search here.."></select>
        </div>

        <!-------agregando la disponibilidad------->
        <hr>
        <h4><span class="badge bg-danger">3</span> Dispon de la cantidad a entregar.</h4>
        <div class="form-group">
          <label for="contador_solicitud"><strong>Cantidad total de productos que seran asignados.</strong></label>
          <input type="number" class="form-control" id="contador_solicitud" name="contador_solicitud" value="0" disabled>
        </div>

        <div class="form-group">
          <label for="contador_solicitud"><strong>Seleccione el tipo de asignacion.</strong></label>
            <select name="tipo_de_asignacion" id="tipo_de_asignacion" required="" class="form-control">
              <option onclick="des_add_cantidad();" value="1">Mantener la cantidad original de cada solicitud</option>
              <option style="display: none;" id="add_cantidad" onclick="act_add_cantidad();" value="2">Asignar una cantidad general para todas las solicitudes</option>
              <option style="display: none;" id="act_cincuenta" onclick="cincuenta();" value="3">Selecciona asignar el 50% de las solicitudes.</option>
            </select>
        </div>
        

        <div class="form-group" id="id_cantidad_entrega" style="display: none;">
          <label for="cantidad_entrega"><strong>Cantidad general a entregar</strong></label>
          <input type="text" id="cantidad_entrega" name="cantidad_entrega" onkeyup="calcular_entrega();" class="form-control col-xs-12 col-md-6"  value="0">
          <p class="help-block">Puede indicar la cantidad a entregar a cada uno de los solicitantes y si no refleja ninguna cantidad su entrega sera la totalidad de la solicitud de cada uno de los transportistas.</p>
        </div>

        <!--<div class="checkbox" id="id_cincuenta" style="display: none;">
          <label>
            <input type="checkbox" value="" id="cincuenta" name="cincuenta" onclick="cincuenta();">
            Calcular 50% de las solicitudes para la entrega. Esta opción calculara una entrega justa del 50% aproximadamente de cada una de las solicitudes.
          </label>
        </div>-->

        <br><h4 id="suger"></h4><br>
        
        <hr>
          <div id="cont_tabla_step_two"></div>
  
        
      </div>
      <div class="modal-footer">
        <span id="bta_2"></span>
        <button type="button" class="btn btn-default" onclick="limpiar_two();" id="cancel_two" name="cancel_two" data-dismiss="modal"><span class="glyphicon glyphicon-remove-circle text-danger" aria-hidden="true"></span>&nbsp;Cancelar</button>
        <button type="button" class="btn btn-danger" onclick="asignar();"><span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>&nbsp;Guardar y Seguir</button>
      </div>
    </div>
  </div>
</div>