<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}

	include_once $_SESSION['base_url'].'/class/system.php';
	$system = new System;


	switch ($_REQUEST['action']) {


		
		case 'change_password_default':
			
			$system->table = "users";
			$arreglo = ['nacionalidad' => $_POST['nac_clp'],
                        'cedula' => $_POST['ced_clp'],
                        'nombre' => $_POST['nom_usuario'],
                        'apellido' => $_POST['ape_usuario'],
                        'password_activo' => 1,
                        'password' => password_hash( $_POST['password'], PASSWORD_DEFAULT )
                       ];
			$system->where = "id = $_SESSION[user_id]";
			$res = $system->modificar($arreglo);

			if($res['r'] === true)
			{	
				$_SESSION['pass_activo'] = '1';
                $_SESSION['nom'] = $_POST['nom_usuario'];
                $_SESSION['ape'] = $_POST['ape_usuario'];
				
			}

			echo json_encode($res);

		break;

        case 'change_password':
			
			$system->table = "users";
			$arreglo = ['password_activo' => 1,
                        'password' => password_hash( $_POST['password'], PASSWORD_DEFAULT )
                       ];
			$system->where = "id = $_SESSION[user_id]";
			$res = $system->modificar($arreglo);

			if($res['r'] === true)
			{	
				$_SESSION['pass_activo'] = '1';

			}
            $_SESSION['flash'] = 1;
			echo json_encode($res);

		break;
            
            
        case 'update_datos_usuario':
				$system->table = 'users';
				$system->where = "id = ".$_POST['id_modificar'];
				unset($_POST['action']);
				unset($_POST['id_modificar']);
				$respuesta = $system->modificar($_POST);
				$respuesta['modificar'] = 1;

			if($respuesta['r'] === true)
			{	
                $_SESSION['nom'] = $_POST['nombre'];
                $_SESSION['ape'] = $_POST['apellido'];
				
			}            
            $_SESSION['flash'] = 1;
				echo json_encode($respuesta);   
                header('location: ./perfil.php');
		break;

		case 'traer_sol_xcat':
			$rubro = $_GET['rubro'];
			unset($action,$_GET['rubro']);
			//$system->sql = "select * from ";
			$wherer = '';
			switch ($rubro) {
				case '1'://Neumatico
					$tabla = 'cauchos';
					$col = 'neumatico';
					$wherer = 'num_neu='.$rubro;
				break;
				case '2'://Lubricante
					$tabla = 'lubricantes';
					$col = 'lubricante';
					$wherer = 'tipo_lub='.$rubro;
				break;
				case '3'://Acumulador
					$tabla = 'acumuladores';
					$col = 'acumulador';
					$wherer = 'acumulador='.$rubro;
				break;
				
				default:
					echo json_encode(array('msg' => false,'msj'=>'Opcion no valida' ));
				break;
			}
		break;

		case 'traer_sol_Xitems_Gneral':
		$item=base64_decode($_GET['item']);
		$estado = base64_decode($_GET['estado']);
	      unset($_GET['action'],$_GET['item']);
	      switch ($item) {
	        case '1'://neumatico
	          $system->sql="select b.descripcion,b.id as rubro,c.neumatico as item,count(*) as solicitudes,SUM(a.cantidad) as cantidad  
	            from detalles_solicitudes as a
	            inner join rubros as b on (a.id_rubro=b.id)
	            inner join solicitudes as s on (a.id = s.id)
	            inner join unidades as u on (a.id_unidad=u.id)
	            inner join cauchos as c on (u.num_neu=c.id)
	            where b.id=".$item." and a.estatus=1 and s.estado = '".$estado."'
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
	            inner join solicitudes as s on (a.id = s.id)
	            inner join unidades as u on (a.id_unidad=u.id)
	            inner join lubricantes as l on (u.tipo_lub=l.id)
	            where b.id=".$item." and a.estatus=1 and s.estado = '".$estado."'
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
	            inner join solicitudes as s on (a.id = s.id)
	            inner join unidades as u on (a.id_unidad=u.id)
	            inner join acumuladores as d on (u.acumulador=d.id)
	            where b.id=".$item." and a.estatus=1 and s.estado = '".$estado."'
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

		case 'traer_lista_cuotas':
			$e = base64_decode($_GET['e']);
      		$r = base64_decode($_GET['r']);
      		$sel = "";
      		$inner = "";
      		if($r == 1){
        		$sel = " cauchos.neumatico as producto_sol,cauchos.id as id_producto_sol ";
        		$inner = " LEFT JOIN cauchos ON mov_items.id_producto_sol = cauchos.id ";
      		}
      		elseif ($r == 2) {
        		$sel = " lubricantes.lubricante as producto_sol,lubricantes.id as id_producto_sol ";
        		$inner=" LEFT JOIN lubricantes ON mov_items.id_producto_sol = lubricantes.id ";
      		}
      		elseif ($r == 3) {
        		$sel = " acumuladores.acumulador as producto_sol,acumuladores.id as id_producto_sol ";
        		$inner=" LEFT JOIN acumuladores ON mov_items.id_producto_sol = acumuladores.id ";
      		}
      		else{json_encode(array('msj' => false, 'msg'=>'Error de tipo.'));}
      		$system->sql="SELECT
      			mov_items.id AS id_mov,
      			mov_items.id_rubro,
      			mov_items.cantidad_solicitudes,
		        rubros.descripcion,
		        mov_items.producto as id_producto,
		        productos.descripcion AS producto,
		        productos.marca,
		        productos.modelo,
		        mov_items.cantidad_asig,
		        mov_items.cantidad_disponible,
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
		        mov_items.id_rubro = '".$r."' AND 
		        (mov_items.estatus = 1 OR mov_items.estatus = 2)
		        ORDER BY
		        mov_items.fec_reg ASC";
			$re = $system->sql();
        	if (count($re)) {
				echo json_encode(array('msg' => true,'msj'=>'Mostrando Cuotas','r'=>$re));
			}else{echo json_encode(array('msg' => false, 'msj'=>'Esté estado no tiene ninguna asignacion de items en su historia'));}
		break;

		case 'traer_solicitudes_act':
			unset($_GET['action']);
			$e = base64_decode($_GET['e']);
      		$r = base64_decode($_GET['r']);
      		$sel = "";
      		$inner = "";
      		$group = "";
      		if($r == 1){//neu
        		$sel = " cauchos.neumatico as producto_sol, cauchos.id as id_producto_sol ";
        		$inner = " LEFT JOIN cauchos ON unidades.num_neu = cauchos.id ";
        		$group = " cauchos.neumatico ";
      		}
      		elseif ($r == 2) {//lub
        		$sel = " lubricantes.lubricante as producto_sol,lubricantes.id as id_producto_sol ";
        		$inner=" LEFT JOIN lubricantes ON unidades.tipo_lub = lubricantes.id ";
        		$group = " lubricantes.lubricante ";
      		}
      		elseif ($r == 3) {//acu
        		$sel = " acumuladores.acumulador as producto_sol,acumuladores.id as id_producto_sol  ";
        		$inner=" LEFT JOIN acumuladores ON unidades.acumulador = acumuladores.id ";
        		$group = " acumuladores.acumulador ";
      		}
      		else{json_encode(array('msj' => false, 'msg'=>'Error de tipo.'));}
			$system->sql="SELECT
			Sum(detalles_solicitudes.cantidad) AS cantidad_producto,
			Count(detalles_solicitudes.id) AS cantidad_sol,
			detalles_solicitudes.id_rubro,
			".$sel."
			FROM
			detalles_solicitudes
			INNER JOIN unidades ON detalles_solicitudes.id_unidad = unidades.id
			".$inner."
			WHERE
			detalles_solicitudes.estatus = 1 AND
			detalles_solicitudes.id_rubro = ".$r." AND
			unidades.estado = ".$e."
			GROUP BY
			".$group."
			ORDER BY
			cantidad_sol DESC";
			$r = $system->sql();
			if (count($r)>0) {
				echo json_encode(array('msg' => true,'msj'=>'Mostrando solicitudes','r'=>$r));
			}else{
				echo json_encode(array('msg' => false,'msj'=>'No hay solicitudes'));
			}
		break;

		case 'traer_solicitudes_act_esp':
			$e = base64_decode($_GET['e']);
			$r = base64_decode($_GET['r']);
			$idsol = base64_decode($_GET['i']);
			unset($_GET['action'],$_GET['e'],$_GET['r'],$_GET['i']);
			$where = "";
			if($r == 1){//neu
				$where = " unidades.num_neu =".$idsol;
			}
			if($r == 2){//lub
				$where = " unidades.tipo_lub=".$idsol;	
			}
			if($r == 3){//acu
				$where = " unidades.acumulador=".$idsol;
			}
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
				".$where."
				ORDER BY detalles_solicitudes.id ASC";
			$r = $system->sql();
			if (count($r)>0) {
				echo json_encode(array('msg' => true,'msj'=>'Mostrando Listado','r'=>$r));
			}else{echo json_encode(array('msg' => false,'msj'=>'Error al cargar personas para preasignacion'));}
		break;

		case 'preasignar':
			unset($_GET['action']);
			//seleccionamos el movimiento de items del estado
			$idmov = base64_decode($_GET['id_mov']);
			$system->sql="SELECT * from mov_items where id=".$idmov." and (estatus = 1 or estatus = 2) and estado=".$_SESSION['edo'];
			$mov = $system->sql();
			if (count($mov)>0) {
				//validamos 
				$cantidadAsig = base64_decode($_GET['cantidad_asignada']);
				if ($mov[0]->cantidad_disponible >= $cantidadAsig) {
					//armamos el serial de la asignacion
					$ser = crear_serial('asignacion');
					if ($ser['r']==true) {
						//verificamos que no este registado 
						$system->sql="select serial from asignaciones where serial =".$ser['s'];
            			if(count($system->sql())==0){
            				//buscamos el precio del producto 
            				$system->sql="SELECT precio FROM productos where id = ".$mov[0]->producto;
            				$precio = $system->sql();
            				if(count($precio)>0){
            					$personas = $_GET['personas'];
	            				$productoSol = base64_decode($_GET['id_producto_solicitado']);
	            				$cantidadSol = base64_decode($_GET['cantidad_solicitada']);
	            				$tipoAsig = base64_decode($_GET['tipo_asignacion']);
	            				$entregar = base64_decode($_GET['entrega_general']);
	            				//buscamos el id para la asignacion
	            				$system->sql="SELECT MAX(id) as idmax from asignaciones";
	            				$idMaxAct = $system->sql();
	            				if (count($idMaxAct)>0) {
	            					//armamos al Sr asiganacion
	            					$conexion = $system->begin();
	            					$system->table="asignaciones";
	            					$idasig = $idMaxAct[0]->idmax+1;
				                	$asig = array(
					                	'id' => $idasig,
					                	'id_mov' => $mov[0]->id,
					                    'serial' => $ser['s'],
			                            'beneficiados' => count($personas),
			                            'precio' => $precio[0]->precio,
			                            'monto_total' => round($cantidadAsig*$precio[0]->precio, 2),
			                            'id_producto' => $mov[0]->producto,
			                            'id_producto_solicitado' => $productoSol,
			                            'cantidad_solicitud' => $cantidadSol,
			                            'cantidad_asignada' => $cantidadAsig,
			                            'fec_reg' => date("Y-m-d"),
			                            'estatus' => 2,
			                            'tipo_asignacion' => $tipoAsig,
			                            'id_rubro' => $mov[0]->id_rubro,
			                            'estado' => $_SESSION['edo'],
				                	);
				                	$r = $system->guardar_begin($asig,$conexion);
				                	if ($r['r'] == true) {
				                		$r = gestionar_SolicitudAsignacion($idasig,$personas,$entregar,$conexion);
				                		if ($r['r']==true) {
				                			//modificamos la disponibilidad de la asignacion de cuotas
				                			$dis = $mov[0]->cantidad_disponible - $cantidadAsig;
				                			//var_dump($mov[0]->cantidad_asig.'-'.$cantidadAsig);
				                			$system->where = "id=".$idmov;
            								$system->table="mov_items";
            								//vemos si la asignacion del estado esta completa
            								$status = '';if($dis == 0){$status = 3;}else{$status = 2;}
				                			$r = $system->modificar_begin(array('cantidad_disponible' => $dis,'estatus' => $status), $conexion);
				                			if ($r['r']==true) {
				                				//guardamos todos los cambios, porfin

				                				$system->table="mov_items_almacenes";
				                				$g =$system->guardar_begin(array('id_mov' => $mov[0]->id,'id_inventario'=>$mov[0]->inventario_salida,'cantidad'=>$cantidadAsig,'estatus'=>1,'id_asignacion'=>$idasig ),$conexion);
				                				if ($g['r']==true) {
				                					$system->commit($conexion);
                                        			echo json_encode(array('msg' => true,'msj' =>'Registro exitoso de la preasignacion','st' => $status,'r'=>$mov[0]->id_rubro));
				                				}else{
				                					$system->rollback($conexion);
				                				echo json_encode(array('msg' => false, 'msj' => 'Error, no se puede registrar la preasignacion.'));	
				                				}
				                			}else{
				                				$system->rollback($conexion);
				                				echo json_encode(array('msg' => false, 'msj' => 'Error, no se puede modificar la disponibilidad del las cuotas asignada.'));
				                			}

				                		}else{
				                			$system->rollback($conexion);
				                			echo json_encode(array('msg' => false, 'msj' => $r['m']));
				                		}

				                	}else{
				                		$system->rollback($conexion);
				                		echo json_encode(array('msg' => false, 'msj' => 'Error, no se puede registrar la preasignacion.'));
				                	}

	            				}else{echo json_encode(array('msg' => false, 'msj' => 'Error, no se pudo obtener acceso a las asignaciones, puede intentarlo luego.'));}

            				}else{echo json_encode(array('msg' => false, 'msj' => 'Lo sentimos, el producto de la preasignacion no posee un precio en su registro. Porfavor comuniquese con los administradores para que solucionen este detalle.'));}

            			}else{echo json_encode(array('msg' => false, 'msj' => 'Error! el serial se encuetra registrado en las asignaciones.'));}
					
					}else{echo json_encode(array('msg' => false, 'msj' => $ser['m']));}


				}else{echo json_encode(array('msg' => false,'msj'=>'Lo sentimos, la cantidad disponible de la cuota asignada es menor a la cantidad asignada en la preasignacion.'));}


			}else{echo json_encode(array('msg' => false,'msj'=>'Lo sentimos, parece que esta cuota de items no esta disponible.'));}
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
                    $g = $system->guardar_begin(array('id_asignacion'=>$r[0]->id_asignacion,'accion' => 'Eliminacion Unidad '.$r[0]->placa, 'observacion' => $observacion,'desde'=>'Estado'),$conexion);
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
                        $g = $system->guardar_begin(array('id_asignacion'=>$c[0]->id_asignacion,'accion' => 'Agregar la Unidad '.$r[0]->placa, 'observacion' => $observacion,'desde'=>'Estado'),$conexion);
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

    case 'cambio_precio_asignacion':
      $precionew = base64_decode($_GET['precio']);
      $system->sql="SELECT * FROM asignaciones WHERE estatus = 2 and id =".base64_decode($_GET['key']);
      $r = $system->sql();
      if (count($r)>0) {
        $monto_total = $r[0]->cantidad_asignada*$precionew;
        $system->where = "id=".$r[0]->id;
        $system->table="asignaciones";
        $m = $system->modificar(array('precio'=>$precionew,'monto_total'=>$monto_total));
        if ($m['r']==true) {
          echo json_encode(array('msg' => true, 'msj'=>'Cambio de precio unitario exitoso.', 'precio'=>$precionew ,'total'=>$monto_total)); 
        }else{
          echo json_encode(array('msg' => false, 'msj'=>'Imposible cambiar el precio unitario del producto para esta asignacion.'));
        }
      }else{
        echo json_encode(array('msg' => false, 'msj'=>'Imposible acceder a la asignacion para su cambio de precio. Talvez ya la asignacion esta confirmada'));
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
          $m = $system->guardar_begin(array('desde' => 'Estado', 'accion' => 'Preasignacion rechazada', 'observacion' => base64_decode($_GET['observacion']),'id_asignacion'=>$r[0]->id) ,$conexion);
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

    case 'confirmar_modificacion_preasignacion':
      $system->sql="SELECT * FROM asignaciones WHERE estatus = 4 and id = ".base64_decode($_GET['key']);
      $r = $system->sql();
      if (count($r)>0) {
        $system->where = "id=".$r[0]->id;
        $system->table = "asignaciones";
        $m = $system->modificar(array('estatus' => 2));
        if($m['r']==true){
          echo json_encode(array('msg' => true, 'msj'=>'Modificacion exitosa.', 'url'=>$_SESSION['base_url1'].'app/estado/asignaciones/asignaciones_for_edit.php'));
        }else{
          echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede mkodificar la preasignacion.'));
        }
        
      }else{echo json_encode(array('msg' => false, 'msj'=>'Error, no se puede acceder a la informacion de la asignacion.'));}
    break;


    /*SOLICITUDES*/
    case 'mostrar_detalles_filtrado':

    $system->sql="SELECT a.id,a.cantidad,a.id_rubro,r.descripcion,
c.id as id_lubricante ,c.lubricante,d.id as id_neumatico,d.neumatico,
h.id as id_acumulador,h.acumulador,b.placa,a.estatus as id_estatus,e.descripcion as estatus,b.estado
    FROM detalles_solicitudes AS a
    INNER JOIN unidades AS b on (a.id_unidad=b.id)
    INNER JOIN lubricantes AS c on(b.tipo_lub=c.id)
    INNER JOIN cauchos AS d on (b.num_neu=d.id)
    INNER JOIN acumuladores AS h on (b.acumulador=h.id)
    INNER JOIN rubros AS r on (a.id_rubro=r.id)
    INNER JOIN estatus AS e on (a.estatus=e.id)
    WHERE id_solicitud=".$_GET['id'];

    echo json_encode(array('msg' => true, 'msj'=>'Mostrando data...','r'=>$system->sql()));

    break;

    case 'filtrar_solicitudes':
      unset($_GET['action']);

      $where="";
      $emu="";
      $es="";
      if ($_GET['estado']!='all') {
        if ($_GET['municipio']!='all') {
          $emu="a.estado=".$_GET['estado']." and a.municipio=".$_GET['municipio'];
        }else{
          $emu="a.estado=".$_GET['estado'];
        }        
      }
      if ($_GET['estatus']!='all') {
        $es="a.estatus=".$_GET['estatus'];        
      }

      if ($emu!='' and $es!='') {
        $where="where ".$emu." and ".$es;        
      }elseif ($emu!='' and $es=='') {
        $where="where ".$emu;
      }else{
        $where="where ".$es;
      }

      if ($where!="") {
        $system->sql="select a.*,e.estado as nomestado,m.municipio as nommunicipio,c.descripcion as nomestatus,u.cedula,u.nombre,u.apellido,u.nombre_linea
        from solicitudes as a
        inner join estados as e on (a.estado=e.id_estado)
        inner join municipios as m on (a.municipio=m.id_municipio and e.id_estado=m.id_estado)
        inner join estatus as c on (a.estatus=c.id)
        inner join users as u on (a.id_user=u.id)".$where."
        order by fec_solicitud DESC";
      }else{
        $system->sql="select a.*,e.estado as nomestado,m.municipio as nommunicipio,c.descripcion as nomestatus,u.cedula,u.nombre,u.apellido,u.nombre_linea
        from solicitudes as a
        inner join estados as e on (a.estado=e.id_estado)
        inner join municipios as m on (a.municipio=m.id_municipio and e.id_estado=m.id_estado)
        inner join estatus as c on (a.estatus=c.id)
        inner join users as u on (a.id_user=u.id)
        order by fec_solicitud DESC";
      }

      $r = $system->sql();
      if (count($r)>0) {
        echo json_encode(array('msg' => true, 'msj'=>'Mostrando data...','r'=>$r));
      }else{
        echo json_encode(array('msg' => false, 'msj'=>'Nada que mostrar...'));
      }
      
    break;
    /*---SOLICITUDES*/
            
		default:
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

?>