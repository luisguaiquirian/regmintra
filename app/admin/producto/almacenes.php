<?
  if(!isset($_SESSION))
  {
      session_start();
  }
  include_once $_SESSION['base_url'].'partials/header.php';

  if (isset($_GET['l'])) {
    switch (base64_decode(trim($_GET['l']))) {
      
      case 'all':

        $system->sql="SELECT a.*,b.id as id_nivel,b.descripcion as niveld,e.estado,m.municipio,p.parroquia 
          from almacenes as a
          inner join almacenes_nivel as b on (a.nivel=b.id)
          INNER JOIN estados as e on (a.estado=e.id_estado)
          INNER join municipios as m on (a.estado=m.id_estado and a.municipio=m.id_municipio)
          INNER JOIN parroquias as p on (a.estado=p.id_estado and a.municipio=p.id_municipio and a.parroquia=p.id_parroquia)
          where a.estatus=7";
      
      break;

      case 'key':
        
        $system->sql="SELECT a.*,b.id as id_nivel,b.descripcion as niveld,e.estado,m.municipio,p.parroquia 
          from almacenes as a
          inner join almacenes_nivel as b on (a.nivel=b.id)
          INNER JOIN estados as e on (a.estado=e.id_estado)
          INNER join municipios as m on (a.estado=m.id_estado and a.municipio=m.id_municipio)
          INNER JOIN parroquias as p on (a.estado=p.id_estado and a.municipio=p.id_municipio and a.parroquia=p.id_parroquia)
          where a.estatus=7 and a.nivel=".base64_decode(trim($_GET['key']));

      break;      
      
      default:
        echo 'Algo esta mal, opcion incorrecta';
      break;
    
    }
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

        <?php 
          if (isset($_GET['n'])) {?>
            
            <div class="col-xs-12">
              <div class="btn-group btn-group-sm" role="group" aria-label="...">

                <a class="btn btn-default btn-sm btn-primary" href="<?= $_SESSION['base_url1'].'app/admin/producto/almacenes.php?l='.base64_encode('all');?>" role="button"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Lisar todos.</a>

                <a class="btn btn-default btn-sm btn-primary" href="javascript:void(0)" role="button" disabled="disabled"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>Listado de almaneces a nivel <?= base64_decode($_GET['n']);?>.</a>

              </div>
              <hr>
            </div>

          <? }

        ?>

        <div class="col-md-12">
          <!-- Table -->
          <table class="table table-bordered table-striped mb-none table-condensed" id="datatable-editable">
            <thead><tr>
              <th>Codigo</th>
              <th>Nombre</th>
              <th>Estado</th>
              <th>Municipio</th>
              <th>Parroquia</th>
              <th>Nivel</th>
              <th>Direccion</th>
              <th>Telefono/Tel. Contacto</th>
              <th>Acción</th>
            </tr></thead>
                  <tbody>
                    <?php
                      foreach ($system->sql() as $rs) {
                        echo '<tr>
                              <td>'.$rs->codigo.'</td>
                              <td>'.$rs->nombre.'</td>
                              <td>'.$rs->estado.'</td>
                              <td>'.$rs->municipio.'</td>
                              <td>'.$rs->parroquia.'</td>
                              <td><a data-tool="tooltip" title="Ver lista de almacenes a nivel '.$rs->niveld.'" href="'.$_SESSION['base_url1'].'app/admin/producto/almacenes.php?l='.base64_encode('key').'&key='.base64_encode($rs->nivel).'&n='.base64_encode($rs->niveld).'" >'.$rs->niveld.'</a></td>
                              <td>'.$rs->direccion.'</td>
                              <td>'.$rs->telefono.'/'.$rs->tel_contac.'</td>
                              <td>&nbsp;<a data-tool="tooltip" title="Ver productos detallados de '.$rs->nombre.'" class="btn btn-default" href="'.$_SESSION['base_url1'].'app/admin/producto/detalle_almacen.php?key='.base64_encode($rs->id).'&nom='.base64_encode($rs->nombre).'&n='.base64_encode($rs->niveld).'&e='.base64_encode($rs->estado).'&m='.base64_encode($rs->municipio).'&d='.base64_encode($rs->direccion).'&t='.base64_encode($rs->telefono.'/'.$rs->tel_contac).'" role="button"><i role="button" class="fa fa-archive" aria-hidden="true"></i></a>&nbsp;</td>
                              </tr>';

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