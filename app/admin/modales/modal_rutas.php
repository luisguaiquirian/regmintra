<?
    $system->sql = "SELECT *,
                    (SELECT estado from estados where id = rutas.estado) as estado_nombre,
                    (SELECT municipio from municipios where id_municipio = rutas.municipio and id_estado = rutas.estado) as municipio_nombre
                     from rutas";
?>

<div id="modalRutas" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 80%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header login-header" style="background-color: black; color: white;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Rutas a nivel Nacional</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                      <table class="table table-bordered table-responsive datatable-default">
                        <thead>
                          <tr>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Municipio</th>
                            <th class="text-center">Ruta</th>
                            <th class="text-center">Descripción</th>
                            <th class="text-center">Cant. de paradas</th>
                            <th class="text-center">Recorrido</th>
                          </tr>
                        </thead>
                        <tbody class="text-center">
                          <?
                            foreach ($system->sql() as $row) {
                              echo "  <tr>
                                        <td>{$row->estado_nombre}</td>
                                        <td>{$row->municipio_nombre}</td>
                                        <td>{$row->ruta}</td>
                                        <td> {$row->ruta_descripcion}</td>
                                        <td> {$row->cant_paradas}</td>
                                        <td><span class='badge alert-danger'>{$row->kilometros} Km.</span></td>
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