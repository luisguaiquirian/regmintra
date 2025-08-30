<?
    $system->sql = "
                  SELECT t2.*,
                  (SELECT estado from estados where id = t1.estado) as estado_nombre,
                  (SELECT municipio from municipios where id_municipio = t1.municipio and id_estado = t1.estado) as municipio_nombre,
                  concat(t1.nombre,' ',t1.apellido) as nombre_completo,
                  t1.telefono,
                  t3.modelo as modelo_carro,
                  t4.marca as marca_carro,
                  t5.ruta as ruta_carro,
                  t6.tipo_unidad as tipo_unidad_carro,
                  t7.lubricante as lubricantes_carro,
                  t8.neumatico as tipo_caucho
                  FROM users as t1
                  INNER JOIN unidades as t2 ON t1.usuario = t2.cod_afiliado
                  INNER JOIN modelos_vehiculos as t3 ON t3.id = t2.modelo
                  INNER JOIN marcas_vehiculos as t4 ON t4.id = t2.marca
                  INNER JOIN rutas as t5 ON t5.id = t2.ruta
                  INNER JOIN tipo_unidad as t6 ON t6.id = t2.tipo_unidad
                  INNER JOIN lubricantes as t7 ON t7.id = t2.tipo_lub
                  INNER JOIN cauchos as t8 ON t8.id = t2.num_neu
                   where t1.perfil = 5 and t2.activo = 1";
?>

<div id="modalUnidadActiva" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 80%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header login-header" style="background-color: black; color: white;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Unidades Activas a Nivel Nacional</h4>
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
                            <th class="text-center">Telefono</th>
                            <th class="text-center">Modelo-Placa</th>
                            <th class="text-center">Marca</th>
                            <th class="text-center">Ruta</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center">Lubricante</th>
                            <th class="text-center">Num Neumaticos</th>
                          </tr>
                        </thead>
                        <tbody class="text-center">
                          <?
                            foreach ($system->sql() as $row) {
                              echo "  <tr>
                                        <td>{$row->estado_nombre}</td>
                                        <td>{$row->municipio_nombre}</td>
                                        <td>{$row->nombre_completo}</td>
                                        <td><span class='badge alert-danger'>{$row->telefono}</span></td>
                                        <td>{$row->modelo_carro}<br/>{$row->placa}</td>
                                        <td>{$row->marca_carro}</td>
                                        <td>{$row->ruta_carro}</td>
                                        <td>{$row->tipo_unidad_carro}</td>
                                        <td>{$row->lubricantes_carro}</td>
                                        <td>{$row->tipo_caucho}</td>
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