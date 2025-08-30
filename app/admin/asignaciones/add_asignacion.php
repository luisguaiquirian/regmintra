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
    $system->table = "asignacion";

    $register = $system->find(base64_decode($_GET['modificar']));
    
  }


?>
  
  <section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
      <div class="panel-actions">
        <a href="#" class="fa fa-caret-down"></a>
        <a href="#" class="fa fa-times"></a>
      </div>
      <h1 class="panel-title">Formulario de Asignaciones</h1>
    </header>
      <div class="panel-body">
        <div class="col-md-12">
          
            <form action="./operaciones.php" class="form-horizontal" id="form_registrar" method="POST">
              <input type="hidden" name="id_modificar" value="<?= $register ? $register->id : '' ?>">
              <input type="hidden" name="type" value="1">
              <input type="hidden" id="action" name="action" value="<?= $register ? 'modificar_asignacion' : 'registrar_asignacion' ?>">
              <div class="row no-gutters">
                <div class="form-group">
                  <div class="col-md-6 col-sm-6">
                    <label for="" class="control-label">Descripción*</label>
                    <textarea class="form-control" required="" name="descripcion" placeholder="Descripción de la asignación"><?= $register ? $register->descripcion : null ?></textarea>
                  </div>
                  <div class="col-md-6 col-sm-6">
                    <label for="" class="control-label">Estado*</label>
                    <select name="id_estado" id="id_estado" required="" class="form-control">
                      <option value="">Seleccione...</option>
                      <?
                        $system->table = "estados";
                        foreach ($system->get() as $row) {
                          $selected = $register && $register->id_estado === $row->id ? 'selected' : '';
                          echo "<option value='{$row->id}' {$selected}>{$row->estado}</option>";
                        }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div><br/><br/>
              <div class="row no-gutters">
                <div class="form-group">
                  <div class="col-md-4 col-sm-4 col-sm-offset-2 col-md-offset-2">
                    <button type="submit" class="btn btn-danger btn-block" id="btn_guardar">Enviar&nbsp;<i class="fa fa-send"></i></button>
                  </div>
                  <div class="col-md-4 col-sm-4">
                    <a href="./asignaciones.php" class="btn btn-info btn-block">Volver&nbsp;<i class="fa fa-reload"></i></a>
                  </div>
                </div>
              </div>
            </form>
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

        if(action === "modificar_asignacion")
        {
          window.location.replace('./asignaciones.php')
        }
        else
        {
          $('#form_registrar')[0].reset()
          toastr.success('Asinación agregada con éxito!', 'Éxito!')
          btn.html(text_replace).prop('disabled',false)
          if(confirm("Desea agregarle items a esta asignación en este momento?")){
            window.location.replace('./add_detalle_asignacion.php?register='+btoa(data.id)) 
          }
        }
                  
      }
      else
      {
        toastr.error('Ha ocurrido un error al ejecutar la operación', 'Error!')
      }
    })
    
  });
</script>