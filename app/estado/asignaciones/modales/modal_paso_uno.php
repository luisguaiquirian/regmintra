<!-- Modal -->
<div class="modal fade" id="id_modal_observacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Agregue justificacion sobre la siguiente accion.</h4>
      </div>
      <div class="modal-body">
        <div class="col-xs-12">
          <h4><span class="label label-danger">Productos solicitados: <span id ="show_cant_sol"></span> en total. </span></h4> 
        </div>
        <hr>
          <div id="cont_tabla_rubros"></div>
          <p class="text-center"><span class="text-danger"> Rojo: No abarcaria la solicitud total.</span><br><span class="text-success">Verde: Abarca en totalidad la solicitud.</span></p>
          <hr>
          <h5 class="text-primary text-justify">La disponibilidad que se muestra a continuación es resulatado de la disponibilidad del rubro seleccionado en los almacenes de nivel nacional.</h5> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick=""  id="cancel_step_one" name="cancel_step_one" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>