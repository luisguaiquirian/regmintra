<?
if(!isset($_SESSION))
{
	session_start();
}


?>

    <div id="modal_cuenta" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="background-color: #C92020; color: white;">
                    <h4 class="modal-title">Cambiar Contraseña Predeterminada&nbsp;<i class="fa fa-warning"></i></h4>
                </div>
                <!-- #076DF7 -->
                <form action="" id="form_password_reset">
                    <input type="hidden" name="action" value="change_password">
                    <br>
                    <h4 class="text-center">Es importante que reestablezca su contraseña para la seguridad de la cuenta.</h4>
                    <div class="modal-body">
                      <div class="form-group">
                            <label for="" class="control-label col-md-3">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required="">
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label col-md-3">Repita Contraseña</label>
                            <input type="password" class="form-control" id="password_repeat" required="">
                        </div>
                    </div>
                    <!-- fin modal-body -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Guardar&nbsp;<i class="fa fa-send"></i></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
            <!-- fin modal-content -->
        </div>
        <!-- fin modal-dialog -->
    </div>
    <!-- fin modal -->

    <script>
        let pass = '<?= $_SESSION["pass_activo"] ?>'

        if (pass === '0') {
            $('#modal_cuenta').modal('show')
        }

        $('#form_password_reset').submit(function(e) {
            e.preventDefault()

            let password = $('#password').val(),
                repeat = $('#password_repeat').val()
            if (password !== repeat) {
                toastr.error('Las contraseñas no coinciden', 'Error!')
                return false
            }

            $.ajax({
                    url: '<?= $_SESSION["base_url1"]."app/ltransporte/operaciones.php" ?>',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $(this).serialize(),
                })
                .done(function(data) {
                    if (data.r) {
                        toastr.success('Sus datos han sido actualizados', 'Éxito!')
                        $('#modal_cuenta').modal('hide')
                        location.reload();
                    } else {
                        toastr.error('Ha ocurrido un error al tratar de ejecutar la operación', 'Error!')
                    }
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                });

        });
    </script>