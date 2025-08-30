<?
	if(!isset($_SESSION))
  	{
    	session_start();
  	}

	include_once $_SESSION['base_url'].'/class/system.php';
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

			echo json_encode($res);

		break;
            
        case 'grabar':
        $system->table ='users';
        $system->where = "usuario = '$_POST[usuario]'";
        if($system->count() > 0)
        {
            $data = ["r" => false];
            echo json_encode($data);
        }
        else
        {	
            unset($_POST['action']);				
            unset($_POST['id_modificar']);
            $system->table ="users";
            $_SESSION['flash'] = 1;
            $res=$system->guardar($_POST);
            if ($res) {
                include_once($_SESSION['base_url'].'lib/phpqrcode/phpqrcode.php');
                $contenido = $_SESSION['base_url1']."app/check/index.php?id=".base64_encode($_REQUEST['usuario']);
                $ruta = $_SESSION['base_url']."assets/images/Qr/afiliados/".$_REQUEST['usuario'].".png"; 
                QRcode::png($contenido,$ruta,QR_ECLEVEL_L,10,2);
                $data = ["r" => true];
                echo json_encode(array('r' => true));
            }
        }
		break;
  
        case 'grabar_unidad':
        $system->table ='unidades';
        $system->where = "placa = '$_POST[placa]'";
        if($system->count() > 0)
        {
            $data = ["r" => false];
            echo json_encode($data);
        }
        else
        {	
            $file = $_FILES["foto"];
            $nombre = $file['name'];
            $tipo = $file["type"];
            $ext = explode("image/",$file["type"]);
            $nombre_foto = $_POST["placa"].".".$ext[1];
            $qr = $_POST["placa"].".png";
            $ruta_provisional = $file["tmp_name"];
            $size = $file['size'];
            $carpeta = $_SESSION['base_url'].'assets/images/Qr/unidades/';
            $src = $carpeta.$nombre_foto;

            unset($_POST['action']);				
            unset($_POST['id_modificar']);
            
            $_POST['qr'] = "assets/images/Qr/unidades/".$_POST["placa"].".png";
            $_POST['foto'] = $nombre_foto;
            move_uploaded_file($ruta_provisional, $src);            
            $system->table ="unidades";
            $_SESSION['flash'] = 1;
            //echo json_encode($system->guardar($_POST));
            $res=$system->guardar($_POST);
            if ($res) {
                include_once($_SESSION['base_url'].'lib/phpqrcode/phpqrcode.php');
                $contenido = $_SESSION['base_url1']."app/check_unds/index.php?id=".base64_encode($_POST["placa"]);
                $ruta = $_SESSION['base_url']."assets/images/Qr/unidades/".$_POST["placa"].".png"; 
                QRcode::png($contenido,$ruta,QR_ECLEVEL_L,10,2);
                $data = ["r" => true];
                echo json_encode(array('r' => true));
            }            
        }
		break;
  
     	        case 'grabar_ruta':
        $system->table ='rutas';
        $ruta = trim($_POST[ruta]," ");    
        $system->where = "ruta like '$ruta'";
        if($system->count() > 0)
        {
            $data = ["r" => false];
            echo json_encode($data);
        }
        else
        {	
            unset($_POST['action']);				
            unset($_POST['id_modificar']);
            $system->table ="rutas";
            echo json_encode($system->guardar($_POST));
        }
		break;

		case 'remover_ruta':
  	            
            $system->table = "rutas";
			$system->eliminar(base64_decode($_GET['eliminar']));
			header('location: ./vista_rutas.php');
        
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
            
        case 'modificar_unidad':
				$system->table = 'unidades';
				$system->where = "id = ".$_POST['id_modificar'];
				unset($_POST['action']);
				unset($_POST['id_modificar']);

				$respuesta = $system->modificar($_POST);
				$respuesta['modificar_unidad'] = 1;
				echo json_encode($respuesta);
		break;
        
		case 'remover_linea':
  	            
            $system->table = "users";
			$system->eliminar(base64_decode($_GET['eliminar']));
			header('location: ./vista.php');
        
		break; 
        
		case 'remover_unidad':
  	            
            $system->table = "unidades";
			$system->eliminar(base64_decode($_GET['eliminar']));
			header('location: ./vista_unidades.php');
        
		break; 
        
        case 'remover_afiliado':
  	            
        $system->table ='unidades';
        $system->where = "cod_afiliado =".(base64_decode($_GET['usuario']));
        if($system->count() > 0)
        {
            $data = ["r" => false];
            $_SESSION['flash'] = 6;
            echo json_encode($data);
			header('location: ./vista.php');
        }
        else
        {
            $system->table = "users";
			$system->eliminar(base64_decode($_GET['eliminar']));
			header('location: ./vista.php');
        
		break; 
        }
		default:
			# code...
			break;
	}

?>