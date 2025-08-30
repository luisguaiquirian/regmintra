<?
  if(!isset($_SESSION))
    {
      session_start();
    }

  include_once $_SESSION['base_url'].'partials/header.php';
  
  if(!isset($_GET['register'])){
    header('Location: index.php');
  }

  $register = base64_decode($_GET['register']);

  $system->sql = "SELECT t2.*,
                  concat(t1.nombre,' ',t1.apellido) as nombre_completo,
                  t3.modelo as modelo_carro,
                  t4.marca as marca_carro,
                  t5.ruta as ruta_carro,
                  t6.tipo_unidad as tipo_unidad_carro,
                  t7.lubricante as lubricantes_carro,
                  cauchos.neumatico
                  FROM users as t1
                  LEFT JOIN unidades as t2 ON t1.usuario = t2.cod_afiliado
                  INNER JOIN modelos_vehiculos as t3 ON t3.id = t2.modelo
                  INNER JOIN marcas_vehiculos as t4 ON t4.id = t2.marca
                  INNER JOIN rutas as t5 ON t5.id = t2.ruta
                  INNER JOIN tipo_unidad as t6 ON t6.id = t2.tipo_unidad
                  INNER JOIN lubricantes as t7 ON t7.id = t2.tipo_lub
                  INNER JOIN cauchos ON cauchos.id = t2.num_neu
                   where t1.id = $register";
  //echo $system->sql;
  $data = $system->sql();
?>
  
  <section class="panel panel-featured panel-featured-danger">
    <header class="panel-heading">
      <div class="panel-actions">
        <a href="#" class="fa fa-caret-down"></a>
        <a href="#" class="fa fa-times"></a>
      </div>
      <h1 class="panel-title">Unidades del Transportista <?= $data[0]->nombre_completo ?></h1>
      <br>
      <a href="<?= $_SESSION['base_url1'].'app/admin/transportistas/index.php' ?>" class="btn btn-default">Volver al Panel</a>
    </header>
    <div class="panel-body">
      <div class="col-md-12">
        <?
          function make_widget($titulo,$valor,$type,$fa){
            if($type === 1){
              return '<section class="panel panel-featured-left panel-featured-secondary">
                        <div class="panel-body">
                          <div class="widget-summary">
                            <div class="widget-summary-col widget-summary-col-icon">
                              <div class="summary-icon bg-secondary">
                                <i class="fa fa-'.$fa.'"></i>
                              </div>
                            </div>
                            <div class="widget-summary-col">
                              <div class="summary">
                                <h4 class="title">'.$titulo.'</h4>
                                <div class="info">
                                  <strong class="amount">'.$valor.'</strong>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </section>';
            }else{
              return '
                    <section class="panel panel-featured-left panel-featured-tertiary">
                      <div class="panel-body">
                        <div class="widget-summary">
                          <div class="widget-summary-col widget-summary-col-icon">
                            <div class="summary-icon bg-tertiary">
                              <i class="fa fa-'.$fa.'"></i>
                            </div>
                          </div>
                          <div class="widget-summary-col">
                            <div class="summary">
                              <h4 class="title">'.$titulo.'</h4>
                              <div class="info">
                                <strong class="amount">'.$valor.'</strong>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </section>';
            }
              
          }

          if(count($data) > 0){
            foreach ($data as $key => $row) {

              $color = $row->color ? $row->color : "No posee";
              $ruta  = explode('-', $row->ruta_carro)[0]."<hr/>".explode('-', $row->ruta_carro)[1];
              $validate= $row->activo == 1 ? "Si" : "No";
              $marca = make_widget("Marca y Modelo",$row->marca_carro."<hr/>".$row->modelo_carro,1,'truck');
              $color_año = make_widget("Color y Año",$color."<hr/>".$row->ano,1,'book');
              $ruta = make_widget("Ruta",$ruta,1,'road');
              $tipo_unidad = make_widget("Tipo Unidad",$row->tipo_unidad_carro,2,'car');
              $lubricante = make_widget("Lubricante",$row->lubricantes_carro,2,'tint');
              $cant_lubricante = make_widget("Cant. Lubricante",$row->cant_lubri,2,'tint');
              $num_reu = make_widget("Nº Neumatico",$row->neumatico,1,'life-ring');
              $cant_num_reu = make_widget("Cant. Neumatico",$row->cant_neu,1,'life-ring');
              $acumulador = make_widget("Acumulador",$row->acumulador,1,'car');
              $activo = make_widget('Activo',$validate,2,'exclamation-circle');
              $obser = $row->obser ? $row->obser : "Sin Observación"; 


              echo '<div class="toggle" data-plugin-toggle data-appear-animation="fadeInUp">
                      <section class="toggle">
                        <label>Marca: '.$row->marca_carro.' Modelo: '.$row->modelo_carro.'. Placa: '.$row->placa.' <i class="fa fa-car fa-1x"></i></label>
                        <div class="toggle-content">
                          <div class="row">
                            <div class="col-md-4">'.$marca.'</div>
                            <div class="col-md-4">'.$color_año.'</div>
                            <div class="col-md-4">'.$ruta.'</div>
                          </div>
                          <div class="row">
                            <div class="col-md-4">'.$tipo_unidad.'</div>
                            <div class="col-md-4">'.$lubricante.'</div>
                            <div class="col-md-4">'.$cant_lubricante.'</div>
                          </div>
                          <div class="row">
                            <div class="col-md-4">'.$num_reu.'</div>
                            <div class="col-md-4">'.$cant_num_reu.'</div>
                            <div class="col-md-4">'.$acumulador.'</div>
                          </div>
                          <div class="row">
                            <div class="col-md-8">
                              <h4 class="text-center">'.$obser.'</h4>
                            </div>
                            <div class="col-md-4">
                                '.$activo.'
                            </div>
                          </div>
                        </div>
                      </section>
                    </div>';
            }
          }else{
            echo "<h3 class='text-center'>No hay unidades asignadas a este chofer</h3>";
          }
        ?>
                
      </div>
    </div>
  </section>

<?

  include_once $_SESSION['base_url'].'partials/footer.php';
?>