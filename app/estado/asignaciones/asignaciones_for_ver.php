<?
  if(!isset($_SESSION))
    {
      session_start();
    }
    include_once $_SESSION['base_url'].'partials/header.php';

    $system->sql="SELECT
asignaciones.serial,
asignaciones.beneficiados,
asignaciones.precio,
asignaciones.monto_total,
asignaciones.fec_reg,
asignaciones.fec_aprobado,
productos.descripcion AS name_producto,
productos.marca,
productos.modelo,
estados.estado,
estatus.descripcion AS name_estatus,
asignaciones.id_mov AS nro_consig,
almacenes.nombre AS acopio,
rubros.descripcion AS name_rubro,
asignaciones.cantidad_asignada,
presentaciones.descripcion AS name_presentacion
FROM
asignaciones
INNER JOIN productos ON asignaciones.id_producto = productos.id
INNER JOIN estados ON asignaciones.estado = estados.id_estado
INNER JOIN estatus ON asignaciones.estatus = estatus.id
INNER JOIN mov_items ON asignaciones.id_mov = mov_items.id
INNER JOIN almacenes ON mov_items.destino = almacenes.id
INNER JOIN rubros ON asignaciones.id_rubro = rubros.id
INNER JOIN presentaciones ON productos.presentacion = presentaciones.id
WHERE asignaciones.estado = ".$_SESSION['edo']."
ORDER BY
asignaciones.estatus ASC";
?>
  
  <section class="panel panel-featured panel-featured-success">
    <header class="panel-heading">
      <div class="panel-actions">
        <a href="#" class="fa fa-caret-down"></a>
        <a href="#" class="fa fa-times"></a>
      </div>
      <h1 class="panel-title"><i class="fa fa-calendar fa-lg" aria-hidden="true"></i> Listado de asignaciones del estado <?= $_SESSION['estado']?></h1>
    </header>
      <div class="panel-body">
        <!-- Table -->
                <table class="table table-bordered table-striped mb-none table-condensed" id="tabla_asignaciones">
                  <thead><tr>
                    <th>Nº Consig.</th>
                    <th>Serial</th>
                    <th>Estado</th>
                    <th>Acopio</th>
                    <th>Tipo / Producto</th>
                    <th>Asignado</th>
                    <th>Monto</th>
                    <th>Registro</th>
                    <th>Aprobado</th>
                    <th>Estatus</th>
                  </tr></thead>

                  <tbody id="cont_tabla_entrada">
                    <?php
                      foreach ($system->sql() as $rs) {
                        //cambiar el formato de la fecha 
                        $rs->fec_reg = date("d/m/Y", strtotime($rs->fec_reg));
                        if ($rs->fec_aprobado==''):$rs->fec_aprobado='00-00-0000'; else: $rs->fec_aprobado = date("d/m/Y", strtotime($rs->fec_aprobado)); endif;
                        echo '<tr>
                          <td>#'.$rs->nro_consig.'</td>
                          <td>'.$rs->serial.'</td>
                          <td><span class="badge bg-danger">'.$rs->estado.'</span></td>
                          <td class="text-warning">'.$rs->acopio.'</td>
                          <td><span class="text-danger">'.$rs->name_rubro.'</span>&nbsp;/&nbsp;<span data-tool="tooltip" title="'.$rs->marca.'/'.$rs->modelo.'" class="text-primary">'.$rs->name_producto.'</span></td>
                          <td class="text-center" ><span class="badge">'.$rs->cantidad_asignada.' '.$rs->name_presentacion.'</span></td>
                          <td><span data-tool="tooltip" title="Precio unitario: '.$rs->precio.'" class="badge bg-success">'.$rs->monto_total.'</span></td>
                          <td>'.$rs->fec_reg.'</td>
                          <td>'.$rs->fec_aprobado.'</td>
                          <td><span class="badge">'.$rs->name_estatus.'</span></td>
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

<script type="text/javascript">
  $('#tabla_asignaciones').dataTable( {"ordering": false});
</script>