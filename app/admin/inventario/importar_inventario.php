<?
  if(!isset($_SESSION))
    {
      session_start();
    }

  include_once $_SESSION['base_url'].'partials/header.php';
?>
  
  
    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Seleccionar Archivo</h3>
          </div>
          <div class="panel-body">
            <form action="../operaciones.php" enctype="multipart/form-data" method="POST">
              <input type="hidden" name="action" value="upload_inventario">

              <div class="form-group col-md-12 col-sm-12">
                <input type="file" accept=".xls,.xlsx,.ops" multiple="false" name="excel_upload" required="">
              </div>
              <div class="form-group">
                <div class="col-md-4 col-sm-4 col-md-offset-4 col-sm-offset-4">
                  <button type="submit" class="btn btn-success btn-block">Enviar&nbsp;<i class="fa fa-send"></i></button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <section class="panel">
          <header class="panel-heading bg-primary">
            <div class="panel-heading-icon">
              <i class="fa fa-pencil"></i>
            </div>
          </header>
          <div class="panel-body text-center">
            <h3 class="text-semibold mt-sm text-center">Instrucciones</h3>
            <p class="text-center">
              <h5>Descargue y rellene todos los campos.</h5>
              <h5>Luego seleccione el archivo y haga click en enviar.</h5>
              <h5>Todos los campos son requeridos.</h5>
              <h5>Descargue el archivo <a href="<?= $_SESSION['base_url1'].'assets/docs/excel_upload.xlsx' ?>">Aquí</a></h5> 
            </p>
          </div>
        </section>
      </div>
    </div>

<?
  include_once $_SESSION['base_url'].'partials/footer.php';
?>