<div id="modal_confirmar_asignacion" data-backdrop="static" data-keyboard="false" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      
      <div class="modal-header">
        <button onclick="limpiar_two();" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Asignación N° <span id="show_serial" class="text-danger"></span>.</h4>
      </div>
      <div class="modal-body">


        <section class="panel panel-featured panel-featured-success">
          <header class="panel-heading">
            <div class="panel-actions">
              <a href="#" class="fa fa-caret-down"></a>
              <a href="#" class="fa fa-times"></a>
            </div>
            <h1 class="panel-title">
              Informacion
            </h1>
          </header>
          <div class="panel-body">
            <strong>
            Estado:<span id="show_estado" class="text-danger"></span> / Municipio:<span id="show_municipio" class="text-danger"></span><br>
            Tipo de Producto: <br>
            Almacen destino:<br>
            Producto solicitado:<br>
            Cantidad solicitado:<br>
            Producto asignado:<br>
            Cantidad asignado:<br>
            </strong>
          <div>
        </section>

        <section class="panel panel-featured panel-featured-success">
          <header class="panel-heading">
            <div class="panel-actions">
              <a href="#" class="fa fa-caret-down"></a>
              <a href="#" class="fa fa-times"></a>
            </div>
            <h1 class="panel-title">
              Almacen destino
            </h1>
          </header>
          <div class="panel-body">
          <strong><span id="show_destino"></span></strong>
          <div>
        </section>



        <div class="col-xs-12"><div class="loader" style="margin: 0 auto;margin-top: 10px;margin-bottom: 10px;display: none;"></div></div>

        <input type="hidden" class="form-control" id="asignacion_con" name="asignacion_con">
        <input type="hidden" class="form-control" id="precio_asignacion" name="precio_asignacion">

        <!-------agregando la disponibilidad------->
        <!---<h4><span class="badge bg-danger">Estado / Municipio / Parroquia.</span></h4>
          <strong> <span id="show_estado"></span>&nbsp;/&nbsp;<span id="show_municipio"></span>&nbsp;/&nbsp;<span id="show_parroquia">/</span> </strong>
        -->
        <hr>
        <h4><span class="badge bg-danger">Almanece(s) a descontar.</span></h4>
        <div id="show_almacen"></div>
        

        <hr>
        <h4><span class="badge bg-danger">Producto solicitado.</span></h4>

        <hr>
        <h4><span class="badge bg-danger">Producto a entregar.</span></h4>

        <hr>
        <h4><span class="badge bg-danger">Precio / Monto total.</span></h4>
        <strong><span id="show_precio_monto"></span></strong>

        <hr>
        <h4><span class="badge bg-danger">Persona(s) a beneficiar.</span></h4>
        <div id="show_tabla_personas"></div>
  
        
      </div>
      <div class="modal-footer">
        <span id="bta_2"></span>
        <button type="button" class="btn btn-default" onclick="limpiar_two();" id="cancel_two" name="cancel_two" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" onclick="asignar();"><span class="glyphicon glyphicon-forward" aria-hidden="true"></span>Guardar y Seguir</button>
      </div>
    </div>
  </div>
</div>