<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}

	include_once $_SESSION['base_url'].'/class/system.php';
    include_once $_SESSION['base_url'].'/helpers/others_functions.php';
	$system = new System;


	switch ($_REQUEST['action']) {

		case 'buscar_persona':
			$system->sql = "SELECT * from rep_sucre where cedula = ".$_GET['ced'];
			echo json_encode($system->sql());
		break;
            
		case 'modelo':
			$system->sql = "SELECT * from modelos_vehiculos where id_marca = ".$_GET['m'];
			echo json_encode($system->sql());
		break;
        
        case 'update_perfil':
				$system->table = 'users';
				$system->where = "id = ".$_POST['id_modificar'];
				unset($_POST['action']);
				unset($_POST['id_modificar']);
                $_POST['password'] = password_hash( $_POST['password'], PASSWORD_DEFAULT );
				$respuesta = $system->modificar($_POST);
				$respuesta['modificar'] = 1;
                $_SESSION['flash'] = 1;
				echo json_encode($respuesta);   

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
            
        case 'grabar':
        $system->table ='unidades';
        $system->where = "placa = '$_POST[placa]'";
        if($system->count() > 0)
        {
            $data = ["r" => false];
            echo json_encode($data);
        }
        else
        {	
            unset($_POST['action']);				
            unset($_POST['id_modificar']);
            $system->table ="unidades";
            echo json_encode($system->guardar($_POST));
        }
		break;
             
        case 'grabar_conduc':
        $system->table ='avances';
        $system->where = "nac_avan = '$_POST[nac_avan]' and ced_avan = '$_POST[ced_avan]'";
        if($system->count() > 0)
        {
            $data = ["r" => false];
            echo json_encode($data);
        }
        elseif(!empty($_POST[id_unidad])) 
        {	
            unset($_POST['action']);				
            unset($_POST['id_modificar']);
            $system->table ="avances";
            $_SESSION['flash'] = 1;
            echo json_encode($system->guardar($_POST));
        }
            else{
            
            unset($_POST['id_unidad']);				
            unset($_POST['action']);				
            unset($_POST['id_modificar']);
            $system->table ="avances";
            $_SESSION['flash'] = 1;
            echo json_encode($system->guardar($_POST));
            }
		break;
  
             
    case 'grabar_carga_familiar':
        $system->table ='carga_familiar';
        $system->where = "nac_carga = '$_POST[nac_carga]' and ced_carga = '$_POST[ced_carga]'";
        if($system->count() > 0)
        {
            $data = ["r" => false];
            echo json_encode($data);
        }
        else
        {    
            unset($_POST['action']);				
            unset($_POST['id_modificar']);
            $system->table ="carga_familiar";
            $_SESSION['flash'] = 1;
            echo json_encode($system->guardar($_POST));
            }
		break;
  
        case 'modificar':
				$system->table = 'users';
				$system->where = "id = ".$_POST['id_modificar'];
				unset($_POST['action']);
				unset($_POST['id_modificar']);

				$respuesta = $system->modificar($_POST);
				$respuesta['modificar'] = 1;
				echo json_encode($respuesta);
		break;
 
        case 'modificar_conduc':

                $system->table = 'avances';
				$system->where = "id = ".$_POST['id_modificar'];
				unset($_POST['action']);
				unset($_POST['id_modificar']);
				$respuesta = $system->modificar($_POST);
                $_SESSION['flash'] = 1;
				$respuesta['modificar'] = 1;
				echo json_encode($respuesta);
		break;
        
        case 'modificar_carga':

                $system->table = 'carga_familiar';
				$system->where = "id = ".$_POST['id_modificar'];
				unset($_POST['action']);
				unset($_POST['id_modificar']);
				$respuesta = $system->modificar($_POST);
                $_SESSION['flash'] = 1;
				$respuesta['modificar'] = 1;
				echo json_encode($respuesta);
		break;
        
		case 'remover_linea':
            $system->table = "users";
			$system->eliminar(base64_decode($_GET['eliminar']));
			header('location: ./vista.php');
        
		break; 
        
		case 'remover_conduc':
            $system->table = "avances";
			$system->eliminar(base64_decode($_GET['eliminar']));
			header('location: ./vista_conductores.php');
        
		break; 
            
		case 'eliminar_carga':
            $system->table = "carga_familiar";
			$system->eliminar(base64_decode($_GET['eliminar']));
			header('location: ./vista_carga.php');
        
		break; 
            
        case 'aceptar_insumo':
				$system->table = 'detalles_solicitudes';
                $id_det_sol = (base64_decode($_GET['id_det_sol']));
                $id_soli = (base64_decode($_GET['idsol']));
				$system->where = "id = ".$id_det_sol;
				unset($_POST['action']);
                $arreglo = ['estatus' => 9];
                $res = $system->modificar($arreglo);
            
 				$system->table = 'asignaciones_solicitud';
				$system->where = "id_detalle = ".$id_det_sol;
                $arreglo1 = ['estatus' => 9];
                $res2 = $system->modificar($arreglo1);
            
 				$system->table = 'detalles_solicitudes';
				$system->where = "estatus = 5 and id_solicitud=".$id_soli;
                $tsta = $system->count();
            
                if($tsta == 0){
 				$system->table = 'solicitudes';
				$system->where = "id = ".$id_soli;
                $arreglo3 = ['estatus' => 9];
                $res3 = $system->modificar($arreglo3);
                     }
                if ($res and $res2) {
                include_once($_SESSION['base_url'].'lib/phpqrcode/phpqrcode.php');
                $contenido = $_SESSION['base_url1']."app/check/index.php?id=".(base64_encode($id_det_sol));
                $ruta = $_SESSION['base_url']."assets/images/Qr/asignaciones/".(base64_decode($_GET['id_det_sol'])).".png"; 
                QRcode::png($contenido,$ruta,QR_ECLEVEL_L,10,2);
                }			
                $_SESSION['flash'] = 1;
                header('location: ./aceptar_insumo.php?ids='.$_GET["idsol"].'');
 
		break;
            
		/**********************************
		Controladores de las solicitudes
		***********************************/
		case 'validar_item':
			$system->sql = "select * from rubros where id=$_GET[id_rubro]";
			$item = $system->sql();
			if (count($item)>0) {
				$id_user = base64_decode($_GET['id_user']);
				$fechaInicio=calculaFecha("days",$item[0]->dias_no_habil*-1);
				$system->sql = "select a.*,b.* from solicitudes as a
					inner join detalles_solicitudes as b on (a.id=b.id_solicitud)
					where a.id_user=$id_user
					and estado=$_GET[estado]
					and municipio=$_GET[municipio] 
					and b.id_unidad=$_GET[id_unidad]
					and b.id_rubro=$_GET[id_rubro]
					and (a.estatus <> 3 and b.estatus<>4)
					and a.fec_solicitud > '$fechaInicio'";
				$items = $system->sql();
				if (count($items)>0) {
					$sum = 0;
					foreach ($items as $val) {
						$sum += $val->cantidad;
					}
					unset($items);
					$sum2=$sum+$_GET['cant_sol'];
					if ($sum >= $_GET['cant_max']) {
						echo json_encode(array('msg'=>'No puede solicitar este item.', 'r'=>true,'case'=>3));
					}elseif ($sum2 <= $_GET['cant_max']) {
						echo json_encode(array('msg' => 'Item, Agregado a la lista de detalles.','r'=>true, 'case' => 1));
					}else{
						$disp = $_GET['cant_max'] - $sum;
						echo json_encode(array('msg'=>'Solo tiene disponible '.$disp.' '.$item[0]->descripcion.' para solicitar.','r'=>true,'case'=>2,'dis'=>$disp));
					}
				}else{
					echo json_encode(array('msg' => 'Item, Agregado a la lista de detalles.','r'=>true, 'case' => 1));
				}
			}else{
				echo json_encode(array('msg' => 'Error, no se encontro relacion con ningun item dentro de la BD.','r'=>false));
			}
		break;

		case 'guardar_solicitud':
			$id_user = base64_decode($_GET['id_user']);
			$dia=date('Y-m-d');
			$system->sql="select id from solicitudes where id_user=$id_user and fec_solicitud='$dia'";
			$solicitud = $system->sql();
			if(count($solicitud)>0) {
				$tabla = $_GET['detalles'];
            	$fin = count($tabla);
            	$system->table ='detalles_solicitudes';
            	for ($i=0; $i < $fin; $i++) {
            		$arr = array(
            			'id_user' => $id_user,
            			'id_solicitud' => $solicitud[0]->id,
            			'id_unidad' => $tabla[$i][0],
            			'id_rubro' => $tabla[$i][1],
            			'cantidad' => $tabla[$i][2]
            		);
            		$res=$system->guardar_multiple($arr);
            	}
            	unset($tabla,$id_user,$fin);
            	if ($res) {echo json_encode(array('msg'=>'Solicitud Registrada', 'r'=>true));}
            	else{echo json_encode(array('msg'=>'Error al registrar los detalles de la solicitud', 'r'=>true));}
			}else{
				$system->table ='solicitudes';
				$sol = array(
					'id_user' => $id_user,
					'cod_linea' => $_GET['cod_linea'],
					'cod_afiliado' => $_GET['cod_afiliado'],
					'estado' => $_GET['estado'],
					'municipio' => $_GET['municipio'],
					'fec_solicitud' => date('Y-m-d')
				);
            	$res=$system->guardar($sol);

            	if ($res['r']==true) {
            		unset($sol);
            		$system->sql="select id from solicitudes where id_user=$id_user and fec_solicitud='$dia'";
            		unset($dia);
            		$solicitud = $system->sql();
            		if (count($solicitud) > 0) {
            			$tabla = $_GET['detalles'];
            			$fin = count($tabla);
            			$system->table ='detalles_solicitudes';
            			for ($i=0; $i < $fin; $i++) {
            				$arr = array(
            					'id_user' => $id_user,
            					'id_solicitud' => $solicitud[0]->id,
            					'id_unidad' => $tabla[$i][0],
            					'id_rubro' => $tabla[$i][1],
            					'cantidad' => $tabla[$i][2]
            				);
            				$res=$system->guardar_multiple($arr);
            			}
            			unset($tabla,$id_user,$fin);
            			if ($res) {
            				echo json_encode(array('msg'=>'Solicitud Registrada', 'r'=>true));
            			}else{
            				echo json_encode(array('msg'=>'Error al registrar los detalles de la solicitud', 'r'=>true));
            			}
            		}else{
            			echo json_encode(array('msg'=>'Error BD al encontrar id de la solicitud registrada', 'r'=>false));
            		}
            	}else{
            		echo json_encode(array('msg'=>'Error BD al registrar la solicitud', 'r'=>false));
            	}
			}
		break;

		case 'mostrar_Items':
			$system->sql="	SELECT
	a.cod_linea,
	a.cod_afiliado,
	a.fec_solicitud,
	b.cantidad,
	b.id_rubro,
	b.fec_aprobado,
	b.fec_entrega,
	b.estatus,
	c.placa,
	f.neumatico,
	e.lubricante,
	h.descripcion,
	acumuladores.acumulador
	FROM
	solicitudes AS a
	INNER JOIN detalles_solicitudes AS b ON (a.id = b.id_solicitud AND a.id_user = b.id_user)
	INNER JOIN unidades AS c ON (b.id_unidad = c.id)
	INNER JOIN rubros AS d ON (b.id_rubro = d.id)
	INNER JOIN lubricantes AS e ON (c.tipo_lub = e.id)
	INNER JOIN cauchos AS f ON (c.num_neu = f.id)
	INNER JOIN estatus AS h ON (b.estatus = h.id)
	INNER JOIN acumuladores ON acumuladores.id = c.acumulador
	WHERE
 a.id=$_GET[id]";
				$val = $system->sql();
				if (count($val)>0) {
					echo json_encode(array('r' => true,$val));
				}else{ echo json_encode(array('r' => false));}
				
		break;
            
        default:
        	//default
		break;
	}

?>