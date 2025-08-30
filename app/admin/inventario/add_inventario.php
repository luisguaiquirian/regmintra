<?
  if(!isset($_SESSION))
    {
      session_start();
    }
  include_once $_SESSION['base_url'].'partials/header.php';

  $register = null;

  if(isset($_GET['modificar']))
  {
    // si existe el where de modificar buscamos el registrp
    $system->table = "inventario";

    $register = $system->find(base64_decode($_GET['modificar']));
    
  }

?>
  
  <section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
      <div class="panel-actions">
        <a href="#" class="fa fa-caret-down"></a>
        <a href="#" class="fa fa-times"></a>
      </div>
      <h1 class="panel-title">Formulario del Inventario</h1>
    </header>
      <div class="panel-body">
        <div class="col-md-12">
          <div class="panel-body">
            <form action="./operaciones.php" class="form-horizontal" id="form_registrar" method="POST">
              <input type="hidden" name="id_modificar" value="<?= $register ? $register->id : '' ?>">
              <input type="hidden" id="action" name="action" value="<?= $register ? 'modificar_inventario' : 'registrar_inventario' ?>">
              <div class="row no-gutters">
                <div class="form-group">
                  <div class="col-md-6 col-sm-6">
                    <label for="" class="control-label">Item*</label>
                    <input type="text" class="form-control" required="" name="item" value="<?= $register ? $register->item : '' ?>" placeholder="Nombre del Item del Inventario">
                  </div>
                  <div class="col-md-6 col-sm-6">
                    <label for="" class="control-label">Cantidad*</label>
                    <input type="number" class="form-control" required="" min="0" name="cantidad" value="<?= $register ? $register->cantidad : '' ?>">
                  </div>
                </div>
              </div>
              <div class="row no-gutters">
                <div class="form-group col-md-6 col-sm-6">
                    <label for="" class="control-label">Activo*</label>
                    <select name="activo" id="activo" class="form-control" required="">
                      <option value="">Seleccione...</option>
                      <option value="activado_validador" <?= $register && $register->activo === "activado_validador" ? 'selected' : '' ?>>Si</option>
                      <option value="desactivado_validador" <?= $register && $register->activo === "desactivado_validador" ? 'selected' : '' ?>>No</option>
                    </select>
                </div>
              </div>
              <div class="clearfix"></div><br/><br/>
              <div class="row no-gutters">
                <div class="form-group">
                  <div class="col-md-4 col-sm-4 col-sm-offset-2 col-md-offset-2">
                    <button type="submit" class="btn btn-danger btn-block" id="btn_guardar">Enviar&nbsp;<i class="fa fa-send"></i></button>
                  </div>
                  <div class="col-md-4 col-sm-4">
                    <a href="./ver_inventario.php" class="btn btn-info btn-block">Volver&nbsp;<i class="fa fa-reload"></i></a>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
  </section>
        
<?
  include_once $_SESSION['base_url'].'partials/footer.php';
?>
<script>

  $('#form_registrar').submit(function(e) {
    e.preventDefault()

    var btn = $('#btn_guardar'),
        text_replace = 'Enviar&nbsp;<i class="fa fa-send"></i>';


    btn.text('Procesando....').prop('disabled',true)

    $.ajax({
      url: '../operaciones.php',
      type: 'POST',
      dataType: 'JSON',
      data: $(this).serialize(),
    })
    .done(function(data) {
      if(data.r)
      {
        let action = $('#action').val()

        if(action === "modificar_inventario")
        {
          window.location.replace('./ver_inventario.php')
        }
        else
        {
          $('#form_registrar')[0].reset()
          toastr.success('Item agregado con éxito!', 'Éxito!')
          btn.html(text_replace).prop('disabled',false)
        }
                  
      }
      else
      {
        toastr.error('Ha ocurrido un error al ejecutar la operación', 'Error!')
      }
    })
    
  });
</script>