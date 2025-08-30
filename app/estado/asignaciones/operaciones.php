<?
	if(!isset($_SESSION)){session_start();}

	include_once $_SESSION['base_url'].'/class/system.php';
	$system = new System;

	switch ($_REQUEST['action']) {

    case 'historial_deAsignaciones':
      $e = base64_decode($_GET['e']);
      $r = base64_decode($_GET['r']);
      $sel = "";
      if($r == 1){
        $sel = " cauchos.neumatico as producto_sol ";
        $inner = " INNER JOIN cauchos ON mov_items.id_producto_sol = cauchos.id ";
      }
      elseif ($r == 2) {
        $sel = " lubricantes.lubricante as producto_sol ";
        $inner=" INNER JOIN lubricantes ON mov_items.id_producto_sol = lubricantes.id ";
      }
      elseif ($r == 3) {
        $sel = " acumuladores.acumulador as producto_sol ";
        $inner=" INNER JOIN acumuladores ON mov_items.id_producto_sol = acumuladores.id ";
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
        mov_items.estado = ".$e."
        ORDER BY
        mov_items.fec_reg ASC";

        $r = $system->sql();
        if (count($r)) {
          echo json_encode(array('msg' => true,'msj'=>'Mostrando Historial','r'=>$r));
        }else{
          echo json_encode(array('msg' => false, 'msj'=>'Esté estado no tiene ninguna asignacion de items en su historia'));
        }
    break;

    case 'asignar_items_estado':
      $asignacion = array(
        'estado' => base64_decode($_GET['estado']),
        'destino' => base64_decode($_GET['destino']),
        'id_producto_sol' => base64_decode($_GET['producto_sol']),
        'producto' => base64_decode($_GET['producto_asig']),
        'id_rubro' => base64_decode($_GET['rubro']),
        'cantidad_asig' => base64_decode($_GET['cantidad_asig']),
        'cantidad_solicitudes' => base64_decode($_GET['cantidad_sol']),
        'fec_reg' => date('Ymd'),
        'estatus' => '2',
      );
      $almacenes=$_GET['almacenes'];
      $system->table="mov_items";
      $r = $system->guardar($asignacion);
      if($r['r']==true){
        $id_asig = $r['id'];
        //modificamos el inventario del destino
        $system->where = "id=".$asignacion['destino'];
        $system->table="inventario";
        $system->modificar(array('asignado' => $asignacion['cantidad_asig']));
        //empezamos con el descuento de items de los almacenes seleccionados        
        $cant_alm = count($almacenes);
        $system->table="mov_items_almacenes";
        $mensajes= array('msg' => true, 'msj' => 'Guardado asignacion con exito.');
        for ($i=0; $i < $cant_alm; $i++) { 
          $r = $system->guardar_multiple(array(
            'id_mov' => $id_asig,
            'id_almacen' => $almacenes[$i][0],
            'cantidad' => $almacenes[$i][1], 
          ));
          //efecto en inventario
          if ($r['r']==true) {
            $system->where = "id=".$almacenes[$i][0];
            $system->table="inventario";
            $r = $system->modificar_Operacional(array('disponible' => 'disponible-'.$almacenes[$i][1],'comprometido'=>'comprometido+'.$almacenes[$i][1]));
            //var_dump($r['sql']);
            if ($r['r']==false) {
              $mensajes = array('msg' => false, 'msj' => 'Error al registrar modificaciones en el inventario.');
            }
          }else{$mensajes = array('msg' => false, 'msj' => 'Error al registrar una relacion de descuentos de los almacenes.');}

          if($r['r']==false) echo json_encode(array('msg' => false, 'msj'=>'Error al registrar almacenes a descontar.')); ;
        }
        echo json_encode($mensajes);
      }else{echo json_encode(array('msg' => true,'msj' => 'Error al registrar la asignacion' ));}
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

		case 'traer_sol_x_item':
      $item=trim($_GET['item']);
      unset($_GET['action'],$_GET['item']);
      switch ($item) {
        case '1'://neumatico
          $system->sql="select b.descripcion,b.id as rubro,c.neumatico as item,count(*) as solicitudes,SUM(a.cantidad) as cantidad  
            from detalles_solicitudes as a
            inner join rubros as b on (a.id_rubro=b.id)
            inner join unidades as u on (a.id_unidad=u.id)
            inner join cauchos as c on (u.num_neu=c.id)
            INNER JOIN solicitudes ON a.id_solicitud = solicitudes.id
            where b.id=".$item." and a.estatus=1 and
            solicitudes.estado = ".$_SESSION['edo']."
            group by(c.neumatico)";
            $con = $system->sql();
            if ($con>0) {
              echo json_encode(array('msj' => true,'msg'=>'Mostrando solicitudes de Neumaticos', 'con'=>$con));
              unset($con,$item);
            }else{
              echo json_encode(array('msj' => false, 'msg'=>'No hay solicitudes de Neumaticos disponibles'));  
              unset($con,$item);
            }
        break;

        case '2'://lubricante
          $system->sql="select b.descripcion,b.id as rubro,l.lubricante as item,count(*) as solicitudes,SUM(a.cantidad) as cantidad 
            from detalles_solicitudes as a
            inner join rubros as b on (a.id_rubro=b.id)
            inner join unidades as u on (a.id_unidad=u.id)
            inner join lubricantes as l on (u.tipo_lub=l.id)
            INNER JOIN solicitudes ON a.id_solicitud = solicitudes.id
            where b.id=".$item." and a.estatus=1 and
            solicitudes.estado = ".$_SESSION['edo']."
            group by (l.lubricante)";
            $con = $system->sql();
            if ($con>0) {
              echo json_encode(array('msj' => true,'msg'=>'Mostrando solicitudes de Lubricantes', 'con'=>$con));
              unset($con,$item);
            }else{
              echo json_encode(array('msj' => false, 'msg'=>'No hay solicitudes de Lubricantes disponibles'));  
              unset($con,$item);
            }
        break;
        
        case '3'://Acumulador(Bateria)
          $system->sql="select b.descripcion,b.id as rubro,d.acumulador as item,count(*) as solicitudes,SUM(a.cantidad) as cantidad 
            from detalles_solicitudes as a
            inner join rubros as b on (a.id_rubro=b.id)
            inner join unidades as u on (a.id_unidad=u.id)
            inner join acumuladores as d on (u.acumulador=d.id)
            INNER JOIN solicitudes ON a.id_solicitud = solicitudes.id
            where b.id=".$item." and a.estatus=1 and
            solicitudes.estado = ".$_SESSION['edo']."
            group by(u.acumulador)";
            $con = $system->sql();
            if ($con>0) {
              echo json_encode(array('msj' => true,'msg'=>'Mostrando solicitudes de Acumuladores', 'con'=>$con));
              unset($con,$item);
            }else{
              echo json_encode(array('msj' => false, 'msg'=>'No hay solicitudes de Acumuladores disponibles'));  
              unset($con,$item);
            }
        break;

        default:
          echo json_encode(array('msj' => false, 'msg'=>'Accion invalida....'));
        break;
      }
    break;

    //muestra los detalles de la asignacion para poder asignar
    case 'step_one':
      $tipo = base64_decode(trim($_GET['tipo']));
      unset($_GET['action'],$_GET['tipo']);

      //capturamos los recursos disponibles de los almacenes a nivel nacional y estadal
      $system->sql="select *,c.codigo as cod_almacen,sum(disponible) as total_disponible,a.id as id_producto,b.id as id_inventario from productos as a 
        inner join inventario as b on (a.id = b.producto) 
        inner join almacenes as c on (b.almacen=c.id)
        where a.tipo=".$tipo." and a.estatus=7 and c.nivel = 1
        GROUP By (b.producto) order by (a.descripcion)";
      $consulta = $system->sql(); 
      if (count($consulta)>0) {
        //capturamos los almacenes sin agrupar
        echo json_encode(array('msg' => true, 'msj' => 'Items disponibles cargados...', 'r' => $consulta));
        unset($consulta);
      }else{
        echo json_encode(array('msg' => false, 'msj' => 'No hay nada disponible en el inventario'));
      }
    break;

    //mostramos modificacion de miembros
    case 'step_two_unico':
      $sol = base64_decode(trim($_GET['solicitud']));
      $e = base64_decode(trim($_GET['estado']));
      $m = base64_decode(trim($_GET['municipio']));
      $l = base64_decode(trim($_GET['linea']));
      $producto = base64_decode(trim($_GET['producto']));
      $producto_sol = base64_decode(trim($_GET['producto_sol']));
      $tipo = base64_decode(trim($_GET['tipo']));
      unset($_GET['action'],$_GET['solicitud'],$_GET['producto'],$_GET['tipo'],$_GET['producto_sol']);
      
      if ($sol == 0) {
        //seleccion multiple
        $condicion = "s.estado='".$e."'";
        if($m > 0){ $condicion.=" and s.municipio='".$m."'"; }
          if ($l > 0) { $condicion.=" and s.cod_linea='".$l."'"; }

          //var_dump($condicion);

        $complete = '';
        switch ($tipo) {
          case '1'://neumatico
            $complete = "inner join cauchos as x on (u.num_neu=x.id)
                    where ".$condicion." and a.id_rubro='".$tipo."' and x.id='".$producto_sol."'
                    and a.estatus = 1 order by(a.id) ASC";
          break;
          case '2'://lubricante
            $complete = "inner join lubricantes as x on (u.tipo_lub=x.id)
                    where ".$condicion." and a.id_rubro='".$tipo."' and x.id='".$producto_sol."'
                    and a.estatus = 1 order by(a.id) ASC";
          break;
          case '3'://acumulador
            $complete = "inner join acumuladores as x on (u.acumulador=x.id)
                    where ".$condicion." and a.id_rubro='".$tipo."' and x.id='".$producto_sol."'
                    and a.estatus = 1 order by(a.id) ASC";
          break;
          default:
            echo json_encode(array('msg' => false, 'msj' => 'Error! opcion incorrecta'));
          break;
        }

        $system->sql="select a.id as id_detalle,a.id_solicitud,s.fec_solicitud,a.cantidad,b.id as id_user,b.cedula,b.nombre,b.apellido,u.placa,e.estado,e.id_estado,m.id_municipio,m.municipio,b.activo
        from detalles_solicitudes as a
        inner join solicitudes as s on (a.id_solicitud=s.id)
        inner join users as b on (a.id_user=b.id)
        inner join unidades as u on (a.id_unidad=u.id)
        inner join estados as e on (b.estado=e.id_estado)
        INNER join municipios as m on (b.municipio=m.id_municipio and b.estado=m.id_estado) ".$complete;

      }elseif ($sol > 0) {
        //condicion de una seleccion individual
        $system->sql="select a.id as id_detalle,a.id_solicitud,s.fec_solicitud,a.cantidad,b.id as id_user,b.cedula,b.nombre,b.apellido,u.placa,e.estado,e.id_estado,m.id_municipio,m.municipio,b.activo
        from detalles_solicitudes as a
        inner join solicitudes as s on (a.id_solicitud=s.id)
        inner join users as b on (a.id_user=b.id)
        inner join unidades as u on (a.id_unidad=u.id)
        inner join estados as e on (b.estado=e.id_estado)
        INNER join municipios as m on (b.municipio=m.id_municipio and b.estado=m.id_estado)
        where a.estatus = 1 and a.id=".$sol;

      }else{echo json_encode(array('msg' => false, 'msj' => 'Error! solicitud no es un valor valido'));}

      //validamos la consulta
      $consulta = $system->sql();
      if (count($consulta)>0) {
        //selecionamos los almacenes con disposicion en esl estado
        $system->sql="select a.id as id_producto,b.id as id_inventario,e.estado as estadod,n.descripcion as niveld,c.nombre,b.disponible
        from productos as a 
        inner join inventario as b on (a.id = b.producto) 
        inner join almacenes as c on (b.almacen=c.id)
        inner join almacenes_nivel as n on (c.nivel=n.id)
        inner JOIN estados as e on (c.estado=e.id_estado)
        where a.id=".$producto." and a.estatus=7 and (c.nivel=1 or (c.nivel=2 and c.estado=".$e."))";
        $consulta2 = $system->sql();

        if (count($consulta2)>0) {//validamos una buena busqueda

          $system->sql="SELECT a.*,b.descripcion,r.descripcion as rubro FROM `almacenes` as a
inner join almacenes_nivel as b on (a.nivel=b.id) 
inner join rubros as r on (a.id_rubro=r.id)
where a.estatus = 7 and a.estado=".$e." order by a.id_rubro, a.nivel asc ";
            $consulta3 = $system->sql();
            if (count($consulta3)>0) {//validamos los almacenes que pueden ser deposito de retiro dentro del estado de asignacion 
              echo json_encode(array('msg' => true, 'msj' => 'Cargado transportista','r' => $consulta,'a'=>$system->sql(),'rsa' => $consulta2));
              unset($consulta,$consulta3,$consulta2);

            }else{echo json_encode(array('msg' => false, 'msj' => 'No existe ningun almacen registrado para disponer como centro de acopio para la asignación del estado.',));}

        }else{echo json_encode(array('msg' => false, 'msj' => 'No se puede cargar los almacenes con disposicion de inventario.',));unset($consulta2,$consulta);}
          
        }else{echo json_encode(array('msg' => false, 'msj' => 'No se pudo cargar o ya no existe disponibilidad de beneficiario.',));unset($consulta);}

    break;

    case 'asignar':
    //recibimos los datos
    $inventarios=$_GET['inventarios'];
    $deposito=trim($_GET['deposito']);
    $solicitud=trim($_GET['solicitud']);
    $entregar=trim($_GET['entregar']);
    $personas = $_GET['personas'];
    $producto =trim( $_GET['producto']);
    $producto_solicitado = trim($_GET['producto_solicitado']);
    $cantidad_solicitada = trim($_GET['cantidad_solicitada']);
    unset($_GET['action'],$_GET['inventarios'],$_GET['deposito'],$_GET['solicitud'],$_GET['personas'],$_GET['producto'],$_GET['producto_solicitado'],$_GET['cantidad_solicitada']);

    //validamos la entrada de los datos
    $r = validar_datosAsignacion(array($inventarios,$deposito,$solicitud,$personas,$producto,$producto_solicitado,$cantidad_solicitada));
    if($r['r']==true){

        $r = crear_serial('asignacion');
        if($r['r']==true){
            //verificamos que el serial no este registrado dentro de las asignaciones
            $system->sql="select serial from asignaciones where serial =".$r['s'];
            if(count($system->sql())==0){

                //creamos la asignacion
                $system->table="asignaciones";
                $asig = array(
                            'serial' => $r['s'],
                            'almacen_destino' => $deposito,
                            'beneficiados' => count($personas),
                            'precio' => 0,
                            'monto_total' => 0,
                            'id_producto' => $producto,
                            'id_producto_solicitado' => $producto_solicitado,
                            'cantidad_solicitud' => $cantidad_solicitada,
                            'cantidad_asignada' => $solicitud,
                            'fec_reg' => date("Y-m-d"),
                            'estatus' => 8,
                );
                $r = $system->guardar($asig);//asig['id'] => id de la asignacion creada 
                //unset($serial,$asig);

                if($r['r']==true){
                    $asignacion = $r['id'];//guardamos el id de la asignacion insertada

                    //consultamos la disponibilidad de los productos en el almacen
                    $dis = consultar_disponibilidad($inventarios);
                    if($dis['r']==true){

                        //validamos que sea viable aun disponer
                        if($dis['d']>$solicitud){
                            $alm = $dis['alm'];
                            unset($dis);//eliminamos datos de la disposicion
                          
                            //empezamos con la transaccion
                            $conexion = $system->begin();

                            //gestionamos los movimiento de los inventarios
                            $r = gestionar_almacenAsignacion($asignacion,$solicitud,$alm,$conexion);
                            if ($r['r'] == true) {
                                
                               $r = gestionar_SolicitudAsignacion($asignacion,$personas,$entregar,$conexion);
                               if ($r['r']==true) {
                                    //modificamos la asignacion para activarlo 
                                    $system->where = "id=".$asignacion;
                                    $system->table="asignaciones";
                                    $r = $system->modificar_begin(array('estatus' => 2,'beneficiados'=>count($personas)), $conexion);
                                    if ($r['r']==true) {
                                        //guardamos todos los cambios, porfin
                                        $system->commit($conexion);
                                        echo json_encode(array('msg' => true,'msj' =>'Registro exitoso de la asignacion, puede pasar a la confirmacion de la misma en el menus de asignacion', 'asignacion' => $asignacion, 'beneficiados' => count($personas)));
                                        
                                    }else{eliminar_asignacion($asignacion);$system->rollback($conexion);echo json_encode(array('msg' => false, 'msj' => $r['m']));}

                               }else{eliminar_asignacion($asignacion);$system->rollback($conexion);echo json_encode(array('msg' => false, 'msj' => $r['m']));}

                            }else{$system->rollback($conexion);echo json_encode(array('msg' => false, 'msj' => $r['m']));}

                        }else{eliminar_asignacion($asignacion);echo json_encode(array('msg' => false, 'msj' => 'Error! no existe disponibilidad dentro de los almacenes para cubrir la asignacion.'));}

                    }else{echo json_encode(array('msg' => false, 'msj' => $r['m']));}

                }else{echo json_encode(array('msg' => false, 'msj' => 'Error! no se pudo registrar la asignacion.'));}

            }else{echo json_encode(array('msg' => false, 'msj' => 'Error! el serial se encuetra registrado en las asignaciones.'));}
        
        }else{ echo json_encode(array('msg' => false, 'msj' => $r['m']));}


    }else{ echo json_encode(array('msg' => false, 'msj' => $r['m']));}

    break;

    //asignamos un valor a los productos de una asignacion
    case 'step_three':
      $precio=base64_decode(trim($_GET['precio']));
      $total=base64_decode(trim($_GET['total']));
      $asignacion=base64_decode(trim($_GET['asignacion']));
      unset($_GET['precio'],$_GET['total'],$_GET['asignacion'],$_GET['action']);

      if ($precio!=0 && $total!=0 && $asignacion!='') {
        //verificamos la existencia de la asignacion
        $system->sql="select * from asignaciones where id=".$asignacion;
        $con = $system->sql();
        if (count($con)>0) {
          $system->where = "id=".$asignacion;
          $system->table="asignaciones";
          $con = $system->modificar(array('monto_total' => $total,'precio'=>$precio));
          if ($con['r']==true) {
            if ($_GET['m']==2) {
              $system->sql="select a.id,a.serial,e.estado,m.municipio,b.nombre as acopio,d.descripcion as tipo,c.descripcion as producto,a.cantidad_solicitud as asignado,a.fec_reg,f.descripcion as estatus,a.precio,a.monto_total
from asignaciones as a 
inner join almacenes as b on (a.almacen_destino=b.id)
inner join productos as c on (a.id_producto=c.id)
inner join rubros as d on (c.tipo=d.id)
inner join estados as e on (b.estado=e.id_estado)
inner join municipios as m on (m.id_municipio=b.municipio and m.id_estado=b.estado)
inner join estatus as f on (a.estatus=f.id)
where a.estatus = '2'";
              echo json_encode(array('msg' => true, 'msj' => 'Precio incluido para los productos de la asignacion.','r'=>$system->sql()));
            }else{
              echo json_encode(array('msg' => true, 'msj' => 'Precio incluido para los productos de la asignacion.'));
            }  
          }
        }else{
          echo json_encode(array('msg' => false, 'msj' => 'No existe la asignación.'));
        } 
      }else{
        echo json_encode(array('msg' => false, 'msj' => 'Error al suministrar los datos.'));
      }

    break;

    case 'traer_asignacion':
      unset($_GET['action']);
      //buscamos la asignacion conjunto el centro de acopio
      $system->sql="SELECT
        almacenes.codigo,
        almacenes.nombre,
        almacenes.direccion,
        almacenes.telefono,
        asignaciones.serial,
        asignaciones.beneficiados,
        asignaciones.precio,
        asignaciones.monto_total,
        asignaciones.cantidad_solicitud,
        asignaciones.fec_reg,
        estados.estado,
        municipios.municipio,
        parroquias.parroquia
        FROM
        asignaciones
        INNER JOIN almacenes ON asignaciones.almacen_destino = almacenes.id
        INNER JOIN estados ON almacenes.estado = estados.id
        INNER JOIN municipios ON almacenes.municipio = municipios.id_municipio AND estados.id_estado = municipios.id_estado
        INNER JOIN parroquias ON almacenes.parroquia = parroquias.id_parroquia AND estados.id_estado = parroquias.id_estado AND municipios.id_municipio = parroquias.id_municipio
        WHERE
        asignaciones.estatus = '2' AND
        asignaciones.id = '7'
        ";
      $asig = $system->sql();
      if (count($asig)>0) {
        //buscamos los almacenes de origen de la asignaciones
        $system->sql="SELECT
          almacenes.id,
          asignaciones_almacen.post_cantidad,
          asignaciones_almacen.previa_cantidad,
          almacenes.nivel,
          almacenes.codigo,
          almacenes.nombre,
          almacenes.direccion,
          almacenes.telefono,
          almacenes_nivel.descripcion,
          estados.estado,
          municipios.municipio,
          parroquias.parroquia
          FROM
          asignaciones_almacen
          INNER JOIN almacenes ON asignaciones_almacen.id_almacen = almacenes.id
          INNER JOIN almacenes_nivel ON almacenes.nivel = almacenes_nivel.id
          INNER JOIN estados ON almacenes.estado = estados.id_estado
          INNER JOIN municipios ON almacenes.municipio = municipios.id_municipio AND municipios.id_estado = estados.id_estado
          INNER JOIN parroquias ON almacenes.parroquia = parroquias.id_parroquia AND parroquias.id_estado = estados.id_estado AND parroquias.id_municipio = municipios.id_municipio
          WHERE
          asignaciones_almacen.id_asignacion = '7'
          ";
        $alm = $system->sql();
        if (count($alm)>0) {
          //buscamos las personas beneficiadas en la asignacion
          $system->sql="SELECT
          asignaciones_solicitud.cantidad AS cantidad_asignada,
          detalles_solicitudes.cantidad AS cantidad_solicitada,
          users.cedula,
          users.nombre,
          users.apellido,
          users.telefono,
          users.email,
          users.foto,
          users.nombre_linea,
          users.rif
          FROM
          asignaciones_solicitud
          INNER JOIN detalles_solicitudes ON asignaciones_solicitud.id_detalle = detalles_solicitudes.id
          INNER JOIN users ON detalles_solicitudes.id_user = users.id
          WHERE
          asignaciones_solicitud.id_asignacion = '7'
          ORDER BY
          detalles_solicitudes.id ASC
          ";
          $per = $system->sql();
          if (count($per)>0) {
            echo json_encode(array('msg' => true, 'msj'=>'Mostrando asignación '.$asig[0]->serial,'a'=>$asig,'alm'=>$alm,'p'=>$per));
            unset($asig,$per,$alm);  
          }else{
            echo json_encode(array('msg' => false, 'msj'=>'Error no se encontraron beneficiados en esta solicitud.'));
          }       
        }else{
          echo json_encode(array('msg' => false, 'msj'=>'Error al traer almacenes de despacho.'));
        }
      }else{
        echo json_encode(array('msg' => false, 'msj'=>'La asignación no esta en sistema o ya fue confirmada'));
      }

    break;

  }
  
  /************FUNCIONES DE LA ASIGNACION******************** */
  function gestionar_SolicitudAsignacion($asignacion,$personas,$entregar,$conexion){
    $system = new System;
    $can_personas = count($personas);//capturamos la cantidad de personas
    $system->table="asignaciones_solicitud";
    //validamos que entregar no sea una cantidad general
    if($entregar == 0){
      for($x=0;$x<$can_personas;$x++){
        $system->table="asignaciones_solicitud";
        $r = $system->guardar_multiple_begin(array('id_asignacion' => $asignacion, 'id_detalle' => $personas[$x][0], 'cantidad' => $personas[$x][1] ),$conexion);
        //var_dump($r['sql']);
        if ($r['r']==true) {
            //modificamos la lista de detalles
            $system->where = "id=".$personas[$x][0];
            $system->table="detalles_solicitudes";
            $r = $system->modificar_begin(array('estatus' => 2), $conexion);

            if ($r['r']==false) {return array('r'=>false,'m'=>'Error! no se puede modificar el detallado de la solicitud');}
        
        }else{return array('r'=>false,'m'=>'Error! no se puede registrar la relacion asignacion con la solicitud');}
      }

    }else{
      for($x=0;$x<$can_personas;$x++){
        $r = $system->guardar_begin(array('id_asignacion' => $asignacion, 'id_detalle' => $personas[$x][0], 'cantidad' => $entregar),$conexion);
        if ($r['r']==true) {
            //modificamos la lista de detalles
            $system->where = "id=".$personas[$x][0];
            $system->table="detalles_solicitudes";
            $r = $system->modificar_begin(array('estatus' => 2), $conexion);

            if ($r['r']==false) {return array('r'=>false,'m'=>'Error! no se puede modificar el detallado de la solicitud');}
        
        }else{return array('r'=>false,'m'=>'Error! no se puede registrar la relacion asignacion con la solicitud');}
      }
    }
    
    return array('r'=>true,'m'=>'Exito! registrado correctamente la relacion asignacion con el detallado de las solicitud.');
}

function gestionar_almacenAsignacion($asignacion,$cant_asig,$alm,$conexion){
  $system = new System;
    $cal = 0;
    $can_alm = count($alm);
    //empezamos el ciclo a realizar los cambios dependiendo de la cantidad de los almacennes
    for ($x=0; $x < $can_alm; $x++) { 
        
        //primera condicion: Impone se el primer o unico almacen puede realizar la asignacion completa de la solicitud establecida.
        if ($cant_asig!=0 and $cant_asig<=$alm[$x]->disponible) {
            $cal = $alm[$x]->disponible-$cant_asig;//restamos lo asignado con lo comprommetido
            $alm[$x]->comprometido+=$cant_asig;//sumamos lo comprometido con lo asignado
            $system->where = "id=".$alm[$x]->id;
            $system->table="inventario";
            $re = $system->modificar_begin(array('disponible' => $cal,'comprometido'=>$alm[$x]->comprometido), $conexion);
                if ($re['r']==true) {
                    $system->table="asignaciones_almacen";
                    $re = $system->guardar_begin(
                        array(
                            'id_asignacion' => $asignacion, 
                            'id_inventario' => $alm[$x]->id, 
                            'id_almacen' => $alm[$x]->almacen,
                            'cantidad_retiro' => $cant_asig,
                            'post_cantidad' => $cal,
                            'previa_cantidad' => $alm[$x]->disponible,
                            'estatus' => 8,
                            )
                            ,$conexion
                        );
                    if ($re['r'] == true) {
                      $cant_asig=0;//igualamos la cantidad asignado por que esta cubiertas las asignaciones
                    }else{
                       return array('r'=>false,/*'m'=>'Error 1000! no se puede guardar la relacion asignaciones e inventarios.',*/'m'=>array(
                            'id_asignacion' => $asignacion, 
                            'id_inventario' => $alm[$x]->id, 
                            'id_almacen' => $alm[$x]->almacen,
                            'cantidad_retiro' => $cant_asig,
                            'post_cantidad' => $cal,
                            'previa_cantidad' => $alm[$x]->disponible,
                            'estatus' => 8,
                            ));
                    }
                }else{
                    return array('r'=>false,'m'=>'Error 2000! no se puede modificar inventario.');
                }
            }

            //segunda condicion las asignaciones no se cubren completamente con un solo almacen, por ende necesitan varios modificaciones de diferentes inventario
            if ($cant_asig!=0 and $cant_asig>$alm[$x]->disponible) {
                $cal = $cant_asig-$alm[$x]->disponible;//restamos lo disponible con lo asignado
                $alm[$x]->comprometido+=$alm[$x]->disponible;//sumamos la disponibilidad total con lo que este comprometido en el inventario
                $system->where = "id=".$alm[$x]->id;
                $system->table="inventario";
                $re = $system->modificar_begin(array('disponible' => 0,'comprometido' => $alm[$x]->comprometido), $conexion);
                if ($re['r']==true) {
                    $system->table="asignaciones_almacen";
                    $re = guardar_begin(
                      array(
                          'id_asignacion' => $asignacion, 
                          'id_inventario' => $alm[$x]->id, 
                          'id_almacen' => $alm[$x]->almacen,
                          'cantidad_retiro' => $alm[$x]->disponible,
                          'post_cantidad' => 0,
                          'previa_cantidad' => $alm[$x]->disponible,
                          'estatus' => 8
                        ),$conexion);

                    if ($re['r'] == true) {
                        $cant_asig=$cal;//igualamos la cantidad asignado a lo restante con la operacion
                    }else{
                        return array('r'=>false,'m'=>'Error 3000! no se puede guardar la relacion asignaciones e inventarios.');
                    }
                }else{
                    return array('r'=>false,'m'=>'Error 4000! no se puede guardar la relacion asignaciones e inventarios.');
                }
            }
            //evaluamos dentro del ciclo si la asignacion pudo haberse completado
            if ($cant_asig == 0) {
                return array('r'=>true,'m'=>'Exito al resgitrar las relaciones asignacion y almacenes');
            }
        }
        //evaluamos al finalizar todo
        if ($cant_asig == 0) {
            return array('r'=>true,'m'=>'Exito al resgitrar las relaciones asignacion y almacenes');
        }else{
            return array('r'=>false,'m'=>'Error 5000! no se puede guardar la relacion asignaciones e inventarios.');
        }
    }

function eliminar_asignacion($id){
  $system = new System;
    $system->table = "asignaciones";
    $e = $system->eliminar($id);
    if($e['r']==true){
        array('r'=>true,'m'=>'Asignacion eliminada con exito');
    }else{return array('r'=>false,'m'=>'Error! no se pudo deshacer los cambiaos previsto al error.');}
}


function consultar_disponibilidad($inventarios){
  $system = new System;
    //armamos la cllausura de la consulta si son multiples o un almacen a descontar
    $can_inv = count($inventarios);
    $where_alm = "id=".$inventarios[0];
    if ($can_inv > 1) {
      for($x=1;$x<$can_inv;$x++){
        $where_alm = $where_alm." or id=".$inventarios[$x];
      }
    }
    unset($inventarios);
    //seleccionamos los almacenes
    $system->sql="select * from inventario where ".$where_alm;
    unset($where_alm);
    $alm = $system->sql();
    if (count($alm)>0) {
      //probamos aun la disponibilidad
      $dis = 0;
      for($x=0;$x<$can_inv;$x++){
        $dis += $alm[$x]->disponible;
      }
      return array('r'=>true,'d'=>$dis,'alm'=>$alm);
      unset($can_inv,$alm,$dis);
    }else{return array('r'=>false,'m'=>'Error! no se pudo puede obtener la disponibilidad de los almacenes.');}
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


function validar_datosAsignacion($array){
    $e = count($array);
    for ($i=0; $i < $e; $i++) { 
        if(is_array($array[$i])){
            if(count($array[$i])==0){return array('r'=>false,'m'=>'Error! no tiene seleccionado a ninguna persona o no tiene ningun almacen para descontar la asignacion');}
        }elseif(is_numeric($array[$i])){
            if($array[$i]=='' ||  $array[$i]==0){return array('r'=>false,'m'=>'Error! Algunos de los campos requeridos esta vacio o su valor es cero(0)');}
        }else{
            return array('r'=>false,'m'=>'Error! 37707');
        }
    }
    return array('r'=>  true,'m'=>'Exito');
}

?>