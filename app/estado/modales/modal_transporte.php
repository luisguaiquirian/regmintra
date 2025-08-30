<?
    $system->sql = "SELECT *, 
                    (SELECT estado from estados where id = users.estado) as estado_nombre,
                    (SELECT municipio from municipios where id_municipio = users.municipio and id_estado = users.estado) as municipio_nombre,
                    (SELECT tipo_ruta from tipo_ruta where id_ruta = users.tipo_ruta) as tipo_ruta_select
                    from users where perfil = 4 and estado =".$_SESSION['edo'];
    $sql = $system->sql();
?>

<div id="modalTransporte" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 80%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header login-header" style="background-color: black; color: white;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Lineas de Transporte a nivel del Estado <?= $sql[0]->estado_nombre?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                      <table class="table table-bordered table-responsive datatable-default">
                        <thead>
                          <tr>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Municipio</th>
                            <th class="text-center">Propietario</th>
                            <th class="text-center">Cédula-Telefono</th>
                            <th class="text-center">Nombre Linea</th>
                            <th class="text-center">Tipo Ruta</th>
                            <th class="text-center">Gremio</th>
                          </tr>
                        </thead>
                        <tbody class="text-center">
                          <?
                            foreach ($system->sql() as $row) {
                              echo "  <tr>
                                        <td>{$row->estado_nombre}</td>
                                        <td>{$row->municipio_nombre}</td>
                                        <td>{$row->nombre}<br/>{$row->apellido}</td>
                                        <td>{$row->cedula}<br/> {$row->telefono}</td>
                                        <td><span class='badge alert-danger'>{$row->nombre_linea}</span></td>
                                        <td>{$row->tipo_ruta_select}</td>
                                        <td>{$row->gremio}</td>
                                      </tr>";
                            }
                          ?>
                        </tbody>
                      </table>
                    </div>
                </div>
            </div><!-- fin modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- fin modal-content -->
    </div><!-- fin modal-dialog -->
</div> <!-- fin modal -->