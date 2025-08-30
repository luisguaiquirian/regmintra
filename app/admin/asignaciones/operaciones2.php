<?php
if(!isset($_SESSION)){session_start();}
include_once $_SESSION['base_url'].'/class/system.php';
$system = new System;

switch ($_REQUEST['action']) {

  case 'confirmar_preasignacion':
    $idasig = base64_decode($_GET['key']);
    $system->sql="SELECT * from asignaciones where estatus=2 AND id=".$idasig;
    $r = $system->sql();
    if (count($r)>0) {

      $conexion = $system->begin();
      $system->where = "id=".$idasig;
      $system->table="asignaciones";
      $m = $system->modificar_begin(array('estatus' => 5,'fec_aprobado'=>date('Y-m-d')),$conexion);
      if ($m['r']==true) {

        $system->where = "id_asignacion=".$idasig;
        $system->table="asignaciones_solicitud";
        $m = $system->modificar_begin(array('estatus' => 5, ),$conexion);
        if ($m['r']==true) {

          $system->sql="select id_detalle from asignaciones_solicitud where id_asignacion = ".$idasig;
          $m = $system->sql();
          $len = count($m);
          if ($len>0) {
            $m = modificar_detalles_solicitud($system,$m,$len,$conexion);
            if($m==true){
              $system->where = "id_mov=".$r[0]->id_mov." AND id_asignacion=".$idasig;
              $system->table="mov_items_almacenes";
              $m = $system->modificar_begin(array('estatus' => 5, ),$conexion);
              if ($m['r']==true) {
                $system->commit($conexion);
                echo json_encode(array('msg' => true, 'msj'=>'Exito, preasignacion confirmada.','url'=>$_SESSION['base_url1'].'app/admin/asignaciones/confirmar_asignaciones.php'));
              }else{
                $system->rollback($conexion);
              echo json_encode(array('msg' => false, 'msj'=>'Error,  no se puede modificar el detalle de los movimientos.'));  
              }
            }else{
              $system->rollback($conexion);
              echo json_encode(array('msg' => false, 'msj'=>'Error,  no se puede modificar los detalles de la solicitud.'));
            }

          }else{
            $system->rollback($conexion);
            echo json_encode(array('msg' => false, 'msj'=>'Error,  no se puede acceder al detallado.'));
          }

        }else{
          $system->rollback($conexion);
          echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede modeificar la sincroniozacion con las solicitudes.'));
        }

      }else{
        $system->rollback($conexion);
        echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede modificar la preasignacion.'));
      }

    }else{
      echo json_encode(array('msg' => false, 'msj'=>'Error, la asignacion no se encuentra disponible.'));
    }
  break;

  case 'asignar_directo':
    $iddetalle = base64_decode($_GET['detalle']);
    $productoAsig = base64_decode($_GET['id_producto_asig']);
    $cantidadAsig = base64_decode($_GET['cantidad_asignada']);
    $productoSol = base64_decode($_GET['id_producto_solicitado']);
    $inventario = base64_decode($_GET['inventario']);
    $system->sql="select * from detalles_solicitudes where estatus = 1 and id=".$iddetalle;
    $d = $system->sql();
    if (count($d)>0) {

      $system->sql="SELECT MAX(id) as max_id FROM mov_items";
      $r = $system->sql();
      if (count($r)>0) {
        $idmov = $r[0]->max_id+1;
        $conexion = $system->begin();
        
        $array = array(
          'id' => $idmov,
          'inventario_salida' => $inventario, 
          'destino' => base64_decode($_GET['centro']),
          'id_producto_sol' => $productoSol,
          'id_rubro' => $d[0]->id_rubro,
          'producto' => $productoAsig,
          'estado' => base64_decode($_GET['estado']),
          'cantidad_solicitudes' => $d[0]->cantidad,
          'cantidad_asig' => $cantidadAsig,
          'cantidad_disponible' => 0,
          'fec_reg' => date('Y-m-d'),
          'estatus' => 3, 
        );
        $system->table = "mov_items";
        $g=$system->guardar_begin($array,$conexion);
        if ($g['r']==true) {
        /*$system->table = "mov_items_almacenes";
        $g=$system->guardar_begin(array(
            'id_mov' => $idmov,
            'id_inventario' => $inventario,
            'cantidad' => base64_decode($_GET['cantidad_asignada']),
            'estatus' => 5,
          ),$conexion);
        if ($g['r']==true) {*/

          $s = crear_serial('asignacion');
          if ($s['r']=true) {
            $system->sql="SELECT MAX(id) max_id from asignaciones";
            $r = $system->sql();
            $idasig = $r[0]->max_id + 1;
            $system->sql="select precio from productos where id=".$productoAsig;
            $pro = $system->sql();
            $array =array(
              'id' => $idasig, 
              'id_mov' => $idmov, 
              'serial' => $s['s'], 
              'beneficiados' => 1, 
              'precio' => $pro[0]->precio, 
              'monto_total' =>  $pro[0]->precio*$cantidadAsig, 
              'id_producto' => $productoAsig, 
              'id_producto_solicitado' => $productoSol, 
              'cantidad_solicitud' => $d[0]->cantidad, 
              'cantidad_asignada' => $cantidadAsig, 
              'fec_reg' => date('Y-m-d'), 
              'fec_aprobado' => date('Y-m-d'), 
              'estado' => base64_decode($_GET['estado']), 
              'estatus' => 5, 
              'tipo_asignacion' => 2, 
              'id_rubro' => $d[0]->id_rubro, 
            );  
            $system->table = "asignaciones";
            $g=$system->guardar_begin($array,$conexion);
            if ($g['r']==true) {
              $system->table = "asignaciones_solicitud";
              $g = $system->guardar_begin(array('id_asignacion' => $idasig, 'id_detalle' => $iddetalle, 'cantidad' => $cantidadAsig, 'estatus' => 5, ),$conexion);
              if ($g['r']==true) {
                $system->where = "id=".$iddetalle;
                $system->table="detalles_solicitudes";
                $r = $system->modificar_begin(array('estatus' => 5,'fec_aprobado'=>date('Y-m-d')),$conexion);
                if ($r['r'] == true) {
                  $system->sql="select disponible, comprometido from inventario where id=".$inventario;
                  $inv = $system->sql();
                  $dis=$inv[0]->disponible-$cantidadAsig;
                  $com=$inv[0]->comprometido+$cantidadAsig;
                  $system->where = "id=".$inventario;
                  $system->table="inventario";
                  $r = $system->modificar_begin(array('comprometido' => $com,'disponible'=>$dis),$conexion);
                  if ($r['r']==true) {
                    $system->table = "mov_items_almacenes";
                    $g=$system->guardar_begin(array(
                        'id_mov' => $idmov,
                        'id_inventario' => $inventario,
                        'cantidad' => base64_decode($_GET['cantidad_asignada']),
                        'estatus' => 5,
                        'id_asignacion'=>$idasig,
                      ),$conexion);
                    if ($g['r']==true) {
                      $system->commit($conexion);
                      echo json_encode(array('msg' => true, 'msj'=>'Exito, se registro correctamente la asignacion directa.'));
                    }else{
                    $system->rollback($conexion);
                    echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede registrar el moviemiento de la asignacion'));
                  }


                  }else{
                    $system->rollback($conexion);
                    echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede acceder al inventario.'));
                  }
         
                }else{
                  $system->rollback($conexion);
                  echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede registrar la asignacion directa.'));
                }

              }else{
                $system->rollback($conexion);
                echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede sincronizar la asignacion con el detalle.'));
              }

            }else{
              $system->rollback($conexion);
              echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede accder a guardar la asignacion.'));
            }

          }else{
            $system->rollback($conexion);
            echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede crear el serial para la asignacion.'));
          }

        }else{
          $system->rollback($conexion);
          echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede registrar los almacenes en los moviemientos.'));
        }


        /*}else{
          $system->rollback($conexion);
          echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede registrar el moviemiento de la asignacion'));
        }*/

        
      }else{
        echo json_encode(array('msg' => false,'msj'=>'Error, imposible acceder a los movimientos de inventario'));  
      }
      
    }else{
      echo json_encode(array('msg' => false,'msj'=>'Error, imposible acceder al detalle de la solicitud'));
    }

    
  break;

  case 'traer_solicitudes_act_directo':
      $e = base64_decode($_GET['e']);
      $r = base64_decode($_GET['r']);
      $idsol = base64_decode($_GET['i']);
      unset($_GET['action'],$_GET['e'],$_GET['r'],$_GET['i']);

      $system->sql="SELECT a.*,b.descripcion,r.descripcion as rubro FROM `almacenes` as a
            inner join almacenes_nivel as b on (a.nivel=b.id) 
            inner join rubros as r on (a.id_rubro=r.id)
            where a.estatus = 7 and a.estado=".$e." order by a.id_rubro, a.nivel asc";
      $destino = $system->sql();
      if (count($destino)>0) {
        $system->sql="SELECT
        unidades.placa,
        detalles_solicitudes.cantidad,
        unidades.tipo_lub,
        detalles_solicitudes.id,
        solicitudes.fec_solicitud,
        municipios.municipio,
        users.id AS id_user,
        users.cedula,
        users.nombre,
        users.apellido,
        users.nombre_linea
        FROM
        detalles_solicitudes
        INNER JOIN unidades ON detalles_solicitudes.id_unidad = unidades.id
        INNER JOIN solicitudes ON detalles_solicitudes.id_solicitud = solicitudes.id
        INNER JOIN municipios ON solicitudes.estado = municipios.id_estado AND solicitudes.municipio = municipios.id_municipio
        INNER JOIN users ON detalles_solicitudes.id_user = users.id
        WHERE unidades.estado = ".$e." AND 
        detalles_solicitudes.id_rubro = ".$r." AND 
        detalles_solicitudes.estatus = 1 AND
        detalles_solicitudes.id = ".$idsol." 
        ORDER BY detalles_solicitudes.id ASC";
      $r = $system->sql();
      if (count($r)>0) {
        echo json_encode(array('msg' => true,'msj'=>'Mostrando Listado','r'=>$r, 'a' =>$destino));
      }else{echo json_encode(array('msg' => false,'msj'=>'Error al cargar personas para preasignacion'));}

      }else{
        echo json_encode(array('msg' => false,'msj'=>'Error, el estado no tiene registrado ningun almacen que pueda servir como centro de acopio de la asignacion'));
      }
    break;

  case 'traer_estadisticas_asigEstados':
      unset($_GET['action']);
      $system->sql = "SELECT
        Count(unidades.id) AS unidades_inactivas,
        estados.estado,
        estados.id_estado
        FROM
        unidades
        INNER JOIN estados ON unidades.estado = estados.id_estado
        WHERE
        unidades.activo = 0
        GROUP BY
        estados.id_estado";
        $r = $system->sql();

        $system->sql="SELECT estados.estado, estados.id_estado FROM estados ORDER BY estados.id ASC";
        $e = $system->sql();

        $date_start=date('Y-m-01');
        $date_fin=strtotime ( '+1 month' , strtotime ( $date_start ) ) ;
        $date_fin = date ( 'Y-m-j' , $date_fin );
        $system->sql="SELECT
          Sum(mov_items.cantidad_asig) AS cantidad_asig,
          mov_items.estado,
          mov_items.id_rubro
          FROM mov_items 
          /*WHERE fec_reg>= '".$date_start."' 
          AND fec_reg<= '".$date_fin."' */
          GROUP BY
          mov_items.estado,
          mov_items.id_rubro
          ORDER BY mov_items.estado ASC";
        $a = $system->sql();
        if (count($r)> 0) {
          echo json_encode(array('msg' => true, 'msj' => 'Mostrando estadisticas' , 'r'=>$r, 'e' => $e, 'a' => $a));
        }else{
          echo json_encode(array('msg' => false, 'msj' => 'Mostrando estadisticas' , 'r'=>$r));
        }
    break;

	case 'historial_de_consignaciones':
      $e = base64_decode($_GET['e']);
      $r = base64_decode($_GET['r']);
      $p = base64_decode($_GET['p']);
      $sel = "";
      if($r == 1){
        $sel = " cauchos.neumatico as producto_sol ";
        $inner = " LEFT JOIN cauchos ON mov_items.id_producto_sol = cauchos.id ";
      }
      elseif ($r == 2) {
        $sel = " lubricantes.lubricante as producto_sol ";
        $inner=" LEFT JOIN lubricantes ON mov_items.id_producto_sol = lubricantes.id ";
      }
      elseif ($r == 3) {
        $sel = " acumuladores.acumulador as producto_sol ";
        $inner=" LEFT JOIN acumuladores ON mov_items.id_producto_sol = acumuladores.id ";
      }
      else{
        json_encode(array('msj' => false, 'msg'=>'Error de tipo.'));
      }
      $system->sql="SELECT
        rubros.descripcion,
        productos.descripcion AS producto,
        productos.marca,
        productos.modelo,
        mov_items.cantidad_asig,
        mov_items.fec_reg,
        estatus.descripcion AS estatus,
        estados.estado,
        ".$sel."
        FROM
        mov_items
        ".$inner."
        INNER JOIN rubros ON mov_items.id_rubro = rubros.id
        INNER JOIN productos ON mov_items.producto = productos.id
        INNER JOIN estatus ON mov_items.estatus = estatus.id
        INNER JOIN estados ON mov_items.estado = estados.id_estado
        WHERE
        mov_items.estado = ".$e." AND
        mov_items.id_rubro = '".$r."'
        ORDER BY
        mov_items.estatus ASC";
        $re = $system->sql();

        if (count($re)>0) {
          $where = "";
          if ($p != 0) {$where = 'mov_items.id_producto_sol ='.$p.' AND';}

        	//mostramos las cuotas activas asignadas
        	$system->sql="SELECT
            Sum(mov_items.cantidad_asig) AS cantidad_activa
            FROM
            mov_items
            WHERE
            mov_items.id_rubro = ".$r." AND
            mov_items.estado = ".$e." AND
            ".$where."
            (mov_items.estatus = 1 OR
            mov_items.estatus = 2)";
            $c = $system->sql();
            //var_dump(count($c));
      			if ($c[0]->cantidad_activa > 0) {
      				echo json_encode(array('msg' => true,'msj'=>'Mostrando Historial','r'=>$re,'c' => $c[0]->cantidad_activa));		
      			}else{
      				echo json_encode(array('msg' => true,'msj'=>'Mostrando Historial','r'=>$re,'c'=>0));
      			}		
        }else{
          echo json_encode(array('msg' => false, 'msj'=>'El estado no posee historico de consignaciones.'));
        }
    break;

    //muestra los detalles de la asignacion para poder asignar
    case 'step_one':
      $tipo = base64_decode(trim($_GET['tipo']));
      unset($_GET['action'],$_GET['tipo']);
      $system->sql="select *,c.codigo as cod_almacen,sum(disponible) as total_disponible,a.id as id_producto,b.id as id_inventario from productos as a 
        inner join inventario as b on (a.id = b.producto) 
        inner join almacenes as c on (b.almacen=c.id)
        where a.tipo=".$tipo." and a.estatus=7 and c.nivel = 1
        GROUP By (b.producto) order by (a.descripcion)";
      $consulta = $system->sql(); 
      if (count($consulta)>0) {
        echo json_encode(array('msg' => true, 'msj' => 'Items disponibles cargados...', 'r' => $consulta));
        unset($consulta);
      }else{
        echo json_encode(array('msg' => false, 'msj' => 'No hay nada disponible en el inventario'));
      }
    break;

    case 'step_one_directo':
      $tipo = base64_decode(trim($_GET['tipo']));
      unset($_GET['action'],$_GET['tipo']);
      $system->sql="SELECT
        a.id,
        a.codigo,
        a.descripcion,
        a.marca,
        a.modelo,
        a.tipo,
        a.subtipo,
        a.presentacion,
        a.estatus,
        a.fec_reg,
        a.precio,
        b.disponible AS total_disponible,
        a.id AS id_producto,
        b.id AS id_inventario,
        c.nombre,
        estados.estado
        FROM
        productos AS a
        INNER JOIN inventario AS b ON (a.id = b.producto)
        INNER JOIN almacenes AS c ON (b.almacen = c.id)
        INNER JOIN estados ON c.estado = estados.id
        where a.tipo=".$tipo." and a.estatus=7 and c.nivel = 1 ORDER BY
a.id ASC
";
              $consulta = $system->sql(); 
      if (count($consulta)>0) {
        echo json_encode(array('msg' => true, 'msj' => 'Items disponibles cargados...', 'r' => $consulta));
        unset($consulta);
      }else{
        echo json_encode(array('msg' => false, 'msj' => 'No hay nada disponible en el inventario'));
      }
    break;

    case 'traer_alm_disponibles':
      unset($_GET['action']);
      $producto = base64_decode($_GET['producto']);
      $estado = base64_decode($_GET['estado']);
      
      $system->sql="SELECT a.*,b.descripcion,r.descripcion as rubro FROM `almacenes` as a
      inner join almacenes_nivel as b on (a.nivel=b.id) 
      inner join rubros as r on (a.id_rubro=r.id)
      where a.estatus = 7 and a.estado=".$estado." order by a.id_rubro, a.nivel asc ";
      $destino = $system->sql();
      if ($destino > 0) {
        $system->sql="select a.id as id_producto,b.id as id_inventario,e.estado as estadod,n.descripcion as niveld,c.nombre,b.disponible
        from productos as a 
        inner join inventario as b on (a.id = b.producto) 
        inner join almacenes as c on (b.almacen=c.id)
        inner join almacenes_nivel as n on (c.nivel=n.id)
        inner JOIN estados as e on (c.estado=e.id_estado)
        where a.id=".$producto." and c.nivel=1";
        $r = $system->sql();
        if (count($r)>0){
           echo json_encode(array('msj' => true,'msg'=>'Mostrando disponibilidad','a' => $destino ,'r'=>$r));
        }else{
          echo json_encode(array('msj' => false, 'msg'=>'No hay disponibilidad'));
        }
      }else{
        echo json_encode(array('msj' => false, 'msg'=>'No hay existe ningun centro de acopio en el estado.'));
      }

    break;

    break;

     case 'asignar_items_estado':
     /*$system->sql="SELECT cantidad_asig FROM mov_items where estado = ".base64_decode($_GET['estado'])." AND id_rubro = ".base64_decode($_GET['rubro'])." AND estatus = 1";
     $qu = $system->sql();
     if (count($qu)>0) {
      
     }*/

      /*buscamos el max mov_items*/
      $system->sql="SELECT max(id) as idmax FROM mov_items";
      $maxasig = $system->sql();
      $idnew = $maxasig[0]->idmax + 1;
      unset($maxasig);
      $almacenes=$_GET['almacenes'];
      $asignacion = array(
        'id' => $idnew,
        'estado' => base64_decode($_GET['estado']),
        'destino' => base64_decode($_GET['destino']),
        'id_producto_sol' => base64_decode($_GET['producto_sol']),
        'producto' => base64_decode($_GET['producto_asig']),
        'id_rubro' => base64_decode($_GET['rubro']),
        'cantidad_asig' => base64_decode($_GET['cantidad_asig']),
        'cantidad_disponible' => base64_decode($_GET['cantidad_asig']),
        'cantidad_solicitudes' => base64_decode($_GET['cantidad_sol']),
        'fec_reg' => date('Ymd'),
        'estatus' => '1',
        'inventario_salida'=>$almacenes[0][0],
      );
      

      $conexion = $system->begin();
      $pas = 0;
      $system->table = "mov_items";
      $r = $system->guardar_begin($asignacion,$conexion);
      if($r['r'] == true){
        /*$cant_alm = count($almacenes);
        for ($i=0; $i < $cant_alm; $i++) { 
          $system->table="mov_items_almacenes";
          $r = $system->guardar_begin(array('id_mov'=>$idnew,'id_inventario'=>$almacenes[$i][0],'cantidad'=>$almacenes[$i][1],'estatus'=>1),$conexion); 
          if ($r['r']==true) {*/
            $system->sql = "SELECT disponible,comprometido FROM inventario WHERE id = ".$almacenes[0][0];
            $sel = $system->sql();
            if(count($sel)>0){
              if ($sel[0]->disponible>=$almacenes[0][1]) {
                $monto =  $sel[0]->disponible ? $sel[0]->disponible : 0;
                $monto1 =  $almacenes[0][1] ?  $almacenes[0][1] : 0;
                $monto2 = $sel[0]->comprometido ? $sel[0]->comprometido : 0;
                $dis = $monto-$monto1;
                $com = $monto2+$monto1;

                $system->where = "id=".$almacenes[0][0];
                $system->table="inventario";
                $r = $system->modificar_begin(array('disponible' => $dis, 'comprometido'=>$com),$conexion);
                if ($r['r'] == true) {
                  $system->commit($conexion);
                  echo json_encode(array('msg' => true, 'msj'=>'La consignacion se realizo exitosamente.'));
                }else{
                  $system->rollback($conexion);
                  echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede modificar el inventario.')); 
                  //$pas++;
                }
              }else{
                $system->rollback($conexion);
                echo json_encode(array('msg' => false, 'msj'=>'Error, la disponibilidad del almacen es menor a lo asignado. Verifique lo asignado'));
              }
            }else{
              $system->rollback($conexion);
              echo json_encode(array('msg' => false, 'msj'=>'Error, no se encontro el inventario a descontar.'));
            }
          /*}else{
            $system->rollback($conexion);
            echo json_encode(array('msg' => false, 'msj'=>'Error, imposible guardar el descuente del almacen_salida('.$almacenes[$i][0].').'));
          }*/
        //}
      }else{
        $system->rollback($conexion);
        echo json_encode(array('msg' => false, 'msj'=>'Error al registrar la asignacion.'));
      }
      
    break;

    case 'listar_unidades_asignacion':
      $system->sql="SELECT
asignaciones_solicitud.id,
asignaciones.id as id_asignacion,
asignaciones_solicitud.cantidad AS cant_asignada,
detalles_solicitudes.cantidad AS cant_solicitada,
detalles_solicitudes.id as id_detalle,
asignaciones.id_rubro AS id_rubro,
unidades.placa,
unidades.activo,
unidades.verf,
users.id AS id_user,
users.cedula,
users.nombre_linea,
users.nombre,
users.apellido,
users.telefono,
solicitudes.fec_solicitud
FROM
asignaciones_solicitud
INNER JOIN asignaciones ON asignaciones_solicitud.id_asignacion = asignaciones.id
INNER JOIN detalles_solicitudes ON asignaciones_solicitud.id_detalle = detalles_solicitudes.id
INNER JOIN unidades ON detalles_solicitudes.id_unidad = unidades.id
INNER JOIN users ON detalles_solicitudes.id_user = users.id
INNER JOIN solicitudes ON detalles_solicitudes.id_solicitud = solicitudes.id
WHERE
asignaciones.id = ".base64_decode($_GET['key'])."
ORDER BY
solicitudes.id ASC";
//var_dump($system->sql);
  $r = $system->sql();
  $inner = "";
  switch (base64_decode($_GET['key3'])) {
    case '1'://neu
      $inner = "unidades.num_neu = ".base64_decode($_GET['key2']);
    break;
    case '2'://lub
      $inner = "unidades.tipo_lub = ".base64_decode($_GET['key2']);
    break;
    case '3'://acu
      $inner = "unidades.acumulador = ".base64_decode($_GET['key2']);
    break;
    default:
      return 'Opcion invalida.-...';
    break;
  }

  $system->sql = "SELECT
solicitudes.fec_solicitud,
detalles_solicitudes.cantidad AS cant_solicitada,
detalles_solicitudes.id_rubro,
detalles_solicitudes.id AS id_detalle,
detalles_solicitudes.cantidad,
unidades.id,
unidades.placa,
unidades.tipo_lub,
users.id AS id_user,
users.cedula,
users.nombre,
users.apellido,
users.telefono,
users.nombre_linea,
detalles_solicitudes.id AS id_detalle
FROM
solicitudes
INNER JOIN detalles_solicitudes ON solicitudes.id = detalles_solicitudes.id_solicitud
INNER JOIN unidades ON detalles_solicitudes.id_unidad = unidades.id
INNER JOIN users ON detalles_solicitudes.id_user = users.id
WHERE
solicitudes.estado = ".base64_decode($_GET['key4'])." AND
detalles_solicitudes.id_rubro = ".base64_decode($_GET['key3'])." AND
detalles_solicitudes.estatus = 1 AND ".$inner." ORDER BY solicitudes.id ASC";

$r2 = $system->sql();
//var_dump($system->sql);
echo json_encode(array('msg'=>true, 'msj'=>'Unidades cargadas...','r' => $r, 'n' => $r2, 'a' => base64_decode($_GET['key'])));

    break;

    case 'eliminar_unidad_lista':
      $iddetalle = base64_decode($_GET['key']);
      $idasignacion = base64_decode($_GET['keyas']);
      $observacion = base64_decode($_GET['obser']);

      //verificamos que existe 
      $system->sql="SELECT
      asignaciones_solicitud.id,
      asignaciones_solicitud.cantidad,
      asignaciones_solicitud.id_asignacion,
      unidades.placa
      FROM
      asignaciones_solicitud
      INNER JOIN detalles_solicitudes ON asignaciones_solicitud.id_detalle = detalles_solicitudes.id
      INNER JOIN unidades ON detalles_solicitudes.id_unidad = unidades.id WHERE asignaciones_solicitud.id_detalle=".$iddetalle." and  asignaciones_solicitud.id_asignacion=".$idasignacion;
      //var_dump($system->sql);
      $r = $system->sql();
      if (count($r)>0) {
        $conexion = $system->begin();

        $system->sql="SELECT * FROM asignaciones WHERE id=".$r[0]->id_asignacion;
        $s = $system->sql();
        if (count($s)>0) {
          $beneficiados = $s[0]->beneficiados-1;
          $monto = $s[0]->monto_total-($s[0]->precio*$r[0]->cantidad);
          $cantidad_asignada = $s[0]->cantidad_asignada-$r[0]->cantidad;
          $system->where = "id=".$idasignacion;
          $system->table="asignaciones";
          $m = $system->modificar_begin(array('beneficiados' => $beneficiados, 'monto_total'=>$monto,'cantidad_asignada'=>$cantidad_asignada),$conexion);
          if ($m['r']==true) {
            $system->sql="SELECT * FROM mov_items where id=".$s[0]->id_mov;
            $mov = $system->sql();
            if (count($mov)>0) {
              $cantidad_disponible = $mov[0]->cantidad_disponible+$r[0]->cantidad;
              $system->where = "id=".$s[0]->id_mov;
              $system->table="mov_items";
              $m = $system->modificar_begin(array('cantidad_disponible' => $cantidad_disponible,'estatus'=>2),$conexion);
              if ($m['r']==true) {
                $system->where = "id=".$iddetalle;
                $system->table="detalles_solicitudes";
                $m = $system->modificar_begin(array('estatus' => 1),$conexion);
                if ($m['r']==true) {
                  $system->table = "asignaciones_solicitud";
                  $e = $system->eliminar_begin($r[0]->id,$conexion);
                  //var_dump($e);
                  if($e['r']==true){
                    $system->table = "asignaciones_observaciones";
                    $g = $system->guardar_begin(array('id_asignacion'=>$r[0]->id_asignacion,'accion' => 'Eliminacion Unidad '.$r[0]->placa, 'observacion' => $observacion,'desde'=>'Ministerio'),$conexion);
                    if ($g['r']==true) {
                      $system->commit($conexion);
                      $system->sql="SELECT cantidad_asignada,beneficiados,monto_total FROM asignaciones where id=".$idasignacion;
                      echo json_encode(array('msg' => true, 'msj'=>'La unidad fue eliminada de la lista exitosamente.','r'=>$system->sql()));
                    }else{
                      $system->rollback($conexion);
                      echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede guardar la observacion.'));
                    }
                    
                  }else{
                    $system->rollback($conexion);
                    echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede eliminar la unidad.')); 
                  }

                }else{
                  $system->rollback($conexion);
                  echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede modificar el detalle de la solicitud.'));  
                }
              }else{
                $system->rollback($conexion);
                echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede modificar la cuota.'));  
              }

            }else{
              $system->rollback($conexion);
              echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede aceder a la movimiento de cuotas.')); 
            }

          }else{
            $system->rollback($conexion);
            echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede modificar la asignacion.'));  
          }
        }else{
          $system->rollback($conexion);
          echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede acceder a la asignacion.'));
        }

      }else{echo json_encode(array('msg' => false, 'msj'=>'Esta persona no esta en esta lista de preasignacion.'));}


    break;

    case 'agregar_unidad_lista':
      $cantidad = base64_decode($_GET['keycan']);
      $observacion = base64_decode($_GET['obser']);
      $system->sql="SELECT detalles_solicitudes.*,unidades.placa FROM detalles_solicitudes INNER JOIN unidades on (detalles_solicitudes.id_unidad = unidades.id)  WHERE detalles_solicitudes.estatus=1 AND detalles_solicitudes.id=".base64_decode($_GET['key'])." AND detalles_solicitudes.id_rubro=".base64_decode($_GET['keyru']);
      $r = $system->sql();
     // var_dump($system->sql);
      if (count($r)>0) {
        if ($r[0]->cantidad >= $cantidad) {
          $system->sql="SELECT
            asignaciones.id AS id_asignacion,
            asignaciones.beneficiados,
            asignaciones.precio,
            asignaciones.monto_total,
            asignaciones.cantidad_asignada,
            mov_items.id AS id_mov,
            mov_items.cantidad_disponible
            FROM
            asignaciones
            INNER JOIN mov_items ON asignaciones.id_mov = mov_items.id
            WHERE
            asignaciones.id =".base64_decode($_GET['keyas']);
            $c = $system->sql();
            if(count($c)>0){
              if ($c[0]->cantidad_disponible >= $cantidad) {
                $conexion = $system->begin();

                $disponible = $c[0]->cantidad_disponible-$cantidad;
                $status = 2; 
                if($disponible == 0){$status=3;}
                $system->where = "id=".$c[0]->id_mov;
                $system->table="mov_items";
                $m = $system->modificar_begin(array('cantidad_disponible'=>$disponible,'estatus' => $status),$conexion);
                if ($m['r']==true) {
                  $beneficiados = $c[0]->beneficiados+1;
                  $monto = ($cantidad*$c[0]->precio)+$c[0]->monto_total;
                  $cantidad_asignada = $c[0]->cantidad_asignada+$cantidad;
                  $system->where = "id=".$c[0]->id_asignacion;
                  $system->table="asignaciones";
                  $m = $system->modificar_begin(array('beneficiados' => $beneficiados, 'monto_total'=>$monto,'cantidad_asignada'=>$cantidad_asignada),$conexion);
                  if ($m['r']==true) {
                    $system->table = "asignaciones_solicitud";
                    $g = $system->guardar_begin(array('id_asignacion'=>$c[0]->id_asignacion,'id_detalle' =>$r[0]->id,'cantidad'=>$r[0]->cantidad,'estatus'=>2),$conexion);
                    if($g['r']==true){
                      $system->where = "id=".$r[0]->id;
                      $system->table="detalles_solicitudes";
                      $m = $system->modificar_begin(array('estatus'=>2),$conexion);
                      if($m['r']==true){
                        $system->table = "asignaciones_observaciones";
                        $g = $system->guardar_begin(array('id_asignacion'=>$c[0]->id_asignacion,'accion' => 'Agregar la Unidad '.$r[0]->placa, 'observacion' => $observacion,'desde'=>'Ministerio'),$conexion);
                        if ($g['r']==true) {
                          $system->commit($conexion);
                          $system->sql="SELECT cantidad_asignada,beneficiados,monto_total FROM asignaciones where id=".$c[0]->id_asignacion;
                          echo json_encode(array('msg' => true, 'msj'=>'La unidad fue agregada a la lista exitosamente.','r'=>$system->sql()));
                        }else{
                          $system->rollback($conexion);
                          echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede agregar la unidad a la lista de preasignacion.'));
                        }

                      }else{
                        $system->rollback($conexion);
                        echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede modificar el detalle de la solicitud.'));  
                      }
                      
                    }else{
                      $system->rollback($conexion);
                      echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede registrar el detalle en la asignacion.'));
                    }

                  }else{
                    $system->rollback($conexion);
                    echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede modificar la asignacion.'));
                  }

                }else{
                  $system->rollback($conexion);
                  echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede modificar la cuota.'));
                }


              }else{echo json_encode(array('msg' => false, 'msj'=>'Error, no hay disponibilidad para abarcar la entrega de este item para esta unidad. Solo esta disponible '.$c[0]->cantidad_disponible.'.'));}

            }else{echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede acceder a la asignacione.'));}

        }else{echo json_encode(array('msg' => false, 'msj'=>'Error, la cantidad a entregar es mayor a la cantidad solicitada.'));}

      }else{echo json_encode(array('msg' => false, 'msj'=>'Error, el detalle de la solicitud no esta disponible.'));}

    break;

    case 'historial_asignaciones_unidades':
      $system->sql="SELECT
detalles_solicitudes.cantidad AS cant_sol,
solicitudes.fec_solicitud,
asignaciones_solicitud.cantidad AS cant_asig,
productos.descripcion AS name_producto,
presentaciones.descripcion AS name_presentacion,
asignaciones.precio,
estatus.descripcion AS name_estatus
FROM
detalles_solicitudes
INNER JOIN asignaciones_solicitud ON asignaciones_solicitud.id_detalle = detalles_solicitudes.id
INNER JOIN solicitudes ON detalles_solicitudes.id_solicitud = solicitudes.id
INNER JOIN asignaciones ON asignaciones_solicitud.id_asignacion = asignaciones.id
INNER JOIN productos ON asignaciones.id_producto = productos.id
INNER JOIN presentaciones ON productos.presentacion = presentaciones.id
INNER JOIN estatus ON detalles_solicitudes.estatus = estatus.id
WHERE
(detalles_solicitudes.estatus = 5 OR
detalles_solicitudes.estatus = 6) AND
detalles_solicitudes.id_user = ".base64_decode($_GET['key'])." AND
detalles_solicitudes.id_rubro = ".base64_decode($_GET['keyru'])."
ORDER BY
detalles_solicitudes.fec_aprobado DESC";
      $r = $system->sql();
      if (count($r)>0) {
        echo json_encode(array('msg' => true, 'msj'=>'Mostrando Historial de vehiculo.'.$r[0]->placa, 'r'=>$r ));
      }else{
        echo json_encode(array('msg' => false, 'msj'=>'No hay datos historicos de asignaciones.'));
      }
    break;

    case 'ver_producto':
      $system->sql="SELECT
productos.codigo,
productos.descripcion,
productos.marca,
productos.modelo,
productos.precio,
rubros_sub.descripcion AS subtipo,
rubros.descripcion AS tipo
FROM
productos
INNER JOIN rubros ON productos.tipo = rubros.id
INNER JOIN rubros_sub ON productos.subtipo = rubros_sub.id
WHERE
productos.id =".base64_decode($_GET['key']);
      $r = $system->sql();
      if (count($r)>0) {
        echo json_encode(array('msg' => true, 'msj'=>'Mostrando producto.', 'r'=>$r ));
      }else{
        echo json_encode(array('msg' => false, 'msj'=>'Imposible acceder a la informacion del producto.'));
      }
    break;

    case 'ver_acopio':
      $system->sql="SELECT
almacenes_nivel.descripcion AS name_nivel,
almacenes.referencia,
almacenes.codigo,
almacenes.nombre,
almacenes.direccion,
almacenes.telefono,
almacenes.tel_contac,
estados.estado AS name_estado,
rubros.descripcion AS name_rubro
FROM
almacenes
INNER JOIN almacenes_nivel ON almacenes.nivel = almacenes_nivel.id
INNER JOIN estados ON almacenes.estado = estados.id_estado
INNER JOIN rubros ON almacenes.id_rubro = rubros.id
WHERE
almacenes.id =".base64_decode($_GET['key']);
      $r = $system->sql();
      if (count($r)>0) {
        echo json_encode(array('msg' => true, 'msj'=>'Mostrando centro de acopio.', 'r'=>$r ));
      }else{
        echo json_encode(array('msg' => false, 'msj'=>'imposible acceder a la informacion del centro de acopio.'));
      }
    break;

    case 'ver_cuota':
      $system->sql="SELECT
mov_items.cantidad_asig,
mov_items.cantidad_disponible,
mov_items.fec_reg,
estatus.descripcion AS name_estatus,
almacenes.nombre AS name_destino,
productos.descripcion AS name_producto,
estados.estado,
mov_items.id
FROM
mov_items
INNER JOIN estatus ON mov_items.estatus = estatus.id
INNER JOIN almacenes ON mov_items.destino = almacenes.id
INNER JOIN productos ON mov_items.producto = productos.id
INNER JOIN estados ON mov_items.estado = estados.id_estado
WHERE
mov_items.id=".base64_decode($_GET['key']);
      $r = $system->sql();
      if (count($r)>0) {
        echo json_encode(array('msg' => true, 'msj'=>'Mostrando cuota asignada.', 'r'=>$r ));
      }else{
        echo json_encode(array('msg' => false, 'msj'=>'Imposible accder a la informacion de la cuota asignada.'));
      }
    break;

    case 'ver_observaciones':
      $system->sql="SELECT
asignaciones_observaciones.observacion,
asignaciones_observaciones.accion,
asignaciones_observaciones.desde
FROM
asignaciones_observaciones
WHERE
asignaciones_observaciones.id_asignacion = ".base64_decode($_GET['key'])."
ORDER BY
asignaciones_observaciones.id DESC";
      $r = $system->sql();
      if (count($r)>0) {
        echo json_encode(array('msg' => true, 'msj'=>'Mostrando observaciones.', 'r'=>$r ));
      }else{
        echo json_encode(array('msg' => false, 'msj'=>'Imposible accder a las observaciones.'));
      }
    break;

    case 'ver_almacenes_descuentos':
      $system->sql="SELECT
mov_items_almacenes.cantidad,
almacenes.nombre,
almacenes_nivel.descripcion AS name_nivel,
almacenes.direccion,
estados.estado,
almacenes.telefono,
almacenes.tel_contac,
rubros.descripcion AS name_rubro,
inventario.id AS id_inventario,
mov_items_almacenes.id AS id_mov_almacen,
almacenes.referencia,
almacenes.codigo
FROM
mov_items_almacenes
INNER JOIN inventario ON mov_items_almacenes.id_inventario = inventario.id
INNER JOIN almacenes ON inventario.almacen = almacenes.id
INNER JOIN almacenes_nivel ON almacenes.nivel = almacenes_nivel.id
INNER JOIN estados ON almacenes.estado = estados.id_estado
INNER JOIN rubros ON almacenes.id_rubro = rubros.id
WHERE
mov_items_almacenes.id_mov =".base64_decode($_GET['key']);
      $r = $system->sql();
      if (count($r)>0) {
        echo json_encode(array('msg' => true, 'msj'=>'Mostrando almacenes.', 'r'=>$r ));
      }else{
        echo json_encode(array('msg' => false, 'msj'=>'Imposible acceder a la informacion de los almacenes.'));
      }
    break;


    case 'cancelar_preasignacion':
      $system->sql="SELECT * FROM asignaciones WHERE estatus = 2 and id = ".base64_decode($_GET['key']);
      $r = $system->sql();
      if (count($r)>0) {
        $conexion = $system->begin();
        $system->where = "id=".$r[0]->id;
        $system->table = "asignaciones";
        $m = $system->modificar_begin(array('estatus' => 4) ,$conexion);
        if ($m['r']==true) {
          $system->table = "asignaciones_observaciones";
          $m = $system->guardar_begin(array('desde' => 'Ministerio', 'accion' => 'Preasignacion rechazada', 'observacion' => base64_decode($_GET['observacion']),'id_asignacion'=>$r[0]->id) ,$conexion);
          if ($m['r']==true) {
            $system->commit($conexion);
            echo json_encode(array('msg' => true, 'msj'=>'La asignacion fue cancelada exitosamente.','url'=>$_SESSION['base_url1'].'app/admin/asignaciones/confirmar_asignaciones.php')); 
          }else{
            $system->rollback($conexion);
            echo json_encode(array('msg' => false, 'msj'=>'Error al guardar la observacion de la cancelacion.'));  
          }
        }else{
          $system->rollback($conexion);
          echo json_encode(array('msg' => false, 'msj'=>'Error al cancelar la asignacion.'));
        }
      }else{echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede acceder a la informacion de la asignacion.'));}

    break;

    case 'confirmar_modificacion_asignacion':
      $system->sql="SELECT * FROM asignaciones WHERE estatus = 4 and id = ".base64_decode($_GET['key']);
      $r = $system->sql();
      if (count($r)>0) {
        $system->where = "id=".$r[0]->id;
        $system->table = "asignaciones";
        $m = $system->modificar(array('estatus' => 2));
        if($m['r']==true){
          echo json_encode(array('msg' => false, 'msj'=>'Modificacion exitosa.', 'url'=>$_SESSION['base_url1'].'app/admin/asignaciones/confirmar_asignaciones.php'));
        }else{
          echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede mkodificar la preasignacion.'));
        }
        
      }else{echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede acceder a la informacion de la asignacion.'));}
    break;

}

function crear_serial($peticion){
  $system = new System;
    //selecionamos el codigo
    $system->sql="select ".$peticion." from seriales";
    $con=$system->sql();
    if(count($con)>0){
        $cod = $con[0]->asignacion+1;
        $newserial = str_pad($cod, 9, "0", STR_PAD_LEFT);
        $system->where = "id=1";
        $system->table="seriales";
        $re = $system->modificar(array('asignacion' => $cod));
        if($re['r']==true){
            unset($cod,$re);
            return array('r'=>true,'s'=>$newserial);
        }else{return array('r'=>false,'m'=>'Error! no se pudo actualizar el serial.');}
    }else{return array('r'=>false,'m'=>'Error! no se puede acceder a los seriales.');}
    
}

function modificar_detalles_solicitud($system,$r,$len,$conexion){
  for ($i=0; $i < $len; $i++) { 
    $system->table="detalles_solicitudes";
    $system->where = "id=".$r[$i]->id_detalle;
    $m = $system->modificar_begin(array('estatus' => 5),$conexion);
    if($m['r']==false){
      return false;
    }
  }
  return true;
}


?>