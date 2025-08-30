<?
  if(!isset($_SESSION))
    {
      session_start();
    }
    include_once $_SESSION['base_url'].'partials/header.php';

    $system->sql="SELECT
asignaciones.id,
estatus.descripcion AS name_estatus,
estados.estado,
asignaciones.serial,
almacenes.nombre AS acopio,
asignaciones.monto_total,
asignaciones.cantidad_asignada,
asignaciones.fec_reg,
asignaciones.id_rubro,
asignaciones.id_producto,
asignaciones.estatus,
productos.descripcion AS name_producto,
rubros.descripcion AS name_rubro,
presentaciones.descripcion AS name_presentacion,
productos.precio
FROM
asignaciones
INNER JOIN estados ON asignaciones.estado = estados.id_estado
INNER JOIN estatus ON asignaciones.estatus = estatus.id
INNER JOIN mov_items ON asignaciones.id_mov = mov_items.id
INNER JOIN almacenes ON mov_items.destino = almacenes.id
INNER JOIN productos ON asignaciones.id_producto = productos.id
INNER JOIN rubros ON productos.tipo = rubros.id
INNER JOIN presentaciones ON productos.presentacion = presentaciones.id
WHERE
asignaciones.estatus = 4 AND
asignaciones.estado = ".$_SESSION['edo']."
ORDER BY
asignaciones.fec_reg ASC";
?>
  
  <section class="panel panel-featured panel-featured-success">
    <header class="panel-heading">
      <div class="panel-actions">
        <a href="#" class="fa fa-caret-down"></a>
        <a href="#" class="fa fa-times"></a>
      </div>
      <h1 class="panel-title"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> Preasignaciones Rechazadas </h1>
    </header>
      <div class="panel-body">
        <!-- Table -->
                <table class="table table-bordered table-striped mb-none table-condensed" id="tabla_confirmaciones">
                  <thead><tr>
                    <th style="display: none;">ID</th>
                    <th>Serial</th>
                    <th>Municipio</th>
                    <th>Acopio</th>
                    <th>Tipo / Producto</th>
                    <th>Asignado</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th style="display: none;">Estatus</th>
                    <th>Accion</th>
                  </tr></thead>

                  <tbody id="cont_tabla_entrada">
                    <?php
                      $x=0;
                      foreach ($system->sql() as $rs) {
                        //cambiar el formato de la fecha 
                        $rs->fec_reg = date("d/m/Y", strtotime($rs->fec_reg));
                        //monto
                        $m = '';
                        if ($rs->monto_total==0) {
                          $m = '<td class="text-center"><a data-tool="tooltip" title="Asignar Precio" onclick="show_asig_precio('.$rs->id.','.$rs->asignado.')" class="btn btn-link btn-xs" href="javascript:void(0)" role="button">Asignar</a></td>';
                        }else{
                          $m = '<td class="text-success"><span data-tool="tooltip" title="Precio Unitario: '.$rs->precio.' Bs.">'.$rs->monto_total.' Bs.</span></td>';
                        }
                        echo '<tr id =fls_'.$x.'>
                              <td style="display:none;">'.$rs->id.'</td>
                              <td>'.$rs->serial.'</td>
                              <td><span class="badge bg-danger">'.$rs->estado.'</span></td>
                              <td class="text-warning">'.$rs->acopio.'</td>
                              <td><span class="text-danger">'.$rs->name_rubro.'</span>&nbsp;/&nbsp;<span class="text-primary">'.$rs->name_producto.'</span></td>
                              <td class="text-center" ><span class="badge">'.$rs->cantidad_asignada.' '.$rs->name_presentacion.'</span></td>
                              '.$m.'
                              <td>'.$rs->fec_reg.'</td>
                              <td style="display: none;"><span class="badge">'.$rs->name_estatus.'</span></td>
                              <td>&nbsp;<a data-tool="tooltip" title="Ver" class="btn btn-success" href="'.$_SESSION['base_url1'].'app/estado/asignaciones/asignaciones_for_edit_detal.php?key='.base64_encode($rs->id).'&key2='.base64_encode($rs->id_rubro).'" role="button"><i role="button" class="fa fa-eye" aria-hidden="true"></i></a></td>
                              </tr>';
                              $x++;
                      }
                      unset($x);
                    ?>
                  </tbody>
                </table>
      </div>
  </section>
        
<?
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/step_three.php';
  include_once $_SESSION['base_url'].'app/admin/asignaciones/modales/confirmar_asignacion.php';
  include_once $_SESSION['base_url'].'partials/footer.php';
?>

<script type="text/javascript">

  /*cargamos todas */



  $('#tabla_confirmaciones').dataTable( {
      "ordering": false
    });

</script>