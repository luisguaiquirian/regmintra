<?
if(!isset($_SESSION))
{
	session_start();
}

?>

<div id="modal_rechazar" class="modal fade" role="dialog">
    <div class="modal-dialog">
<!-- Modal content-->
            <div class="modal-content">
                  <div class="modal-header" style="background-color: #C92020; color: white;">
                    <h4 class="modal-title">Observación&nbsp;<i class="fa fa-warning"></i></h4>
                    </div>
                    <form action="<?= $_SESSION["base_url1"]."app/municipio/operaciones.php" ?>" id="form_rechazo">
                    <br>
                    <h4 class="text-center">Describa brevemente el motivo de rechazo de la unidad de transporte.</h4>
                    <div class="modal-body">
                      <div class="form-group">
                            <label for="" class="control-label col-md-3">Descripción</label>
                          <textarea type="text" class="form-control" id="descripcion" name="descripcion" onKeyUp="this.value=this.value.toUpperCase();" required></textarea>
                            <input type="hidden" name="id_rechazado" id="id_rechazado">
                            <input type="hidden" name="id_linea" id="id_linea" value="<?= $_GET['id'] ?>">
				            <input type="hidden" name="action" value="rechazar">

                        </div>
                    <!-- fin modal-body -->
                    <div class="modal-footer">
                        <button type="submit" id="save" onclick="validar();" class="btn btn-success">Guardar&nbsp;<i class="fa fa-send"></i></button>
				        <button type="button" data-dismiss="modal" class="btn btn-default modal-dismiss">Cancel</button>
                    </div>
                </form>
            </div>
            <!-- fin modal-content -->
<script>                    

function validar() {
  if ($('#descripcion').val().length == 0) {
        alert('Ingrese el motivo de rechazo de la unidad de transporte');
        toastr.error('Rellene todos los campos','Error!')

      return false;
  }
}

</script>