<div id="modal_paso_dos_directo" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      
      <div class="modal-header">
        <button onclick="clean_dos();"  type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Listado de beneficiados.</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="env_tipo_asig" id="env_tipo_asig">
        <input type="hidden" name="env_detalle" id="env_detalle">
        <input type="hidden" name="env_idrubro" id="env_idrubro">
        <input type="hidden" name="env_cantasig" id="env_cantasig">
        <input type="hidden" name="env_cantsol" id="env_cantsol">
        <input type="hidden" name="env_productoAsig" id="env_productoAsig">
        <input type="hidden" name="env_productoSol" id="env_productoSol">
        <input type="hidden" name="env_inventario" id="env_inventario">
        <input type="hidden" name="env_estado" id="env_estado">


        <!-------agregando la disponibilidad------->

        <strong><h4>Almacen: <span class="descripcion_asig text-danger"></span>.</h4></strong> 
        <strong><h4>Disponibilidad: <span class="descripcion_asig_can text-danger"></span>.</h4></strong>
        <hr>
        <div class="form-group">
          <label for="contador_solicitud"><strong>Cantidad total de productos que seran asignados.</strong></label>
          <input type="number" class="form-control" id="contador_solicitud" name="contador_solicitud" value="0" disabled>
        </div>
        <h4><span class="badge bg-danger">1</span> Selecciona la forma que quieres asignar.</h4>
        <div class="form-group">
          <label for="tipo_de_asignacion"><strong>Seleccione el tipo de asignacion.</strong></label>
            <select name="tipo_de_asignacion" id="tipo_de_asignacion" required="" class="form-control">
              <option onclick="des_add_cantidad();" value="1">Mantener la cantidad original de cada solicitud</option>
              <option style="display: none;" id="add_cantidad" onclick="act_add_cantidad();" value="2">Asignar una cantidad general para todas las solicitudes</option>
              <option style="display: none;" id="act_cincuenta" onclick="cincuenta();" value="3">Selecciona asignar el 50% de las solicitudes.</option>
            </select>
        </div>
        

        <div class="form-group" id="id_cantidad_entrega" style="display: none;">
          <label for="cantidad_entrega"><strong>Ingrese la cantidad general a entregar a cada solicitud</strong></label>
          <input type="text" id="cantidad_entrega" name="cantidad_entrega" onkeyup="calcular_entrega();" class="form-control col-xs-12 col-md-6"  value="">
          <p class="help-block">Puede indicar la cantidad a entregar a cada uno de los solicitantes y si no refleja ninguna cantidad su entrega sera la totalidad de la solicitud de cada uno de los transportistas.</p>
        </div>

        <h4><span class="badge bg-danger">2</span> Selecciona el almacen de acopio para la entrega de las asignaciones.</h4>
        <div class="form-group">
          <select class="form-control" id="centro" name="centro" searchable="Search here.."></select>
        </div>

        <br><h4 id="suger"></h4><br>

        <!-------SELECCIONANDO EL ALMACEN DE ACOPIO------->
        <hr>
        <div id="cont_tabla_lista_personas"></div>
  
        
      </div>
      <div class="modal-footer">
        <span id="bta_2"></span>
        <button  onclick="clean_dos();" type="button" onclick="" class="btn btn-default" id="cancel_two" name="cancel_two" data-dismiss="modal"><span class="glyphicon glyphicon-remove-circle text-danger" aria-hidden="true"></span>&nbsp;Cancelar</button>
        <button type="button" class="btn btn-danger" onclick="asignar();"><span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>&nbsp;Asignar</button>
      </div>
    </div>
  </div>
</div>