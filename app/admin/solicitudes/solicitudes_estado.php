<?
  if(!isset($_SESSION))
  {
      session_start();
  }
  include_once $_SESSION['base_url'].'partials/header.php';

  $system->sql= "select *,count(*) as solicitudes from solicitudes as a INNER join estados as e on (a.estado=e.id_estado) GROUP BY(a.estado)"; 

?>
  
  <section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
      <div class="panel-actions">
        <a href="#" class="fa fa-caret-down"></a>
        <a href="#" class="fa fa-times"></a>
      </div>
      <h1 class="panel-title">Listados de Solicitudes</h1>
    </header>
      <div class="panel-body">
      	<!-- Table -->
                <table class="table table-bordered table-striped mb-none table-condensed" id="datatable-editable">
                  <thead><tr>
                    <th>Estado</th>
                    <th>cantidad de solicitudes</th>
                    <th>Accion</th>
                  </tr></thead>

                  <tbody id="cont_tabla_entrada">
                    <?php
                      foreach ($system->sql() as $rs) {
                        echo '<tr>
                              <td>'.$rs->estado.'</td>
                              <td>'.$rs->solicitudes.'</td>
                              <td>&nbsp;<a data-tool="tooltip" title="Ver solicitudes del estado '.$rs->estado.'" class="btn btn-default" href="javascript:void(0)" role="button"><i role="button" class="fa fa-check" aria-hidden="true"></i></a>&nbsp;</td>
                              </tr>';
                      }
                    ?>
                  </tbody>
                </table>
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