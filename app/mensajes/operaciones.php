<?php
	if(!isset($_SESSION)){session_start();}

	include_once $_SESSION['base_url'].'/class/system.php';
	$system = new System;
	$edo = $_SESSION['edo'];
	$mun = $_SESSION['mun'];
	$nvl = $_SESSION['nivel'];

	switch ($_REQUEST['action']) {
		case 'store':		
			
			unset($_POST['action']);
			$type = $_POST['type_message'];

			if($type == 2){
				foreach ($_POST['users_message_id'] as $row) {
					$array_store = [
						'id_usuario_envio' => $_SESSION['user_id'],
						'id_usuario_receptor' => $row,
						'mensaje' => $_POST['message'],
						'created_at' => date('Y-m-d H:i:s'),
						'readed' => 2
					];

					$system->table = "mensajes";
					$system->guardar($array_store);
				}
			}else{
				$array_store = [
					'id_usuario_envio' => $_SESSION['user_id'],
					'id_usuario_receptor' => $_POST['id_singular'],
					'mensaje' => $_POST['message'],
					'created_at' => date('Y-m-d H:i:s'),
					'readed' => 2
				];

				$system->table = "mensajes";
				$system->guardar($array_store);
			}

			echo json_encode(['r' => true]);
		break;

		case 'get_users':

			$id_perfil = $_REQUEST['id_perfil'];
			if($nvl < $id_perfil){
				$where = $nvl > 1 ? "(estado = $edo and municipio = $mun) and perfil = $id_perfil" : "perfil = $id_perfil";
			}else{
				if($nvl == 5){
					if($id_perfil == 4){
						$where = "cod_linea = (select usuario from unidades where estado = $edo and municipio = $mun and perfil = 4)";
					}else{
						$where = "and estado = $edo and municipio = $mun and perfil = $id_perfil";	
					}
				}elseif($nvl == 4){
					$where = "estado = $edo and municipio = $mun and perfil = $id_perfil";
				}elseif($nvl == 3){
					$where = "estado = $edo and perfil = $id_perfil";
				}elseif($nvl == 2){
					$where = "estado = $edo and perfil = $id_perfil";
				}

				if($id_perfil == (INT)0){
					$where = "perfil = $id_perfil";
				}
			}

			$system->sql = "SELECT id,cedula,usuario,perfil,concat(nombre,' ',apellido) as nombre, telefono ,email,
			(SELECT nombre from perfiles where id = users.perfil) as nombre_perfil
			from 
			users WHERE $where ORDER BY perfil asc";
			
			echo json_encode(['users' => $system->sql()]);
		break;

		case 'read':
			$id = $_POST['id'];
			$system->table = "mensajes";
			$system->where = "id = $id";
			$arreglo = ['readed' => 1];
			$res = $system->modificar($arreglo);
			if($res['r']){
				
				$system->sql = "SELECT *, 
									date_format(created_at,'%d-%m-%Y') as fecha,
									IFNULL(
										(SELECT CONCAT(nombre,' ',apellido) from users where id = mensajes.id_usuario_envio),
										(SELECT usuario from users where id = mensajes.id_usuario_envio)
									) as usuario_emisor,
									case readed 
									WHEN 2 THEN 'No leido'
									ELSE 'leido'
									END as estado_mensaje
									FROM mensajes 
									where id_usuario_receptor = $_SESSION[user_id]";

				$mensajes = $system->sql();
				$res['mensajes'] = $mensajes;
				echo json_encode($res);
			}else{
				echo json_encode($res);
			}
		break;

		case 'response':
			$array_store = [
					'id_usuario_envio' => $_SESSION['user_id'],
					'id_usuario_receptor' => $_POST['id_singular'],
					'mensaje' => $_POST['message'],
					'created_at' => date('Y-m-d H:i:s'),
					'readed' => 2
				];

				$system->table = "mensajes";
				echo json_encode($system->guardar($array_store));
		break;
	}
?>
