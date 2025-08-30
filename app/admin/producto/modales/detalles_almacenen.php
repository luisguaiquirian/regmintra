<?
  if(!isset($_SESSION))
  {
      session_start();
  }
  include_once $_SESSION['base_url'].'partials/header.php';

  if (isset($_GET['key'])) {
    $system->sql="SELECT a.id,a.nombre as almacen,r.descripcion as tipo, s.descripcion as subtipo,b.cantidad,b.disponible,b.comprometido,b.asignado
from almacenes as a
INNER JOIN inventario as b on (a.id=b.almacen)
INNER JOIN productos as c on (b.producto=c.id)
INNER JOIN rubros_sub as s on (c.subtipo=s.id)
inner join rubros as r on (c.tipo=r.id and s.id_rubro=r.id)
WHERE a.id=".trim($_GET['key']);
unset($_GET['key'])
  }
?>
  
  <section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
      <div class="panel-actions">
        <a href="#" class="fa fa-caret-down"></a>
        <a href="#" class="fa fa-times"></a>
      </div>
      <h1 class="panel-title">Almacenes</h1>
    </header>
      <div class="panel-body">
        <div class="col-md-12">
          <!-- Table -->
          <table class="table table-bordered table-striped mb-none table-condensed" id="datatable-editable">
            <thead><tr>
              <th>Almacen</th>
              <th>Tipo</th>
              <th>Subtipo</th>
              <th>Cantidad</th>
              <th>Disponible</th>
              <th>Comprometido</th>
              <th>Asignado</th>
            </tr></thead>
                  <tbody>
                    <?php
                      foreach ($system->sql() as $rs) {
                        echo '<tr>
                              <td>'.$rs->almacen.'</td>
                              <td>'.$rs->tipo.'</td>
                              <td>'.$rs->subtipo.'</td>
                              <td>'.$rs->cantidad.'</td>
                              <td>'.$rs->disponible.'</td>
                              <td>'.$rs->comprometido.'</td>
                              <td>'.$rs->asignado.'</td></tr>';

                      }
                    ?>
                  </tbody>
                </table>
        </div>
      </div>
  </section>
        
<?
  include_once $_SESSION['base_url'].'partials/footer.php';
?>
<script>
 $(document).ready(function(){
    $("#datatable-editable").DataTable();
  });
</script>