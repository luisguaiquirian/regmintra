<?
  if(!isset($_SESSION))
    {
      session_start();
    }
  include_once $_SESSION['base_url'].'partials/header.php';

  $register = null;
  $asignacion = isset($_GET['register']) ? base64_decode($_GET['register']) : null;

  if(isset($_GET['modificar']))
  {
    // si existe el where de modificar buscamos el registrp
    $system->table = "asignacion_detalle";

    $register = $system->find(base64_decode($_GET['modificar']));

    $asignacion = $asignacion ? $asignacion : $register->id_asignacion;
    
  }

?>

  <section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
      <div class="panel-actions">
        <a href="#" class="fa fa-caret-down"></a>
        <a href="#" class="fa fa-times"></a>
      </div>
      <h1 class="panel-title">Formulario de Detalle Asignaciones</h1>
    </header>
      <div class="panel-body">
        <div class="col-md-12">
          <div class="panel-body">
            <form action="./operaciones.php" id="form_registrar" method="POST">
              <input type="hidden" name="id_modificar" value="<?= $register ? $register->id : '' ?>">
              <input type="hidden" name="id_asignacion" value="<?= $register ? $register->id_asignacion : $asignacion ?>">
              <input type="hidden" id="action" name="action" value="<?= $register ? 'modificar_detalle_asignacion' : 'registrar_detalle_asignacion' ?>">

              <div class="row">
                <div class="form-group col-md-6 col-sm-6">
                  <label for="" class="control-label">Item del Inventario</label>    
                  <select name="id_inventario" id="id_inventario" class="form-control" required="">
                    <option value="">Seleccione...</option>
                    <?
                      $system->sql = "SELECT id,item,cantidad from inventario where activo like 'activado_validador'";
                      foreach ($system->sql() as $row) {
                        $selected = $register && $register->id_inventario === $row->id ? 'selected' : '';
                        echo "<option value='{$row->id}' data-cantidad='{$row->cantidad}' {$selected}>$row->item</option>";
                      }
                    ?>
                  </select>
                </div>
                <div class="form-group col-md-6 col-sm-6">
                  <label for="" class="control-label">Cantidad</label>    
                  <input type="number" class="form-control" required="" min="0" name="cantidad" id="cantidad" value="<?= $register ? $register->cantidad : '' ?>" onkeyup="validar_cantidad(this)"
                  <?= $register ? '' : 'readonly=""' ?>>
                </div>
              </div>
              <div class="clearfix"></div><br/><br/>
              <div class="row no-gutters">
                <div class="form-group">
                  <div class="col-md-4 col-sm-4 col-sm-offset-2 col-md-offset-2">
                    <button type="submit" class="btn btn-danger btn-block" id="btn_guardar">Enviar&nbsp;<i class="fa fa-send"></i></button>
                  </div>
                  <div class="col-md-4 col-sm-4">
                    <a href="./detalle_asignaciones.php?register=<?=base64_encode($asignacion) ?>" class="btn btn-info btn-block">Volver&nbsp;<i class="fa fa-reload"></i></a>
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

  function validar_cantidad(e){
    var inv = $('#id_inventario').val(),
        cantidad_inv = $('#id_inventario').children('option[value="'+inv+'"]').data('cantidad')

    if(e.value > cantidad_inv){
      toastr.warning('No hay esa cantidad del item en el inventario. <br/> Cantidad Disponible: '+cantidad_inv,'Aviso!')
      e.value = '';
    }
  }

  $('#id_inventario').change(function(e) {
    var val = e.target.value

    if(val !== ""){
      $('#cantidad').prop('readonly',false)
    }else{
      $('#cantidad').prop('readonly',true).val('')

    }
  });

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
      if(data.r === true)
      {
        let action = $('#action').val()

        if(action === "modificar_detalle_asignacion")
        {
          window.location.replace('./detalle_asignaciones.php')
        }
        else
        {
          $('#form_registrar')[0].reset()
          
          var option = "<option>Seleccione...</option>";

          $.grep(data.inventario,function(i,e){
            option+= "<option value='"+i.id+"' data-cantidad='"+i.cantidad+"'>"+i.item+"</option>";
          })
          $('#id_inventario').html(option)
          toastr.success('Item agregado con éxito!', 'Éxito!')
          btn.html(text_replace).prop('disabled',false)
        }
                  
      }
      else
      {
        if(data.r === 3){
          toastr.error('Ya existe este item en esta asignación', 'Error!')
        }else{
          toastr.error('Ha ocurrido un error al ejecutar la operación', 'Error!')
        }
        $('#form_registrar')[0].reset()
        btn.html(text_replace).prop('disabled',false)
      }
    })
    
  });
</script>